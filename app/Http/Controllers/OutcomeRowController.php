<?php

namespace App\Http\Controllers;

use App\Models\OutcomeRow;
use Illuminate\Http\Request;


class OutcomeRowController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        return "";
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
        $cout = count($request->income_row_id);
        $outcome_rows_ids=array();

        for ($i = 0; $i < $cout; $i++) 
        {
            $outcome_row = new OutcomeRow;
            $outcome_row->outcome_id = $request->outcomeID;
            $outcome_row->income_row_id = $request->income_row_id[$i];
            $outcome_row->units = $request->units[$i];
            $outcome_row->bundles = $request->bundles[$i];
            $outcome_row->ump = $request->ump[$i];
            $outcome_row->umb = $request->umb[$i];
            $outcome_row->net_weight = $request->net_weight[$i];
            $outcome_row->gross_weight = $request->gross_weight[$i];
            $outcome_row->save();
            array_push($outcome_rows_ids,$outcome_row->id);
        }
        
        return $outcome_rows_ids;
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\OutcomeRow  $outcomeRow
     * @return \Illuminate\Http\Response
     */
    public function show(OutcomeRow $outcomeRow)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\OutcomeRow  $outcomeRow
     * @return \Illuminate\Http\Response
     */
    public function edit(OutcomeRow $outcomeRow)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\OutcomeRow  $outcomeRow
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, OutcomeRow $outcomeRow)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\OutcomeRow  $outcomeRow
     * @return \Illuminate\Http\Response
     */
    public function destroy(OutcomeRow $outcome_row_id)
    {
        $outcome_row_id->delete();
    }
}
