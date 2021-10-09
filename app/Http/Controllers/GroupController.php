<?php

namespace App\Http\Controllers;

use App\model\Group;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class GroupController extends Model
{

    public function validater()
    {
        return Validator::make(request()->all(), [
            'name' => 'required|string',
        ]);
    }

    public function index()
    {
        $Groups = Group::with('subjs')->paginate(20);
        return BaseController::successData($Groups, "تم جلب البيانات بنجاح");
    }

    // public function index()
    // {
    //     $Groups = Group::leftJoin('levelyear_subj', 'groups.levelyear_subj_id', 'levelyear_subj.id')
    //         ->leftJoin('subjs', 'levelyear_subj.subj_id', 'subjs.id')
    //         ->leftJoin('levelyears', 'levelyear_subj.levelyear_id', 'levelyears.id')
    //         ->select(
    //             'groups.*',
    //             'levelyear_subj.*',
    //             'subjs.name as SubName',
    //             'levelyears.name as levelYearName '
    //         )->paginate(20);
    //     // with('subjs')->paginate(20);
    //     return BaseController::successData($Groups, "تم جلب البيانات بنجاح");
    // }



    public function getById(Request $request)
    {
        $user = Group::find(1)->with('levelyearsSubjs')->get();
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
        $user = Group::create($validator->validate());
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
        $Group = Group::findOrFail($request->id);
        $Group->update($request->all());
        if ($Group) {
            return response()->json(['message' => 'updated', 'data' =>  $Group], 200);
        }
        return response()->json(['message' => 'Error Ocurred', 'data' => null], 400);
    }

    public  function remove(Request $request)
    {

        $Group = Group::destroy($request->id);
        return BaseController::successData($Group, "تمت العملية بنجاح");
    }


    //
}
