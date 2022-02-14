<?php

namespace App\Http\Controllers;

use App\model\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    public function validater()
    {
        return Validator::make(request()->all(), [
            'tel' => 'required|string|min:10|max:16',
        ]);
    }

    public function validaterName()
    {
        return Validator::make(request()->all(), [
            'name' => 'required|string|min:4|max:25',
            'id' => 'required'
        ]);
    }

    public function store(Request $request)
    {


        $user = User::create(request()->all());
        if ($user) {
            return response()->json(['message' => 'Created', 'data' => $user], 200);
        }
        return response()->json(['message' => 'Error Ocurred', 'data' => null], 400);
    }


    public function updateToken(Request $request)
    {
        $user = User::where("id", $request->id)->update(["token" => $request->token]);
        if ($user) {
            return response()->json(['message' => 'updated', 'data' => $user], 200);
        }
        return response()->json(['message' => 'Error Ocurred', 'data' => null], 400);
    }

    public function avatar(Request $request)
    {
        if (!$request->hasFile('photo'))
            return response()->json(['upload_file_not_found'], 400);

        $file = $request->file('photo');

        if (!$file->isValid())
            return response()->json(['invalid_file_upload'], 400);

        $image = date('Y-m-d H:i:s') . $file->getClientOriginalName();
        $path = public_path() . '/uploads/profile/';
        $file->move($path, $image);

        $user = User::where("id", $request->id)->update(["photo" => $image]);
        if ($user) {
            return response()->json(['message' => 'updated', 'data' => $image], 200);
        }
        return response()->json(['message' => 'Error Ocurred', 'data' => null], 400);
    }

    public function updateName(Request $request)
    {
        $validator = $this->validaterName();
        if ($validator->fails()) {
            return response()->json(['message' => $validator->getMessageBag(), 'data' => null], 400);
        }
        $user = User::where("id", $request->id)->update(["name" => $request->name]);
        if ($user) {
            return response()->json(['message' => 'updated', 'data' =>  $user], 200);
        }
        return response()->json(['message' => 'Error Ocurred', 'data' => null], 400);
    }

    public function put(Request $request)
    {
        $validator = $this->validater();
        if ($validator->fails()) {
            return response()->json(['message' => $validator->getMessageBag(), 'data' => null], 400);
        }
        $user = User::findOrFail($request->id);
        $user->update($request->all());
        if ($user) {
            return response()->json(['message' => 'updated', 'data' =>  $user], 200);
        }
        return response()->json(['message' => 'Error Ocurred', 'data' => null], 400);
    }
    public function me(Request $request)
    {
        return $request->user();
    }

    public function login(Request $request)
    {
        // if (!Auth::attempt($request->only('email', 'password'))) {
        //     return response()->json([
        //         'message' => 'Invalid login details'
        //     ], 401);
        // }

        $user = User::where('name', $request['name'])
            ->where("password", $request['password'])->first();

        // $token = $user->createToken('auth_token')->plainTextToken;

        if ($user) {
            return response()->json($user, 200);
        }
        return BaseController::errorData("error", "السجل غير موجود");
    }


    public function index()
    {
        $users = User::orderBy('id', 'desc')->paginate(10);
        return response()->json($users, 200);
    }
    public function getById(Request $request)
    {
        $user = User::find($request->id);
        if ($user) {
            return BaseController::successData($user, "تم جلب البيانات بنجاح");
        }
        return BaseController::errorData($user, "السجل غير موجود");
    }
    public function register(Request $request)
    {
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8',
        ]);

        $user = User::create([
            'name' => $validatedData['name'],
            'email' => $validatedData['email'],
            'password' => Hash::make($validatedData['password']),
        ]);

        $token = $user->createToken('auth_token')->plainTextToken;

        return response()->json([
            'access_token' => $token,
            'token_type' => 'Bearer',
        ]);
    }


    public  function remove(Request $request)
    {
        $user = User::destroy($request->id);
        return BaseController::successData($user, "تمت العملية بنجاح");
    }
}
