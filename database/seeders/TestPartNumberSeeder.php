<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\PartNumber;
use App\Models\Customer;

class TestPartNumberSeeder extends Seeder
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

        $sql = "SELECT NumeroDeParte.id, NumeroDeParte.NumerodeParte, NumeroDeParte.Cliente, NumeroDeParte.UM, NumeroDeParte.PesoU, NumeroDeParte.Descripcion_Ing, NumeroDeParte.Descripcion_Esp, NumeroDeParte.PaisDeOrigen, NumeroDeParte.Fraccion, NumeroDeParte.nico, NumeroDeParte.Marca, NumeroDeParte.Modelo, NumeroDeParte.Serie, NumeroDeParte.IMMEX,NumeroDeParte.fraccion_especial, NumeroDeParte.Regimen, NumeroDeParte.alerta FROM NumeroDeParte where Cliente > 0;";
        $result = $conn->query($sql);

        if ($result->num_rows > 0) 
        {
            // output data of each row
            
            while($row = $result->fetch_assoc()) 
            {
                
                if(!is_null($row["NumerodeParte"]) && $row["NumerodeParte"] != "" && $row["Cliente"] != 0)
                {
                    if(Customer::find($row["Cliente"]) != null)
                    {
                        $part_number = PartNumber::where('part_number',$row["NumerodeParte"])->where('customer_id',$row["Cliente"])->first();
                        if($part_number)
                        {
                            continue;
                        }
                        $part_number = new PartNumber;

                        $part_number->id = $row["id"];
                        $part_number->part_number = utf8_encode($row["NumerodeParte"]);
                        $part_number->customer_id = $row["Cliente"];
                        $part_number->um = $row["UM"];
                        $part_number->unit_weight = $row["PesoU"];
                        $part_number->desc_ing = utf8_encode($row["Descripcion_Ing"]);
                        $part_number->desc_esp = utf8_encode($row["Descripcion_Esp"]);
                        $part_number->origin_country = $row["PaisDeOrigen"];
                        $part_number->fraccion = $row["Fraccion"];
                        $part_number->nico = $row["nico"] ?? '';
                        $part_number->brand = utf8_encode($row["Marca"]);
                        $part_number->model = utf8_encode($row["Modelo"]);
                        $part_number->serial = utf8_encode($row["Serie"]);
                        $part_number->imex = $row["IMMEX"] ?? '';
                        $part_number->fraccion_especial = $row["fraccion_especial"] ?? '';
                        $part_number->regime = $row["Regimen"] ?? '';
                        $part_number->warning = $row["alerta"] ?? '';
                        $part_number->save();
                    }
                }
            }
        } 
        $conn->close();
    }


}
