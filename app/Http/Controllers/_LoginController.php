<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\View;
use Auth;
use \Response;

class _LoginController extends Controller
{
    //
    public function showLogin(){
        //return view('login');
        if(Auth::check()){
            //Session::flash('message', 'Login !');
            //Redirect::back();
            //return Redirect::to('config');
            return redirect()->route('config.index');
        }
        if(view()->exists('login')){
            //Session::flash('message', 'Login !');
            return View::make('login');
        }
    }
    
    public function doLogin(){
        // validate the info, create rules for the inputs
        $rules = array(
            'email'    => 'required|email',
            'password' => 'required|min:3'
        );
        // run the validation rules on the inputs from the form
        $validator = Validator::make(Input::all(), $rules);
        // if the validator fails, redirect back to the form
        if($validator->fails()){
            //Session::flash('message', 'Login !');
            return redirect()->route('login.showLogin')
            ->withErrors($validator) // send back all errors to the login form
            ->withInput(Input::except('password')); // send back the input (not the password) so that we can repopulate the form
        }else{
            // create our user data for the authentication
            $userdata = array(
                'email'     => Input::get('email'),
                'password'  => Input::get('password'),
                'active'  => '1'
            );
            // attempt to do the login
            if(Auth::attempt($userdata)){
                // validation successful!
                // redirect them to the secure section or whatever
                // return Redirect::to('secure');
                // for now we'll just echo success (even though echoing in a controller is bad)
                //echo 'SUCCESS!';
                return redirect()->route('config.index');
            }else{        
                // validation not successful, send back to form 
                //Session::flash('message', 'Login !');
                return redirect()->route('login.showLogin')->withInput(Input::except('password'));
            }
        }
        
    }
    
    public function doLogout(){
        //Auth::logout(); // log the user out of our application
        
        return redirect()->route('login.showLogin'); // redirect the user to the login screen
    }
}
