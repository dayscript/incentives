<?php

namespace App\Http\Controllers;

use Parsedown;

class DocsController extends Controller
{
    /**
     * Index of documentation
     * @param string $option
     * @param string $folder
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index( $folder='general', $option = 'todos' )
    {
        $parsedown = new Parsedown;
        $file = resource_path('views/docs/markdown/'.$folder.'/'.$option.'.md');
        if(file_exists($file)){
            $markdown = file_get_contents($file);
        } else {
            $markdown = '```No existe el archivo: '.$file.'```';
        }
        $content = $parsedown->text($markdown);

        return view('docs.index', compact('content'));
    }
}
