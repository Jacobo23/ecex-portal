<?php

namespace Database\Seeders;

use Illuminate\Support\Facades\Storage;
use Illuminate\Database\Seeder;
use Illuminate\Database\File;
use App\Models\Income;
use App\Models\Outcome;



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
            //limpiar
            Storage::delete('/public/entradas/'.$numero_de_entrada);
            //PACKINGLISTS
            try {
            $paking_list_url = "https://ecex-intranet.dx.am/common_Functions/UploadFile/PackingLists/".$numero_de_entrada."/packinglist.pdf";
            if($this->file_contents_exist($paking_list_url))
            {
                $file = file_get_contents($paking_list_url);
                Storage::put('/public/entradas/'.$numero_de_entrada.'/packing_list/packing-list.pdf', $file);
            }

            $income_imgs_paths='public/filezilla/entradas/'.$numero_de_entrada.'/';
            if(file_exists($income_imgs_paths))
            {
                $income_imgs = Storage::files($income_imgs_paths);
                foreach ($income_imgs as $income_img) 
                {
                    $img_file_name_array=explode('/',$income_img);
                    $img_file_name=$img_file_name_array[count($img_file_name_array)-1];
                    Storage::put('/public/entradas/'.$numero_de_entrada.'/images/'.$img_file_name, file_get_contents($income_img), 'public');
                }
            }
            }
            catch(Exception $e) {
                echo 'error con: ' . $numero_de_entrada;
              }

        }
        //salidas

        $outcomes = Outcome::all();
        foreach ($outcomes as $outcome) 
        {
            $numero_de_salida = $outcome->getOutcomeNumber(false);
            //limpiar
            Storage::delete('/public/salidas/'.$numero_de_salida);
            //PACKINGLISTS

            $outcome_packing='public/filezilla/salidas_packing/'.$numero_de_salida.'/';
            if(file_exists($outcome_packing))
            {
                $outcome_packs = Storage::files($outcome_packing);
                foreach ($outcome_packs as $outcome_pack) 
                {
                    $pack_file_name_array=explode('/',$outcome_pack);
                    $pack_file_name=$pack_file_name_array[count($pack_file_name_array)-1];
                    Storage::put('/public/salidas/'.$numero_de_salida.'/packing_list/'.$pack_file_name, file_get_contents($outcome_pack));
                }
            }
            
            //imagenes
            $outcome_imgs_paths='public/filezilla/salidas/'.$outcome->getOutcomeNumber(true).'/';
            if(file_exists($outcome_imgs_paths))
            {
                $outcome_imgs = Storage::files($outcome_imgs_paths);
                foreach ($outcome_imgs as $outcome_img) 
                {
                    $img_file_name_array=explode('/',$outcome_img);
                    $img_file_name=$img_file_name_array[count($img_file_name_array)-1];
                    Storage::put('/public/salidas/'.$numero_de_salida.'/images/'.$img_file_name, file_get_contents($outcome_img), 'public');
                }
            }
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
