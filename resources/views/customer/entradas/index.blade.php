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
                    <option value="15" selected>15 días</option>
                    <option value="30" @if ( $rango == 30) selected @endif >30 días</option>
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

        <div class="row">
            <div class="col-lg-10">
            </div>
            <div class="col-lg-2">
                <input type="text" class="form-control" id="txtQuickSearch" placeholder="Busca rapida">       
            </div>
        </div>
        <br>

        <table class="table table-sm table-striped table-bordered table-hover">
            <thead>
                <tr>
                    <th scope="col">Entrada #</th>
                    <th scope="col">Fecha</th>
                    <th scope="col"><input type="checkbox" class="form-check-input" id="chkRevisada" onclick="filtrarRevisadas()"> Revisada</th>
                    <th scope="col">Bultos</th>
                    <th scope="col">Dias</th>
                    <th scope="col">Proveedor</th>
                    <th scope="col">PO</th>
                    <th scope="col">Tracking</th>
                    <th scope="col">Impo/Expo</th>
                    <th scope="col">Materia/Equipo</th>
                    <th scope="col">Adjuntos</th>
                    <th scope="col"><input type="checkbox" class="form-check-input" id="chkUrgente" onclick="filtrarUrgentes()"> Urgente</th>
                    <th scope="col"><input type="checkbox" class="form-check-input" id="chkOnhold" onclick="filtrarOnhold()"> On-hold</th>
                </tr>
            </thead>
            <tbody id="tbl_Incomes">
                @foreach ($incomes as $income)
                <tr id="inc_row_{{ $income->id }}" @if ( $income->get_color_fila_estado() != '') class="tr_tbl table-{{ $income->get_color_fila_estado() }}" @endif>
                    <td><a href="/ext/entradas/{{ $income->id }}/download_pdf">{{ $income->getIncomeNumber() }}</a></td>
                    <td>{{ date_format(date_create(explode(" ", $income->cdate)[0]),"m/d/y") }}</td>
                    <td id="tdRevisada_{{ $income->id }}">@if ($income->reviewed) <i class="fas fa-check-square" style="color:green"></i> @endif</td>
                    <td>{{ $income->getBultosOriginales() }} {{ $income->getTipoBultos() }}</td>
                    <td>{{ $income->getDiasTrascurridos() }}</td>
                    <td>{{ $income->supplier->name }}</td>
                    <td>{{ $income->po }}</td>
                    <td class="oversized-col">{{ $income->tracking }}</td>
                    <td>{{ $income->impoExpo }}</td>
                    <td>{{ $income->type }}</td>
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
                    <td id="tdUrgente_{{ $income->id }}">@if ($income->urgent) <i class="fas fa-check-square" style="color:red"></i> @endif</td>
                    <td id="tdOnhold_{{ $income->id }}">@if ($income->onhold) @if ($can_quit_onhold) <button id="btn_onhold_{{ $income->id }}" class="btn btn-primary" onclick="quitarOnhold({{ $income->id }})">on hold</button> @else on hold @endif @endif</td>
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

function quitarOnhold(income_id)
{
    if(!confirm("Desea quitar el estado 'on hold' de esta entrada"))
    {
        return;
    }
    $.ajax({url: "/quitar_on_hold/" + income_id,context: document.body}).done(function(response) 
        {
            showModal("Notificación","Se ha enviado la notificación a almacén.");
            $("#btn_onhold_"+income_id).remove();
        });
}


function filtrarRevisadas()
{
    $(".tr_tbl").each(function()
        {
            $(this).show();
        });

    $('#chkUrgente').prop('checked', false);
    $('#chkOnhold').prop('checked', false);

    if($('#chkRevisada').prop('checked'))
    {        
        $(".tr_tbl").each(function()
        {
            var index = $(this).attr('id').split("_")[2];
            if( $("#tdRevisada_"+index).html() == "")
            {
                $("#inc_row_"+index).hide();
            }
        });
    }
}

function filtrarUrgentes()
{
    $(".tr_tbl").each(function()
        {
            $(this).show();
        });

    $('#chkRevisada').prop('checked', false);
    $('#chkOnhold').prop('checked', false);

    if($('#chkUrgente').prop('checked'))
    {        
        $(".tr_tbl").each(function()
        {
            var index = $(this).attr('id').split("_")[2];
            if( $("#tdUrgente_"+index).html() == "")
            {
                $("#inc_row_"+index).hide();
            }
        });
    }
}

function filtrarOnhold()
{
    $(".tr_tbl").each(function()
        {
            $(this).show();
        });

    $('#chkRevisada').prop('checked', false);
    $('#chkUrgente').prop('checked', false);

    if($('#chkOnhold').prop('checked'))
    {        
        $(".tr_tbl").each(function()
        {
            var index = $(this).attr('id').split("_")[2];
            if( $("#tdOnhold_"+index).html() == "")
            {
                $("#inc_row_"+index).hide();
            }
        });
    }
}


$(document).ready(function(){
  $("#txtQuickSearch").on("keyup", function() {
    var value = $(this).val().toLowerCase();
    $("#tbl_Incomes tr").filter(function() {
      $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1)
    });
  });
});
</script>
@endsection