<?php


namespace App\Modules\Words\src\Controllers;


use App\Http\Controllers\Controller;
use App\Models\Words;
use App\Models\WordsProgress;
use App\Models\WordsTypes;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class WordsController extends Controller
{
    public function __construct()
    {
        date_default_timezone_set('Europe/Minsk');
    }

    public function getWords(Request $request): JsonResponse
    {
        $data = json_decode($request->data);
        $words = Words::query()->join('words_types as wt', 'wt.word_type_id', 'words.word_type_id');
        if (!empty($data->search)) {
            $words = $words->whereRaw("words.in_english LIKE '%$data->search%' OR words.in_russia LIKE '%$data->search%'");
        } elseif (!empty($data->uri) && $data->uri == 'allWords' && empty($data->sort)) {
            $words = $words->orderBy('words.created_at', 'desc');
        } elseif(!empty($data->uri) && $data->uri == 'learnWords') {
            $words = $words->leftJoin('words_progress as wp', 'wp.word_progress_id', 'words.word_id')
                ->where('words.is_show', 1)->whereRaw('words.word_id NOT IN (SELECT word_progress_id FROM words_progress)')
                ->orderBy('words.is_frequency', 'desc')->inRandomOrder();
        }

        if (!empty($data->sort)) {
            foreach ($data->sort as $sort) {
                $words = $words->orderBy("words.$sort->sort", $sort->sParam);
            }
            $words = $words->orderBy('words.updated_at', 'desc');
        }

        if (!empty($data->typeWords)) {
            $words = $words->where('words.word_type_id', $data->typeWords);
        }

        $words = $words->get();
        return response()->json($words);
    }

    public function getProgress(): JsonResponse
    {
        return response()->json(WordsProgress::all());
    }

    public function getInfo(): JsonResponse
    {
        $data = [
            'allWords' => Words::all()->count(),
            'showWords' => Words::query()->where('is_show', 1)->get()->count(),
        ];
        return response()->json($data);
    }

    public function getTypes(): JsonResponse
    {
        return response()->json(WordsTypes::all());
    }

    public function clearProgress(Request $request): bool
    {
        WordsProgress::query()->truncate();
        return true;
    }

    public function saveWords(Request $request): JsonResponse
    {
        for ($i = 0; $i < count($request->in_english); $i++) {
            if (empty($request->in_english[$i]) || empty($request->in_russia[$i])) {
                continue;
            }

            $existEnglish = Words::query()->where('in_english', $request->in_english[$i])->first();
            $existRussia = Words::query()->where('in_russia', $request->in_russia[$i])->first();

            if(!empty($existEnglish) && !empty($existRussia)) {
                continue;
            }

            Words::query()->create([
                'in_english' => $request->in_english[$i],
                'transcription' => $request->transcription[$i] ?? null,
                'in_russia' => $request->in_russia[$i],
                'word_type_id' => $request->word_type_id,
            ]);
        }
        return response()->json();
    }

    public function saveOneWord(Request $request): JsonResponse
    {
        Words::query()->where('word_id', $request->word_id)
            ->update([
                'in_english' => $request->in_english,
                'transcription' => $request->transcription,
                'in_russia' => $request->in_russia,
                'word_type_id' => $request->word_type_id,
                'is_show' => $request->is_show,
                'is_frequency' => $request->is_frequency,
                ]);

        return response()->json();
    }

    public function saveProgress(Request $request): bool
    {
        foreach ($request->data as $value) {
            WordsProgress::query()->create(['word_progress_id' => $value['word_progress_id']]);
        }

        return true;
    }

    public function deleteWord(Request $request): JsonResponse
    {
        Words::query()->where('word_id', $request->deleteId)->delete();

        return response()->json();
    }

}
