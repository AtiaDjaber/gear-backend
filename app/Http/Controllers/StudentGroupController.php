<?php

namespace App\Http\Controllers;

use App\model\Group;
use App\model\Student;
use App\model\StudentGroup;
use App\model\StudentGroupr;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class StudentGroupController extends Model
{

    public function validater()
    {
        return Validator::make(request()->all(), [
            // 'name' => 'required|string',
        ]);
    }

    public function index(Request $request)
    {
        $StudentGrouprs = StudentGroup::alldata();
        if ($request->studentFirstname)
            $StudentGrouprs =  $StudentGrouprs->where('students.firstname', 'LIKE', '%' . request()->studentFirstname . '%');
        if ($request->studentLastname != null)
            $StudentGrouprs =  $StudentGrouprs->where('students.lastname', 'LIKE', '%' . request()->studentLastname . '%');

        $StudentGrouprs = $StudentGrouprs->paginate(10);
        return BaseController::successData($StudentGrouprs, "تم جلب البيانات بنجاح");
    }

    public function getGroupSubjsByStudent(Request $request)
    {

        if ($request->has('barcode')) {
            $student  = Student::where("barcode", '=', $request->barcode)
                ->first();
            if ($student) {
                $student_id =  $student['id'];
            } else {
                return response()->json('no data', 204);
            }
        } else {
            $student_id = $request->student_id;
        }

        $StudentGrouprs = StudentGroup::where('student_id', $student_id)
            ->with("group.teacher", "group.subj")->get();

        return response()->json($StudentGrouprs, 200);
    }



    public function getAllGroupSubjs(Request $request)
    {

        $StudentGrouprs = StudentGroup::with(["group.teacher", "group.subj", "stduent"])
            ->paginate(10);

        return response()->json($StudentGrouprs, 200);
    }

    public function getById(Request $request)
    {
        $user = StudentGroup::find($request->id);
        if ($user) {
            return BaseController::successData($user, "تم جلب البيانات بنجاح");
        }
        return BaseController::errorData($user, "السجل غير موجود");
    }

    public function store()
    {
        $user = StudentGroup::create(request()->all());
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
        $StudentGroupr = StudentGroup::findOrFail($request->id);
        $StudentGroupr->update($request->all());
        if ($StudentGroupr) {
            return response()->json(['message' => 'updated', 'data' =>  $StudentGroupr], 200);
        }
        return response()->json(['message' => 'Error Ocurred', 'data' => null], 400);
    }

    public  function remove(Request $request)
    {

        $StudentGroupr = StudentGroup::destroy($request->id);
        return BaseController::successData($StudentGroupr, "تمت العملية بنجاح");
    }


    //
}
