<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Models\User;
use App\Traits\Listingapi;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use App\Notifications\ResetPasswordNotification;
use App\Models\PasswordReset;
use App\Mail\WelcomeMail;
use Illuminate\Support\Facades\Mail;

class AuthController extends Controller
{
     /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request)
    {
        $validaiton = Validator::make($request->all(), [
            'name'     => 'required|max:25',
            'email'    => 'required|email|max:255|unique:users',
            'password' => 'required|min:8|max:10',
            'phone'    => 'required|numeric',
            'pincode'  => 'required|numeric',
            'address'  => 'required|max:30',
            'city'     => 'required|max:255',
            'role'     => 'required|max:20',       
        ]);   
        
        if ($validaiton->fails()) {
            return error($validaiton->errors()->first());
        }

        $user = User::create($request->only(['name','email,,password','phone','pincode','address',  'city','role'])
        + [
            'password' =>  Hash::make($request->password),
            'email'    => $request->email,
            
        ]);
        Mail::to($user->email)->send(new WelcomeMail($user));
        
        return ok($user);
    }
    
// login 
    public function login(Request $request)
    {            
        $request->only('email','password');

        if (Auth::attempt(['email'=>$request->email,'password'=>$request->password])){                
        $user = User::where('email',$request->email)->first();
        $token = $user->createToken('Token')->plainTextToken;

        return response()->json([
            'user'  => $user,
            'token' => $token
        ]); 
        }
        return response()->json(['The email address or password you entered is incorrect.' ], 401);
     
    }
//forogt password link send mailtrap
    public function forgotPasswordLink(Request $request)
    {
        $validation = Validator::make($request->all(), [
            'email' => 'required|email|exists:users,email'
        ]);
        if ($validation->fails()) {
            return errorResponse($validation->errors()->first());
        }
        $user = User::where('email', $request->email)->first();
        $token = Str::random(16);
        $user->notify(new ResetPasswordNotification($token));
        PasswordReset::create([
            'token' => $token,
            'email' => $request->email
        ]);
        return "Mail Sent Successfully";
    }
// user forgot password
    public function forgotPassword(Request $request)
    {
        $validation = Validator::make($request->all(), [
            'token'     => 'required|exists:password_resets,token',
            'email'     => 'required|exists:password_resets,email|exists:users,email',
            'password'  => 'required|min:8|confirmed',
            'password_confirmation' => 'required'
        ]);
        if ($validation->fails()) {
            return error($validation->errors()->first());
        }
        $passwordReset = PasswordReset::where('token', $request->token)->first();
        $user = User::where('email', $passwordReset->email)->first();
        $user->update([
            'password'  => Hash::make($request->password)
        ]);
        return 'Password Changed Successfully';    
    }
}
