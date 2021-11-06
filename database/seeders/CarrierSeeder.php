<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\Carrier;

class CarrierSeeder extends Seeder
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

        $sql = "SELECT * FROM Transportista order by Nombre;";
        $result = $conn->query($sql);

        if ($result->num_rows > 0) 
        {
            // output data of each row
            
            while($row = $result->fetch_assoc()) 
            {
                $nombre = utf8_encode($row["Nombre"]);
                $carrier = Carrier::where('name',$nombre)->first();
                if($carrier)
                {
                    continue;
                }
                DB::table('carriers')->insert(['id' => null, 'name' => $nombre]);
            }
        } 
        
        $conn->close();


    }
}