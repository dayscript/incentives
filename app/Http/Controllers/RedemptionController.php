<?php

namespace App\Http\Controllers;

use App\Kokoriko\Redemption;
use App\Incentives\Core\Entity;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

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
    public function store(Request $request)
    {
      $redemption         = '';
      $results            = [];

      sleep(1);
      $this->validate(request(), [
          'entity_id'     => 'required|numeric',
          'value'         => 'required|numeric',
      ]);

      $entity = Entity::find($request->input('entity_id'));
      if( $request->input('value') <= $entity->getPoints() && $request->input('value') > 0   ){
          $redemption = Redemption::create(request()->all());
          $redemption->token = str_random(15);
          $redemption->save();
          $redemption->createZoho();
          $results['vars']    = $redemption;
          $results['status']  = 200;
          $results['message'] = __('Has redimido con exito' . $request->input('value') . ', se ha enviado una notificación a tu correo electronico con los detalles' );
      }else{
        $results['vars']    = $redemption;
        $results['status']  = 401;
        $results['message'] = __('El numero de kokoripesos a redirmir no es valido');
        return \Response::json([
            $results
        ], 401); // Status code here
      }

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
