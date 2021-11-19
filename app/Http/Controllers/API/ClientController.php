<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Pet;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
class ClientController extends Controller
{
    public function __construct()
    {

        $this->middleware('auth:api');

    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return User::where('type','client')->get();
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function PetDetails()
    {
        return Pet::latest()->paginate(10);
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
            'number' => 'required|numeric',
            'address' => 'required|string'
        ]);

        return User::create([
            'name' => $request['name'],
            'email' => $request['email'],
            'number' =>$request['number'],
            'gender' => $request['gender'],
            'address' => $request['address'],
            'type'=>$request['type'],
            'password' => Hash::make($request['password']),
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function PetInfo($id)
    {
        return Pet::where('id', $id)->first();
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function showPet($id)
    {
        return Pet::Where('user_id', $id)->latest()->get();
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
        $client = User::findOrFail($id);

        $this->validate($request,[
            'name' => 'required|string|max:191',
            'email' => 'required|string|email|max:191|unique:users,email,'.$client->id,
            'gender' => 'required|string',
            'number' => 'required|numeric',
            'address' => 'required|string'
        ]);

        $client->update($request->all());
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

        $user->delete();

        return ['message' => 'User Deleted'];
    }
}
