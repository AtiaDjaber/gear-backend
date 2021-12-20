<?php

namespace App\Http\Controllers;

use App\model\Group;
use App\model\Student;
use App\model\StudentGroup;
use App\model\StudentGroupr;
use Carbon\Carbon;
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


    public function getGroupSubjsByStudent(Request $request)
    {
        if ($request->has('barcode')) {
            $student  = Student::where("barcode", '=', $request->barcode)->first();
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



    public function getGroupSubjsByStudentforAttendance(Request $request)
    {
        if ($request->has('barcode')) {
            $student  = Student::where("barcode", '=', $request->barcode)->first();
            if ($student) {
                $student_id =  $student['id'];
            } else {
                return response()->json('no data', 204);
            }
        } else {
            $student_id = $request->student_id;
        }

        $StudentGrouprs = StudentGroup::with("group.teacher", "group.subj")
            ->whereIn('group_id', $request->idsGroups)
            ->where('student_id', $student_id)->get();

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

    public function getNotifications(Request $request)
    {
        $StudentGrouprs = StudentGroup::orderBy("updated_at", "desc")
            ->with(["group.teacher", "group.subj", "stduent"])
            ->where("quotas", "<=", 0)
            ->where(function ($q) {
                $q->where("updated_at", ">=", Carbon::now()->subDays(30))
                    ->orWhere("created_at", ">=", Carbon::now()->subDays(30));
            })->get();

        return response()->json($StudentGrouprs, 200);
    }

    public function getStudentsByGroups(Request $request)
    {
        $StudentGrouprs = StudentGroup::with(["stduent"])
            ->whereIn('group_id', $request->idsGroups)->get();
        return response()->json($StudentGrouprs, 200);
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
