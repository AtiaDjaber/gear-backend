<?php

namespace App\Http\Controllers;

use App\model\Config;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ConfigController extends Model
{
    // public function validater()
    // {
    //     return Validator::make(request()->all(), [
    //         'mobile' => 'required|string|min:9|max:16',
    //         'name' => 'required|string|min:2',
    //         'address' => 'nullable|string|min:3|max:10',
    //         'photo' => 'nullable|string',
    //         'email' => 'nullable|string',
    //     ]);
    // }
    public function index()
    {
        $configs = Config::get()->first();
        return response()->json($configs, 200);
    }


    public function put(Request $request)
    {
        $Config = Config::where('id', 1)->first();
        if ($Config->update($request->all())) {
            return response()->json($Config, 200);
        }
        return response()->json(['message' => 'Error Ocurred', 'data' => null], 400);
    }
}
