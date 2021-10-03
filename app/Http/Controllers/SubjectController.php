<?php

namespace App\Http\Controllers;

use App\model\Subject;
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
        $Subjects = Subject::paginate(20);
        return BaseController::successData($Subjects, "تم جلب البيانات بنجاح");
    }


    public function getById(Request $request)
    {
        $user = Subject::find($request->id);
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
        $user = Subject::create($validator->validate());
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
        $Subject = Subject::findOrFail($request->id);
        $Subject->update($request->all());
        if ($Subject) {
            return response()->json(['message' => 'updated', 'data' =>  $Subject], 200);
        }
        return response()->json(['message' => 'Error Ocurred', 'data' => null], 400);
    }

    public  function deleteSubject(Request $request)
    {
        $Subject = Subject::destroy($request->id);
        return BaseController::successData($Subject, "تمت العملية بنجاح");
    }


    //
}
