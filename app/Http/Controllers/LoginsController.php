<?php

namespace App\Http\Controllers;

use App\Models\Logins;
use App\Models\Users;
use Illuminate\Http\Request;
use App\Custom\Authentication;

class LoginsController extends Controller
{
    public function create(Request $request)
    {
        $request->request->add([
            "device_token" => request()->header("device_token"),
            "device_brand" => request()->header("device_brand"),
            "device_model" => request()->header("device_model"),
            "app_version" => request()->header("app_version"),
            "os_version" => request()->header("os_version")
        ]);
        if (Users::find($request->request->get("user_id"))) {
            $log = Logins::updateOrCreate(["user_id" => $request->request->get("user_id"), "device_token" => $request->request->get("device_token")], $request->all());
            $auth = new Authentication();
            return response()->json([
                "status" => true,
                "message" => "User logged in successfully.",
                "data" => [
                    "theme" => $log->users->theme,
                    "token" => $auth->encode($request->request->get("user_id"))
                ]
            ], 200);
        } else {
            return response()->json([
                "status" => false,
                "message" => "User not found."
            ], 404);
        }
    }

    public function read(Request $request)
    {
        if (sizeof(Logins::where("user_id", $request->request->get("user_id"))->get()) > 0) {
            return response()->json([
                "status" => true,
                "message" => "Login data retrieved successfully.",
                "data" => Logins::where("user_id", $request->request->get("user_id"))->get()
            ], 200);
        } else {
            return response()->json([
                "status" => false,
                "message" => "Login not found."
            ], 404);
        }
    }

    public function read_all()
    {
        if (Logins::all()) {
            return response()->json([
                "status" => true,
                "message" => "All login data retrieved successfully.",
                "data" => Logins::all()
            ], 200);
        } else {
            return response()->json([
                "status" => false,
                "message" => "No login found."
            ], 404);
        }
    }

    public function update(Request $request)
    {
        if (sizeof(Logins::where("user_id", $request->request->get("user_id"))->where("device_token", request()->header("device_token"))->get()) > 0) {
            Logins::where("user_id", $request->request->get("user_id"))->where("device_token", request()->header("device_token"))->update($request->all());
            return response()->json([
                "status" => true,
                "message" => "Login data updated successfully."
            ], 200);
        } else {
            return response()->json([
                "status" => false,
                "message" => "Login not found."
            ], 404);
        }
    }

    public function delete(Request $request)
    {
        Logins::where("user_id", $request->request->get("user_id"))->where("device_token", request()->header("device_token"))->delete();
        return response()->json([
            "status" => true,
            "message" => "User logged out successfully."
        ], 200);
    }
}
