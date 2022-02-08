<?php

namespace App\Http\Controllers;

use App\model\Client;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ClientController extends Model
{

    public function validater()
    {
        return Validator::make(request()->all(), [
            'mobile' => 'required|string|min:9|max:16',
            'name' => 'required|string|min:2',
            'address' => 'nullable|string|min:3|max:10',
            'ancien' => 'nullable|numeric',
            'montant' => 'nullable|numeric',
            'photo' => 'nullable|string',
            'email' => 'nullable|string',
        ]);
    }

    public function index(Request $request)
    {
        $Clients = Client::orderBy('id', 'desc');
        if ($request->name != null) {
            $Clients = $Clients->where("name", 'LIKE', '%' . $request->name . '%');
        }

        $Clients = $Clients->paginate(10);
        return response()->json($Clients, 200, [], JSON_NUMERIC_CHECK);
    }

    public function getGroupSubjById(Request $request)
    {
        $user = Client::find($request->id);
        if ($user) {
            return response()->json($user->subjs, 200);
        }
        return BaseController::errorData($user, "السجل غير موجود");
    }

    public function getProductsById(Request $request)
    {
        $user = Client::find($request->id);
        if ($user) {
            return response()->json($user->Products()->paginate(10), 200);
        }
        return BaseController::errorData($user, "السجل غير موجود");
    }

    public function getById(Request $request)
    {
        $Client = Client::find($request->id);
        if ($Client) {
            return BaseController::successData($Client->groups, "تم جلب البيانات بنجاح");
        }
        return BaseController::errorData($Client->groups(), "السجل غير موجود");
    }

    public function store()
    {

        $validator = $this->validater();
        if ($validator->fails()) {
            return response()->json(['message' => $validator->getMessageBag(), 'data' => null], 400);
        }
        $user = Client::create($validator->validate());
        if ($user) {
            return response()->json(['message' => 'Created', 'data' => $user], 200);
        }
        return response()->json(['message' => 'Error Ocurred', 'data' => null], 400);
    }

    public function put(Request $request)
    {
        $validator = $this->validater();
        if ($validator->fails()) {
            return response()->json(['message' => $validator->getMessageBag(), 'data' => null], 400);
        }
        $Client = Client::findOrFail($request->id);
        $Client->update($request->all());
        if ($Client) {
            return response()->json(['message' => 'updated', 'data' =>  $Client], 200);
        }
        return response()->json(['message' => 'Error Ocurred', 'data' => null], 400);
    }

    public  function deleteClient(Request $request)
    {
        $Client = Client::destroy($request->id);
        return BaseController::successData($Client, "تمت العملية بنجاح");
    }


    //
}
