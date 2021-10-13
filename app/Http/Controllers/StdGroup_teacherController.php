<?php

namespace App\Http\Controllers;

use App\model\StdGroupTeacher;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class StdGroup_teacherController extends Model
{

    public function validater()
    {
        return Validator::make(request()->all(), [
            // 'name' => 'required|string',
        ]);
    }

    public function index(Request $request)
    {
        $StdGroupTeachers = StdGroupTeacher::alldata();
        if ($request->studentFirstname)
            $StdGroupTeachers =  $StdGroupTeachers->where('students.firstname', 'LIKE', '%' . request()->studentFirstname . '%');
        if ($request->studentLastname != null)
            $StdGroupTeachers =  $StdGroupTeachers->where('students.lastname', 'LIKE', '%' . request()->studentLastname . '%');

        $StdGroupTeachers = $StdGroupTeachers->paginate(15);
        return BaseController::successData($StdGroupTeachers, "تم جلب البيانات بنجاح");
    }

    public function getGroupSubjsByStudent(Request $request)
    {
        $StdGroupTeachers = StdGroupTeacher::getGroupSubjsByStudent($request->student_id);
//        if ($request->studentFirstname)
//            $StdGroupTeachers =  $StdGroupTeachers->where('students.firstname', 'LIKE', '%' . request()->studentFirstname . '%');
//        if ($request->studentLastname != null)
//            $StdGroupTeachers =  $StdGroupTeachers->where('students.lastname', 'LIKE', '%' . request()->studentLastname . '%');

        $StdGroupTeachers = $StdGroupTeachers->paginate(15);
        return response()->json($StdGroupTeachers, 200);
    }
    public function getById(Request $request)
    {
        $user = StdGroupTeacher::find($request->id);
        if ($user) {
            return BaseController::successData($user, "تم جلب البيانات بنجاح");
        }
        return BaseController::errorData($user, "السجل غير موجود");
    }

    public function store()
    {
        $user = StdGroupTeacher::create(request()->all());
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
        $StdGroupTeacher = StdGroupTeacher::findOrFail($request->id);
        $StdGroupTeacher->update($request->all());
        if ($StdGroupTeacher) {
            return response()->json(['message' => 'updated', 'data' =>  $StdGroupTeacher], 200);
        }
        return response()->json(['message' => 'Error Ocurred', 'data' => null], 400);
    }

    public  function remove(Request $request)
    {

        $StdGroupTeacher = StdGroupTeacher::destroy($request->id);
        return BaseController::successData($StdGroupTeacher, "تمت العملية بنجاح");
    }


    //
}
