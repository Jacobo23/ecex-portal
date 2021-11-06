<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Database\File;
use App\Models\Income;
use App\Models\Carrier;
use App\Models\Customer;
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
        $password = "EcexOVJR3875";
        $dbname = "test";

        // Create connection
        $conn = mysqli_connect($servername, $username, $password, $dbname);

        //////////////////ENCABEZADO
        //$sql = "SELECT Entradas.Year, Entradas.Num, Entradas.Fecha, Entradas.Cliente, Transportista.Nombre AS Transportista, Proveedores.Nombre AS Proveedor, Entradas.Referencia, Entradas.Caja, Entradas.Sello, Entradas.Observaciones, Entradas.ImpoExpo, Entradas.Factura, Entradas.Tracking, Entradas.PO, Entradas.Enviada, Entradas.Usuario, Entradas.Revisada_por, Entradas.Cerrada, Entradas.Urgente, Entradas.OnHold, Entradas.clasificacion FROM Entradas JOIN Transportista ON Transportista.id = Entradas.Transportista JOIN Proveedores ON Entradas.Proveedor = Proveedores.id where Entradas.Cliente > 0;";
        $sql = "SELECT Entradas.Year, Entradas.Num, Entradas.Fecha, Entradas.Cliente, Transportista.Nombre AS Transportista, Proveedores.Nombre AS Proveedor, Entradas.Referencia, Entradas.Caja, Entradas.Sello, Entradas.Observaciones, Entradas.ImpoExpo, Entradas.Factura, Entradas.Tracking, Entradas.PO, Entradas.Enviada, Entradas.Usuario, Entradas.Revisada_por, Entradas.Cerrada, Entradas.Urgente, Entradas.OnHold, Entradas.clasificacion FROM Entradas JOIN Transportista ON Transportista.id = Entradas.Transportista JOIN Proveedores ON Entradas.Proveedor = Proveedores.id join (SELECT T1.YearEntrada, T1.NumEntrada FROM (SELECT Partida_Entrada.id,Partida_Entrada.observacion_partida, Partida_Entrada.YearEntrada, Partida_Entrada.NumEntrada, DATE_FORMAT(Entradas.Fecha, '%m/%d/%Y') AS Fecha, Partida_Entrada.NumeroDeParte_Cl, Partida_Entrada.Cliente AS ClienteID, Clientes.Nombre AS Cliente, Partida_Entrada.Locacion, (Partida_Entrada.CantidadPiezas - SUM(IFNULL(T2.CantidadPiezas,0))) AS Cantidad_Piezas, (Partida_Entrada.PesoNeto - SUM(IFNULL(T2.PesoNeto,0))) AS PesoNeto, Partida_Entrada.UMPiezas, CantidadBultosInventario.Cantidad AS CantidadBultos, CantidadBultosInventario.id AS IdBultos, Partida_Entrada.UMBultos, Partida_Entrada.Descripcion_Ing, Partida_Entrada.Descripcion_Esp, Partida_Entrada.PO, Partida_Entrada.PaisDeOrigen, Partida_Entrada.Fraccion,Partida_Entrada.nico, Partida_Entrada.Marca, Partida_Entrada.Modelo, Partida_Entrada.Serie, Partida_Entrada.SKID, Partida_Entrada.Packing_ID FROM Partida_Entrada LEFT JOIN (SELECT Entradas_Salida.PE, Entradas_Salida.CantidadPiezas, Entradas_Salida.PesoNeto FROM Entradas_Salida JOIN Salidas ON Salidas.Year = Entradas_Salida.YearSalida AND Salidas.Num = Entradas_Salida.NumSalida WHERE Salidas.Descontada = true OR isnull(Salidas.Descontada) ) AS T2  ON T2.PE = Partida_Entrada.id JOIN Entradas on Entradas.Year = Partida_Entrada.YearEntrada AND Entradas.Num = Partida_Entrada.NumEntrada JOIN CantidadBultosInventario ON CantidadBultosInventario.PE = Partida_Entrada.id JOIN Clientes ON Clientes.id = Partida_Entrada.Cliente WHERE Entradas.Cliente > 0 AND Entradas.Fecha > DATE_SUB(CURDATE(), INTERVAL 2000 DAY) GROUP BY Partida_Entrada.id ) AS T1 WHERE T1.Cantidad_Piezas > 0 GROUP BY T1.YearEntrada, T1.NumEntrada) as t2 on t2.YearEntrada = Entradas.Year and t2.NumEntrada = Entradas.Num";
        $result = $conn->query($sql);

        if ($result->num_rows > 0) 
        {
            // output data of each row
            while($row = $result->fetch_assoc()) 
            {
                if(!Customer::find($row["Cliente"]))
                {
                    continue;
                }
                $entrada = new Income;
                $entrada->year = $row["Year"];
                $entrada->number = $row["Num"];
                $entrada->cdate = $row["Fecha"];
                $entrada->customer_id = $row["Cliente"];
                $Transportista = Carrier::where("name",utf8_encode($row["Transportista"]))->first();
                $entrada->carrier_id = $Transportista ? $Transportista->id : 1;
                $Proveedor = Supplier::where("name",utf8_encode($row["Proveedor"]))->first();
                $entrada->supplier_id = $Proveedor ? $Proveedor->id : 1;
                $entrada->reference = utf8_encode($row["Referencia"]);
                $entrada->trailer = utf8_encode($row["Caja"]) ;
                $entrada->seal = utf8_encode($row["Sello"]) ;
                $entrada->observations = utf8_encode($row["Observaciones"]) ;
                $entrada->impoExpo = $row["ImpoExpo"] ;
                $entrada->invoice = utf8_encode($row["Factura"]) ;
                $entrada->tracking = utf8_encode($row["Tracking"]) ;
                $entrada->po = utf8_encode($row["PO"]);
                $entrada->sent = is_numeric($row["Enviada"]) ? $row["Enviada"] : 0;
                $entrada->user =  utf8_encode($row["Usuario"]) ;
                $entrada->reviewed = isset($row["Revisada_por"]);
                $entrada->reviewed_by = utf8_encode($row["Revisada_por"] ?? "");
                $entrada->closed = $row["Cerrada"] ?? 0;
                $entrada->urgent = $row["Urgente"] ?? 0;
                $entrada->onhold = $row["OnHold"] ?? 0;
                $entrada->type = $row["clasificacion"] ?? "";
                $entrada->save();
                echo $entrada->year . "-" . $entrada->number . " -> ";
                //////////////////PARTIDAS
                //$sql2 = "select Partida_Entrada.id, Partida_Entrada.NumeroDeParte_Cl, Partida_Entrada.NumeroDeParteID, Partida_Entrada.CantidadPiezas, Partida_Entrada.CantidadBultos, Partida_Entrada.UMBultos, Partida_Entrada.UMPiezas, Partida_Entrada.PesoNeto, Partida_Entrada.PesoBruto, Partida_Entrada.PO, Partida_Entrada.Descripcion_Ing, Partida_Entrada.Descripcion_Esp, Partida_Entrada.PaisDeOrigen, Partida_Entrada.Fraccion, Partida_Entrada.nico, Partida_Entrada.Locacion, Partida_Entrada.observacion_partida, Partida_Entrada.Marca, Partida_Entrada.Modelo, Partida_Entrada.Serie, Partida_Entrada.lote, Partida_Entrada.IMEX, Partida_Entrada.SKID from Partida_Entrada where Partida_Entrada.Cliente = ".$entrada->customer_id." and Partida_Entrada.YearEntrada = ".$entrada->year." and Partida_Entrada.NumEntrada = " . $entrada->number;
                
                $sql2 = "select Partida_Entrada.id, Partida_Entrada.NumeroDeParte_Cl, Partida_Entrada.NumeroDeParteID, t3.Cantidad_Piezas as CantidadPiezas, Partida_Entrada.CantidadBultos, Partida_Entrada.UMBultos, Partida_Entrada.UMPiezas, t3.PesoNeto, Partida_Entrada.PesoBruto, Partida_Entrada.PO, Partida_Entrada.Descripcion_Ing, Partida_Entrada.Descripcion_Esp, Partida_Entrada.PaisDeOrigen, Partida_Entrada.Fraccion, Partida_Entrada.nico, Partida_Entrada.Locacion, Partida_Entrada.observacion_partida, Partida_Entrada.Marca, Partida_Entrada.Modelo, Partida_Entrada.Serie, Partida_Entrada.lote, Partida_Entrada.IMEX, Partida_Entrada.SKID from Partida_Entrada join (SELECT T1.id,T1.Cantidad_Piezas,T1.PesoNeto FROM (SELECT Partida_Entrada.id,Partida_Entrada.observacion_partida, Partida_Entrada.YearEntrada, Partida_Entrada.NumEntrada, DATE_FORMAT(Entradas.Fecha, '%m/%d/%Y') AS Fecha, Partida_Entrada.NumeroDeParte_Cl, Partida_Entrada.Cliente AS ClienteID, Clientes.Nombre AS Cliente, Partida_Entrada.Locacion, (Partida_Entrada.CantidadPiezas - SUM(IFNULL(T2.CantidadPiezas,0))) AS Cantidad_Piezas, (Partida_Entrada.PesoNeto - SUM(IFNULL(T2.PesoNeto,0))) AS PesoNeto, Partida_Entrada.UMPiezas, CantidadBultosInventario.Cantidad AS CantidadBultos, CantidadBultosInventario.id AS IdBultos, Partida_Entrada.UMBultos, Partida_Entrada.Descripcion_Ing, Partida_Entrada.Descripcion_Esp, Partida_Entrada.PO, Partida_Entrada.PaisDeOrigen, Partida_Entrada.Fraccion,Partida_Entrada.nico, Partida_Entrada.Marca, Partida_Entrada.Modelo, Partida_Entrada.Serie, Partida_Entrada.SKID, Partida_Entrada.Packing_ID FROM Partida_Entrada LEFT JOIN (SELECT Entradas_Salida.PE, Entradas_Salida.CantidadPiezas, Entradas_Salida.PesoNeto FROM Entradas_Salida JOIN Salidas ON Salidas.Year = Entradas_Salida.YearSalida AND Salidas.Num = Entradas_Salida.NumSalida WHERE Salidas.Descontada = true OR isnull(Salidas.Descontada) ) AS T2  ON T2.PE = Partida_Entrada.id JOIN Entradas on Entradas.Year = Partida_Entrada.YearEntrada AND Entradas.Num = Partida_Entrada.NumEntrada JOIN CantidadBultosInventario ON CantidadBultosInventario.PE = Partida_Entrada.id JOIN Clientes ON Clientes.id = Partida_Entrada.Cliente WHERE Entradas.Cliente > 0 AND Entradas.Fecha > DATE_SUB(CURDATE(), INTERVAL 2000 DAY) and Entradas.Year = ".$entrada->year." and Entradas.Num = ".$entrada->number." GROUP BY Partida_Entrada.id) AS T1 WHERE T1.Cantidad_Piezas > 0) t3 on t3.id = Partida_Entrada.id where Partida_Entrada.Cliente = ".$entrada->customer_id." and Partida_Entrada.YearEntrada = ".$entrada->year." and Partida_Entrada.NumEntrada = ".$entrada->number.";";
                
                $result2 = $conn->query($sql2);
                if ($result2->num_rows > 0) 
                {
                    // output data of each row
                    $partidas_count = 0;
                    while($row2 = $result2->fetch_assoc()) 
                    {
                        $incomeRow = new IncomeRow;

                        //buscamos el numero de parte en la base ecexv2
                        $part_n = PartNumber::where('part_number',$row2["NumeroDeParte_Cl"])->where('customer_id',$entrada->customer_id)->first();
                        if (!$part_n)
                        {
                            //buscamos en la base ecexv1

                            $sql3 = "SELECT NumeroDeParte.id, NumeroDeParte.NumerodeParte, NumeroDeParte.Cliente, NumeroDeParte.UM, NumeroDeParte.PesoU, NumeroDeParte.Descripcion_Ing, NumeroDeParte.Descripcion_Esp, NumeroDeParte.PaisDeOrigen, NumeroDeParte.Fraccion, NumeroDeParte.nico, NumeroDeParte.Marca, NumeroDeParte.Modelo, NumeroDeParte.Serie, NumeroDeParte.IMMEX,NumeroDeParte.fraccion_especial, NumeroDeParte.Regimen, NumeroDeParte.alerta FROM NumeroDeParte where NumerodeParte = '".$row2["NumeroDeParte_Cl"]."' and Cliente = ".$entrada->customer_id.";";
                            //echo $sql3;
                            $result3 = $conn->query($sql3);

                            if ($result3->num_rows > 0) 
                            {                                
                                while($row3 = $result3->fetch_assoc()) 
                                {
                                    $part_number = new PartNumber;
                                    if(!is_null($row3["NumerodeParte"]) && $row3["NumerodeParte"] != "" && $row3["Cliente"] != 0)
                                    {
                                        if(Customer::find($row3["Cliente"]) != null)
                                        {
                                            $part_number->id = $row3["id"];
                                            $part_number->part_number = utf8_encode($row3["NumerodeParte"]);
                                            $part_number->customer_id = $row3["Cliente"];
                                            $part_number->um = $row3["UM"];
                                            $part_number->unit_weight = $row3["PesoU"];
                                            $part_number->desc_ing = utf8_encode($row3["Descripcion_Ing"]);
                                            $part_number->desc_esp = utf8_encode($row3["Descripcion_Esp"]);
                                            $part_number->origin_country = $row3["PaisDeOrigen"];
                                            $part_number->fraccion = $row3["Fraccion"];
                                            $part_number->nico = $row3["nico"] ?? '';
                                            $part_number->brand = utf8_encode($row3["Marca"]);
                                            $part_number->model = utf8_encode($row3["Modelo"]);
                                            $part_number->serial = utf8_encode($row3["Serie"]);
                                            $part_number->imex = $row3["IMMEX"] ?? '';
                                            $part_number->fraccion_especial = $row3["fraccion_especial"] ?? '';
                                            $part_number->regime = $row3["Regimen"] ?? '';
                                            $part_number->warning = $row3["alerta"] ?? '';
                                            $part_number->save();

                                            $part_n = $part_number;

                                            echo " [NP:" . $part_n->id . " ecexV1] ";
                                        }
                                    }
                                    break;
                                }
                            }

                            //si el numero de parte no existe en ningun lado debemos crearlo

                            if (!$part_n)
                            {
                                $part_number = new PartNumber;
                                $part_number->part_number = utf8_encode($row2["NumeroDeParte_Cl"]);
                                $part_number->customer_id = $entrada->customer_id;
                                $part_number->um = $row2["UMPiezas"];
                                $part_number->unit_weight = $row2["PesoNeto"] / $row2["CantidadPiezas"];
                                $part_number->desc_ing = utf8_encode($row2["Descripcion_Ing"]);
                                $part_number->desc_esp = utf8_encode($row2["Descripcion_Esp"]);
                                $part_number->origin_country = $row2["PaisDeOrigen"];
                                $part_number->fraccion = $row2["Fraccion"];
                                $part_number->nico = $row2["nico"] ?? '';
                                $part_number->brand = utf8_encode($row2["Marca"]);
                                $part_number->model = utf8_encode($row2["Modelo"]);
                                $part_number->serial = utf8_encode($row2["Serie"]);
                                $part_number->imex = $row2["IMEX"] ?? '';
                                $part_number->fraccion_especial = '';
                                $part_number->regime = '';
                                $part_number->warning = '';
                                $part_number->save();

                                $part_n = $part_number;

                                echo " [NP:" . $part_n->id . " NEW] ";
                            }

                        }
                        else
                        {
                            echo " [NP:" . $part_n->id . " OK] ";
                        }
                        // if ($row2["NumeroDeParteID"] == 0)
                        // {
                        //     $part_n = PartNumber::where('part_number',$row2["NumeroDeParte_Cl"])->where('customer_id',$entrada->customer_id)->first();
                        // }
                        // else
                        // {
                        //     $part_n = PartNumber::where('id',$row2["NumeroDeParteID"])->first();
                        // }

                        if(IncomeRow::find($row2["id"]))
                        {
                            continue;
                        }
                        $incomeRow->id = $row2["id"];
                        $incomeRow->part_number_id = $part_n->id;
                        $incomeRow->income_id = $entrada->id;
                        $incomeRow->units = $row2["CantidadPiezas"] ;
                        $incomeRow->bundles = $row2["CantidadBultos"] ;
                        $incomeRow->umb = $row2["UMBultos"] ;
                        $incomeRow->ump = $row2["UMPiezas"] ;
                        $incomeRow->net_weight = $row2["PesoNeto"] ;
                        $incomeRow->gross_weight = $row2["PesoBruto"] ;
                        $incomeRow->po = utf8_encode($row2["PO"] ?? "");
                        $incomeRow->desc_ing = utf8_encode($row2["Descripcion_Ing"]) ;
                        $incomeRow->desc_esp = utf8_encode($row2["Descripcion_Esp"]) ;
                        $incomeRow->origin_country = utf8_encode($row2["PaisDeOrigen"]) ;
                        $incomeRow->fraccion = utf8_encode($row2["Fraccion"]) ;
                        $incomeRow->nico = utf8_encode($row2["nico"] ?? "");
                        $incomeRow->location = utf8_encode($row2["Locacion"] ?? "");
                        $incomeRow->observations = utf8_encode($row2["observacion_partida"] ?? "");
                        $incomeRow->brand = utf8_encode($row2["Marca"] ?? "");
                        $incomeRow->model = utf8_encode($row2["Modelo"] ?? "");
                        $incomeRow->serial = utf8_encode($row2["Serie"] ?? "");
                        $incomeRow->lot = utf8_encode($row2["lote"] ?? "");
                        $incomeRow->imex = utf8_encode($row2["IMEX"] ?? "");
                        $incomeRow->regime = "";
                        $incomeRow->skids = utf8_encode($row2["SKID"] ?? "");
                        $incomeRow->save();

                        $partidas_count++;

                        //registrar bultos en inventario
                        $inv_bundle = new InventoryBundle;
                        $inv_bundle->income_row_id = $incomeRow->id;
                        $inv_bundle->quantity = $incomeRow->bundles;
                        $inv_bundle->save();
                    }
                } 
                echo "Partidas: " . $partidas_count . "\n";
            }
        } 
        
        $conn->close();
    }


}
