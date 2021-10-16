<?php

namespace App\Http\Controllers;

use App\model\Subj;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class SubjectController extends Model
{

    public function validater()
    {
        return Validator::make(request()->all(), [
            'name' => 'required|string|min:3|max:20',
            'level' => 'required|string|min:3|max:20',
            'grade' => 'required|string|min:3|max:20',
        ]);
    }

    public function index()
    {
        $Subjs = Subj::orderBy('id', 'desc')->with('groups')->paginate(15);
        return BaseController::successData($Subjs, "تم جلب البيانات بنجاح");
    }


    public function getById(Request $request)
    {
        $user = Subj::find($request->id);
        if ($user) {
            return BaseController::successData($user, "تم جلب البيانات بنجاح");
        }
        return BaseController::errorData($user, "السجل غير موجود");
    }

    public function store()
    {
        $validator = $this->validater();
        if ($validator->fails())
            return response()->json(['message' => $validator->getMessageBag(), 'data' => null], 400);
        //
        $user = Subj::create($validator->validate());
        if ($user)
            return response()->json(['message' => 'Created', 'data' => $user], 200);

        return response()->json(['message' => 'Error Ocurred', 'data' => null], 400);
    }

    public function put(Request $request)
    {
        $validator = $this->validater();
        if ($validator->fails()) {
            return response()->json(['message' => $validator->getMessageBag(), 'data' => null], 400);
        }
        $Subj = Subj::findOrFail($request->id);
        $Subj->update($request->all());
        if ($Subj) {
            return response()->json(['message' => 'updated', 'data' =>  $Subj], 200);
        }
        return response()->json(['message' => 'Error Ocurred', 'data' => null], 400);
    }

    public  function deleteSubj(Request $request)
    {
        $Subj = Subj::destroy($request->id);
        return BaseController::successData($Subj, "تمت العملية بنجاح");
    }


    //
}
