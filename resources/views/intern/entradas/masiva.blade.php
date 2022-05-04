@extends('layouts.common')
@section('headers')
<style>
    th{
        text-align:center;
    }
</style>
@endsection
@section('content')
<!-- Page Heading -->
<header class="bg-white shadow">
    <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Importar masivo - {{$income_number}} <input type="hidden" name="incomeID" id="incomeID" value="{{$income->id}}">
        </h2>
    </div>
</header>

<!-- Page Content -->
<div class="py-12">
<div class="max-w-full mx-auto sm:px-6 lg:px-8">
<div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
<div class="p-6 bg-white border-b border-gray-200">

    <div class="row">

        <div class="btn-group col-lg-4" role="group">
            <button type="button" class="btn btn-outline-info" onclick="downloadTemplete()">Plantilla .xls <i class="fas fa-file-download"></i></button>
            <button type="button" class="btn btn-outline-primary" onclick="cargar()">Cargar archivo <i class="fas fa-file-upload"></i></button>
        </div>

        <br>
        <div style="display:none">
            <form id="frm_Cargar" action="/income_row_massive_load" method="post" enctype="multipart/form-data">
                @csrf
                <input type="file" id="txtArchivo" name="file" accept=".xlsx" onchange="subirArchivo()">
                <input type="text" id="fileNumEntrada" name="fileNumEntrada" value="{{$income_number}}">
            </form>
        </div>
    </div>
															

    <br><br>

<table class="table table-sm table-striped table-bordered table-hover">
    <thead>
        <tr>
            <th scope="col">Numero_de_Parte</th>
            <th scope="col">Description_Ing</th>
            <th scope="col">Descripcion_Esp</th>
            <th scope="col">Pais</th>
            <th scope="col">Cantidad</th>
            <th scope="col">UM</th>
            <th scope="col">Bultos</th>
            <th scope="col">TipoBulto</th>
            <th scope="col">PesoNeto</th>
            <th scope="col">PesoBruto</th>
            <th scope="col">Fraccion</th>
            <th scope="col">Nico</th>
            <th scope="col">PO</th>
            <th scope="col">Marca</th>
            <th scope="col">Modelo</th>
            <th scope="col">Serie</th>
            <th scope="col">Locacion</th>
            <th scope="col">Regimen</th>
            <th scope="col">Lote</th>
            <th scope="col">Skid</th>
            <th scope="col">Immex</th>
            <th scope="col"><i class="fas fa-exclamation-triangle"></i></th>
        </tr>
    </thead>
    <tbody>

    @if (isset($excel_data))
        @foreach ($excel_data as $excel_row)
        <tr id="mass_row_{{ $loop->index }}" @if ($excel_row->validation != "good") class="mass_row table-{{ $excel_row->validation }}" @else class="mass_row" @endif>
            <td id="f{{ $loop->index }}_part_number_name">{{ $excel_row->part_number_name }}</td>
            <td id="f{{ $loop->index }}_desc_ing">{{ $excel_row->desc_ing }}</td>
            <td id="f{{ $loop->index }}_desc_esp">{{ $excel_row->desc_esp }}</td>
            <td id="f{{ $loop->index }}_origin_country">{{ $excel_row->origin_country }}</td>
            <td id="f{{ $loop->index }}_units">{{ $excel_row->units }}</td>
            <td id="f{{ $loop->index }}_um">{{ $excel_row->um }}</td>
            <td id="f{{ $loop->index }}_bundles">{{ $excel_row->bundles }}</td>
            <td id="f{{ $loop->index }}_bundle_type">{{ $excel_row->bundle_type }}</td>
            <td id="f{{ $loop->index }}_net_weight">{{ $excel_row->net_weight }}</td>
            <td id="f{{ $loop->index }}_gross_weight">{{ $excel_row->gross_weight }}</td>
            <td id="f{{ $loop->index }}_fraccion">{{ $excel_row->fraccion }}</td>
            <td id="f{{ $loop->index }}_nico">{{ $excel_row->nico }}</td>
            <td id="f{{ $loop->index }}_po">{{ $excel_row->po }}</td>
            <td id="f{{ $loop->index }}_brand">{{ $excel_row->brand }}</td>
            <td id="f{{ $loop->index }}_model">{{ $excel_row->model }}</td>
            <td id="f{{ $loop->index }}_serial">{{ $excel_row->serial }}</td>
            <td id="f{{ $loop->index }}_location">{{ $excel_row->location }}</td>
            <td id="f{{ $loop->index }}_regime">{{ $excel_row->regime }}</td>
            <td id="f{{ $loop->index }}_lot">{{ $excel_row->lot }}</td>
            <td id="f{{ $loop->index }}_skids">{{ $excel_row->skids }}</td>
            <td id="f{{ $loop->index }}_imex">{{ $excel_row->imex }}</td>
            <td >@if ($excel_row->validation_msg != "")<button class="btn " onclick="showModal('Precauciones' , '{{ $excel_row->validation_msg }}')"><i class="far fa-eye"></i></button>@endif</td>
            <td id="f{{ $loop->index }}_income_id" style="display:none">{{ $excel_row->income_id }}</td>
            <td id="f{{ $loop->index }}_part_number_id" style="display:none">@if (!is_null($excel_row->part_number_id)){{ $excel_row->part_number_id }}@else 0 @endif</td>
        </tr>
        @endforeach
    @endif
        
    </tbody>
</table>


<div class="row" style="margin-top:20px;">
    <div class="col-lg-9 controlDiv"></div>
    <input type="button" class="col-lg-2 btn btn-success " value="Guardar" onclick="guardar()">
</div>  







</div>
</div>
</div>
</div>

@endsection
@section('scripts')
<script>

function subirArchivo()
{
    $("#frm_Cargar").submit();
}
function downloadTemplete()
{
    location.href = "/download_massive_template";
}
function cargar()
{
    $('#txtArchivo').click();
}
function guardar()
{
    let txtIncome_id = $("#incomeID").val();
    if(txtIncome_id == "" || txtIncome_id == 0)
    {
        showModal("Error", "Hubo un problema identificando la Entrada.");
        return;
    }
    let token = $("[name='_token']").val();
    //validar que todas las filas esten correctas
    let rows = $(".mass_row");
    let row_count = rows.length;
    let successful_rows = 0;
    let got_error = false;
    rows.each(function( index ) {
        if($( this ).attr('class').includes("danger"))
        {
            got_error = true;
            return;
        }
    });
    if(got_error)
    {
        showModal("Validación", "Algunas filas cuentan con un error de validación.<br>Revise las filas en 'rojo'.<br>Puede hacer click sobre el botón '<i class='far fa-eye'></i>' para obtener más detalles.");
        return;
    }

    $.ajax(
        {
            url: "/income_row_massive_clear_rows/"+txtIncome_id,
            type: 'POST',
            data: {
                "_token": token,
                "income":txtIncome_id,
            },
            success: function (response){
                console.log(response);
                if(response["status"]!=0)
                {
                    showModal("Error",response["msg"]);
                }
                else
                {
                    //En lugar de mandar un request por cada row vamos a mandar no solo
                    // for (let i = 0; i < row_count; i++) 
                    // {
                    //     $.ajax(
                    //         {
                    //             url: "/income_row_massive_store_row",
                    //             type: 'POST',
                    //             data: {
                    //                 "_token": token,
                    //                 "desc_ing": $("#f"+i+"_desc_ing").html(), 
                    //                 "desc_esp": $("#f"+i+"_desc_esp").html(), 
                    //                 "origin_country": $("#f"+i+"_origin_country").html(), 
                    //                 "units": $("#f"+i+"_units").html(), 
                    //                 "um": $("#f"+i+"_um").html(), 
                    //                 "bundles": $("#f"+i+"_bundles").html(), 
                    //                 "bundle_type": $("#f"+i+"_bundle_type").html(), 
                    //                 "net_weight": $("#f"+i+"_net_weight").html(), 
                    //                 "gross_weight": $("#f"+i+"_gross_weight").html(), 
                    //                 "fraccion": $("#f"+i+"_fraccion").html(), 
                    //                 "nico": $("#f"+i+"_nico").html(), 
                    //                 "po": $("#f"+i+"_po").html(), 
                    //                 "brand": $("#f"+i+"_brand").html(), 
                    //                 "model": $("#f"+i+"_model").html(), 
                    //                 "serial": $("#f"+i+"_serial").html(), 
                    //                 "location": $("#f"+i+"_location").html(), 
                    //                 "regime": $("#f"+i+"_regime").html(), 
                    //                 "lot": $("#f"+i+"_lot").html(), 
                    //                 "skids": $("#f"+i+"_skids").html(), 
                    //                 "imex": $("#f"+i+"_imex").html(), 
                    //                 "income_id": $("#f"+i+"_income_id").html(), 
                    //                 "part_number_id": $("#f"+i+"_part_number_id").html(), 
                    //                 "part_number_name": $("#f"+i+"_part_number_name").html(), 
                    //             },
                    //             success: function (response){
                    //                 let resp_id = Number(response);
                    //                 if(Number.isInteger(resp_id))
                    //                 {
                    //                     successful_rows++;
                    //                 }
                    //             }
                    //         }).done(function(data){
                    //                 //console.log( data );                                 
                    //                 //es la utima fila?
                    //                 if(i == row_count-1)
                    //                 {
                    //                     let color = "red";
                    //                     if(row_count == successful_rows)
                    //                     {
                    //                         color = "green";
                    //                     }
                    //                     showModal("Notificación", "Se cargaron <strong style='color:"+color+"'>"+ successful_rows +"</strong> de <strong style='color:"+color+"'>"+row_count+"</strong> partidas.");
                    //                     location.href = "/int/entradas/{{$income_number}}";
                    //                 }
                    //             });;
                    // }

                    $.ajax({url: "/income_row_massive_store_all/"+txtIncome_id,context: document.body}).done(function(response) 
                    {
                        location.href = "/int/entradas/{{$income_number}}";
                    });

                }
                
            }
        });


    


}


</script>
@endsection