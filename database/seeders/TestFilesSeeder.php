<?php

namespace Database\Seeders;

use Illuminate\Support\Facades\Storage;
use Illuminate\Database\Seeder;
use Illuminate\Database\File;
use App\Models\Income;
use App\Models\Outcome;


//php artisan db:seed --class=TestFilesSeeder
class TestFilesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $incomes = Income::where('year','=','2021')->get();
        foreach ($incomes as $income) 
        {
            
            $numero_de_entrada = $income->getIncomeNumber();
            echo $numero_de_entrada . " -> ";
            //limpiar
            Storage::delete('/public/entradas/'.$numero_de_entrada);
            //PACKINGLISTS
            try {
            $paking_list_url = "https://ecex-intranet.dx.am/common_Functions/UploadFile/PackingLists/".$numero_de_entrada."/packinglist.pdf";
            if($this->file_contents_exist($paking_list_url))
            {
                $file = file_get_contents($paking_list_url);
                Storage::put('/public/entradas/'.$numero_de_entrada.'/packing_list/packing-list.pdf', $file);
                echo " packinglist OK ";
            }
            //images https://ecex-intranet.dx.am/Entradas-Salidas/imgUPLOAD/202112198/img2.png
            //$income_imgs_paths='public/filezilla/entradas/'.$numero_de_entrada.'/';
            $income_imgs_paths = file_get_contents("https://ecex-intranet.dx.am/common_Functions/UploadFile/getfiles.php?Entrada=".$numero_de_entrada);
            if($income_imgs_paths == "")
            {
                echo "Sin imagenes \n";
                continue;
            }
            $income_imgs_paths = explode(",",$income_imgs_paths);
            // if(file_exists($income_imgs_paths))
            // {
                //$income_imgs = Storage::files($income_imgs_paths);
                $img_count = 0;
                foreach ($income_imgs_paths as $income_img) 
                {
                    if($this->file_contents_exist($income_img))
                    {
                        
                        $img_file_name_array=explode('/',$income_img);
                        $img_file_name=$img_file_name_array[count($img_file_name_array)-1];
                        Storage::put('/public/entradas/'.$numero_de_entrada.'/images/'.$img_file_name, file_get_contents($income_img), 'public');
                        //echo " img ";
                        $img_count++;
                        echo ".";
                    }
                }
                echo "Imagenes: " .$img_count;
            //}
            }
            catch(Exception $e) {
                echo 'error con: ' . $numero_de_entrada;
              }

              echo "\n";

        }

    }
    function file_contents_exist($url, $response_code = 200)
    {
        $headers = get_headers($url);

        if (substr($headers[0], 9, 3) == $response_code)
        {
            return TRUE;
        }
        else
        {
            return FALSE;
        }
    }


}
