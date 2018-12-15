<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Incentives\Utils\Export;


class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('home');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     * @param  \Illuminate\Http\Request  $request
     *
     */
    public function export(Request $request)
    {
      $export = new Export;
      $models = $export->models;
      $response = [];
      $attributes = [];
      $model = '';
      $attribute = '';


      if($request->input('model')){
        $model = $request->input('model');
        if( $attributes = $model::find(1) ){
            $attributes = $attributes->getAttributes();
        }else{
          $response['message'] = 'No existen datos';
        }

      }

      $models = json_encode($models);
      $response = json_encode($response);
      $attributes = json_encode($attributes);
      $attribute = json_encode($attribute);
      $model = json_encode($model);

      return view('export.index', compact('models','attributes','response','model','attribute'));
    }


}
