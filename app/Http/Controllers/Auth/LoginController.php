<?php

namespace App\Http\Controllers\Auth;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Providers\RouteServiceProvider;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Foundation\Auth\AuthenticatesUsers;

class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = RouteServiceProvider::HOME;
    public function redirectTo() : string {
       
        if(Auth::user()->type !== 'client'){
          return '/home';
        }else{
          return '/profile';
        }
       
      }
    /**
     * Create a new controller instance.
     *
     * @return void
     */

     
   

 

    /**
     * Get username property.
     *
     * @return string
     */

    public function login(Request $request)
    {
     
    
      $this->validate($request, [
          'email' => 'required',
          'password' => 'required',
      ]);
     
        $user = DB::table('users')->where('email', $request->input('email'))->first();

        if($user === null){
          Session::put('login_error', 'Your email and password wrong!!');
          die();
        }

        if ($user->is_deleted =! null &&  auth()->guard('web')->attempt(['email' => $request->input('email'), 'password' => $request->input('password') ])) {
         
            $new_sessid   = Session::getId(); //get new session_id after user sign in
            
            if($user->session_id != null) {
                $last_session = Session::getHandler()->read($user->session_id); 

                if ($last_session) {
                    if (Session::getHandler()->destroy($user->session_id)) {
                        
                    }
                }
            }
            
            DB::table('users')->where('id', $user->id)->update(['session_id' => $new_sessid]);
            $user = auth()->guard('web')->user();

          
            return redirect($this->redirectTo());
        }   
        Session::put('login_error', 'Your email and password wrong!!');
        return back();

    }

    public function logout(){
    
      $user = Auth::user();


      Session::flush();
      Redirect::back();
   
      return redirect('/');
  }


}
