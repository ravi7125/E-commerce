<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;
class CheckRole
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle($request, Closure $next, $roles)
    {
        $user = auth()->user();
          //  dd($role);
        $roles = explode("|", $roles);    
        $status = false; 
        $userRole = auth()->user()->role;
        if(in_array($userRole, $roles)){
              $status = true;
        }
        //  dd($user);
       if(!$status) return response()->json(['error' => 'Unauthorized'], 403);
        return $next($request);
    }
    
}
