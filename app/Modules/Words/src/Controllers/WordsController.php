<?php


namespace App\Modules\Words\src\Controllers;


use App\Http\Controllers\Controller;
use App\Models\Words;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class WordsController extends Controller
{
    public function getWords(Request $request): JsonResponse
    {
        $words = Words::all()->toArray();
        return response()->json($words);
    }

    public function saveWords(Request $request): JsonResponse
    {
        for ($i = 0; $i < count($request->in_english); $i++) {
            if (empty($request->in_english[$i]) || empty($request->in_russia[$i])) {
                continue;
            }

            $existEnglish = DB::table('words')->where('in_english', $request->in_english[$i])->first();
            $existRussia = DB::table('words')->where('in_russia', $request->in_russia[$i])->first();

            if(!empty($existEnglish) && !empty($existRussia)) {
                continue;
            }

            DB::table('words')->insert([
                'in_english' => $request->in_english[$i],
                'transcription' => $request->transcription[$i] ?? null,
                'in_russia' => $request->in_russia[$i],
            ]);
        }
        return response()->json();
    }

}
