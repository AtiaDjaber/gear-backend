<?php

namespace App\Http\Controllers;

use App\model\Student;
use App\model\Teacher;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class StudentController extends Model
{

    public function validater()
    {
        return Validator::make(request()->all(), [
            'mobile' => 'required|string|min:9|max:16',
            'firstname' => 'required|string|min:2|max:20',
            'lastname' => 'required|string|min:2|max:20',
            'parent' => 'required|string|min:3|max:20',
        ]);
    }

    public function index()
    {
        $students = student::paginate(15);
        return BaseController::successData($students, "تم جلب البيانات بنجاح");
    }


    public function getById(Request $request)
    {
        $user = Student::find($request->id);
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
        $user = Student::create($validator->validate());
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
        $student = Student::findOrFail($request->id);
        $student->update($request->all());
        if ($student) {
            return response()->json(['message' => 'updated', 'data' =>  $student], 200);
        }
        return response()->json(['message' => 'Error Ocurred', 'data' => null], 400);
    }

    public  function deleteStudent(Request $request)
    {

        $student = Student::destroy($request->id);
        return BaseController::successData($student, "تمت العملية بنجاح");
    }


    //
}
