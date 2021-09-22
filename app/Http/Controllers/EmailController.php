<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Income;
use App\Models\Outcome;
use Illuminate\Support\Facades\Storage;
use Mail;
use PDF;

class EmailController extends Controller
{

    public function sendEmailEntrada(string $numero_de_entrada)
    {
        $yearInc=substr($numero_de_entrada,0,-5);
        $numInc=substr($numero_de_entrada,4);
        $income = Income::where('year', $yearInc)->where('number', $numInc)->first();
        $emails = $income->customer->emails1();
        
        if($income)
        {
            // Marcar entrada como "enviada"
            $income->sent = true;
            $income->save();
            $files_path = 'public/entradas/'.$numero_de_entrada.'/';

            //generar PDF
            $income->income_rows; //<- se llama esta linea con el fin de cargar las partidas de esta entrada
            $pdf = PDF::loadView('intern.entradas.pdf', compact('income'))->setPaper('a4', 'landscape');
            $pdf->save(public_path('/storage/entradas/'.$numero_de_entrada.'/'.$numero_de_entrada.'.pdf'));
            //borrar un archivo innecesario que podria estar presente
            Storage::delete($files_path."temp_massive.xlsx");

            //Enviar correo
            Mail::send('emails.entrada', ['income' => $income], function ($m) use ($income, $numero_de_entrada,$emails, $files_path) {
                $m->from('do-not-reply@ecex-portal.org', 'Ecex Notification');
                foreach ($emails as $email) 
                {
                    $email = trim($email," ");
                    if($email != "")
                    {
                        $m->to($email,null)->subject('Entrada '. $numero_de_entrada);
                    }
                }
                
                if (Storage::exists($files_path)) 
                {
                    $files = Storage::allFiles($files_path);
                    foreach ($files as $file) 
                    {
                        $m->attach(public_path(Storage::url($file)));
                    }
                }
            });
        }
    }

    public function sendEmailSalida(Outcome $outcome)
    {
        $numero_de_salida = $outcome->getOutcomeNumber(false);
        $emails = $outcome->customer->emails1();
    
        $files_path = 'public/salidas/'.$numero_de_salida.'/';

        //generar PDF

        $outcome->outcome_rows; //<- se llama esta linea con el fin de cargar las partidas de esta salida

        $pdf = PDF::loadView('intern.salidas.pdf', compact('outcome'))->setPaper('a4', 'landscape');
        $pdf->save(public_path('/storage/salidas/'.$numero_de_salida.'/'.$numero_de_salida.'.pdf'));
        
        //Enviar correo
        Mail::send('emails.salida', ['outcome' => $outcome], function ($m) use ($outcome, $numero_de_salida,$emails, $files_path) {
            $m->from('do-not-reply@ecex-portal.org', 'Ecex Notification');
            foreach ($emails as $email) 
            {
                $email = trim($email," ");
                if($email != "")
                {
                    $m->to($email,null)->subject('Salida '. $numero_de_salida);
                }
            }
            
            if (Storage::exists($files_path)) 
            {
                $files = Storage::allFiles($files_path);
                foreach ($files as $file) 
                {
                    $m->attach(public_path(Storage::url($file)));
                }
            }
            //archivos de las entradas de esta salida
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
                        $m->attach(public_path(Storage::url($file)));
                    }
                }
            }

        });
        
    }

    public static function onHoldNotification(Income $income)
    {
        $emails = $income->customer->emails1();
        $numero_de_entrada = $income->getIncomeNumber();
        Mail::send('emails.onhold', ['income' => $income], function ($m) use ($income, $numero_de_entrada, $emails) {
            $m->from('do-not-reply@ecex-portal.org', 'Ecex Notification');
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

