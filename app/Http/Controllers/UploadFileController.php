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
    public function uploadPakinglistOutcome(Request $request)
    {
        $i = 0;
        foreach ($request->file('files') as $file) 
        {
            $i++;
            Storage::put('/public/salidas/'.$request->fileNumSalida.'/packing_list/'.time().'_'.$i.'.'.$file->extension(), file_get_contents($file));
        }
        //return redirect('/int/entradas/'.$request->fileNumEntradaImg);  
        //Storage::put('/public/salidas/'.$request->fileNumSalida.'/packing_list/packing-list.pdf', file_get_contents($request->file('file')));
        return redirect('/int/salidas/'.$request->fileNumSalida);        
    }

    public function downloadPacking($entrada)
    {
        return Storage::download('public/entradas/'.$entrada.'/packing_list/packing-list.pdf');
    }
    public function downloadPackingOutcome($salida, $filename)
    {
        return Storage::download('public/salidas/'.$salida.'/packing_list/'.$filename);
    }

    public function deletePacking(Request $request)
    {
        Storage::delete('public/entradas/'.$request->fileDeleteNumEntrada.'/packing_list/packing-list.pdf');
        return redirect('/int/entradas/'.$request->fileDeleteNumEntrada); 
    }
    public function deletePackingOutcome(Request $request)
    {
        Storage::delete('public/salidas/'.$request->fileDeleteNumSalida.'/packing_list/'.$request->fileDeleteNumSalida_filename);
        return redirect('/int/salidas/'.$request->fileDeleteNumSalida); 
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
    public function uploadImgSalida(Request $request)
    {
        $i = 0;
        foreach ($request->file('filenames') as $file) 
        {
            $i++;
            Storage::put('/public/salidas/'.$request->fileNumSalidaImg.'/images/'.time().'_'.$i.'.'.$file->extension(), file_get_contents($file), 'public');
        }
        return redirect('/int/salidas/'.$request->fileNumSalidaImg);  
    }

    public function deleteImgEntrada(Request $request)
    {
        Storage::delete('public/entradas/'.$request->ImgDeleteNumEntrada.'/images/'.$request->ImgNameDeleteNumEntrada);
        return redirect('/int/entradas/'.$request->ImgDeleteNumEntrada); 
    }
    public function deleteImgOutcome(Request $request)
    {
        Storage::delete('public/salidas/'.$request->ImgDeleteNumSalida.'/images/'.$request->ImgNameDeleteNumSalida);
        return redirect('/int/salidas/'.$request->ImgDeleteNumSalida); 
    }
}
