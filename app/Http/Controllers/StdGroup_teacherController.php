<?php

namespace App\Http\Controllers;

use App\model\StdSubGroup;
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

    public function index()
    {
        $StdSubGroups = StdSubGroup::leftJoin('groups', 'std_sub_groups.group_id', 'groups.id')
            ->leftJoin('levelyear_subj', 'std_sub_groups.levelyear_subj_id', 'levelyear_subj.id')
            ->leftJoin('students', 'std_sub_groups.student_id', 'students.id')
            ->select(
                'std_sub_groups.created_at',
                'levelyear_subj.id as levelyear_subjId',
                'groups.id as groupId',
                'groups.name as groupName',
                'students.id as studentId',
                'students.firstname',
                'students.lastname',
                'students.barcode',
                'students.mobile',
                'students.firstname'
            )->paginate(20);
        return BaseController::successData($StdSubGroups, "تم جلب البيانات بنجاح");
    }


    public function getById(Request $request)
    {
        $user = StdSubGroup::find($request->id);
        if ($user) {
            return BaseController::successData($user, "تم جلب البيانات بنجاح");
        }
        return BaseController::errorData($user, "السجل غير موجود");
    }

    public function store()
    {
        $user = StdSubGroup::create(request()->all());
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
        $StdSubGroup = StdSubGroup::findOrFail($request->id);
        $StdSubGroup->update($request->all());
        if ($StdSubGroup) {
            return response()->json(['message' => 'updated', 'data' =>  $StdSubGroup], 200);
        }
        return response()->json(['message' => 'Error Ocurred', 'data' => null], 400);
    }

    public  function remove(Request $request)
    {

        $StdSubGroup = StdSubGroup::destroy($request->id);
        return BaseController::successData($StdSubGroup, "تمت العملية بنجاح");
    }


    //
}
