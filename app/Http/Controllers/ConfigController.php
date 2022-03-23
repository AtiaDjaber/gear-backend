<?php

namespace App\Http\Controllers;

use App\model\Config;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Http\Traits\UploadTrait;

class ConfigController extends Model
{
    use UploadTrait;
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


    public function uploadImage(Request $request)
    {
        $Config = Config::where('id', 1)->first();

        $logo = $this->uploadFile($request->file('logo'), "");

        if ($Config->update(["logo" => $logo])) {
            return response()->json($Config, 200);
        }
        return response()->json(['message' => 'Error Ocurred', 'data' => null], 400);
    }
}
