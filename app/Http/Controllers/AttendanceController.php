<?php

namespace App\Http\Controllers;

use App\model\Attendance;
use App\model\Student;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class AttendanceController extends Model
{

    public function validater()
    {
        return Validator::make(request()->all(), [
            'std_group_id' => 'required|exists:std_group,id',
            'date' => 'required|date',
            'isPresent' => 'required|boolean',
            'isJsutified' => 'nullable|boolean'
        ]);
    }

    public function index(Request $request)
    {
        $Attendances = Attendance::orderBy('id', 'desc');
        if ($request->studentName)
            $Attendances = $Attendances->orWhere('studentName', 'LIKE', '%' . request()->studentName . '%');
        if ($request->studentBarcode)
            $Attendances = $Attendances->orWhere('studentBarcode', 'LIKE', '%' . request()->studentBarcode . '%');
        if ($request->teacherName != null)
            $Attendances = $Attendances->orWhere('teacherName', 'LIKE', '%' . request()->teacherName . '%');
        if ($request->subjName != null)
            $Attendances = $Attendances->orWhere('subjName', 'LIKE', '%' . request()->subjName . '%');
        if ($request->from != null)
            $Attendances = $Attendances->where('date', '>=', $request->from);
        if ($request->to != null)
            $Attendances = $Attendances->where('date', '<=', $request->to);

        $Attendances = $Attendances->paginate(10);
        return response()->json($Attendances, 200);
    }




    public function getAnalytics(Request $request)
    {
        $Attendances = Attendance::orderBy('id', 'desc');
        if ($request->from != null)
            $Attendances = $Attendances->where('date', '>=', $request->from);
        if ($request->to != null)
            $Attendances = $Attendances->where('date', '<=', $request->to);

        $Attendances = $Attendances->paginate(10);
        return response()->json($Attendances, 200);
    }




    public function getGroupSubjsByStudent(Request $request)
    {
        $Attendances = Attendance::getGroupSubjsByStudent($request->student_id);
        //        if ($request->studentFirstname)
        //            $Attendances =  $Attendances->where('students.firstname', 'LIKE', '%' . request()->studentFirstname . '%');
        //        if ($request->studentLastname != null)
        //            $Attendances =  $Attendances->where('students.lastname', 'LIKE', '%' . request()->studentLastname . '%');

        $Attendances = $Attendances->paginate(10);
        return response()->json($Attendances, 200);
    }


    public function getById(Request $request)
    {
        $user = Attendance::find($request->id);
        if ($user) {
            return BaseController::successData($user, "تم جلب البيانات بنجاح");
        }
        return BaseController::errorData($user, "السجل غير موجود");
    }

    public function getTeachersBenifits(Request $request)
    {
        $dataset = [];
        $user = Attendance::
            // join('groups', 'attendances.group_id', 'groups.id')
            // join('subjs', 'attendances.subj_id', 'subjs.id')
            // ->
            select(
                // 'groups.id',
                // 'groups.price',
                // 'groups.name',
                // 'attendances.*',
                'attendances.teacherName',
                // 'subjs.name as subjName',
                // 'subjs.grade',
                // 'subjs.level',
                // 'attendances.groupName',
                DB::raw("SUM(attendances.price) as total")
                // DB::raw("COUNT(attendances.student_id) as 'numberStudents'")
            )
            // ->where('attendances.teacher_id', $request->teacher_id)
            ->whereBetween(
                'date',
                [$request->from, $request->to]
            )->groupBy('teacher_id')->get();

        if ($user) {
            $dataset["data"] = $user->pluck("total");
            $dataset["labels"] = $user->pluck("teacherName");
            return response()->json($dataset, 200);
        }
        return BaseController::errorData($user, "السجل غير موجود");
    }


    public function getTeacherBenifitById(Request $request)
    {
        $user = Attendance::with('group.subj')
            // ->join('subjs', 'attendances.subj_id', 'subjs.id')
            ->select(
                'attendances.group_id',
                'attendances.teacher_id',
                DB::raw("SUM(attendances.price) as 'total'")
            )
            ->where('attendances.teacher_id', $request->teacher_id)
            ->whereBetween(
                'date',
                [$request->from, $request->to]
            )->groupBy(['group_id'])->get();

        if ($user) {
            return BaseController::successData($user, "تم جلب البيانات بنجاح");
        }
        return BaseController::errorData($user, "السجل غير موجود");
    }

    //  $user = Attendance::join('groups', 'attendances.group_id', 'groups.id')
    //         ->join('subjs', 'groups.subj_id', 'subjs.id')
    //         ->select(
    //             'groups.id',
    //             'groups.price',
    //             'groups.name',
    //             'subjs.name as subjName',
    //             'subjs.grade',
    //             'subjs.level',
    //             DB::raw("SUM(groups.price) as 'total'"),
    //             DB::raw("COUNT(attendances.id) as 'number'")
    //         )
    //         ->where('attendances.teacher_id', $request->teacher_id)
    //         ->whereBetween(
    //             'date',
    //             [$request->from, $request->to]
    //         )->groupBy([
    //             'groups.id', 'groups.name', 'subjs.name', 'subjs.grade',
    //             'subjs.level', 'groups.price'
    //         ])->get();

    public function store()
    {
        // $validator = $this->validater();
        // if ($validator->fails()) {
        //     return response()->json(['message' => $validator->getMessageBag(), 'data' => null], 400);
        // }
        $user = Attendance::create(Request()->all());
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
        $Attendance = Attendance::findOrFail($request->id);
        $Attendance->update($request->all());
        if ($Attendance) {
            return response()->json(['message' => 'updated', 'data' =>  $Attendance], 200);
        }
        return response()->json(['message' => 'Error Ocurred', 'data' => null], 400);
    }

    public  function remove(Request $request)
    {
        $Attendance = Attendance::destroy($request->id);
        return BaseController::successData($Attendance, "تمت العملية بنجاح");
    }

    //
}
