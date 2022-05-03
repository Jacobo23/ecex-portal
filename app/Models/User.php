<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Auth;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function canDeleteIncome()
    {
        return str_contains(Auth::user()->permits, 'del_income');
    }
    public function canDeleteOutcome()
    {
        return str_contains(Auth::user()->permits, 'del_outcome');
    }

    public function canHideIncome()
    {
        return str_contains(Auth::user()->permits, 'hide_income');
    }
    public function canHideOutcome()
    {
        return str_contains(Auth::user()->permits, 'hide_outcome');
    }

    public function canDeletePartNumber()
    {
        return str_contains(Auth::user()->permits, 'del_part_number');
    }
    public function canEditPartNumber()
    {
        return str_contains(Auth::user()->permits, 'edit_part_number');
    }

    public function canDeleteCustomer()
    {
        return str_contains(Auth::user()->permits, 'del_customer');
    }
    public function canEditCustomer()
    {
        return str_contains(Auth::user()->permits, 'edit_customer');
    }

    public function canDeleteCarrier()
    {
        return str_contains(Auth::user()->permits, 'del_carrier');
    }
    public function canEditCarrier()
    {
        return str_contains(Auth::user()->permits, 'edit_carrier');
    }
    public function canQuitOnhold()
    {
        return str_contains(Auth::user()->permits, 'quit_onhold');
    }
    public function canCreateOC()
    {
        return str_contains(Auth::user()->permits, 'create_oc');
    }
}
