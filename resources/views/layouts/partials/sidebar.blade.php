<div class="sidebar sidebar-main">
    <div class="sidebar-content">
        <!-- User menu -->
        <div class="sidebar-user">
            <div class="category-content">
                <div class="media">
                    <a href="#" class="media-left">
                        <avatar image="{{ Auth::user()->avatar }}" fullname="{{ Auth::user()->name }}" :size="36"></avatar>
                    </a>
                    <div class="media-body">
                        <span class="media-heading text-semibold">{{ Auth::user()->name }}</span>
                        <div class="text-size-mini text-muted">
                            <i class="icon-pin text-size-small"></i> &nbsp;{{ Auth::user()->city->name ?? 'Bogotá' }}, {{ Auth::user()->city->country_code ?? 'CO' }}
                        </div>
                    </div>
                    <div class="media-right media-middle">
                        <ul class="icons-list">
                            <li>
                                <a href="/users/{{ Auth::user()->id }}/edit"><i class="icon-cog3"></i></a>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
        <!-- /user menu -->


        <!-- Main navigation -->
        <div class="sidebar-category sidebar-category-visible">
            <div class="category-content no-padding">
                <ul class="navigation navigation-main navigation-accordion">

                    <!-- Main -->
                    <li class="navigation-header"><span>Main</span> <i class="icon-menu" title="Main pages"></i></li>
                    <li class="{{ request()->is('/')?'active':'' }}"><a href="/"><i class="icon-home4"></i> <span>{{ __('Dashboard') }}</span></a></li>
                    <li class="{{ request()->is('users/*') ?'active':''}}">
                        <a href="#"><i class="icon-users"></i> <span>{{ __('Usuarios') }}</span></a>
                        <ul>
                            <li class="{{ request()->is('users')?'active':'' }}"><a href="/users">{{ __('Listado de usuarios') }}</a></li>
                            <li class="{{ request()->is('users/'. Auth::user()->id .'/edit')?'active':'' }}"><a href="/users/{{ Auth::user()->id }}/edit">{{ __('Editar mi perfil') }}</a></li>
                            <li class="{{ request()->is('users/create')?'active':'' }}"><a href="/users/create">{{ __('Agregar usuario') }}</a></li>
                            <li class="{{ request()->is('users/api')?'active':'' }}"><a href="/users/api">{{ __('API Tokens') }}</a></li>
                        </ul>
                    </li>
                    <li class="{{ request()->is('clients/*') ?'active':''}}">
                        <a href="#"><i class="icon-briefcase"></i> <span>{{ __('Clientes') }}</span></a>
                        <ul>
                            <li class="{{ request()->is('clients')?'active':'' }}"><a href="/clients">{{ __('Listado de clientes') }}</a></li>
                            <li class="{{ request()->is('clients/create')?'active':'' }}"><a href="/clients/create">{{ __('Agregar cliente') }}</a></li>
                        </ul>
                    </li>
                    <li class="{{ request()->is('rules/*') ?'active':''}}">
                        <a href="#"><i class="icon-add-to-list"></i> <span>{{ __('Acumulación') }}</span></a>
                        <ul>
                            <li class="{{ request()->is('vars')?'active':'' }}"><a href="/vars">{{ __('Vars') }}</a></li>
                            <li class="{{ request()->is('rules')?'active':'' }}"><a href="/rules">{{ __('Reglas') }}</a></li>
                            <li class="{{ request()->is('goals')?'active':'' }}"><a href="/goals">{{ __('Metas') }}</a></li>
{{--                            <li class="{{ request()->is('rules/create')?'active':'' }}"><a href="/rules/create">{{ __('Agregar regla') }}</a></li>--}}
                            <li class="{{ request()->is('entities')?'active':'' }}"><a href="/entities">{{ __('Entidades') }}</a></li>


                        </ul>
                    </li>
                    <li class="{{ request()->is('templates/*') ?'active':''}}">
                        <a href="#"><i class="icon-add-to-list"></i> <span>{{ __('Herramientas') }}</span></a>
                        <ul>
                            <li class="{{ request()->is('Plantillas')?'active':'' }}"><a href="/templates">{{ __('Plantillas') }}</a></li>
                        </ul>
                    </li>
                    <li>
                        <a href="#"><i class="icon-question3"></i> <span>{{ __('Ayuda') }}</span></a>
                        <ul>
                            <li><a href="#">{{ __('General') }}</a></li>
                            <li>
                                <a href="#">{{ __('Usuarios') }}</a>
                                <ul>
                                    <li><a href="3_col_dual.html">{{ __('Crear usuarios') }}</a></li>
                                    <li><a href="3_col_double.html">{{ __('Eliminar un usuario') }}</a></li>
                                </ul>
                            </li>
                            <li class="navigation-divider"></li>
                            <li><a href="/docs">{{ __('Documentación técnica') }}</a></li>
                        </ul>
                    </li>
                    <!-- /main -->

                </ul>
            </div>
        </div>
        <!-- /main navigation -->

    </div>
</div>
