<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Custom\Authentication;
use App\Models\Users;
use App\Models\Logins;

class TokenValidation
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
        if ($request->path() != "api/v1/users/create" || $request->path() != "api/v1/logins/create") {
            if ($request->bearerToken() != "") {
                $auth = new Authentication();
                $data = $auth->decode($request->bearerToken());
                if ($data != false && isset($data["user_id"])) {
                    $user_id = $data["user_id"];
                    if (!Users::find($user_id)) {
                        return response()->json([
                            "status" => false,
                            "message" => "Unauthorized access, unknown user."
                        ], 401);
                    } else {
                        $request->request->add(["user_id" => $user_id]);
                        Logins::where("user_id", $user_id)->where("device_token", $request->header("device_token"))->update([
                            "device_brand" => $request->header("device_brand"),
                            "device_model" => $request->header("device_model"),
                            "app_version" => $request->header("app_version"),
                            "os_version" => $request->header("os_version")
                        ]);
                    }
                } else {
                    return response()->json([
                        "status" => false,
                        "message" => "The bearer token is invalid."
                    ], 401)->throwResponse();
                }
            } else {
                return response()->json([
                    "status" => false,
                    "message" => "The bearer token authorization header is missing."
                ], 401)->throwResponse();
            }
        }
        return $next($request);
    }
}
