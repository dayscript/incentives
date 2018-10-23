<?php

namespace App\Http\Controllers;

use App\Utils\Template;
use App\Incentives\Compute\Vars;
use App\Incentives\Core\Client;
use Illuminate\Http\Request;
use App\Utils\TemplateVars;


class TemplateController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
      $templates = Template::orderBy('name')->paginate(10);
      return view('templates.index', compact('templates'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('templates.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
      sleep(1);
      $this->validate(request(), [
          'name'     => 'required|min:3',
      ]);


      $template = Template::create(request()->all());
      $results            = [];
      $results['templates']    = $template;
      $results['status']  = 'success';
      $results['message'] = __('Variable creada');

      return $results;
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Template  $template
     * @return \Illuminate\Http\Response
     */
    public function show(Template $template)
    {
      foreach ($template->vars as $key => $value) {
         $template->vars[$key] = $template->vars[$key]->var;
      }

      $results           = [];
      $results['templates']   = $template;
      $results['status'] = 'success';

      return $results;
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Template  $template
     * @return \Illuminate\Http\Response
     */
    public function edit(Template $template)
    {
        return view('templates.edit', compact('template'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Template  $template
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Template $template)
    {

      sleep(1);
      $this->validate(request(), [
          'name'     => 'required|min:3',
      ]);
      $template->update(request()->all());
      $template->vars()->delete();

      foreach ( request()->all()['vars'] as $key => $value) {
          $templateVars = new TemplateVars;
          $templateVars->template_id = $template['id'];
          $templateVars->var_id = $value['id']; ;
          $templateVars->save();
      }

      foreach ($template->vars as $key => $value) {
         $template->vars[$key] = $template->vars[$key]->var;
      }
      $results            = [];
      $results['templates']    = $template;
      $results['status']  = 'success';
      $results['message'] = __('Datos actualizados');

      return $results;
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Template  $template
     * @return \Illuminate\Http\Response
     */
    public function destroy(Template $template)
    {
      $template->delete();
      $results            = [];
      $results['status']  = 'success';
      $results['message'] = __('Datos eliminados');
      return $results;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function apilist()
    {
        $results            = [];
        $templates            = Template::orderBy('name')->get();
        foreach ($templates as $key => $template) {
          $template->vars;
          $templates[$key] = $template;
        }
        $results['variables'] = $templates;

        return $results;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function devel($id)
    {
        $results = Template::find($id);
        $template = $results;
        dd($template);
    }
}
