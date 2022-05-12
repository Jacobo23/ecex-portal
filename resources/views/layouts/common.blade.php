<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Ecex') }}</title>
        <style>
            body
            {
 
                /*background-image: url("{{ asset('storage/images/background.jpg') }}");
                background-repeat: repeat;
                background-size: 40px 40px;*/
            }
        </style>

        <!-- Fonts -->
        <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;600;700&display=swap">
        
        <!-- Styles -->
        <link rel="stylesheet" href="{{ asset('css/app.css') }}">
        <link rel="stylesheet" href="{{ asset('css/common.css') }}">
        <!-- <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" rel="stylesheet"> -->
        <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css" rel="stylesheet">

        <!-- Scripts -->
        <script src="{{ asset('js/app.js') }}" defer></script>
        <!-- bootstrap css -->
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
        @yield('headers')
    </head>
    <body class="font-sans antialiased">
        <div class="min-h-screen shadow container-fluid"> <!-- bg-gray-100 -->
            @include('layouts.navigation')

            <!-- Page Content -->
            @yield('content')
            
        </div>
        <!-- bootstrap JS (down at the end of body) -->
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
        <script src = "https://ajax.googleapis.com/ajax/libs/jquery/2.1.3/jquery.min.js"></script>

        <!-- MODAL -->

        <!-- Button trigger modal -->
        <button id="linkmodal" type="button" data-bs-toggle="modal" data-bs-target="#alertModal" style="display:none;"></button>

        <!-- Modal -->
        <div class="modal fade" id="alertModal" tabindex="-1" aria-labelledby="TituloModal" aria-hidden="true">
        <div class="modal-dialog" id="alertModal_content">
            <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="TituloModal">Modal title</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div id="MsgModal" class="modal-body">
                ...
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
            </div>
            </div>
        </div>
        </div>

        <script>
            function showModal(titulo,msg)
            {
                $("#MsgModal").html(msg);
                $("#TituloModal").html(titulo);
                $("#linkmodal").click();
            }
        </script>

        <!-- FIN MODAL -->
        @yield('scripts')     
    </body>
</html>