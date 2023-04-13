<?php

namespace App\Http\Controllers;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use App\Models\Products;
use App\Models\subcategory;
use Illuminate\Support\Facades\Validator;
use App\Traits\Listingapi;
class ProductsController extends Controller
{
    use Listingapi;
    /**
     * Display a listing of the resource.
     */
    public function list(Request $request)
    {
        $query = Products::query(); // get all modules
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
     * Show the form for creating a new resource.
     */
    public function create(Request $request)
    {
        $validaiton = Validator::make($request->all(), [            
            'name'             => 'required|string|max:255',
            'description'      => 'required|string|max:255',
            'price'            => 'required|integer|min:0',
            'quantity'         => 'required',
            'categories_id'    => 'required',
            'subcategories_id' => ['required',
            function($attribute, $value, $fail) {
                if (!subcategory::where('id', $value)->exists()) {
                    $fail('Invalid subcategories_id.');
                }
            }
        ]
    ]);  
        if ($validaiton->fails()) {
            return error($validaiton->errors()->first());
        }
        $products = Products::create($request->all());  
  
        return ok($products);
    }

    /**
     * Display the specified resource.
     */
    public function view($id = null)
   {
        try {
            $products = $id ? Products::findOrFail($id) : Products::all();
            return ok($products);
        }catch (ModelNotFoundException $e) {
            return response()->json(['message' => 'Products Not Found..'], 400);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request ,$id)
    {
        $validaiton = Validator::make($request->all(), [            
            'name'             => 'required|string|max:255',
            'description'      => 'required|string|max:255',
            'price'            => 'required|integer|min:0',
            'quantity'         => 'required',
            'categories_id'    => 'required',
            'subcategories_id' => ['required',
            function($attribute, $value, $fail) {
                if (!subcategory::where('id', $value)->exists()) {
                    $fail('Invalid subcategories_id.');
                }
            }
        ]
    ]);  
        if ($validaiton->fails()) {
            return error($validaiton->errors()->first());
        }
        $products  = Products::find($id);
        if (!$products) {
            return error('products not found');
        }
        $products->update($request->only(['name']));
    
        return ok($products);
    }
     
    public function destroy($id)
    {
        $products = Products::where('id', $id)->first();
        if ($products) {
            $products->delete();
        return ok('Products Delete Successfully');
        }
        return error('Products Already Deleted');
    }

}


