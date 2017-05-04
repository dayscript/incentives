@extends('layouts.app')

@section('page-header')
    <div class="page-header page-header-default">
        <div class="page-header-content">
            <div class="page-title">
                <h4><i class="icon-arrow-left52 position-left"></i> <span class="text-semibold">Dashboard</span> - Inicio</h4>
            </div>

            {{--<div class="heading-elements">--}}
                {{--<a href="#" class="btn btn-labeled btn-labeled-right bg-teal-400 heading-btn">Button <b><i class="icon-menu7"></i></b></a>--}}
            {{--</div>--}}
        </div>

        <div class="breadcrumb-line">
            <ul class="breadcrumb">
                <li><a href="index.html"><i class="icon-home2 position-left"></i> {{ __('Inicio') }}</a></li>
                <li class="active">{{ __('Dashboard') }} </li>
            </ul>

            {{--<ul class="breadcrumb-elements">--}}
                {{--<li><a href="#"><i class="icon-comment-discussion position-left"></i> Link</a></li>--}}
                {{--<li class="dropdown">--}}
                    {{--<a href="#" class="dropdown-toggle" data-toggle="dropdown">--}}
                        {{--<i class="icon-gear position-left"></i>--}}
                        {{--Dropdown--}}
                        {{--<span class="caret"></span>--}}
                    {{--</a>--}}

                    {{--<ul class="dropdown-menu dropdown-menu-right">--}}
                        {{--<li><a href="#"><i class="icon-user-lock"></i> Account security</a></li>--}}
                        {{--<li><a href="#"><i class="icon-statistics"></i> Analytics</a></li>--}}
                        {{--<li><a href="#"><i class="icon-accessibility"></i> Accessibility</a></li>--}}
                        {{--<li class="divider"></li>--}}
                        {{--<li><a href="#"><i class="icon-gear"></i> All settings</a></li>--}}
                    {{--</ul>--}}
                {{--</li>--}}
            {{--</ul>--}}
        </div>
    </div>
@endsection

@section('content')
    <div class="panel panel-flat">
        <div class="panel-heading">
            <h5 class="panel-title">Dashboard</h5>
            <div class="heading-elements">
                <ul class="icons-list">
                    <li><a data-action="collapse"></a></li>
                    {{--<li><a data-action="close"></a></li>--}}
                </ul>
            </div>
        </div>
        <div class="panel-body">
            <h6 class="text-semibold">Mantente al día con la información mas relevante</h6>
            <p class="content-group">En este panel de control, tienes acceso a la información mas importante y consolidada de tu cuenta. Revisa periódicamente este dashboard para enterarte de las novedades de la plataforma.</p>
        </div>
    </div>
    <div class="row">
        <div class="col-lg-6">

            <!-- Members online -->
            <div class="panel bg-teal-400">
                <div class="panel-body">
                    <div class="heading-elements">
                        <span class="heading-text badge bg-teal-800">+53,6%</span>
                    </div>

                    <h3 class="no-margin">3,450</h3>
                    Usuarios activos
                    <div class="text-muted text-size-small">489 promedio</div>
                </div>

                <div class="container-fluid">
                    <div id="members-online"><svg width="322.4375" height="50"><g width="322.4375"><rect class="d3-random-bars" width="9.28832304526749" x="3.9807098765432096" height="45" y="5" style="fill: rgba(255, 255, 255, 0.498039);"></rect><rect class="d3-random-bars" width="9.28832304526749" x="17.24974279835391" height="50" y="0" style="fill: rgba(255, 255, 255, 0.498039);"></rect><rect class="d3-random-bars" width="9.28832304526749" x="30.51877572016461" height="32.5" y="17.5" style="fill: rgba(255, 255, 255, 0.498039);"></rect><rect class="d3-random-bars" width="9.28832304526749" x="43.78780864197531" height="45" y="5" style="fill: rgba(255, 255, 255, 0.498039);"></rect><rect class="d3-random-bars" width="9.28832304526749" x="57.05684156378601" height="37.5" y="12.5" style="fill: rgba(255, 255, 255, 0.498039);"></rect><rect class="d3-random-bars" width="9.28832304526749" x="70.32587448559671" height="35" y="15" style="fill: rgba(255, 255, 255, 0.498039);"></rect><rect class="d3-random-bars" width="9.28832304526749" x="83.59490740740742" height="40" y="10" style="fill: rgba(255, 255, 255, 0.498039);"></rect><rect class="d3-random-bars" width="9.28832304526749" x="96.86394032921811" height="37.5" y="12.5" style="fill: rgba(255, 255, 255, 0.498039);"></rect><rect class="d3-random-bars" width="9.28832304526749" x="110.13297325102882" height="47.5" y="2.5" style="fill: rgba(255, 255, 255, 0.498039);"></rect><rect class="d3-random-bars" width="9.28832304526749" x="123.40200617283952" height="37.5" y="12.5" style="fill: rgba(255, 255, 255, 0.498039);"></rect><rect class="d3-random-bars" width="9.28832304526749" x="136.6710390946502" height="35" y="15" style="fill: rgba(255, 255, 255, 0.498039);"></rect><rect class="d3-random-bars" width="9.28832304526749" x="149.9400720164609" height="42.5" y="7.5" style="fill: rgba(255, 255, 255, 0.498039);"></rect><rect class="d3-random-bars" width="9.28832304526749" x="163.20910493827162" height="32.5" y="17.5" style="fill: rgba(255, 255, 255, 0.498039);"></rect><rect class="d3-random-bars" width="9.28832304526749" x="176.4781378600823" height="50" y="0" style="fill: rgba(255, 255, 255, 0.498039);"></rect><rect class="d3-random-bars" width="9.28832304526749" x="189.747170781893" height="32.5" y="17.5" style="fill: rgba(255, 255, 255, 0.498039);"></rect><rect class="d3-random-bars" width="9.28832304526749" x="203.01620370370372" height="37.5" y="12.5" style="fill: rgba(255, 255, 255, 0.498039);"></rect><rect class="d3-random-bars" width="9.28832304526749" x="216.28523662551441" height="27.500000000000004" y="22.499999999999996" style="fill: rgba(255, 255, 255, 0.498039);"></rect><rect class="d3-random-bars" width="9.28832304526749" x="229.5542695473251" height="42.5" y="7.5" style="fill: rgba(255, 255, 255, 0.498039);"></rect><rect class="d3-random-bars" width="9.28832304526749" x="242.82330246913583" height="35" y="15" style="fill: rgba(255, 255, 255, 0.498039);"></rect><rect class="d3-random-bars" width="9.28832304526749" x="256.0923353909465" height="45" y="5" style="fill: rgba(255, 255, 255, 0.498039);"></rect><rect class="d3-random-bars" width="9.28832304526749" x="269.3613683127572" height="32.5" y="17.5" style="fill: rgba(255, 255, 255, 0.498039);"></rect><rect class="d3-random-bars" width="9.28832304526749" x="282.6304012345679" height="30" y="20" style="fill: rgba(255, 255, 255, 0.498039);"></rect><rect class="d3-random-bars" width="9.28832304526749" x="295.89943415637856" height="32.5" y="17.5" style="fill: rgba(255, 255, 255, 0.498039);"></rect><rect class="d3-random-bars" width="9.28832304526749" x="309.1684670781893" height="45" y="5" style="fill: rgba(255, 255, 255, 0.498039);"></rect></g></svg></div>
                </div>
            </div>
            <!-- /members online -->

        </div>

        <div class="col-lg-6">

            <!-- Today's revenue -->
            <div class="panel bg-pink-400">
                <div class="panel-body">
                    <div class="heading-elements">
                        <ul class="icons-list">
                            <li><a data-action="reload"></a></li>
                        </ul>
                    </div>

                    <h3 class="no-margin">18,390</h3>
                    Puntos asignados
                    <div class="text-muted text-size-small">37,578 promedio</div>
                </div>

                <div id="today-revenue"><svg width="342.4375" height="50"><g transform="translate(0,0)" width="342.4375"><defs><clipPath id="clip-line-small"><rect class="clip" width="342.4375" height="50"></rect></clipPath></defs><path d="M20,8.46153846153846L70.40625,25.76923076923077L120.8125,5L171.21875,15.384615384615383L221.62499999999997,5L272.03125,36.15384615384615L322.4375,8.46153846153846" clip-path="url(#clip-line-small)" class="d3-line d3-line-medium" style="stroke: rgb(255, 255, 255);"></path><g><line class="d3-line-guides" x1="20" y1="50" x2="20" y2="8.46153846153846" style="stroke: rgba(255, 255, 255, 0.298039); stroke-dasharray: 4, 2; shape-rendering: crispEdges;"></line><line class="d3-line-guides" x1="70.40625" y1="50" x2="70.40625" y2="25.76923076923077" style="stroke: rgba(255, 255, 255, 0.298039); stroke-dasharray: 4, 2; shape-rendering: crispEdges;"></line><line class="d3-line-guides" x1="120.8125" y1="50" x2="120.8125" y2="5" style="stroke: rgba(255, 255, 255, 0.298039); stroke-dasharray: 4, 2; shape-rendering: crispEdges;"></line><line class="d3-line-guides" x1="171.21875" y1="50" x2="171.21875" y2="15.384615384615383" style="stroke: rgba(255, 255, 255, 0.298039); stroke-dasharray: 4, 2; shape-rendering: crispEdges;"></line><line class="d3-line-guides" x1="221.62499999999997" y1="50" x2="221.62499999999997" y2="5" style="stroke: rgba(255, 255, 255, 0.298039); stroke-dasharray: 4, 2; shape-rendering: crispEdges;"></line><line class="d3-line-guides" x1="272.03125" y1="50" x2="272.03125" y2="36.15384615384615" style="stroke: rgba(255, 255, 255, 0.298039); stroke-dasharray: 4, 2; shape-rendering: crispEdges;"></line><line class="d3-line-guides" x1="322.4375" y1="50" x2="322.4375" y2="8.46153846153846" style="stroke: rgba(255, 255, 255, 0.298039); stroke-dasharray: 4, 2; shape-rendering: crispEdges;"></line></g><g><circle class="d3-line-circle d3-line-circle-medium" cx="20" cy="8.46153846153846" r="3" style="stroke: rgb(255, 255, 255); fill: rgb(41, 182, 246); opacity: 1;"></circle><circle class="d3-line-circle d3-line-circle-medium" cx="70.40625" cy="25.76923076923077" r="3" style="stroke: rgb(255, 255, 255); fill: rgb(41, 182, 246); opacity: 1;"></circle><circle class="d3-line-circle d3-line-circle-medium" cx="120.8125" cy="5" r="3" style="stroke: rgb(255, 255, 255); fill: rgb(41, 182, 246); opacity: 1;"></circle><circle class="d3-line-circle d3-line-circle-medium" cx="171.21875" cy="15.384615384615383" r="3" style="stroke: rgb(255, 255, 255); fill: rgb(41, 182, 246); opacity: 1;"></circle><circle class="d3-line-circle d3-line-circle-medium" cx="221.62499999999997" cy="5" r="3" style="stroke: rgb(255, 255, 255); fill: rgb(41, 182, 246); opacity: 1;"></circle><circle class="d3-line-circle d3-line-circle-medium" cx="272.03125" cy="36.15384615384615" r="3" style="stroke: rgb(255, 255, 255); fill: rgb(41, 182, 246); opacity: 1;"></circle><circle class="d3-line-circle d3-line-circle-medium" cx="322.4375" cy="8.46153846153846" r="3" style="stroke: rgb(255, 255, 255); fill: rgb(41, 182, 246); opacity: 1;"></circle></g></g></svg></div>
            </div>
            <!-- /today's revenue -->

        </div>
    </div>
@endsection
