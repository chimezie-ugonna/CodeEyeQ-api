<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class CheckHeaders
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
        if ($request->hasHeader("Accept") == null || $request->header("Accept") != "application/json") {
            return response()->json([
                "status" => false,
                "message" => "Missing content-type 'application/json' header."
            ], 400)->throwResponse();
        } else if ($request->hasHeader("device_token") == null || $request->header("device_token") == "") {
            return response()->json([
                "status" => false,
                "message" => "The device token header is missing."
            ], 400)->throwResponse();
        } else if ($request->hasHeader("device_brand") == null || $request->header("device_brand") == "") {
            return response()->json([
                "status" => false,
                "message" => "The device brand header is missing."
            ], 400)->throwResponse();
        } else if ($request->hasHeader("device_model") == null || $request->header("device_model") == "") {
            return response()->json([
                "status" => false,
                "message" => "The device model header is missing."
            ], 400)->throwResponse();
        } else if ($request->hasHeader("app_version") == null || $request->header("app_version") == "") {
            return response()->json([
                "status" => false,
                "message" => "The app version header is missing."
            ], 400)->throwResponse();
        } else if ($request->hasHeader("os_version") == null || $request->header("os_version") == "") {
            return response()->json([
                "status" => false,
                "message" => "The os version header is missing."
            ], 400)->throwResponse();
        }
        return $next($request);
    }
}
