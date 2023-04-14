<?php

namespace App\Http\Controllers;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use App\Models\Category;
use Illuminate\Support\Facades\Validator;
use App\Traits\Listingapi;


class CategoryController extends Controller
{
    use Listingapi;
    /**
     * Display a listing of the resource.
     */
    public function list(Request $request)
    {
        
        $query = Category::query(); // get all modules
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
            'name'     => 'required|max:25',
        ]);   
        if ($validaiton->fails()) {
            return error($validaiton->errors()->first());
        }  

        $category = Category::create($request->all());
       
        return ok($category);
    }

    /**
     * Display the specified resource.
     */
    public function view($id = null)
    {
        try {
            $category = $id ? Category::findOrFail($id) : Category::all();
            return ok($category);
        }catch (ModelNotFoundException $e) {
            return response()->json(['message' => 'Category Not Found.'], 400);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request ,$id)
    {
        $validaiton = Validator::make($request->all(), [
            'name'  => 'required',
            
        ]);   
        if ($validaiton->fails()) {
            return error($validaiton->errors()->first());
        }
       
        $category  = Category::find($id);
        if (!$category) {
            return error('Category not found');
        }
        $category->update($request->only(['name']));
    
        return ok($category);
    }
    
    public function destroy($id)
    {
        $category = Category::findOrFail($id)->delete();
        return ok('Category Delete Successfully');
    }
}
