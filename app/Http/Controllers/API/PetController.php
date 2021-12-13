<?php

namespace App\Http\Controllers\API;

use App\Models\Pet;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Intervention\Image\Facades\Image;

class PetController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if(Auth::user()->type === 'client'){
             return Pet::with('clientData')->where('user_id', Auth::user()->id)->latest()->paginate(10);
        }

        return Pet::with('clientData')->latest()->paginate(10);
    }

    public function __construct()
    {

        $this->middleware('auth:api');

    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function petRecord()
    {
        return Pet::where('user_id',auth('api')->user()->id)->get();
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // dd($request->all());
        $this->validate($request,[
            'Name' => 'required|string|max:191',
            'species' => 'required|string',
            'breed' => 'required|string',
            'color' => 'required|string',
            'gender' => 'required|string',
            'birthday' => 'required|string',
           
        ]);

       
            $name = time().'.' . explode('/', explode(':', substr($request->photo, 0, strpos($request->photo, ';')))[1])[1];

            Image::make($request->photo)->save(public_path('image/pet/').$name);
            $request->merge(['photo' => $name]);

            $userPhoto = public_path('image/pet/').$request->photo;
           

       

        return Pet::create([
            'user_id' =>  $request['client_id'],
            'name' => $request['Name'],
            'species' => $request['species'],
            'breed' =>$request['breed'],
            'color'=> $request['color'],
            'gender' => $request['gender'],
            'birthday' => $request['birthday'],
            'photo' =>  $name ,

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
        $pet = Pet::findOrFail($id);
      
        $this->validate($request,[
            'Name' => 'required|string|max:191',
            'species' => 'required|string',
            'breed' => 'required|string',
            'color' => 'required|string',
            'gender' => 'required|string',
            'birthday' => 'required|string',
           
        ]);

   
    
        $currentPhoto = $pet->photo;


        if($request->photo != $currentPhoto){
            $name = time().'.' . explode('/', explode(':', substr($request->photo, 0, strpos($request->photo, ';')))[1])[1];

            Image::make($request->photo)->save(public_path('image/pet/').$name);
            $request->merge(['photo' => $name]);

            $userPhoto = public_path('image/pet/').$currentPhoto;
            if(file_exists($userPhoto)){
                @unlink($userPhoto);
            }

        }



        $pet->update($request->all());
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
        $user = Pet::findOrFail($id);
        // delete the user

        $user->delete();

        return ['message' => 'User Deleted'];
    }
}
