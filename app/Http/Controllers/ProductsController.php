<?php

namespace App\Http\Controllers;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use App\Models\Products;
use App\Models\subcategory;
use App\Models\Category;
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
        $validation = Validator::make($request->all(), [
            'categories_id'         => 'nullable|integer',
            'subcategories_id'      => 'nullable|integer',
            'page'                  => 'nullable|integer',
            'perpage'               => 'nullable|integer',
            'search'                => 'nullable|string',

        ], [
            'subcategories_id'      => 'The subcategories_id field must be an integer.',
            'categories_id.integer' => 'The categories_id field must be an integer.',
            'page.integer'          => 'The page field must be an integer.',
            'perpage.integer'       => 'The perpage field must be an integer.',
            'search.string'         => 'The search field must be a string.',    
        ]);
    
        if ($validation->fails()) {
            return response()->json([
                'success' => false,
                'message' => $validation->errors()->first(),
            ]);
        }
    
        $query = Products::query();
    
        if ($request->has('categories_id')) {
            $query->where('categories_id', $request->categories_id);
        }
        if ($request->has('subcategories_id')) {
            $query->where('subcategories_id', $request->subcategories_id);
        }
    
        $searchable_fields = ['name', 'categories_id','subcategories_id'];
    
        if ($request->has('search')) {
            $search = $request->search;
            $this->filterSearchPagination($query, $searchable_fields);
        }
    
        $perPage = $request->per_page ?? 10;
    
        $data = $query->paginate($perPage);
    
        return response()->json([
            'success' => true,
            'data' => $data->items(),
            'total' => $data->total(),
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
            'categories_id'    => 'required|exists:categories,id',
            'subcategories_id' => 'required|exists:subcategories,id',
            'categories_id'    =>[ 'required',
            function($attribute, $value, $fail) {
                if (!Category::where('id', $value)->exists()) {
                    $fail('Invalid categories_id.');
                } elseif (Category::where('id', $value)->onlyTrashed()->exists()) {
                 }}
        ],
        'subcategories_id'  => [
            'required',
            function($attribute, $value, $fail) {
                if (!subcategory::where('id', $value)->exists()) {
                    $fail('Invalid sub_category_id.');
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
            'categories_id'    => 'required|exists:categories,id',
            'subcategories_id' => 'required|exists:subcategories,id',
            'categories_id' =>[ 'required',
            function($attribute, $value, $fail) {
                if (!Category::where('id', $value)->exists()) {
                    $fail('Invalid categories_id.');
                } elseif (Category::where('id', $value)->onlyTrashed()->exists()) {
                 }}
        ],
        'subcategories_id'  => [
            'required',
            function($attribute, $value, $fail) {
                if (!subcategory::where('id', $value)->exists()) {
                    $fail('Invalid sub_category_id.');
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
        $products = Products::findOrFail($id)->delete();
        return ok('Products Delete Successfully');
    }
}


