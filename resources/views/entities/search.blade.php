    {!! Form::open(['url' => '/entities','style'=>'display:inline-block']) !!}
    <div class="form-group col-md-12">
      <label for="" class="col-md-12">Identificación: </label>
      {!! Form::text('identification','',['class'=>'form-control col-md-12','size'=>20, 'maxlength'=>20] )!!}
    </div>
    <div class="form-group col-md-12">
      <label for="" class="col-md-12">Tipo: </label>
      {!! Form::select('type', array('1' => 'Contacto', '2' => 'Factura'),['class'=>'']); !!}
      <br>
    </div>
    <div class="form-group col-md-12">
      {!! Form::submit('Buscar',['class' => 'btn btn-primary'])!!}
    </div>
    {!! Form::close() !!}
