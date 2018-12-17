<div class="">
  {{-- <h6 class="panel-title">{{ __('Entidades') }}</h6> --}}
  {{-- {{ $entities->links() }} --}}
  <div class="">
    <div class="">
      {!! Form::open(['url' => '/entities']) !!}
      <div class="form-group row">
        {!! Form::text('identification','',['class'=>'form-control'] )!!}
      </div>
      <div class="form-group row">
        {{!! Form::select('type', array('1' => 'Contacto', '2' => 'Factura')); !!}}
      </div>
      <div class="form-group row">
        {!! Form::submit('Buscar',['class' => 'btn btn-primary'])!!}
      </div>
        {!! Form::close() !!}
    </div>
  </div>
</div>
