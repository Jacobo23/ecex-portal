@extends('layouts.common')
@section('headers')
@endsection
@section('content')
<!-- Page Heading -->
<header class="bg-white shadow">
    <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Encabezado
        </h2>
    </div>
</header>

<!-- Page Content -->
<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6 bg-white border-b border-gray-200">
                Seccion 1
            </div>
        </div>
    </div>
</div>

<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6 bg-white border-b border-gray-200">
                Seccion 2
                <button class="btn btn-primary" onclick="myFunction()">boostrap</button>
            </div>
        </div>
    </div>
</div>

<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6 bg-white border-b border-gray-200">
                <h5><strong>Seccion 3</strong> - controles de formulario</h5>

                <div class="mb-3">
                    <label for="exampleFormControlInput1" class="form-label">Ancho</label>
                    <input type="text" class="form-control"  placeholder="texto">
                </div>

                <h5 class="separtor">Grid & rows</h5>

                <div class="row">
                    <div class="col-sm-12 col-lg-4" style="display: inline-block;">
                        <label class="form-label">Grid</label>
                        <input type="text" class="form-control"  placeholder="texto">
                    </div>

                    <div class="col-sm-12 col-lg-4" style="display: inline-block;">
                        <label class="form-label">Grid</label>
                        <input type="text" class="form-control"  placeholder="texto">
                    </div>

                    <div class="col-sm-12 col-lg-4" style="display: inline-block;">
                        <label class="form-label">Grid</label>
                        <input type="text" class="form-control"  placeholder="texto">
                    </div>
                </div>

                <h5 class="separtor">Controles +</h5>

                <div class="row">

                    <div class="col-sm-12 col-lg-4" style="display: inline-block;">
                        <label  class="form-label">date</label>
                        <select class="form-select" aria-label="Default select example">
                            <option selected>Open this select menu</option>
                            <option value="1">One</option>
                            <option value="2">Two</option>
                            <option value="3">Three</option>
                        </select>
                    </div>


                    <div class="col-sm-12 col-lg-4" style="display: inline-block;">
                        <label  class="form-label">date</label>
                        <input type="date" class="form-control"  placeholder="texto">
                    </div>

                    <div class="col-sm-12 col-lg-4" style="display: inline-block;">
                        <label for="exampleFormControlInput1" class="form-label">Grid</label>
                        <input type="text" class="form-control"  placeholder="texto">
                    </div>
                </div>




            </div>
        </div>
    </div>
</div>
@endsection
@section('scripts')
<script>

function myFunction()
{
  alert("hola");
}

</script>
@endsection