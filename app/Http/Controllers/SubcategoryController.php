<?php

namespace App\Http\Controllers;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use App\Models\subcategory;
use Illuminate\Support\Facades\Validator;
use App\Traits\Listingapi;
use App\Models\Category;

use Illuminate\Http\Request;

class SubcategoryController extends Controller
{
    use Listingapi;
    /**
     * Display a listing of the resource.
     */
    public function list(Request $request)
    {
        $query = subcategory::query(); // get all modules
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
            'name'            => 'required|max:25',
            'categories_id'   => ['required',
            function($attribute, $value, $fail) {
                if (!Category::where('id', $value)->exists()) {
                    $fail('Invalid category_id.');
                }
            }
        ]
    ]);
        if ($validaiton->fails()) {
            return error($validaiton->errors()->first());
        }
        $subcategory = subcategory::create($request->all());
       
        return ok($subcategory);
    }

    /**
     * Display the specified resource.
     */
    public function view($id = null)
    {
        try {
            $subcategory = $id ? subcategory::findOrFail($id) : subcategory::all();
            return ok($subcategory);
        }catch (ModelNotFoundException $e) {
            return response()->json(['message' => 'Subcategory Not Found..'], 400);
        }
    }

      /**
     * Update the specified resource in storage.
     */
    public function update(Request $request ,$id)
    {
        $validaiton = Validator::make($request->all(), [
            'name'            => 'required|max:25',
            'categories_id'   => ['required',
           function($attribute, $value, $fail) {
                if (!Category::where('id', $value)->exists()) {
                    $fail('Invalid category_id.');
                }
            }
        ]
    ]);
        if ($validaiton->fails()) {
            return error($validaiton->errors()->first());
        }
        $subcategory  = subcategory::find($id);
        if (!$subcategory) {
            return error('subcategory not found');
        }
        $subcategory->update($request->only(['name']));
    
        return ok($subcategory);
    }
    public function destroy($id)
    {
        $subcategory = subcategory::where('id', $id)->first();
        if ($subcategory) {
            $subcategory->delete();
        return ok('Subcategory Delete Successfully');
        }
        return error('Subcategory Already Deleted');
    }


}

