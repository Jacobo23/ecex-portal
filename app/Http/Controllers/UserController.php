<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Auth;


class UserController extends Controller
{
    //
    public function redirectUser()
    {
        if (Auth::user()->type == "user") 
        {
            return view('intern.home');
        }
        else
        {
            return view('customer.home');
        }
    }

    

}
