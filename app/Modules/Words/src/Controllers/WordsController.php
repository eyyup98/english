<?php


namespace App\Modules\Words\src\Controllers;


use App\Http\Controllers\Controller;
use App\Models\Words;
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
        $words = Words::join('words_types as wt', 'wt.word_type_id', 'words.word_type_id');
        if (!empty($data->search)) {
            $words = $words->whereRaw("in_english LIKE '%$data->search%' OR in_russia LIKE '%$data->search%'");
        } elseif (!empty($data->uri) && $data->uri == 'allWords' && empty($data->sort)) {
            $words = $words->orderBy('in_english');
        } elseif(!empty($data->uri) && $data->uri == 'learnWords') {
            $words = $words->where('is_show', 1)->orderBy('is_frequency', 'desc')->inRandomOrder();
        }

        if (!empty($data->sort)) {
            foreach ($data->sort as $sort) {
                $words = $words->orderBy($sort->sort, $sort->sParam);
            }
        }

        if (!empty($data->typeWords)) {
            $words = $words->where('words.word_type_id', $data->typeWords);
        }

        $words = $words->get();
        return response()->json($words);
    }

    public function getTypes(): JsonResponse
    {
        return response()->json(WordsTypes::all());
    }

    public function saveWords(Request $request): JsonResponse
    {
        for ($i = 0; $i < count($request->in_english); $i++) {
            if (empty($request->in_english[$i]) || empty($request->in_russia[$i])) {
                continue;
            }

            $existEnglish = Words::where('in_english', $request->in_english[$i])->first();
            $existRussia = Words::where('in_russia', $request->in_russia[$i])->first();

            if(!empty($existEnglish) && !empty($existRussia)) {
                continue;
            }

            Words::create([
                'in_english' => $request->in_english[$i],
                'transcription' => $request->transcription[$i] ?? null,
                'in_russia' => $request->in_russia[$i],
            ]);
        }
        return response()->json();
    }

    public function saveOneWord(Request $request): JsonResponse
    {
        Words::where('word_id', $request->word_id)
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

    public function deleteWord(Request $request): JsonResponse
    {
        Words::where('word_id', $request->deleteId)->delete();

        return response()->json();
    }

}
