<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class IncomingDataValidation
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        if ($request->isMethod("put") && sizeof($request->all()) == 0 || $request->isMethod("patch") && sizeof($request->all()) == 0) {
            return response()->json([
                "status" => false,
                "message" => "There is nothing to update."
            ], 400)->throwResponse();
        } else if ($request->isMethod("post") && $request->path() == "api/v1/users") {
            $request->validate([
                "user_id" => ["bail", "required"],
                "full_name" => ["bail", "required"],
                "email" => ["bail", "required"]
            ]);
        } else if ($request->isMethod("post") && $request->path() == "api/v1/logins") {
            $request->validate([
                "user_id" => ["bail", "required"]
            ]);
        }
        return $next($request);
    }
}
