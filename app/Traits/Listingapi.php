<?php

namespace App\Traits;

trait Listingapi
{

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
 //Search and Pagination for listing APIs
    public function filterSearchPagination($query, $searchable_fields)
    {

// For filtering by is_active field
        if (isset(request()->is_active)) {
            $query = $query->where('is_active', request()->is_active);
        }

//Get deleted record only....
        if (request()->only_trashed) {
            $query = $query->onlyTrashed();
        }
        
//Search with searchable fields ...
        if (request()->search) {
            $search = request()->search;
            $query  = $query->where(function ($q) use ($search, $searchable_fields) {
                
//adding searchable fields to or where condition logic...           
        foreach ($searchable_fields as $searchable_field) {
             $q->orWhere($searchable_field, 'like', '%'.$search.'%');
                }
            });
        }

//Pagination query logic..  
        $count          = $query->count();
        if (request()->page || request()->perPage) {
            $page       = request()->page;
            $perPage    = request()->perPage ?? 10;
            $query      = $query->skip($perPage * ($page - 1))->take($perPage);
        }
        return ['query' => $query, 'count' => $count];
    }
}