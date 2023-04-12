<?php

namespace App\Http\Controllers;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\Products;
use Illuminate\Support\Facades\Validator;
use App\Traits\Listingapi;

class OrderController extends Controller
{
    use Listingapi;
    /**
     * Display a listing of the resource.
     */
    public function list(Request $request)
    {
        $query = Order::query(); // get all modules
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
        $validator = Validator::make($request->all(), [
            'user_id'         => 'required',
            'cart_id'         => 'required',
            'quantity'        => 'required|integer|min:1',
            'price'           => 'required|integer|min:1',
            'payment_method'  => 'required|string',
            'address'         => 'required|string',
            'phone'           => 'required|string|min:10|max:10',
            'city'            => 'required|string',
            'pincode'         => 'required|integer|min:6',
            'status'          => 'required|string|max:10',
            'product_id'      => ['required',
            function($attribute, $value, $fail) {
                if (!Products::where('id', $value)->exists()) {
                    $fail('Invalid product_id.');
                }
            }
        ]
    ]);
        if ($validator->fails()) {
            return error($validator->errors()->first());
        }

        $order = Order::create($request->all());
       
        return ok($order);
    }
    /**
     * Display the specified resource.
     */
    public function view($id = null)
   {
        try {
            $order = $id ? Order::findOrFail($id) : Order::all();
            return ok($order);
        }catch (ModelNotFoundException $e) {
            return response()->json(['message' => 'Order Not Found..'], 400);
        }
    }

}



