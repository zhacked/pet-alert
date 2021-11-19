<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Report;
class ReportController extends Controller
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
        return Report::with(['clientData','petData'])->latest()->paginate(10);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function petrecord()
    {
        return Report::with(['clientData','petData'])->where('client_id',auth('api')->user()->id)->get();
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {

        $this->validate($request,[
            'procedure' => 'required|string|max:191',
            'employee_id' => 'required|numeric',
            'client_id' => 'required|numeric',
            'pet_id' => 'required|numeric',
            'note' => 'required|string',
            'weight' => 'required|string',
            'due_date' => 'required|string',
        ]);
  
        return Report::create([
            'procedure' =>  $request['procedure'],
            'employee_id' => $request['employee_id'],
            'client_id' => $request['client_id'],
            'pet_id' =>$request['pet_id'],
            'note'=> $request['note'],
            'weight' => $request['weight'],
            'due_date' => $request['due_date']
       
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

        $report = Report::findOrFail($id);
      
        $this->validate($request,[
            'procedure' => 'required|string|max:191',
            'employee_id' => 'required|numeric',
            'client_id' => 'required|numeric',
            'pet_id' => 'required|numeric',
            'note' => 'required|string',
            'weight' => 'required|string',
            'due_date' => 'required|string',
        ]);

        $report->update($request->all());
        return ['message' => 'Updated the Client info'];
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
