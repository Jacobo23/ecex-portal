@extends('layouts.common_customer')
@section('headers')
@endsection
@section('content')
<!-- Page Heading -->
<header class="bg-white shadow">
    <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Bienvenido - {{ Auth::user()->name }}
        </h2>
    </div>
</header>

<!-- Page Content -->
<div class="py-12">
    <div class="max-w-5xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6 bg-white border-b border-gray-200" >
                <div class="row">
                    <div class="col-lg-6">
                        <h4 class="separtor">Principal</h4>
                        <a class="selectMenu" href="/ext/entradas/">Entradas <i class="fas fa-sign-in-alt"></i></a>
                        <a class="selectMenu" href="/ext/inventario/">Inventario <i class="fas fa-pallet"></i></a>
                    </div>
                    <div class="col-lg-6">
                        <h4 class="separtor">&nbsp;</h4>
                        <a class="selectMenu" href="/ext/salidas/">Salidas <i class="fas fa-sign-out-alt"></i></a>
                        <a class="selectMenu " href="/ext/ordenes_de_carga/">Ordenes de carga<i class="fas fa-shopping-cart"></i></a>
                        <!--<a class="selectMenu" href="/ext/capacidad/">Capacidad <i class="fas fa-chart-pie"></i></a>
                        <a class="selectMenu " href="/ext/cajas">Cajas <i class="fas fa-trailer"></i></a> -->
                    </div>                    
                </div>
            </div>
        </div>
    </div>
</div>

@endsection
@section('scripts')
<script>

@php
if (Auth::user()->type == "user")
{
    echo 'window.location.replace("/");';
}
@endphp


</script>
@endsection