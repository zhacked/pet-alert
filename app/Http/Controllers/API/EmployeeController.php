<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class EmployeeController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return User::where('type','employee')
                    ->WhereNull('is_deleted')->get();
    }


    public function customemployees()
    {
        
        return User::where('position' ,'veterinarian')
                    ->where('type','employee')
                    ->get();
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
            'name' => 'required|string|max:191',
            'email' => 'required|string|email|max:191|unique:users',
            'gender' => 'required|string',
            'number' => 'required|numeric|digits:11|regex:/(09)[0-9]{9}/',
            'position' => 'required|string',
            'schedFrom' => 'required',
            'schedTo' => 'required',
            'position' => 'required|string',
            'address' => 'required|string',
            'password' => 'required|string',

        ]);
     
        return User::create([
            'name' => $request['name'],
            'email' => $request['email'],
            'number' =>$request['number'],
            'gender' => $request['gender'],
            'address' => $request['address'],
            'position'=>$request['position'],
            'schedFrom' => $request['schedFrom'],
            'schedTo' => $request['schedTo'],
            'type'=>$request['type'],
            'color' => $request['color'],
            'password' => Hash::make($request['password']),
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
        $employee = User::findOrFail($id);
      
        $this->validate($request,[
            'name' => 'required|string|max:191',
            'email' => 'required|string|email|max:191|unique:users,email,'.$employee->id,
            'gender' => 'required|string',
            'number' => 'required|numeric|digits:11|regex:/(09)[0-9]{9}/',
            'position' => 'required|string',
            'address' => 'required|string',
            'schedFrom' => 'required|string',
            'schedTo' => 'required|string',
            'password' => 'sometimes|min:6'
        ]);

        $employee->update($request->all());
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
       
        $user = User::findOrFail($id);
        // delete the user

        $user->update([
            'is_deleted' => true
        ]);

        return ['message' => 'User Deleted'];
    }
}
