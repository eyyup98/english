<?php


namespace App\Modules\SearchCities\src\Controllers;


use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SearchCitiesController extends Controller
{
    public function searchCity(Request $request)
    {
        $cities = DB::select("SELECT name FROM cities WHERE name LIKE '$request->city%' ORDER BY name");
        return response()->json($cities);
    }

}
