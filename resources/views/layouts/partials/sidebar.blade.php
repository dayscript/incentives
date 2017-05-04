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
                            <li><a href="#">{{ __('Documentación técnica') }}</a></li>
                        </ul>
                    </li>
                    <li class="{{ request()->is('users/*') ?'active':''}}">
                        <a href="#"><i class="icon-users"></i> <span>{{ __('Usuarios') }}</span></a>
                        <ul>
                            <li class="{{ request()->is('users/'. Auth::user()->id .'/edit')?'active':'' }}"><a href="/users/{{ Auth::user()->id }}/edit">{{ __('Editar mi perfil') }}</a></li>
                        </ul>
                    </li>
                    <!-- /main -->

                </ul>
            </div>
        </div>
        <!-- /main navigation -->

    </div>
</div>