<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Database\File;
use App\Models\Income;
use App\Models\Carrier;
use App\Models\Supplier;
use App\Models\IncomeRow;
use App\Models\PartNumber;
use App\Models\InventoryBundle;


class TestIncomeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $servername = "test123.cegsylsiwyfd.us-west-1.rds.amazonaws.com";
        $username = "root";
        $password = "Jakoloco16";
        $dbname = "test";

        // Create connection
        $conn = mysqli_connect($servername, $username, $password, $dbname);


        //////////////////ENCABEZADO
        $sql = "SELECT Entradas.Year, Entradas.Num, Entradas.Fecha, Entradas.Cliente, Transportista.Nombre AS Transportista, Proveedores.Nombre AS Proveedor, Entradas.Referencia, Entradas.Caja, Entradas.Sello, Entradas.Observaciones, Entradas.ImpoExpo, Entradas.Factura, Entradas.Tracking, Entradas.PO, Entradas.Enviada, Entradas.Usuario, Entradas.Revisada_por, Entradas.Cerrada, Entradas.Urgente, Entradas.OnHold, Entradas.clasificacion FROM Entradas JOIN Transportista ON Transportista.id = Entradas.Transportista JOIN Proveedores ON Entradas.Proveedor = Proveedores.id where Entradas.Cliente = 18;";
        $result = $conn->query($sql);

        if ($result->num_rows > 0) 
        {
            // output data of each row
            while($row = $result->fetch_assoc()) 
            {
                $entrada = new Income;
                $entrada->year = $row["Year"];
                $entrada->number = $row["Num"];
                $entrada->cdate = $row["Fecha"];
                $entrada->customer_id = $row["Cliente"];
                $Transportista = Carrier::where("name",$row["Transportista"])->first();
                $entrada->carrier_id = $Transportista ? $Transportista->id : 1;
                $Proveedor = Supplier::where("name",$row["Proveedor"])->first();
                $entrada->supplier_id = $Proveedor ? $Proveedor->id : 1;
                $entrada->reference = $row["Referencia"];
                $entrada->trailer = $row["Caja"] ;
                $entrada->seal = $row["Sello"] ;
                $entrada->observations = $row["Observaciones"] ;
                $entrada->impoExpo = $row["ImpoExpo"] ;
                $entrada->invoice = $row["Factura"] ;
                $entrada->tracking = $row["Tracking"] ;
                $entrada->po = $row["PO"];
                $entrada->sent = is_numeric($row["Enviada"]) ? $row["Enviada"] : 0;
                $entrada->user =  $row["Usuario"] ;
                $entrada->reviewed = isset($row["Revisada_por"]);
                $entrada->reviewed_by = $row["Revisada_por"] ?? "";
                $entrada->closed = $row["Cerrada"] ?? 0;
                $entrada->urgent = $row["Urgente"] ?? 0;
                $entrada->onhold = $row["OnHold"] ?? 0;
                $entrada->type = $row["clasificacion"] ?? "";
                $entrada->save();
                //////////////////PARTIDAS
                $sql2 = "select Partida_Entrada.id, Partida_Entrada.NumeroDeParte_Cl, Partida_Entrada.NumeroDeParteID, Partida_Entrada.CantidadPiezas, Partida_Entrada.CantidadBultos, Partida_Entrada.UMBultos, Partida_Entrada.UMPiezas, Partida_Entrada.PesoNeto, Partida_Entrada.PesoBruto, Partida_Entrada.PO, Partida_Entrada.Descripcion_Ing, Partida_Entrada.Descripcion_Esp, Partida_Entrada.PaisDeOrigen, Partida_Entrada.Fraccion, Partida_Entrada.nico, Partida_Entrada.Locacion, Partida_Entrada.observacion_partida, Partida_Entrada.Marca, Partida_Entrada.Modelo, Partida_Entrada.Serie, Partida_Entrada.lote, Partida_Entrada.IMEX, Partida_Entrada.SKID from Partida_Entrada where Partida_Entrada.YearEntrada = ".$entrada->year." and Partida_Entrada.NumEntrada = " . $entrada->number;
                $result2 = $conn->query($sql2);
                if ($result2->num_rows > 0) 
                {
                    // output data of each row
                    while($row2 = $result2->fetch_assoc()) 
                    {
                        $incomeRow = new IncomeRow;
                        if ($row2["NumeroDeParteID"] == 0)
                        {
                            $part_n = PartNumber::where('part_number',$row2["NumeroDeParte_Cl"])->first();
                        }
                        else
                        {
                            $part_n = PartNumber::where('part_number',$row2["NumeroDeParteID"])->first();
                        }
                        $incomeRow->id = $row2["id"];
                        $incomeRow->part_number_id = $part_n ? $part_n->id : 130373;
                        $incomeRow->income_id = $entrada->id;
                        $incomeRow->units = $row2["CantidadPiezas"] ;
                        $incomeRow->bundles = $row2["CantidadBultos"] ;
                        $incomeRow->umb = $row2["UMBultos"] ;
                        $incomeRow->ump = $row2["UMPiezas"] ;
                        $incomeRow->net_weight = $row2["PesoNeto"] ;
                        $incomeRow->gross_weight = $row2["PesoBruto"] ;
                        $incomeRow->po = $row2["PO"] ?? "";
                        $incomeRow->desc_ing = $row2["Descripcion_Ing"] ;
                        $incomeRow->desc_esp = $row2["Descripcion_Esp"] ;
                        $incomeRow->origin_country = $row2["PaisDeOrigen"] ;
                        $incomeRow->fraccion = $row2["Fraccion"] ;
                        $incomeRow->nico = $row2["nico"] ?? "";
                        $incomeRow->location = $row2["Locacion"] ?? "";
                        $incomeRow->observations = $row2["observacion_partida"] ?? "";
                        $incomeRow->brand = $row2["Marca"] ?? "";
                        $incomeRow->model = $row2["Modelo"] ?? "";
                        $incomeRow->serial = $row2["Serie"] ?? "";
                        $incomeRow->lot = $row2["lote"] ?? "";
                        $incomeRow->imex = $row2["IMEX"] ?? "";
                        $incomeRow->regime = "";
                        $incomeRow->skids = $row2["SKID"] ?? "";
                        $incomeRow->save();
                        //registrar bultos en inventario
                        $inv_bundle = new InventoryBundle;
                        $inv_bundle->income_row_id = $incomeRow->id;
                        $inv_bundle->quantity = $incomeRow->bundles;
                        $inv_bundle->save();
                    }
                } 
            }
        } 
        
        $conn->close();
    }


}
