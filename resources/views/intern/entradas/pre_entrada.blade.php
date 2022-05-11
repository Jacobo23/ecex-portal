@extends('layouts.common')
@section('headers')
@endsection
@section('content')
<!-- Page Heading -->
<header class="bg-white shadow">
    <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Pre-Entrada
        </h2>
    </div>
</header>

<!-- Page Content -->
<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6 bg-white border-b border-gray-200">
            
            <form action="/int/preentrada/imprimir" method="post" enctype="multipart/form-data">
                @csrf

            <div class="row">
                <div class="col-lg-3 controlDiv" >
                    <label class="form-label">Numero de Entrada:</label>
                    <input type="text" class="form-control" id="txtNumEntrada" name="txtNumEntrada" readonly style="text-align:center;">       
                </div>

                <div class="col-lg-3 controlDiv" >
                    <label class="form-label">Fecha:</label>
                    <input type="date" class="form-control" id="txtFecha" name="txtFecha" value="{{date('Y-m-d')}}">
                </div>

                <div class="col-lg-3 controlDiv" >
                    <label class="form-label">Cliente:</label>
                    <select class="form-select" id = "txtCliente" name = "txtCliente" onchange="checkCampoCliente()">
                    <option value=0 selected></option>
                    @foreach ($clientes as $clienteOp)
                    <option value="{{ $clienteOp->id }}" @php if(isset($income)){if($income->customer_id == $clienteOp->id){echo "selected";}}@endphp >{{ $clienteOp->name }}</option>
                    @endforeach
                    </select>
                </div>

                <div class="col-lg-3 controlDiv" >
                    <label class="form-label">Bultos:</label>
                    <input type="number" class="form-control" id="txtBultos" name="txtBultos">       
                </div>

            </div>

            <div class="row">
                <div class="col-lg-3 controlDiv" >
                    <label class="form-label">Transportista:</label>
                    <select class="form-select" id = "txtTransportista" name = "txtTransportista" onchange="agregarTransportista()">
                    <option value=0 selected></option>
                    @foreach ($transportistas as $transportistaOp)
                    <option value="{{ $transportistaOp->id }}" @php if(isset($income)){if($income->carrier_id == $transportistaOp->id){echo "selected";}}@endphp >{{ $transportistaOp->name }}</option>
                    @endforeach
                    <option value = "-2" id="option_new_transportista" >(Crear nuevo +)</option>
                    </select>
                </div>
                <div class="col-lg-3" >
                    <label class="form-label">Proveedor:</label>
                    <select class="form-select" id = "txtProveedor" name = "txtProveedor" onchange="agregarProveedor()">
                    <option value=0 selected></option>
                    @foreach ($proveedores as $proveedoresOp)
                    <option value="{{ $proveedoresOp->id }}" @php if(isset($income)){if($income->supplier_id == $proveedoresOp->id){echo "selected";}}@endphp >{{ $proveedoresOp->name }}</option>
                    @endforeach
                    <option value = "-2" id="option_new_proveedor" >(Crear nuevo +)</option>
                    </select>
                </div>

                <div class="col-lg-3 controlDiv" >
                    <label class="form-label">Usuario:</label>
                    <input type="text" class="form-control" id="txtUsuario" name="txtUsuario" value="">       
                </div>

                <div class="col-lg-3 controlDiv" >
                    <label class="form-label">Archivo:</label>
                    <input type="file" class="form-control" id="txtArchivo" name="file" accept="application/pdf">       
                </div>

            </div>

            <div class="row">
                <div class="mb-3 col-lg-6">
                    <label class="form-label">Observaciones</label>
                    <textarea class="form-control" id="txtObservaciones" name="txtObservaciones" rows="2"></textarea>
                </div>

                <input type="submit" class="btn btn-success" value="Guardar & Imprimir">
            </div>

            </form>


                
            </div>
        </div>
    </div>
</div>

@endsection
@section('scripts')
<script>


</script>
@endsection