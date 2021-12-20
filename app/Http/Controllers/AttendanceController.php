<?php

namespace App\Http\Controllers;

use App\model\Attendance;
use App\model\Student;
use App\model\StudentGroup;
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
            'group_id' => 'required|exists:groups,id',
            'student_id' => 'required|exists:students,id',
            'teacher_id' => 'required|exists:teachers,id',
            'date' => 'required|date',
            'isPresent' => 'required|boolean',
            'isJsutified' => 'nullable|boolean'
        ]);
    }
    public function index(Request $request)
    {
        $Attendances = Attendance::orderBy('id', 'desc');
        if ($request->name) {
            $Attendances = $Attendances->where(function ($q) use ($request) {
                $q->orWhere('studentName', 'LIKE', '%' . $request->name . '%')
                    ->orWhere('studentBarcode', 'LIKE', '%' . $request->name . '%')
                    ->orWhere('teacherName', 'LIKE', '%' . $request->name . '%');
            });
        }
        if ($request->group_id != null)
            $Attendances = $Attendances->where('group_id', '=', $request->group_id);
        if ($request->teacher_id != null)
            $Attendances = $Attendances->where('teacher_id', '=', $request->teacher_id);
        if ($request->student_id != null)
            $Attendances = $Attendances->where('student_id', '=', $request->student_id);
        if ($request->from != null)
            $Attendances = $Attendances->where('date', '>=', $request->from);
        if ($request->to != null)
            $Attendances = $Attendances->where('date', '<=', $request->to);
        if ($request->isPresent != null)
            $Attendances = $Attendances->where('isPresent', '=', $request->isPresent);

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

    public function getTeachersBenifitsChart(Request $request)
    {
        $dataset = [];
        $user = Attendance::select(
            'attendances.teacherName',
            'attendances.teacher_id',
            DB::raw("SUM(attendances.price) as total")
        )->whereBetween(
            'date',
            [$request->from, $request->to]
        )->groupBy('teacher_id')->get();

        if ($user) {
            $dataset["data"] = $user->pluck("total");
            $dataset["teachers_ids"] = $user->pluck("teacher_id");
            $dataset["labels"] = $user->pluck("teacherName");
            return response()->json($dataset, 200);
        }
        return BaseController::errorData($user, "السجل غير موجود");
    }


    public function getTeachersBenifits(Request $request)
    {
        $attendances = Attendance::select(
            'attendances.teacherName',
            'attendances.teacher_id',
            DB::raw("SUM(attendances.price) as total")
        )->whereBetween(
            'date',
            [$request->from, $request->to]
        )->groupBy('teacher_id')->orderBy("total", "desc")->get();

        if ($attendances) {
            return response()->json($attendances, 200);
        }
        return BaseController::errorData(null, "السجل غير موجود");
    }


    public function getTeacherBenifitById(Request $request)
    {
        $user = Attendance::select(
            'attendances.group_id',
            'attendances.teacher_id',
            'attendances.subjName',
            'attendances.groupName',
            DB::raw("SUM(attendances.price) as 'total'")
        )
            ->where('attendances.teacher_id', $request->teacher_id)
            ->whereBetween(
                'date',
                [$request->from, $request->to]
            )->groupBy(['group_id'])->get();

        if ($user) {
            return response()->json($user, 200);
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

    public function store(Request $request)
    {
        $validator = $this->validater();
        if ($validator->fails()) {
            return response()->json(['message' => $validator->getMessageBag(), 'data' => null], 400);
        }
        DB::beginTransaction();
        try {
            $student =  Student::find($request->student_id);
            $attendence =  Request()->all();
            $attendence["studentName"] = $student->firstname . " " . $student->lastname;
            $user = Attendance::create($attendence);
            if ($user) {

                $studentGroup = StudentGroup::where('student_id', $request->student_id)
                    ->where('group_id', $request->group_id)->first();

                if ($request->has("isPresent")) {
                    if ($request->isPresent == 1) {

                        $newQuotas =  $studentGroup->quotas - 1;
                        StudentGroup::where('student_id', $request->student_id)
                            ->where('group_id', $request->group_id)->update(['quotas' => $newQuotas]);
                    }
                }
                DB::commit();

                return response()->json(['message' => 'Created', 'data' => $user], 200);
            }
            return response()->json(['message' => 'Error Ocurred', 'data' => null], 400);
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json(['message' => 'Error ', 'data' => $e], 500);
        }
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

    public function remove(Request $request)
    {
        DB::beginTransaction();

        try {
            $attendance =  Attendance::findOrFail($request->id);
            $attendance->delete();

            if ($attendance->isPresent == 1) {

                $studentGroup = StudentGroup::where('student_id', $attendance->student_id)
                    ->where('group_id', $attendance->group_id)->first();

                $newQuotas =  $studentGroup->quotas + 1;
                StudentGroup::where('student_id', $attendance->student_id)
                    ->where('group_id', $attendance->group_id)->update(['quotas' => $newQuotas]);
            }

            DB::commit();
            return BaseController::successData($attendance, "تمت العملية بنجاح");
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json(['message' => 'Error ', 'data' => $e], 500);
        }
    }

    //
}
