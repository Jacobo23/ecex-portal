@extends('layouts.common')
@section('headers')
@endsection
@section('content')
<!-- Page Heading -->
<header class="bg-white shadow">
    <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Opciones
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
                        <h4 class="separtor">Entradas</h4>
                        <a class="selectMenu" href="/int/entradas/">Lista <i class="fas fa-list"></i></a>
                        <a class="selectMenu " href="/int/entradas/create">Crear <i class="fas fa-plus"></i></a>
                        <!-- <a class="selectMenu " href="/int/preentrada/create">Pre-Entrada <i class="fa-solid fa-cubes-stacked"></i></a> -->
                    </div>
                    <div class="col-lg-6">
                        <h4 class="separtor">Salidas</h4>
                        <a class="selectMenu" href="/int/salidas/">Lista <i class="fas fa-list"></i></a>
                        <a class="selectMenu " href="/int/salidas/create">Crear <i class="fas fa-plus"></i></a>
                    </div>
                    <div class="col-lg-6">
                        <h4 class="separtor">otros</h4>
                        <a class="selectMenu " href="int/ordenes_de_carga">Ordenes de carga<i class="fas fa-shopping-cart"></i></a>
                        <a class="selectMenu" href="/int/inventory">Inventario <i class="fas fa-th-list"></i></a>
                        <!--<a class="selectMenu " href="/int/cajas/">Cajas <i class="fas fa-trailer"></i></a> -->
                    </div>
                    <div class="col-lg-6">
                        <h4 class="separtor">Catalogos</h4>
                        <a class="selectMenu" href="/part_number">Números de parte<i class="fas fa-book"></i></a>
                        <a class="selectMenu " href="/int/catalog/customers">Clientes <i class="far fa-address-book"></i></a>
                        <a class="selectMenu " href="/int/catalog/suppliers">Proveedores <i class="fas fa-truck"></i></a>
                        <a class="selectMenu " href="/int/catalog/carriers">Trasportistas <i class="fas fa-bus"></i></a>
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
if (Auth::user()->type == "customer")
{
    echo 'window.location.replace("/");';
}
@endphp

</script>
@endsection