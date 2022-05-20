<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Income;
use App\Models\Outcome;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;
use Mail;
use PDF;

class EmailController extends Controller
{

    public function FormatoEmailEntrada(string $numero_de_entrada)
    {
        $yearInc=substr($numero_de_entrada,0,-5);
        $numInc=substr($numero_de_entrada,4);
        $income = Income::where('year', $yearInc)->where('number', $numInc)->first();
        //$emails = $income->customer->emails1();
        
        if($income)
        {
            return view('emails.formatos.entrada', [
                'income' => $income
            ]);
        }
    }

    public function sendEmailEntrada(Request $request)
    {
        $income = Income::find($request->incomeID);
        $numero_de_entrada = $income->getIncomeNumber();
        
        if($income)
        {
            // Marcar entrada como "enviada"
            $income->sent = true;
            $income->save();
            $files_path = 'public/entradas/'.$numero_de_entrada.'/';

            if (!Storage::exists($files_path)) 
            {
                Storage::makeDirectory($files_path);
            }
            //generar PDF
            $income->income_rows; //<- se llama esta linea con el fin de cargar las partidas de esta entrada
            $pdf = PDF::loadView('intern.entradas.pdf', compact('income'))->setPaper('a4', 'landscape');
            $pdf->save(public_path('/storage/entradas/'.$numero_de_entrada.'/'.$numero_de_entrada.'.pdf'));
            //borrar un archivo innecesario que podria estar presente
            Storage::delete($files_path."temp_massive.xlsx");
            //subir adjuntos adicionales
            if (!Storage::exists($files_path."adjuntos/")) 
            {
                Storage::makeDirectory($files_path."adjuntos/");
            }
            if(null !== $request->file('filenames'))
            {
                $i = 0;
                foreach ($request->file('filenames') as $file) 
                {
                    $i++;
                    Storage::put('/public/entradas/'.$numero_de_entrada.'/adjuntos/'.time().'_'.$i.'.'.$file->extension(), file_get_contents($file), 'public');
                }
            }

            //Enviar correo 
            Mail::send('emails.entrada', ['income' => $income, 'body' => $request->txtObservaciones], function ($m) use ($income, $numero_de_entrada, $files_path, $request) {
                $username = Auth::user()->name;
                $m->from('do-not-reply@ecex-portal.com', $username);
                $emails = explode(";",$request->txtTo);
                $emails2 = explode(";",$request->txtCc);
                
                foreach ($emails as $email) 
                {
                    $email = trim($email," ");
                    if($email != "")
                    {
                        $m->to($email,null)->subject($request->txtSubject);
                    }
                }

                foreach ($emails2 as $email) 
                {
                    $email = trim($email," ");
                    if($email != "")
                    {
                        $m->cc($email,null)->subject($request->txtSubject);
                    }
                }
                
                if (Storage::exists($files_path)) 
                {
                    //PDF entrada
                    $pdf_url = $files_path;
                    if(isset($request->chkPDF) && Storage::exists($pdf_url))
                    {
                        $pdfs = Storage::files($pdf_url);
                        foreach ($pdfs as $pdf) 
                        {
                            if (Storage::exists($pdf))
                            {
                                $m->attach(public_path(Storage::url($pdf)));
                            }
                        }
                    }
                    //packing list
                    $packing_list_url = $files_path . "packing_list/packing-list.pdf";
                    if(isset($request->chkPck) && Storage::exists($packing_list_url))
                    {
                        if (Storage::exists($packing_list_url))
                        {
                            $m->attach(public_path(Storage::url($packing_list_url)));
                        }
                    }
                    //imagenes
                    $images_url = $files_path . "images/";

                    if(isset($request->chkImgs) && Storage::exists($images_url))
                    {
                        $images = Storage::allFiles($images_url);
                        foreach ($images as $image) 
                        {
                            if (Storage::exists($image))
                            {
                                $m->attach(public_path(Storage::url($image)));
                            }
                        }
                    }
                    //adjuntos
                    $adjuntos_url = $files_path . "adjuntos/";

                    if(Storage::exists($adjuntos_url))
                    {
                        $adjuntos = Storage::allFiles($adjuntos_url);
                        foreach ($adjuntos as $adjunto) 
                        {
                            if (Storage::exists($adjunto))
                            {
                                $m->attach(public_path(Storage::url($adjunto)));
                            }
                        }
                    }
                }
            });
            //subir adjuntos adicionales
            if (!Storage::exists($files_path."adjuntos/")) 
            {
                Storage::deleteDirectory($files_path."adjuntos/");
            }
        }
        //return redirect('/int/entradas/'.$numero_de_entrada);
        return redirect('/');
    }

    public function FormatoEmailSalida(Outcome $outcome)
    {
        return view('emails.formatos.salida', [
            'outcome' => $outcome
        ]);
    }

    public function sendEmailSalida(Request $request)
    {
        $outcome = Outcome::find($request->outcomeID);
        // Marcar salida como "enviada"
        $outcome->sent = true;
        $outcome->save();

        $numero_de_salida = $outcome->getOutcomeNumber(false);
    
        $files_path = 'public/salidas/'.$numero_de_salida.'/';

        if (!Storage::exists($files_path)) 
        {
            Storage::makeDirectory($files_path);
        }

        //generar PDF

        $outcome->outcome_rows; //<- se llama esta linea con el fin de cargar las partidas de esta salida

        $pdf = PDF::loadView('intern.salidas.pdf', compact('outcome'))->setPaper('a4', 'landscape');
        $pdf->save(public_path('/storage/salidas/'.$numero_de_salida.'/'.$numero_de_salida.'.pdf'));

        //subir adjuntos adicionales
        if (!Storage::exists($files_path."adjuntos/")) 
        {
            Storage::makeDirectory($files_path."adjuntos/");
        }
        if(null !== $request->file('filenames'))
        {
            $i = 0;
            foreach ($request->file('filenames') as $file) 
            {
                $i++;
                Storage::put('/public/salidas/'.$numero_de_salida.'/adjuntos/'.time().'_'.$i.'.'.$file->extension(), file_get_contents($file), 'public');
            }
        }
        
        //Enviar correo
        Mail::send('emails.salida', ['outcome' => $outcome, 'body' => $request->txtObservaciones], function ($m) use ($outcome, $numero_de_salida, $request, $files_path) {
            $username = Auth::user()->name;
            $m->from('do-not-reply@ecex-portal.com', $username);

            $emails = explode(";",$request->txtTo);
            $emails2 = explode(";",$request->txtCc);

            foreach ($emails as $email) 
            {
                $email = trim($email," ");
                if($email != "")
                {
                    $m->to($email,null)->subject($request->txtSubject);
                }
            }

            foreach ($emails2 as $email) 
            {
                $email = trim($email," ");
                if($email != "")
                {
                    $m->cc($email,null)->subject($request->txtSubject);
                }
            }
            
            if (Storage::exists($files_path)) 
            {
                //PDF salida
                $pdf_url = $files_path;
                if(isset($request->chkPDF) && Storage::exists($pdf_url))
                {
                    $pdfs = Storage::files($pdf_url);
                    foreach ($pdfs as $pdf) 
                    {
                        if (Storage::exists($pdf))
                        {
                            $m->attach(public_path(Storage::url($pdf)));
                        }
                    }
                }
                //packing lists
                $packing_list_urls = $files_path . "packing_list/";

                if(isset($request->chkPck) && Storage::exists($packing_list_urls))
                {
                    $packings = Storage::allFiles($packing_list_urls);
                    foreach ($packings as $packing) 
                    {
                        if (Storage::exists($packing))
                        {
                            $m->attach(public_path(Storage::url($packing)));
                        }
                    }
                }
                //imagenes
                $images_url = $files_path . "images/";

                if(isset($request->chkImgs) && Storage::exists($images_url))
                {
                    $images = Storage::allFiles($images_url);
                    foreach ($images as $image) 
                    {
                        if (Storage::exists($image))
                        {
                            $m->attach(public_path(Storage::url($image)));
                        }
                    }
                }
            }
            //archivos de las entradas de esta salida
            
            if(isset($request->chkIncome))
            {
                $incomes = $outcome->getIncomes();
                foreach ($incomes as $income) 
                {
                    $income_path = 'public/entradas/'.$income.'/';
                    if (Storage::exists($income_path)) 
                    {
                        //borrar un archivo innecesario que podria estar presente
                        Storage::delete($income_path."temp_massive.xlsx");

                        $files = Storage::allFiles($income_path);
                        foreach ($files as $file) 
                        {
                            if (Storage::exists($file))
                            {
                                $m->attach(public_path(Storage::url($file)));
                            }
                        }
                    }
                }
            }

            //adjuntos
            $adjuntos_url = $files_path . "adjuntos/";

            if(Storage::exists($adjuntos_url))
            {
                $adjuntos = Storage::allFiles($adjuntos_url);
                foreach ($adjuntos as $adjunto) 
                {
                    if (Storage::exists($adjunto))
                    {
                        $m->attach(public_path(Storage::url($adjunto)));
                    }
                }
            }

        });
        //subir adjuntos adicionales
        if (!Storage::exists($files_path."adjuntos/")) 
        {
            Storage::deleteDirectory($files_path."adjuntos/");
        }
        return redirect('/int/salidas/'.$numero_de_salida);
        
    }

    public static function onHoldNotification(Income $income)
    {
        $emails = explode(';', $income->customer->emails);
        $numero_de_entrada = $income->getIncomeNumber();
        Mail::send('emails.onhold', ['income' => $income], function ($m) use ($income, $numero_de_entrada, $emails) {
            $m->from('do-not-reply@ecex-portal.com', 'Ecex Notification');
            foreach ($emails as $email) 
            {
                $email = trim($email," ");
                if($email != "")
                {
                    $m->to($email,null)->subject('On hold - Entrada: '. $numero_de_entrada);
                }
            }
        });
    }
}

