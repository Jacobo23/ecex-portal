<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class UploadFileController extends Controller
{
    public function uploadPakinglist(Request $request)
    {
        Storage::put('/public/entradas/'.$request->fileNumEntrada.'/packing_list/packing-list.pdf', file_get_contents($request->file('file')));
        return redirect('/int/entradas/'.$request->fileNumEntrada);        
    }

    public function downloadPacking($entrada)
    {
        return Storage::download('public/entradas/'.$entrada.'/packing_list/packing-list.pdf');
    }

    public function deletePacking(Request $request)
    {
        Storage::delete('public/entradas/'.$request->fileDeleteNumEntrada.'/packing_list/packing-list.pdf');
        return redirect('/int/entradas/'.$request->fileDeleteNumEntrada); 
    }

    public function uploadImgEntrada(Request $request)
    {
        $i = 0;
        foreach ($request->file('filenames') as $file) 
        {
            $i++;
            Storage::put('/public/entradas/'.$request->fileNumEntradaImg.'/images/'.time().'_'.$i.'.'.$file->extension(), file_get_contents($file), 'public');
        }
        return redirect('/int/entradas/'.$request->fileNumEntradaImg);  
    }

    public function deleteImgEntrada(Request $request)
    {
        Storage::delete('public/entradas/'.$request->ImgDeleteNumEntrada.'/images/'.$request->ImgNameDeleteNumEntrada);
        return redirect('/int/entradas/'.$request->ImgDeleteNumEntrada); 
    }
    
    
}
