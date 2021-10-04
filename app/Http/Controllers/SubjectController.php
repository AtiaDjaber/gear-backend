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
            'mobile' => 'required|string|min:9|max:16',
            'firstname' => 'required|string|min:3|max:10',
            'lastname' => 'required|string|min:3|max:10',
            'parent' => 'required|string|min:3|max:20',
            'parent' => 'required|string|min:3|max:20',
            'parent' => 'required|string|min:3|max:20',
        ]);
    }

    public function index()
    {
        $Subjs = Subj::with('levelyears')->paginate(20);
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
        if ($validator->fails()) {
            return response()->json(['message' => $validator->getMessageBag(), 'data' => null], 400);
        }
        $user = Subj::create($validator->validate());
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
