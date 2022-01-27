<?php

namespace App\Http\Controllers\API;

use App\Models\Pet;
use App\Models\User;
use App\Models\Client;
use App\Models\Service;
use App\Models\Schedule;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
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
      
        if($schedule  == false){
         
            

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

        
           
            return $create;
            }
    
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function Email(request $request)
    {
    
        $client = User::findOrFail($request->data['client_data']['id']); 
        $service = Service::findOrFail($request->data['service_data']['id']);
        $pet = Pet::findOrFail($request->data['pet_data']['id']);
        $employee = User::findOrFail($request->data['employee_data']['id']);
        $data=[
            'client'=>$client,
            'employee' => $employee,
            'service' => $service,
            'pet' => $pet,
            'messages'=> $request->data['details'],
            'date_start' => $request->data['start'],
            'date_end' => $request->data['end'],
            'status' => 'accepted'
        ];
        
        Mail::send('mail',$data,function($messages) use ($client){

            $messages->to($client->email);
            $messages->subject('Hi There');
        });
    }

    public function adminEmail(request $request){
        dd($request);
        $client = User::findOrFail($request['clientId']); 
        $service = Service::findOrFail($request['serviceId']);
        $pet = Pet::findOrFail($request['petId']);
        $employee = User::findOrFail($request['employeeId']);
        $data=[
            'client'=>$client,
            'employee' => $employee,
            'service' => $service,
            'pet' => $pet,
            'messages'=> $request['details'],
            'date_start' => $request['start_date'],
            'date_end' => $request['end'],
            'status' => $request['status']
        ];
        
        Mail::send('mail',$data,function($messages) use ($client){

            $messages->to($client->email);
            $messages->subject('Hi There');
        });

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

    public function removeSched(Request $request) {
       
        $schedule = Schedule::where('appointHash', '=', $request['appointHash']);

    

        $updateSched = $schedule->update(['status' => 'remove']);

        return $updateSched;
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
