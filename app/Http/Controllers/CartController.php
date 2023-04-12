<?php

namespace App\Http\Controllers;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use App\Models\Cart;
use App\Models\Products;
use Illuminate\Support\Facades\Validator;
use App\Traits\Listingapi;

class CartController extends Controller
{
    use Listingapi;
    /**
     * Display a listing of the resource.
     */
    public function list(Request $request)
    {
        $query = Cart::query(); // get all modules
        $searchable_fields = ['user_id']; // fields to search
    
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

}
