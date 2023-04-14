<?php

namespace App\Http\Controllers;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use App\Models\Cart;
use App\Models\Products;
use Illuminate\Support\Facades\Validator;
use App\Traits\Listingapi;
use Illuminate\Support\Facades\Auth;

class CartController extends Controller
{
    use Listingapi;
    /**
     * Display a listing of the resource.
     */
    public function list(Request $request)
    {
        $cart  = Cart::query();
        if (Auth::user()->role == 'user') {
            $user_id = Auth::user()->id;
            $cart = $cart->where('user_id', $user_id);
        }
        $cart = $cart->get();
        return ok('cart detail', $cart);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request)
    {
        $validaiton = Validator::make($request->all(), [
            'user_id'     => 'required',
            'quantity'    => 'required',
            'price'       => 'required',
            'product_id'  => ['required',
        function($attribute, $value, $fail) {
            if (!Products::where('id', $value)->exists()) {
                $fail('Invalid product_id.');
                }
            }
        ]
    ]);   
        if ($validaiton->fails()) {
            return error($validaiton->errors()->first());
        }
        $cart = Cart::create($request->all());
       
        return ok($cart);
    }
    /**
     * Display the specified resource.
     */
    public function view($id = null)
   {
        try {
            $cart = $id ? Cart::findOrFail($id) : Cart::all();
            return ok($cart);
        }catch (ModelNotFoundException $e) {
            return response()->json(['message' => 'Cart Not Found..'], 400);
        }
    }

    public function update(Request $request ,$id)
    {
        $validaiton = Validator::make($request->all(), [
            'user_id'     => 'required',
            'quantity'    => 'required',
            'price'       => 'required',
            'product_id'  => ['required',
        function($attribute, $value, $fail) {
            if (!Products::where('id', $value)->exists()) {
                $fail('Invalid product_id.');
                }
            }
        ]
    ]);   
        if ($validaiton->fails()) {
            return error($validaiton->errors()->first());
        }
        $cart  = Cart::find($id);
        if (!$cart) {
            return error('cart not found');
        }
        $cart->update($request->all());
    
        return ok($cart);
    }
    public function destroy($id)
    {
        $cart = Cart::findOrFail($id)->delete();
        return ok('Cart Delete Successfully');
    }
    
}
