<?php

namespace App\Http\Controllers;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Models\User;
use App\Traits\Listingapi;
use Illuminate\Support\Facades\Hash;
class UserController extends Controller
{
    use Listingapi;
    /**
     * Display a listing of the resource.
     */
    public function list(Request $request)
    {   
        $query = User::query(); // get all modules
        $searchable_fields = ['name']; // fields to search
    
        // validate request
        $this->ListingValidation();
    
        // filter, search and paginate data
        $data = $this->filterSearchPagination($query, $searchable_fields);
    
        // return response
        return response()->json([
            'success' => true,
            'data' => $data['query']->get(),
            'total' => $data['count']
        ]);
    }

   

    /**
     * Display the specified resource.
     */
    public function view($id = null)
    {
        try {
            $user = $id ? User::findOrFail($id) : User::all();
            return ok($user);
        }catch (ModelNotFoundException $e) {
            return response()->json(['message' => 'User Not Found..'], 400);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request ,$id)
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
       
        $user  = User::find($id);
        if (!$user) {
            return error('User not found');
        }
    
        $user->update($request->only(['name','email,,password','phone','pincode','address',  'city','role']));
    
        return ok($user);
    }
     
    public function destroy($id)
    {
        $user = User::findOrFail($id)->delete();
        return ok('User Delete Successfully');
    }


    //User Change Password
       public function changepassword(Request $request)
       {
           $validation = Validator::make($request->all(), [
               'current_password'        => 'required|current_password',
               'password'                => 'required|min:8',
               'password_confirmation'   => 'required|same:password',
           ]);
           if ($validation->fails()) {
               return error($validation->errors()->first());
           }
           $user = Auth::user();
           if ($user) {
               $user->update([
                   'password' => Hash::make($request->password),
               ]);
               return response()->json([
                   'message' => 'Password changed successfully',
               ]);
           }
           return response()->json([
               'message' => 'Invalid current password',
           ], 400);
        }

    //Logout user
        public function logout(Request $request)
        {
            $request->user()->currentAccessToken()->delete();
            return response()->json([
                "message" => "User successfully logout",
            ]);
            return response()->json(['message' => 'Logout successfully']);
        }

    
}






