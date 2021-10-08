<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Outcome;
use App\Models\Carrier;
use App\Models\OutcomeRow;
use App\Models\IncomeRow;
use App\Models\Customer;


class TestOutcomeSeeder extends Seeder
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
        $sql = "select Salidas.Year, Salidas.Num, Salidas.Regimen, Salidas.Fecha, Salidas.Cliente, Transportista.Nombre as Transportista, Salidas.Caja_T, Salidas.Sello, Salidas.Observaciones, Salidas.Factura, Salidas.Pedimento, Salidas.Referencia, Salidas.Usuario, Salidas.RecibidoPor,Salidas.Placas, Salidas.Despacho, Salidas.Descontada from Salidas join Transportista on Transportista.id = Salidas.Transportista where Salidas.Cliente > 0";
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
                if($row["Cliente"] == '22' || $row["Cliente"] == '20')
                {
                    continue;
                }
                if($row["Cliente"] == 22 || $row["Cliente"] == 20)
                {
                    continue;
                }

                $salida = new Outcome;
                $salida->year = $row["Year"];
                $salida->number = $row["Num"];
                $salida->regime = $row["Regimen"];
                $salida->cdate = $row["Fecha"];
                $salida->customer_id = $row["Cliente"];
                $Transportista = Carrier::where("name",$row["Transportista"])->first();
                $salida->carrier_id = $Transportista ? $Transportista->id : 1;
                $salida->trailer = utf8_encode($row["Caja_T"] ?? "");
                $salida->seal = utf8_encode($row["Sello"] ?? "");
                $salida->observations = substr(utf8_encode($row["Observaciones"] ?? ""),0,1000);
                $salida->invoice = utf8_encode($row["Factura"] ?? "");
                $salida->pediment = utf8_encode($row["Pedimento"] ?? "");
                $salida->reference = utf8_encode($row["Referencia"] ?? "");
                $salida->user = utf8_encode($row["Usuario"] ?? "");
                $salida->received_by = utf8_encode($row["RecibidoPor"] ?? "");
                $salida->plate = utf8_encode($row["Placas"] ?? "");
                $salida->leave = $row["Despacho"] ?? '2020-12-31 00:00:00';
                $salida->discount = isset($row["Descontada"]);

                $salida->save();
                //////////////////PARTIDAS
                $sql2 = "select Entradas_Salida.PE, Entradas_Salida.CantidadPiezas, Entradas_Salida.Bultos, Entradas_Salida.UMPiezas, Entradas_Salida.UMBultos, Entradas_Salida.PesoNeto, Entradas_Salida.PesoBruto from Entradas_Salida where Entradas_Salida.Cliente = " . $salida->customer_id . " and Entradas_Salida.YearSalida = " . $salida->year . " and Entradas_Salida.NumSalida = ". $salida->number;
                //Storage::put('example.txt', $sql2);
                $result2 = $conn->query($sql2);

                    if ($result2->num_rows > 0) 
                    {
                        // output data of each row
                        while($row2 = $result2->fetch_assoc()) 
                        {
                            if (IncomeRow::find($row2["PE"]) != null)
                            {
                                $outcome_row = new OutcomeRow;
                                $outcome_row->outcome_id = $salida->id;
                                $outcome_row->income_row_id = $row2["PE"];
                                $outcome_row->units = $row2["CantidadPiezas"];
                                $outcome_row->bundles = $row2["Bultos"];
                                $outcome_row->ump = $row2["UMPiezas"];
                                $outcome_row->umb = $row2["UMBultos"];
                                $outcome_row->net_weight = $row2["PesoNeto"];
                                $outcome_row->gross_weight = $row2["PesoBruto"];
                                $outcome_row->save();
                            }
                            
                        }
                    }
                
            }
        } 
        
        $conn->close();
    }


}
