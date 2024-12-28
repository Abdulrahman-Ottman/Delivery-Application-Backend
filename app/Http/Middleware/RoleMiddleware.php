<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RoleMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $store_id = $request->header('store_id');
        $role = base64_decode($request->header('role'));
        if($role!='admin' && $role!='superAdmin')
            return response()->json(['message' => 'Access Denied.'], 403);

        if (!$request->user() || ($request->user()->role->name=='admin' && 'admin'!= $role)) {
            return response()->json(['message' => 'Access Denied.'], 403);
        }

        if($request->user()->role->name == 'admin')
            if(!($request->user()->store()->pluck('id') == $store_id))
                return response()->json(['message' => 'Access Denied.'], 440);

        return $next($request);
    }
}
