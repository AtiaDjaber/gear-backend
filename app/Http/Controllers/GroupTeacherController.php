<?php

namespace App\Http\Controllers;

use App\model\GroupTeacher;
use App\model\Teacher;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class GroupTeacherController extends Model
{

    public function validater()
    {
        return Validator::make(request()->all(), [
            // 'mobile' => 'required|string|min:9|max:16',
            // 'firstname' => 'required|string|min:3|max:10',
            // 'lastname' => 'required|string|min:3|max:10',
        ]);
    }

    public function index()
    {
        $Teachers = Teacher::paginate(20);
        return BaseController::successData($Teachers, "تم جلب البيانات بنجاح");
    }


    public function getById(Request $request)
    {
        $user = Teacher::find($request->id);
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
        $user = GroupTeacher::create(request()->all());
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
        $Teacher = Teacher::findOrFail($request->id);
        $Teacher->update($request->all());
        if ($Teacher) {
            return response()->json(['message' => 'updated', 'data' =>  $Teacher], 200);
        }
        return response()->json(['message' => 'Error Ocurred', 'data' => null], 400);
    }

    public  function deleteTeacher(Request $request)
    {

        $Teacher = Teacher::destroy($request->id);
        return BaseController::successData($Teacher, "تمت العملية بنجاح");
    }


    //
}
