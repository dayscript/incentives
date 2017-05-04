<?php

namespace App\Http\Controllers\Utils;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class UploadsController extends Controller
{
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
     * @return \Illuminate\Http\Response
     */
    public function store()
    {
        $results = [];
        $folder = request()->get('folder','uploads');
        if(request()->hasFile('file')){
            if (request()->file('file')->isValid()) {
                if($file = request()->file('file')){
                    $path = $file->store($folder, 'public');
//                    if($old = $user->avatar){
//                        Storage::delete($old);
//                    }
//                    $user->avatar = $path;
//                    $user->save();
                    $results['path'] = '/storage/'.$path;
                }
            }
        }
        return $results;
    }

    /**
     * Display the specified resource.
     *
     * @param string $folder
     * @param string $file
     * @return \Illuminate\Http\Response
     */
    public function show($folder = '', $file='')
    {
        return '/storage/'.$folder . '/'. $file;
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
