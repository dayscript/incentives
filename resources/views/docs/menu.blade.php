<aside class="menu">
    <p class="menu-label">General</p>
    <ul class="menu-list">
        <li><a class="{{ request()->is('docs/general/limitless')?'is-active':'' }}" href="/docs/general/limitless">Limitless Theme</a></li>
        <li><a class="{{ request()->is('docs/general/todos')?'is-active':'' }}" href="/docs/general/todos">TODOs</a></li>
    </ul>
    <p class="menu-label">Instalación</p>
    <ul class="menu-list">
        <li><a class="{{ request()->is('docs/instalation/requirements')?'is-active':'' }}" href="/docs/instalation/requirements">Requerimientos</a></li>
        <li><a class="{{ request()->is('docs/instalation/instalation')?'is-active':'' }}" href="/docs/instalation/instalation">Instalación</a></li>
        <li><a class="{{ request()->is('docs/instalation/config')?'is-active':'' }}" href="/docs/instalation/config">Configuración</a></li>
    </ul>
    <p class="menu-label">API</p>
    <ul class="menu-list">
        <li><a class="{{ request()->is('docs/api/test')?'is-active':'' }}" href="/docs/api/test">Prueba de conexión</a></li>
        <li><a class="{{ request()->is('docs/api/entities_addvalue')?'is-active':'' }}" href="/docs/api/entities_addvalue">Entities Add Value</a></li>
        <li><a class="{{ request()->is('docs/api/entities_show')?'is-active':'' }}" href="/docs/api/entities_show">Entity Points Summary</a></li>
    </ul>
    <p class="menu-label">Base de datos</p>
    <ul class="menu-list">
        @php($tables = DB::select('SHOW TABLES'))
        @foreach($tables as $table)
            @php($table_name = $table->Tables_in_incentives)
            <li><a class="{{ request()->is('docs/database/'.$table_name)?'is-active':'' }}" href="/docs/database/{{ $table_name }}">{{ $table_name }}</a></li>
        @endforeach
    </ul>
</aside>