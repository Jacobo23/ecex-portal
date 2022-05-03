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

//php artisan db:seed --class=TestIncomeSeederFIX
class TestIncomeSeederFIX extends Seeder
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
        $sql = "SELECT Entradas.Year, Entradas.Num, Entradas.Fecha, Entradas.Cliente, Transportista.Nombre AS Transportista, Proveedores.Nombre AS Proveedor, Entradas.Referencia, Entradas.Caja, Entradas.Sello, Entradas.Observaciones, Entradas.ImpoExpo, Entradas.Factura, Entradas.Tracking, Entradas.PO, Entradas.Enviada, Entradas.Usuario, Entradas.Revisada_por, Entradas.Revisada, Entradas.Cerrada, Entradas.Urgente, Entradas.OnHold, Entradas.clasificacion FROM Entradas JOIN Transportista ON Transportista.id = Entradas.Transportista JOIN Proveedores ON Entradas.Proveedor = Proveedores.id join (SELECT T1.YearEntrada, T1.NumEntrada FROM (SELECT Partida_Entrada.id,Partida_Entrada.observacion_partida, Partida_Entrada.YearEntrada, Partida_Entrada.NumEntrada, DATE_FORMAT(Entradas.Fecha, '%m/%d/%Y') AS Fecha, Partida_Entrada.NumeroDeParte_Cl, Partida_Entrada.Cliente AS ClienteID, Clientes.Nombre AS Cliente, Partida_Entrada.Locacion, (Partida_Entrada.CantidadPiezas - SUM(IFNULL(T2.CantidadPiezas,0))) AS Cantidad_Piezas, (Partida_Entrada.PesoNeto - SUM(IFNULL(T2.PesoNeto,0))) AS PesoNeto, Partida_Entrada.UMPiezas, CantidadBultosInventario.Cantidad AS CantidadBultos, CantidadBultosInventario.id AS IdBultos, Partida_Entrada.UMBultos, Partida_Entrada.Descripcion_Ing, Partida_Entrada.Descripcion_Esp, Partida_Entrada.PO, Partida_Entrada.PaisDeOrigen, Partida_Entrada.Fraccion,Partida_Entrada.nico, Partida_Entrada.Marca, Partida_Entrada.Modelo, Partida_Entrada.Serie, Partida_Entrada.SKID, Partida_Entrada.Packing_ID FROM Partida_Entrada LEFT JOIN (SELECT Entradas_Salida.PE, Entradas_Salida.CantidadPiezas, Entradas_Salida.PesoNeto FROM Entradas_Salida JOIN Salidas ON Salidas.Year = Entradas_Salida.YearSalida AND Salidas.Num = Entradas_Salida.NumSalida WHERE Salidas.Descontada = true OR isnull(Salidas.Descontada) ) AS T2  ON T2.PE = Partida_Entrada.id JOIN Entradas on Entradas.Year = Partida_Entrada.YearEntrada AND Entradas.Num = Partida_Entrada.NumEntrada JOIN CantidadBultosInventario ON CantidadBultosInventario.PE = Partida_Entrada.id JOIN Clientes ON Clientes.id = Partida_Entrada.Cliente WHERE Entradas.Cliente > 0 AND Entradas.Fecha > DATE_SUB(CURDATE(), INTERVAL 2000 DAY) GROUP BY Partida_Entrada.id ) AS T1 WHERE T1.Cantidad_Piezas > 0 GROUP BY T1.YearEntrada, T1.NumEntrada) as t2 on t2.YearEntrada = Entradas.Year and t2.NumEntrada = Entradas.Num";
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
                $entrada = Income::where("year",$row["Year"])->where("number",$row["Num"])->first();
                if(!$entrada)
                {
                    continue;
                }
                echo $entrada->year . "-" . $entrada->number . " -> " . $entrada->reviewed . " ===> " . $row["Revisada"] . ";\n";
                $entrada->reviewed = is_numeric($row["Revisada"]) ? $row["Revisada"] : 0;
                $entrada->save();
                
                
            }
        } 
        
        $conn->close();
    }


}
