<?php

namespace App\Http\Controllers;

use App\model\Attendance;
use App\model\Student;
use Carbon\Carbon;
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
            'parentMobile' => 'required|string|min:3|max:20',
            'birthday' => 'required|date|min:3|max:20',
            'barcode' => 'required|string|min:3|max:20',
        ]);
    }

    public function index(Request $request)
    {
        $students = Student::orderBy('id', 'desc');
        if ($request->has("name")) {
            $students = $students->where("firstname", 'LIKE', '%' . $request->name . '%')
                ->orWhere("lastname", 'LIKE', '%' . $request->name . '%');
        }
        $students = $students->paginate(10);
        return BaseController::successData($students, "تم جلب البيانات بنجاح");
    }

    public function getSessionsByStudentId(Request $request)
    {
        $sessions = Student::with(['sessions' => function ($q) use ($request) {
            $q->with(['group.teacher', 'group.subj'])->whereBetween(
                "start",
                [$request->start . " 00:00:00", $request->end . " 23:59:00"]
            );
        }])->where("id", $request->id)->get();

        if ($sessions) {
            return response()->json($sessions, 200);
        }
        return BaseController::errorData("error", "السجل غير موجود");
    }

    // public function getAbsences(Request $request)
    // {
    //     $Attendances = Student::leftjoin('attendances', 'attendances.student_id', 'students.id');
    //     $Attendances = $Attendances->select("students.*");
    //     // ->whereIn('group_id', $request->idsGroups)
    //     $Attendances = $Attendances->where('attendances.student_id', null)
    //         // ->where(
    //         //     'attendances.date',
    //         //     $request->date
    //         // )
    //         ->whereHas(
    //             'groups',
    //             function ($q) use ($request) {
    //                 $q->whereIn('groups.id',  $request->idsGroups);
    //             }
    //         )
    //         // ->whereHas(
    //         //     'attendances',
    //         //     function ($q) use ($request) {
    //         //         $q->where('attendances.created_at', $request->date);
    //         //     }
    //         // )
    //         ->paginate(100);
    //     return BaseController::successData($Attendances, "تم جلب البيانات بنجاح");
    // }
    public function getAbsences(Request $request)
    {
        $student =  Student::whereDoesntHave(
            'attendances',
            function ($q) use ($request) {
                $q->where('group_id', $request->get('group_id'))
                    ->where('date', $request->get('date'));
            }
        )->whereHas("groups", function ($q) use ($request) {
            $q->where('groups.id', $request->group_id);
        })->get();

        return BaseController::successData($student, "تم جلب البيانات بنجاح");
    }

    public function getGroup()
    {
        $groups = Student::orderBy('id', 'desc')->with(['groups.subj', 'groups.teacher'])->paginate(10);
        return BaseController::successData($groups, "تم جلب البيانات بنجاح");
    }



    public function generate()
    {
        return response()->json($this->generateBarcodeNumber(), 200);
    }
    function generateBarcodeNumber()
    {
        $number = mt_rand(1000000000000, 9999999999999);
        if ($this->barcodeNumberExists($number)) {
            return $this->generateBarcodeNumber();
        }

        return $number;
    }

    function barcodeNumberExists($number)
    {
        return Student::where('barcode', $number)->exists();
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
