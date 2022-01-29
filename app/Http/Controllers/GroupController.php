<?php

namespace App\Http\Controllers;

use App\model\Group;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class GroupController extends Model
{

    public function validater()
    {
        return Validator::make(request()->all(), [
            'name' => 'required|string',
            'subj_id' => 'required|exists:subjs,id',
            'Client_id' => 'required|exists:Clients,id',
            'price' => 'required|numeric',
            'quotas' => 'required|numeric',
            'percenatgeClient' => 'required|numeric'
        ]);
    }

    public function index(Request $request)
    {
        $Groups = Group::orderBy('id', 'desc')->with(['subj', 'Client']);
        if ($request->has("name")) {
            $Groups = $Groups->where("name", 'LIKE', '%' . $request->name . '%');
        }
        $Groups =  $Groups->paginate(10);
        return response()->json($Groups, 200);
    }

    public function getById(Request $request)
    {
        $user = Group::find($request->id)->with(['subj', 'Client'])
            ->first();
        if ($user) {
            return BaseController::successData($user, "تم جلب البيانات بنجاح");
        }
        return BaseController::errorData($user, "السجل غير موجود");
    }

    public function getGroupSubjById(Request $request)
    {
        $groups = Group::where('Client_id', $request->Client_id)->with(['subj'])
            ->get();
        if ($groups) {
            return response()->json($groups, 200);
        }
        return BaseController::errorData("erro data", "السجل غير موجود");
    }

    public function getProductByGroupId(Request $request)
    {
        $groups = Group::with('Products')
            ->find($request->id);
        if ($groups) {
            return response()->json($groups, 200);
        }
        return BaseController::errorData("erro data", "السجل غير موجود");
    }

    public function store()
    {

        $validator = $this->validater();
        if ($validator->fails()) {
            return response()->json(['message' => $validator->getMessageBag(), 'data' => null], 400);
        }
        $user = Group::create($validator->validate());

        if ($user) {
            return response()->json(['message' => 'Created', 'data' => Group::where('id', $user->id)->with(['subj', 'Client'])->first()], 200);
        }
        return response()->json(['message' => 'Error Ocurred', 'data' => null], 400);
    }

    public function put(Request $request)
    {
        $validator = $this->validater();
        if ($validator->fails()) {
            return response()->json(['message' => $validator->getMessageBag(), 'data' => null], 400);
        }
        $Group = Group::where('id', $request->id)->with(['subj', 'Client'])->first();
        if ($Group->update($request->all())) {
            return response()->json(['message' => 'Created', 'data' => $Group], 200);
        }
        return response()->json(['message' => 'Error Ocurred', 'data' => null], 400);
    }

    public  function remove(Request $request)
    {

        $Group = Group::destroy($request->id);
        return BaseController::successData($Group, "تمت العملية بنجاح");
    }


    //
}
