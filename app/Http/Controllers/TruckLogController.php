<?php

namespace App\Http\Controllers;

use App\Models\TruckLog;
use Illuminate\Http\Request;

class TruckLogController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return "hola";
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\TruckLog  $truckLog
     * @return \Illuminate\Http\Response
     */
    public function show(TruckLog $truckLog)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\TruckLog  $truckLog
     * @return \Illuminate\Http\Response
     */
    public function edit(TruckLog $truckLog)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\TruckLog  $truckLog
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, TruckLog $truckLog)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\TruckLog  $truckLog
     * @return \Illuminate\Http\Response
     */
    public function destroy(TruckLog $truckLog)
    {
        //
    }
}
