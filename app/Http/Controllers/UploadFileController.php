<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class UploadFileController extends Controller
{
    //
    public function miltiupload(Request $request)
    {
        $i = 1;
        foreach ($request->file('filenames') as $file) 
        {
            $i++;
            Storage::put('/entradas/202100010/packing_list/file'.$i.'.txt', file_get_contents($file));
        }
    }
    public function uploadPakinglist(Request $request)
    {
        //Storage::put('/entradas/'.$request->fileNumEntrada.'/packing_list/packing-list.pdf', file_get_contents($request->file('file')));
        //return redirect('/int/entradas/'.$request->fileNumEntrada);
        
    }
    public function uploadImgEntrada(Request $request)
    {
        //$i = 0;
        //foreach ($request->file('filenames') as $file) 
        //{
        //    $i++;
        //    $file->store('avatars');
        //    //$file->storeAs(public_path('entradas/'.$request->fileNumEntradaImg.'/images/'), time().'_'.$i.'.'.$file->extension());
        //    //$fileName = time().'.'.$file->extension();  
   //
        //    //$file->move(public_path('uploads'), $fileName);
        //}
        //return '/entradas/'.$request->fileNumEntradaImg.'/images/';


        $i = 0;
        foreach ($request->file('filenames') as $file) 
        {
            $i++;
            Storage::put('/entradas/'.$request->fileNumEntradaImg.'/images/'.time().'_'.$i.'.'.$file->extension(), file_get_contents($file), 'public');
        }
        //return '/entradas/'.$request->fileNumEntradaImg.'/images/';
    }
    public function viewIncomeImg()
    {
        return storage_path();
        //return response()->file('/entradas/202100010/images/1628238110_1.jpg');
        //return Storage::download('/entradas/202100010/images/1628238110_1.jpg');
        //return Storage::url('app/entradas/202100010/images/1628238110_1.jpg');
    }
    
    public function download()
    {
        return Storage::download('/entradas/202100010/packing_list/uuu.txt');
    }
}
