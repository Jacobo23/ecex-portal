@extends('layouts.common')
@section('headers')
@endsection
@section('content')
<!-- Page Heading -->
<header class="bg-white shadow">
    <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Número de parte
        </h2>
    </div>
</header>

<!-- Page Content -->
<div class="py-12">
<div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
<div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
<div class="p-6 bg-white border-b border-gray-200">

<form id="PartNumberForm" action="/part_number" method="post">
@csrf
<div class="row">
    <div class="col-lg-3 controlDiv" >
        <label class="form-label">Número de parte:</label>
        <input type="text" class="form-control" id="txtNumeroDeParte" name="txtNumeroDeParte" value="{{ $part_number ?? '' }}" style="text-align:center;" @if ($part_number != '') readonly @endif>       
    </div>
    <div class="col-lg-3 controlDiv" >
        <label class="form-label">Cliente:</label>
        <select class="form-select" id = "txtCliente" name = "txtCliente" @if (isset($cliente)) readonly @endif>
        <option value=0 selected></option>
        @foreach ($clientes as $clienteOp)
        <option value="{{ $clienteOp->id }}" @php if(isset($cliente)){if($cliente == $clienteOp->id){echo "selected ";}else{echo "disabled ";}}@endphp >{{ $clienteOp->name }}</option>
        @endforeach
        </select>
    </div>
    <div class="col-lg-3 controlDiv" >
        <label class="form-label">UM:</label>
        <select class="form-select" id = "txtUM" name = "txtUM">
        @foreach ($unidades_de_medida as $unidade_de_medidaOp)
        <option value="{{ $unidade_de_medidaOp->desc }}">{{ $unidade_de_medidaOp->desc }}</option>
        @endforeach
        </select>
    </div>
    <div class="col-lg-3 controlDiv" >
        <label class="form-label">Peso unitario:</label>
        <input type="number" class="form-control" id="txtPesoUnitario" name="txtPesoUnitario" value="@if (isset($part_number_obj)){{ $part_number_obj->unit_weight }}@endif">       
    </div>
</div>
<div class="row">
    <div class="col-lg-3 controlDiv" >
        <label class="form-label">Descripción Inglés:</label>
        <input type="text" class="form-control" id="txtDescIng" name="txtDescIng" value="@if (isset($part_number_obj)){{ $part_number_obj->desc_ing }}@endif">       
    </div>
    <div class="col-lg-3 controlDiv" >
        <label class="form-label">Descripción Español:</label>
        <input type="text" class="form-control" id="txtDescEsp" name="txtDescEsp" value="@if (isset($part_number_obj)){{ $part_number_obj->desc_esp }}@endif">       
    </div>
    <div class="col-lg-2 controlDiv" >
        <label class="form-label">País:</label>
        <input type="text" class="form-control" id="txtPais" name="txtPais" value="@if (isset($part_number_obj)){{ $part_number_obj->origin_country }}@endif" list="listaPaises">       
    </div>
    <div class="col-lg-2 controlDiv" >
        <label class="form-label">fracción:</label>
        <input type="text" class="form-control" id="txtFraccion" name="txtFraccion" value="@if (isset($part_number_obj)){{ $part_number_obj->fraccion }}@endif">       
    </div>
    <div class="col-lg-2 controlDiv" >
        <label class="form-label">nico:</label>
        <input type="text" class="form-control" id="txtNico" name="txtNico" value="@if (isset($part_number_obj)){{ $part_number_obj->nico }}@endif">       
    </div>
</div>
<div class="row">
    <div class="col-lg-3 controlDiv" >
        <label class="form-label">marca:</label>
        <input type="text" class="form-control" id="txtMarca" name="txtMarca" value="@if (isset($part_number_obj)){{ $part_number_obj->brand }}@endif">       
    </div>
    <div class="col-lg-3 controlDiv" >
        <label class="form-label">modelo:</label>
        <input type="text" class="form-control" id="txtModelo" name="txtModelo" value="@if (isset($part_number_obj)){{ $part_number_obj->model }}@endif">       
    </div>
    <div class="col-lg-2 controlDiv" >
        <label class="form-label">serie:</label>
        <input type="text" class="form-control" id="txtSerie" name="txtSerie" value="@if (isset($part_number_obj)){{ $part_number_obj->serial }}@endif">       
    </div>
    <div class="col-lg-2 controlDiv" >
        <label class="form-label">IMMEX:</label>
        <input type="text" class="form-control" id="txtIMMEX" name="txtIMMEX" value="@if (isset($part_number_obj)){{ $part_number_obj->imex }}@endif">       
    </div>
    <div class="col-lg-2 controlDiv" >
        <label class="form-label">regimen:</label>
        <input type="text" class="form-control" id="txtRegimen" name="txtRegimen" value="@if (isset($part_number_obj)){{ $part_number_obj->regime }}@endif">       
    </div>
</div>

<div class="mb-3">
    <label class="form-label">Observaciones de fracción...{{ $from_income ?? '' }}...</label>
    <textarea class="form-control" id="txtObservacionesFraccion" name="txtObservacionesFraccion" rows="2">@if (isset($part_number_obj)){{ $part_number_obj->fraccion_especial }}@endif</textarea>
</div>

<input type="hidden" name="from_Incomes" value="{{ $from_income ?? '' }}">

<div class="row" style="margin-top:20px;">
    <div class="col-lg-6 controlDiv"></div>
    <input type="button" class="col-lg-2 btn btn-danger " value="Cancelar" onclick="cancel()">
    <div class="col-lg-1 controlDiv"></div>
    <input type="button" class="col-lg-3 btn btn-success " value="Guardar" onclick="guardar()">
</div>
</form>

<datalist id="listaPaises">
<option value='AF'>AFGANISTAN</option>
<option value='AL'>ALBANIA</option>
<option value='DE'>ALEMANIA</option>
<option value='AD'>ANDORRA</option>
<option value='AO'>ANGOLA</option>
<option value='AI'>ANGUILA</option>
<option value='AG'>ANTIGUA Y BARBUDA</option>
<option value='1'>ANTILLAS N.</option>
<option value='SA'>ARABIA S.</option>
<option value='2'>ARGELIA</option>
<option value='AR'>ARGENTINA</option>
<option value='AM'>ARMENIA</option>
<option value='AW'>ARUBA</option>
<option value='AU'>AUSTRALIA</option>
<option value='AT'>AUSTRIA</option>
<option value='AZ'>AZERBAIJAN</option>
<option value='BS'>BAHAMAS</option>
<option value='BH'>BAHREIN</option>
<option value='BD'>BANGLADESH</option>
<option value='BB'>BARBADOS</option>
<option value='BE'>BELGICA</option>
<option value='BZ'>BELICE</option>
<option value='BJ'>BENIN</option>
<option value='BM'>BERMUDAS</option>
<option value='8'>BIELORRUSIA</option>
<option value='BO'>BOLIVIA</option>
<option value='BA'>BOSNIA Y H.</option>
<option value='BW'>BOTSWANA</option>
<option value='BR'>BRASIL</option>
<option value='BN'>BRUNEI</option>
<option value='BG'>BULGARIA</option>
<option value='BF'>B. FASO</option>
<option value='BI'>BURUNDI</option>
<option value='3'>BUTAN</option>
<option value='CV'>C. VERDE</option>
<option value='KY'>CAIMAN</option>
<option value='4'>CAMBOYA</option>
<option value='CM'>CAMERUN</option>
<option value='CA'>CANADA</option>
<option value='5'>I. DEL CANAL</option>
<option value='TD'>CHAD</option>
<option value='CL'>CHILE</option>
<option value='CN'>CHINA</option>
<option value='CY'>CHIPRE</option>
<option value='VA'>VATICANO</option>
<option value='CC'>COCOS</option>
<option value='CO'>COLOMBIA</option>
<option value='KM'>COMORAS</option>
<option value='EU'>U. EUROPEA</option>
<option value='CG'>CONGO</option>
<option value='CK'>COOK</option>
<option value='KR'>COREA</option>
<option value='KP'>COREA</option>
<option value='CI'>C. DE MARFIL</option>
<option value='CR'>COSTA RICA</option>
<option value='HR'>CROACIA</option>
<option value='CU'>CUBA</option>
<option value='9'>CURAZAO</option>
<option value='DK'>DINAMARCA</option>
<option value='DJ'>DJIBOUTI</option>
<option value='DM'>DOMINICA</option>
<option value='EC'>ECUADOR</option>
<option value='EG'>EGIPTO</option>
<option value='SV'>EL SALVADOR</option>
<option value='12'>E. ARABES U.</option>
<option value='11'>ERITREA</option>
<option value='SK'>ESLOVAQUIA</option>
<option value='SI'>ESLOVENIA</option>
<option value='ES'>ESPA¥A</option>
<option value='FM'>MICRONESIA</option>
<option value='US'>E.U.A.</option>
<option value='EN'>ESTONIA</option>
<option value='ET'>ETIOPIA</option>
<option value='FJ'>FIJI</option>
<option value='PH'>FILIPINAS</option>
<option value='FI'>FINLANDIA</option>
<option value='FR'>FRANCIA</option>
<option value='GZ'>GAZA</option>
<option value='GM'>GAMBIA</option>
<option value='GE'>GEORGIA</option>
<option value='GA'>GHANA</option>
<option value='GD'>GRANADA</option>
<option value='GR'>GRECIA</option>
<option value='GL'>GROENLANDIA</option>
<option value='GP'>GUADALUPE</option>
<option value='GU'>GUAM</option>
<option value='GT'>GUATEMALA</option>
<option value='GN'>GUINEA</option>
<option value='GO'>GUINEA E.</option>
<option value='GW'>GUINEA B.</option>
<option value='GY'>GUYANA</option>
<option value='GF'>GUYANA FRANCESA</option>
<option value='HT'>HAITI</option>
<option value='HN'>HONDURAS</option>
<option value='HK'>HONG KONG</option>
<option value='HU'>HUNGRIA</option>
<option value='IN'>INDIA</option>
<option value='ID'>INDONESIA</option>
<option value='IQ'>IRAK</option>
<option value='IR'>IRAN</option>
<option value='IE'>IRLANDA</option>
<option value='IS'>ISLANDIA</option>
<option value='HM'>HEARD Y MCDONALD</option>
<option value='FK'>MALVINAS</option>
<option value='13'>MARIANAS</option>
<option value='MH'>MARSHALL</option>
<option value='SB'>SALOMON</option>
<option value='SJ'>SVALBARD Y J. M.</option>
<option value='TK'>TOKELAU</option>
<option value='WF'>WALLIS Y FUTUNA</option>
<option value='IL'>ISRAEL</option>
<option value='IT'>ITALIA</option>
<option value='JM'>JAMAICA</option>
<option value='JP'>JAPON</option>
<option value='JO'>JORDANIA</option>
<option value='KZ'>KAZAKHSTAN</option>
<option value='KE'>KENYA</option>
<option value='KI'>KIRIBATI</option>
<option value='KW'>KUWAIT</option>
<option value='KG'>KYRGYZSTAN</option>
<option value='LS'>LESOTHO</option>
<option value='LV'>LETONIA</option>
<option value='LB'>LIBANO</option>
<option value='LR'>LIBERIA</option>
<option value='LY'>LIBIA</option>
<option value='LI'>LIECHTENSTEIN</option>
<option value='LT'>LITUANIA</option>
<option value='LX'>LUXEMBURGO</option>
<option value='MO'>MACAO</option>
<option value='MK'>MACEDONIA</option>
<option value='MG'>MADAGASCAR</option>
<option value='MY'>MALASIA</option>
<option value='MW'>MALAWI</option>
<option value='MV'>MALDIVAS</option>
<option value='ML'>MALI</option>
<option value='MT'>MALTA</option>
<option value='MA'>MARRUECOS</option>
<option value='MQ'>MARTINICA</option>
<option value='MU'>MAURICIO</option>
<option value='MR'>MAURITANIA</option>
<option value='MX'>MEXICO</option>
<option value='MD'>MOLDAVIA</option>
<option value='MC'>MONACO</option>
<option value='MN'>MONGOLIA</option>
<option value='MS'>MONSERRAT</option>
<option value='MZ'>MOZAMBIQUE</option>
<option value='MM'>MYANMAR</option>
<option value='NA'>NAMIBIA</option>
<option value='NR'>NAURU</option>
<option value='CX'>NAVIDAD</option>
<option value='NL'></option>
<option value='NP'>NEPAL</option>
<option value='NI'>NICARAGUA</option>
<option value='NE'>NIGER</option>
<option value='NG'>NIGERIA</option>
<option value='NU'>NIVE</option>
<option value='NF'>NORFOLK</option>
<option value='NO'>NORUEGA</option>
<option value='NC'>N. CALEDONIA</option>
<option value='NZ'>N. ZELANDA</option>
<option value='OM'>OMAN</option>
<option value='14'>I. DEL PACIFICO</option>
<option value='6'>P. BAJOS</option>
<option value='7'>PAISES N.D.</option>
<option value='PK'>PAKISTAN</option>
<option value='PW'>PALAU</option>
<option value='PA'>PANAMA</option>
<option value='PG'>PAPUA N. GUINEA</option>
<option value='PY'>PARAGUAY</option>
<option value='PE'>PERU</option>
<option value='PN'>PITCAIRNS</option>
<option value='PF'>P. FRANCESA</option>
<option value='PL'>POLONIA</option>
<option value='PT'>PORTUGAL</option>
<option value='PR'>PUERTO RICO</option>
<option value='QA'>QATAR</option>
<option value='GB'>REINO U.</option>
<option value='CF'>R. CENTROAFRICANA</option>
<option value='CZ'>REPUBLICA CHECA</option>
<option value='LA'>R. LAOS</option>
<option value='DO'>R DOMINICANA</option>
<option value='20'>R DEL CONGO</option>
<option value='15'>R. RUANDESA</option>
<option value='17'>R SLOVAKIA</option>
<option value='RE'>REUNION</option>
<option value='RO'>RUMANIA</option>
<option value='RU'>RUSIA</option>
<option value='10'>SAHARA O.</option>
<option value='16'>SAMOA</option>
<option value='KN'>SAN CRISTOBAL.</option>
<option value='SM'>SAN MARINO</option>
<option value='PM'>SAN PEDRO</option>
<option value='VC'>SAN VICENTE</option>
<option value='SH'>SANTA ELENA</option>
<option value='LC'>SANTA LUCIA</option>
<option value='ST'>SANTO TOME</option>
<option value='SN'>SENEGAL</option>
<option value='SC'>SEYCHELLES</option>
<option value='SL'>S. LEONA</option>
<option value='00'>SIN PAIS</option>
<option value='SG'>SINGAPUR</option>
<option value='SY'>SIRIA</option>
<option value='SO'>SOMALIA</option>
<option value='LK'>SRI LANKA</option>
<option value='ZA'>SUDAFRICA</option>
<option value='SD'>SUDAN</option>
<option value='SE'>SUECIA</option>
<option value='CH'>SUIZA</option>
<option value='SR'>SURINAME</option>
<option value='SZ'>SWAZILANDIA</option>
<option value='TJ'>TADJIKISTAN</option>
<option value='TH'>TAILANDIA</option>
<option value='TW'>TAIWAN</option>
<option value='TZ'>TANZANIA</option>
<option value='IO'>T. BRITANICOS O. INDICO</option>
<option value='18'>T. FRANCESES AUSTRALES</option>
<option value='19'>TIMOR ORIENTAL</option>
<option value='TG'>TOGO</option>
<option value='TO'>TONGA</option>
<option value='TT'>TRINIDAD Y TOBAGO</option>
<option value='TN'>TUNEZ</option>
<option value='TC'>TURCAS Y CAICOS</option>
<option value='TM'>TURKMENISTAN</option>
<option value='TR'>TURQUIA</option>
<option value='TV'>TUVALU</option>
<option value='UA'>UCRANIA</option>
<option value='UG'>UGANDA</option>
<option value='UY'>URUGUAY</option>
<option value='UZ'>UZBEJISTAN</option>
<option value='VU'>VANUATU</option>
<option value='VE'>VENEZUELA</option>
<option value='VN'>VIETNAM</option>
<option value='VG'>I.VIRGENES BRIT</option>
<option value='VI'>I.VIRGENES AMER</option>
<option value='YD'>YEMEN</option>
<option value='YU'>YUGOSLAVIA</option>
<option value='ZM'>ZAMBIA</option>
<option value='ZW'>ZIMBABWE</option>
<option value='21'>CANAL DE PANAMA</option>
<option value='NT'>IRAQ-ARABIA</option>
</datalist>

</div>
</div>
</div>
</div>

@endsection
@section('scripts')
<script>

function guardar()
{
    //validaciones
    if($("#txtNumeroDeParte").val().length < 1)
    {
        showModal("Alerta!", "Llene el campo número de parte.");
        return;
    }
    if($("#txtCliente").val() == 0)
    {
        showModal("Alerta!", "Llene el campo Cliente.");
        return;
    }
    if($("#txtUM").val() == 0)
    {
        showModal("Alerta!", "Llene el campo UM.");
        return;
    }
    if($("#txtPesoUnitario").val() == 0)
    {
        showModal("Alerta!", "Llene el campo Peso unitario.");
        return;
    }
    // if($("#txtDescIng").val() == "")
    // {
    //     showModal("Alerta!", "Llene el campo Descripción en inglés.");
    //     return;
    // }
    // if($("#txtDetxtDescEspscIng").val() == "")
    // {
    //     showModal("Alerta!", "Llene el campo Descripción en español.");
    //     return;
    // }
    if($("#txtPais").val() == "")
    {
        showModal("Alerta!", "Llene el campo País.");
        return;
    }
    if($("#txtFraccion").val().length != 8)
    {
        showModal("Alerta!", "Llene correctamente el campo Fracción.");
        return;
    }
    if($("#txtNico").val().length != 2)
    {
        showModal("Alerta!", "Llene correctamente el campo nico.");
        return;
    }
    document.getElementById("PartNumberForm").submit();
}

function cancel()
{
    location.href = "/int/entradas/{{ $from_income ?? '' }}";
}
</script>
@endsection