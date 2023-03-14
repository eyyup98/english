<?php


namespace App\Modules\Words\src\Controllers;


use App\Http\Controllers\Controller;
use App\Models\Words;
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
        if (!empty($request->search)) {
            $words = Words::whereRaw("in_english LIKE '%$request->search%' OR in_russia LIKE '%$request->search%'")->get();
        } else {
            $words = Words::all()->toArray();
        }

        return response()->json($words);
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
            ->update(['in_english' => $request->in_english, 'transcription' => $request->transcription, 'in_russia' => $request->in_russia,]);

        return response()->json();
    }

    public function deleteWord(Request $request): JsonResponse
    {
        Words::where('word_id', $request->deleteId)->delete();

        return response()->json();
    }

}
