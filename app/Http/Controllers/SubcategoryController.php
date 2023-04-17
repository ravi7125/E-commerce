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

    // Validation for listing APIs
    public function ListingValidation()
    {
        $this->validate(request(), [
            'page'      => 'integer',
            'perPage'   => 'integer',           
            'search'    => 'nullable',            
        ]);
        return true;
    }

    public function list(Request $request)
{
    $validation = Validator::make($request->all(), [
        'categories_id'         => 'nullable|integer',
        'page'                  => 'nullable|integer',
        'perpage'               => 'nullable|integer',
        'search'                => 'nullable|string',
    ], [
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

    $query = Subcategory::query();

    if ($request->has('categories_id')) {
        $query->where('categories_id', $request->categories_id);
    }

    $searchable_fields = ['name', 'categories_id'];

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
            'name'            => 'required|max:25',
            'categories_id'   => ['required','exists:categories,id',
            function($attribute, $value, $fail) {
                if (!Category::where('id', $value)->exists()) {
                    $fail('The selected categories id is invalid.');
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
            'categories_id'   => ['required','exists:categories,id',
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
        $subcategory = subcategory::findOrFail($id)->delete();
        return ok('Subcategory Delete Successfully');
    }

}

