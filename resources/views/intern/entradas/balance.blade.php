@extends('layouts.common')
@section('headers')
<style>
    td
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
            Balance.
        </h2>
    </div>
</header>

<!-- Page Content -->

<div class="py-12">
<div class="max-w-8xl mx-auto sm:px-6 lg:px-8">
<div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
<div class="p-6 bg-white border-b border-gray-200">

        <form action="/int/balance" method="get">
        <div class="row">
            
            <div class="col-lg-2 controlDiv" style="">
                <label class="form-label">Entrada:</label>
                <input type="text" class="form-control" id="txtEntrada" name="entrada" value="@if(isset($income)){{ $income->getIncomeNumber() }}@endif" placeholder="Entrada">       
            </div>

            <div class="col-lg-2 controlDiv" style="position:relative;top:30px;">
                <button type="submit" class="btn btn-primary">Buscar</button>     
            </div>
            <div class="col-lg-2 controlDiv" style="position:relative;top:30px;">
                <button type="button" class="btn btn-outline-success" onclick="getPDF(@if(isset($income)){{ $income->id }}@endif)">Descargar <span style="color:red"><i class="far fa-file-pdf"></i></span></button>     
            </div>
        </div>
            
        </form>

        <h5 class="separtor">Desglose:</h5>



        <table class="table table-sm table-striped table-bordered table-hover">
            <thead>
                <tr>
                    <th scope="col">#</th>
                    <th scope="col">Numero de parte</th>
                    <th scope="col">Cantidad original</th>
                    <th scope="col">Cantidad restante</th>
                    <th scope="col">salidas</th>
                </tr>
            </thead>
            <tbody>
                @if(isset($income)) 
                
                
                @foreach ($income->income_rows as $income_row)
                <tr id="inc_row_{{ $income_row->id }}">
                    <td>{{ $loop->iteration }}</td>
                    <td>{{ $income_row->part_number()->part_number }}</td>
                    <td>{{ $income_row->units }}</td>
                    <td>{{ $income_row->units - $income_row->get_discounted_units() }}</td>
                    <td>
                    
                        <table class="table table-sm table-striped table-bordered table-hover">
                            <thead>
                                <tr>
                                    <th scope="col">#</th>
                                    <th scope="col">Salidas</th>
                                    <th scope="col">Cantidad</th>
                                    <th scope="col">descontada</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($income_row->get_discounting_outcomes_rows() as $outcome_row)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>{{ $outcome_row->outcome->getOutcomeNumber(false) }}</td>
                                    <td>{{ $outcome_row->units }}</td>
                                    <td>@if ($outcome_row->outcome->discount)<i class="fas fa-check-circle"></i>@else<i class="far fa-circle"></i>@endif</td>
                                </tr>
                                @endforeach

                            </tbody>
                        </table>

                    </td>
                    
                </tr>
                @endforeach
                @endif
                
            </tbody>
        </table>
</div>
</div>
</div>
</div>
@endsection
@section('scripts')
<script>


function getPDF(income_id)
{
    let path = "/int/balance_pdf/"+income_id;
    location.href = path;   
}

</script>
@endsection