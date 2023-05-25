<?php

namespace App\Http\Controllers;

use App\Model\City;
use App\Model\Town;
use Illuminate\Http\Request;

class CityController extends Controller
{
  public function town(Request $request)
  {
    if($request->post() && @$request->all()['city']) {
      if (is_numeric($request->all()['city'])) {
        $towns = Town::select(['id', 'name'])->where('city_id', $request->all()['city'])->orderBy('name', 'asc')->get();
        return \Illuminate\Support\Facades\Response::json(
          [
            'success' => true,
            'towns' => json_encode($towns),
          ], 200);
      }
    }
  }
}
