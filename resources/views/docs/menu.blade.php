<aside class="menu">
    <p class="menu-label">General</p>
    <ul class="menu-list">
        <li><a class="{{ request()->is('docs/general/todos')?'is-active':'' }}" href="/docs/general/todos">TODOs</a></li>
        <li><a class="{{ request()->is('docs/general/limitless')?'is-active':'' }}" href="/docs/general/limitless">Limitless Theme</a></li>
    </ul>
    <p class="menu-label">Instalación</p>
    <ul class="menu-list">
        <li><a class="{{ request()->is('docs/instalation/requirements')?'is-active':'' }}" href="/docs/instalation/requirements">Requerimientos</a></li>
        <li><a class="{{ request()->is('docs/instalation/instalation')?'is-active':'' }}" href="/docs/instalation/instalation">Instalación</a></li>
        <li><a class="{{ request()->is('docs/instalation/config')?'is-active':'' }}" href="/docs/instalation/config">Configuración</a></li>
    </ul>
    <p class="menu-label">Base de datos</p>
    <ul class="menu-list">
        <li><a class="{{ request()->is('docs/database/users')?'is-active':'' }}" href="/docs/database/users">users</a></li>
    </ul>
</aside>