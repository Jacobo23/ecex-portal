@extends('layouts.common_customer')
@section('headers')
<style>
    td, th
    {
        text-align:center;
    }
    .oversized-col
    {
        max-width:150px;
        overflow:hidden;
    }
</style>
@endsection
@section('content')
<!-- Page Heading -->
<header class="bg-white shadow">
    <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Entradas.
        </h2>
    </div>
</header>

<!-- Page Content -->

<div class="py-12">
<div class="max-w-8xl mx-auto sm:px-6 lg:px-8">
<div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
<div class="p-6 bg-white border-b border-gray-200">

        <h5 class="separtor">Filtros:</h5>

        <form action="/ext/entradas" method="get">
        <div class="row">
            <div class="col-lg-2 controlDiv" >
                <label class="form-label">Rango:</label>
                <select class="form-select" id = "txtRango" name = "txtRango">
                    <option value="30" selected>30 días</option>
                    <option value="90" @if ( $rango == 90) selected @endif >90 días</option>
                    <option value="190" @if ( $rango == 190) selected @endif >6 meses</option>
                    <option value="365" @if ( $rango == 365) selected @endif >1 año</option>
                </select>
            </div>
            <div class="col-lg-2 controlDiv" style="">
                <label class="form-label">Tracking:</label>
                <input type="text" class="form-control" id="txtTracking" name="txtTracking" value="{{ $tracking }}" placeholder="Tracking">       
            </div>
            
            <div class="col-lg-2 controlDiv" style="position:relative;top:30px;">
                <button type="submit" class="btn btn-primary">Buscar</button>     
            </div>
            <div class="col-lg-2 controlDiv" style="position:relative;top:30px;">
                <button type="button" class="btn btn-success" onclick="descargarXLS()">Descargar <i class="far fa-file-excel"></i></button>     
            </div>
        </div>
            
        </form>

        <h5 class="separtor">Lista:</h5>

        <table class="table table-sm table-striped table-bordered table-hover">
            <thead>
                <tr>
                    <th scope="col">Entrada #</th>
                    <th scope="col">Fecha</th>
                    <th scope="col">Revisada</th>
                    <th scope="col">Bultos</th>
                    <th scope="col">Dias</th>
                    <th scope="col">Proveedor</th>
                    <th scope="col">PO</th>
                    <th scope="col">Tracking</th>
                    <th scope="col">Impo/Expo</th>
                    <th scope="col">Adjuntos</th>
                    <th scope="col">Urgente</th>
                    <th scope="col">On-hold</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($incomes as $income)
                <tr id="inc_row_{{ $income->id }}" @if ( $income->get_color_fila_estado() != '') class="table-{{ $income->get_color_fila_estado() }}" @endif>
                    <td><a href="">{{ $income->getIncomeNumber() }}</a></td>
                    <td>{{ explode(" ", $income->cdate)[0] }}</td>
                    <td>@if ($income->reviewed) <i class="fas fa-check-square" style="color:green"></i> @endif</td>
                    <td>{{ $income->getBultos() }} {{ $income->getTipoBultos() }}</td>
                    <td>{{ $income->getDiasTrascurridos() }}</td>
                    <td>{{ $income->supplier->name }}</td>
                    <td>{{ $income->po }}</td>
                    <td class="oversized-col">{{ $income->tracking }}</td>
                    <td>{{ $income->impoExpo }}</td>
                    <td><button type="button" class="btn btn-light" onclick="showAdjuntos('adjuntos_income_{{ $income->id }}')"><i class="far fa-folder-open"></i></button></td>

                    <td id="adjuntos_income_{{ $income->id }}" style="display:none">
                        @php
                        $packinglist_path='/public/entradas/'.$income->getIncomeNumber().'/packing_list/packing-list.pdf';
                        if (Storage::exists($packinglist_path)) 
                        {
                            echo "<br>";
                            echo "<div class='img_card col-lg-12' style='padding:10px'>";
                            echo "    <div class='img_card_top'>";
                            echo "        <h6><b>Packing list</b></h6>"; 
                            echo "    </div>";
                            echo "    <p><a href='/download_pakinglist/".$income->getIncomeNumber()."'><i class='fas fa-arrow-circle-down'></i></a><strong>Tamaño: </strong> ". round(Storage::size($packinglist_path)/1000000,2,PHP_ROUND_HALF_UP ) ." Mb</p>";
                            echo "</div>";
                        }
                        $income_imgs_paths='public/entradas/'.$income->getIncomeNumber().'/images/';
                        $income_imgs = Storage::files($income_imgs_paths);
                        echo "<br>";

                        foreach ($income_imgs as $income_img) 
                        {
                            $img_file_name_array=explode('/',$income_img);

                            $img_file_name=$img_file_name_array[count($img_file_name_array)-1];
                            $img_file_url='storage/entradas/'.$income->getIncomeNumber().'/images/'.$img_file_name;

                            echo "<div class='img_card col-lg-5' >";
                            echo "    <div class='img_card_top'>";
                            echo "        <h6><b>".$img_file_name."</b></h6>"; 
                            echo "    </div>";
                            echo "    <img src='".asset($img_file_url)."'>";
                            echo "</div>";
                        }
                        @endphp
                    </td>
                    
                    <td>@if ($income->urgent) <i class="fas fa-check-square" style="color:red"></i> @endif</td>
                    <td>@if ($income->onhold) <button id="btn_onhold_{{ $income->id }}" class="btn btn-primary">on hold</button> @endif</td>
                </tr>
                @endforeach
            </tbody>
        </table>
</div>
</div>
</div>
</div>
@endsection
@section('scripts')
<script>

function descargarXLS()
{
    let path = "/ext/entradas_xls?txtRango="+$("#txtRango").val()+"&txtTracking="+$("#txtTracking").val();
    location.href = path;   
}

function showAdjuntos(content_row)
{
    var html = $("#"+content_row).html();   
    showModal("Adjuntos",html);
}
</script>
@endsection