<?php

namespace App\Http\Controllers\API;

use App\Models\Schedule;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class ScheduleController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    public function __construct()
    {

        $this->middleware('auth:api');

    }
    public function index()
    {
        return Schedule::all();
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */

    public function scheduleEvent()
    {
        return Report::with(['clientData','employeeData'])->get();
    }
   

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {

        // $this->validate($request,[
        //     'procedure' => 'required|max:191',
        //     'employee_id' => 'required|numeric',
        //     'client_id' => 'required|numeric',
        //     'pet_id' => 'required|numeric',
        //     'note' => 'required',
        //     'weight' => 'required',
        //     'due_date' => 'required',
        // ]);
  
        return Schedule::create([
           
            'employee_id' => $request['employeeId'],
            'client_id' => $request['clientId'],
            // 'service_id' => $request['serviceId'],
            'start' =>$request['start'],
            'end'=> $request['end'],
           
       
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

   
}
