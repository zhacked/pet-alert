<?php

namespace App\Http\Controllers\API;

use App\Models\Pet;
use App\Models\User;
use App\Models\Client;
use App\Models\Service;
use App\Models\Schedule;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Mail;

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
        return Schedule::with(['clientData','employeeData', 'petData', 'serviceData'])->get();
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */

    public function eventSchedule()
    {
        return Schedule::with(['clientData','employeeData', 'petData', 'serviceData'])->get();
    }

    public function petrecord()
    {
       
        return Schedule::with(['clientData','employeeData', 'petData', 'serviceData'])->where('client_id',auth('api')->user()->id)->get();
    }
    
    public function acceptancereport($id,$status){

        $report = Schedule::where('id',$id)->update([
            'status' => $status
        ]);

        return $report;
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {

  
        $schedule = Schedule::where('start',$request->start)
                            ->where('client_id',$request->client_id)
                            ->where('employee_id',$request->employee_id)
                            ->exists();
        $client = User::findOrFail($request['clientId']); 
        $service = Service::findOrFail($request['serviceId']);
        $pet = Pet::findOrFail($request['petId']);
        $employee = User::findOrFail($request['employeeId']);
        if($schedule  == false){
            $data=[
                'client'=>$client,
                'employee' => $employee,
                'service' => $service,
                'pet' => $pet,
                'messages'=> $request['details'],
                'date_start' => $request['start'],
                'date_end' => $request['end'],
                'status' => $request['status']
            ];
            
            

            $create =  Schedule::create([
                'appointHash' => $request['appointHash'],
                'employee_id' => $request['employeeId'],
                'client_id' => $request['clientId'],
                'service_id' => $request['serviceId'],
                'pet_id' => $request['petId'],
                'start' =>$request['start'],
                'end'=> $request['end'],
                'details' => $request['details'],
                'status' => $request['status']
            ]);

            Mail::send('mail',$data,function($messages) use ($client){

                $messages->to($client->email);
                $messages->subject('Hi There');
            });

            return $create;
            }
     




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
      
        
        $filterschedule = Schedule::where('start',$request->start)
                            ->where('client_id',$request->client_id)
                            ->where('employee_id',$request->employee_id)
                            ->exists();
        
        $schedule = Schedule::findOrFail($id);
        if($filterschedule == false){
            $schedule->update($request->all());
            return $schedule;
        }
        return 'error';


    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroySched(Request $request)
    {
        //
  
        Schedule::where('appointHash', $request['appointHash'])->delete();
        return true;
        
    }
  

   
}
