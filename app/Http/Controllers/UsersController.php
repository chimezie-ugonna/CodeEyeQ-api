<?php

namespace App\Http\Controllers;

use App\Models\Users;
use Illuminate\Http\Request;
use App\Custom\Authentication;

class UsersController extends Controller
{
    public function create(Request $request)
    {
        $first_name = "";
        $last_name = "";
        if ($request->request->has("full_name")) {
            $full_name_split = explode(" ", $request->request->get("full_name"), 2);
            $first_name = $full_name_split[0];
            if (count($full_name_split) > 1) {
                $last_name = $full_name_split[1];
            }
        }
        $request->request->add([
            "first_name" => $first_name,
            "last_name" => $last_name,
            "image_path" => "",
            "gender" => "",
            "dob" => "",
            "theme" => "system",
            "type" => "user",
            "point" => "0",
            "device_token" => request()->header("device_token"),
            "device_brand" => request()->header("device_brand"),
            "device_model" => request()->header("device_model"),
            "app_version" => request()->header("app_version"),
            "os_version" => request()->header("os_version")
        ]);
        Users::firstOrCreate(["user_id" => $request->request->get("user_id")], $request->all());
        Users::find($request->request->get("user_id"))->logins()->updateOrCreate(["user_id" => $request->request->get("user_id"), "device_token" => $request->request->get("device_token")], $request->all());
        $auth = new Authentication();
        return response()->json([
            "status" => true,
            "message" => "User created successfully.",
            "data" => [
                "theme" => Users::find($request->request->get("user_id"))->value("theme"),
                "token" => $auth->encode($request->request->get("user_id"))
            ]
        ], 201);
    }

    public function read(Request $request)
    {
        if (Users::find($request->request->get("user_id"))) {
            return response()->json([
                "status" => true,
                "message" => "User data retrieved successfully.",
                "data" => Users::find($request->request->get("user_id"))
            ], 200);
        } else {
            return response()->json([
                "status" => false,
                "message" => "User not found."
            ], 404);
        }
    }

    public function read_all()
    {
        if (Users::all()) {
            return response()->json([
                "status" => true,
                "message" => "All users data retrieved successfully.",
                "data" => Users::all()
            ], 200);
        } else {
            return response()->json([
                "status" => false,
                "message" => "No user found."
            ], 404);
        }
    }

    public function update(Request $request)
    {
        if (Users::find($request->request->get("user_id"))) {
            if ($request->request->has("full_name")) {
                $full_name_split = explode(" ", $request->request->get("full_name"), 2);
                $first_name = $full_name_split[0];
                $last_name = "";
                if (count($full_name_split) > 1) {
                    $last_name = $full_name_split[1];
                }
                $request->request->add([
                    "first_name" => $first_name,
                    "last_name" => $last_name
                ]);
            }
            Users::find($request->request->get("user_id"))->update($request->all());
            return response()->json([
                "status" => true,
                "message" => "User data updated successfully."
            ], 200);
        } else {
            return response()->json([
                "status" => false,
                "message" => "User not found."
            ], 404);
        }
    }

    public function delete(Request $request)
    {
        Users::find($request->request->get("user_id"))->logins()->delete();
        Users::destroy($request->request->get("user_id"));
        return response()->json([
            "status" => true,
            "message" => "User deleted successfully."
        ], 200);
    }
}
