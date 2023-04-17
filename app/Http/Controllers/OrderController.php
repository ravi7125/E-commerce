<?php

namespace App\Http\Controllers;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\Products;
use App\Models\User;
use Illuminate\Support\Facades\Validator;
use App\Traits\Listingapi;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class OrderController extends Controller
{
    use Listingapi;
    /**
     * Display a listing of the resource.
     */
    public function list(Request $request)
    {
        $validaiton = Validator::make($request->all(), [
            'page'    => 'nullable|integer',
            'perpage' => 'nullable|integer',
           
        ]);   
        if ($validaiton->fails())
            return $validaiton->errors();
        $query = DB::table('users')->orderBy('id','Desc'); // display to user data is descending order
        $perPage = request()->query('perPage', 10);
        $page = request()->query('page', 1);
    
        // Filter the results based on the user role
        if (Auth::user()->role == 'admin') {
            // Show all users' data like a admin and user all display 
        } else {
            // Only show the logged-in user's data
            $query->where('id', Auth::user()->id);
        }
    
        // Paginate the results
        $users = $query->paginate($perPage, ['*'], 'page', $page);
    
        // Return response massage
        return response()->json([
            'data' => $users->items(),
            'total' => $users->total(),
            'perPage' => $perPage,
            'currentPage' => $page,
            'lastPage' => $users->lastPage(),
            'from' => $users->firstItem(),
            'to' => $users->lastItem(),
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'user_id'         => 'required|exists:users,id',
            'cart_id'         => 'required|exists:carts,id',
            'quantity'        => 'required|integer|min:1',
            'price'           => 'required|integer|min:1',
            'payment_method'  => 'required|string',
            'address'         => 'required|string',
            'phone'           => 'required|string|min:10|max:10',
            'city'            => 'required|string',
            'pincode'         => 'required|integer|min:6',
            'status'          => 'required|string|max:10',
            'product_id'      => 'required|exists:products,id',

            'product_id' => ['required',
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

    public function update(Request $request ,$id)
    {
        $validator = Validator::make($request->all(), [
            'user_id'         => 'required|exists:users,id',
            'cart_id'         => 'required|exists:carts,id',
            'quantity'        => 'required|integer|min:1',
            'price'           => 'required|integer|min:1',
            'payment_method'  => 'required|string',
            'address'         => 'required|string',
            'phone'           => 'required|string|min:10|max:10',
            'city'            => 'required|string',
            'pincode'         => 'required|integer|min:6',
            'status'          => 'required|string|max:10',
            'product_id'      => 'required|exists:products,id',

            'product_id' => ['required',
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

        $order  = Order::find($id);
        if (!$order) {
            return error('order not found');
        }
        $order->update($request->all());
    
        return ok($order);
    }
    public function destroy($id)
    {
        $order = Order::findOrFail($id)->delete();
        return ok('Order Delete Successfully');
    }

}



