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
            'teacher_id' => 'required|exists:teachers,id',
        ]);
    }

    public function index()
    {
        $Groups = Group::orderBy('id', 'desc')->with(['subj', 'teacher'])
            ->paginate(10);
        return response()->json($Groups, 200);
    }

    public function getById(Request $request)
    {
        $user = Group::find($request->id)->with(['subj', 'teacher'])
            ->first();
        if ($user) {
            return BaseController::successData($user, "تم جلب البيانات بنجاح");
        }
        return BaseController::errorData($user, "السجل غير موجود");
    }

    public function getGroupSubjById(Request $request)
    {
        $groups = Group::where('teacher_id', $request->teacher_id)->with(['subj'])
            ->get();
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
        $Group = Group::find($request->id);
        $Group->update($request->all());
        if ($Group) {
            return response()->json(['message' => 'updated', 'data' =>  $Group], 200);
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
