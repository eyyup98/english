<?php


namespace App\Modules\SearchCities\src\Controllers;


use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SearchCitiesController extends Controller
{
    public function searchCity(Request $request): JsonResponse
    {
        $cities = DB::select("SELECT name FROM cities WHERE name LIKE '$request->city%' ORDER BY name");
        return response()->json(!empty($cities) ? $cities : [0 => ['name' => 'Город не найден']]);
    }

}
