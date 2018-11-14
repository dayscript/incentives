<?php

namespace App\Http\Controllers;

use App\Kokoriko\Redemption;
use App\Incentives\Core\Entity;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

use App\Http\Requests\StoreRedemtionPost;

use Validator;
class RedemptionController extends Controller
{

    public function __construct()
    {

    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreRedemtionPost $request)
    {
      $redemption         = '';
      $results            = [];

      sleep(1);
      $validator = $request->validate();


      $entity = Entity::find($request->input('entity_id'));
      $redemption = Redemption::create(request()->all());
      $redemption->token = str_random(15);
      $redemption->save();
      $redemption->createZoho();


      $results['status']  = 200;
      $results['message'] = __('Has redimido con éxito ' . $redemption->value . " kokoripesos\n, se ha enviado una notificación a tu correo electrónico con las instrucciones para disfrutarlo" );

      return $results;
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Kokoriko\Redemption  $redemption
     * @return \Illuminate\Http\Response
     */
    public function show(Redemption $redemption)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Kokoriko\Redemption  $redemption
     * @return \Illuminate\Http\Response
     */
    public function edit(Redemption $redemption)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Kokoriko\Redemption  $redemption
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Redemption $redemption)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Kokoriko\Redemption  $redemption
     * @return \Illuminate\Http\Response
     */
    public function destroy(Redemption $redemption)
    {
        //
    }


    public function Devel(){
      $redemtion = Redemption::find(5);
      return $redemtion->createZoho();
    }
}
