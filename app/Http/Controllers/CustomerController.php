<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CustomerController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $clientes =  Customer::All();
        $can_edit = Auth::user()->canEditCustomer();
        $can_delete = Auth::user()->canDeleteCustomer();

        return view('intern.clientes.index', [
            'can_delete' => false,
            'can_edit' => $can_edit,
            'can_delete' => $can_delete,
            'customers' => $clientes
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('intern.clientes.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $customer = Customer::where('name',$request->txtNombre)->first();
        if(!$customer)
        {
            $customer = new Customer;
            $customer->name = $request->txtNombre;
        }
        $customer->capacity = 1500;

        //formateando
        $correos1_aux = str_split($request->txtCorreos1);
        $correos1 = "";
        foreach ($correos1_aux as $char) 
        {
            if($char == "," || $char == " " || $char == ";" )
            {
                $correos1 .= ",";
                continue;
            }
            $correos1 .= $char;
        }
        $correos1_aux = explode(",",$correos1);
        $correos1 = "";
        foreach ($correos1_aux as $item) 
        {
            if(!is_null($item) && $item != "")
            {
                $correos1 .= $item . "; ";
            }
        }

        //

        $correos2_aux = str_split($request->txtCorreos2);
        $correos2 = "";
        foreach ($correos2_aux as $char) 
        {
            if($char == "," || $char == " " || $char == ";" )
            {
                $correos2 .= ",";
                continue;
            }
            $correos2 .= $char;
        }
        $correos2_aux = explode(",",$correos2);
        $correos2 = "";
        foreach ($correos2_aux as $item) 
        {
            if(!is_null($item) && $item != "")
            {
                $correos2 .= $item . "; ";
            }
        }

        //

        $customer->emails = $correos1;
        $customer->emails_add = $correos2;
        //
        $customer->save();
        return redirect('/int/catalog/customers');
        
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Customer  $customer
     * @return \Illuminate\Http\Response
     */
    public function show(Customer $customer)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Customer  $customer
     * @return \Illuminate\Http\Response
     */
    public function edit(Customer $customer)
    {
        return view('intern.clientes.create', [
            'customer' => $customer,
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Customer  $customer
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Customer $customer)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Customer  $customer
     * @return \Illuminate\Http\Response
     */
    public function destroy(Customer $customer)
    {
        //
    }
}
