@extends('layouts.common_customer')
@section('headers')
<script src="https://cdn.jsdelivr.net/npm/litepicker/dist/litepicker.js"></script>
<style>
    td
    {
        text-align:center;
    }
</style>
@endsection
@section('content')
<!-- Page Heading -->
<header class="bg-white shadow">
    <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Archivos.
        </h2>
    </div>
</header>

<!-- Page Content -->

<div class="py-12">
<div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
<div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
<div class="p-6 bg-white border-b border-gray-200">

    

        

        <h5 class="separtor">{{ $outcome->getOutcomeNumber(false) }}</h5>

        <br>

        <div class='img_card col-lg-3' style='padding:10px'>
            <div class='img_card_top'>
            <h6><a href='/ext/salidas/{{ $outcome->id }}/download_pdf'><i class="fa-solid fa-file-pdf"></i></a>  <b>PDF</b> </h6>
            </div>
            
        
        </div>


        <h6 class="separtor">PDF's de Salida</h6>
            <br>

        @foreach ($pdfs_salida as $pdf_salida)
            @php
                $arr = explode(' = ',$pdf_salida);
                $nombre = $arr[0];
                $path_pdf_salida = str_replace('public','storage',$arr[1]);
            @endphp
            <div class='img_card col-lg-3' style='padding:10px'>
            <div class='img_card_top'>
            <h6><a href='{{ asset($path_pdf_salida) }}'><i class="fa-solid fa-file-pdf"></i></a> <b>{{ $nombre }}</b></h6>
            </div>
            
            </div>
        @endforeach

        <h6 class="separtor">Imagenes de Salida</h6>

        @foreach ($imagenes_salida as $imagen_salida)
            @php
                $arr = explode(' = ',$imagen_salida);
                $nombre = $arr[0];
                $path_imagen_salida = str_replace('public','storage',$arr[1]);
            @endphp
            <div class='img_card col-lg-3' >
                <div class='img_card_top'>
                <h6><b>{{ $nombre }}</b></h6>
                 </div>
                 <img src="{{ asset($path_imagen_salida) }}">
                </div>
        @endforeach
        

        <h6 class="separtor">Entradas</h6>


        @foreach ($entradas as $entrada)
        <div class='img_card col-lg-3' style='padding:10px'>
            <div class='img_card_top'>
            <h6><a href='/ext/entradas/{{ $entrada->id }}/download_pdf'><i class="fa-solid fa-file-pdf"></i></a> <b>PDF - {{ $entrada->getIncomeNumber()}}</b></h6>
            </div>
        </div>
        @endforeach




        <h6 class="separtor">PDF's de Entradas</h6>
            <br>



        @foreach ($pdfs_entradas as $pdf_entradas)

            @php
                $arr = explode(' = ',$pdf_entradas);
                $nombre = $arr[0];
                $path_pdf_entradas = str_replace('public','storage',$arr[1]);
            @endphp
            <div class='img_card col-lg-3' style='padding:10px'>
            <div class='img_card_top'>
            <h6><a href='{{ asset($path_pdf_entradas) }}'><i class="fa-solid fa-file-pdf"></i></a> <b>{{ $nombre }}</b></h6>
            </div>
            
            </div>
        @endforeach

        <h6 class="separtor">Imagenes de Entradas</h6>

        @foreach ($imagenes_entradas as $imagen_entradas)
            @php
                $arr = explode(' = ',$imagen_entradas);
                $nombre = $arr[0];
                $path_imagen_entradas = str_replace('public','storage',$arr[1]);
            @endphp
            <div class='img_card col-lg-3' >
                <div class='img_card_top'>
                <h6><b>{{ $nombre }}</b></h6>
                 </div>
                 <img src="{{ asset($path_imagen_entradas) }}">
                </div>
        @endforeach        





        <br><br>

        



</div>
</div>
</div>
</div>
@endsection
@section('scripts')
<script>


</script>
@endsection