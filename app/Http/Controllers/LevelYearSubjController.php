<?php

namespace App\Http\Controllers;

use App\model\Levelyear;
use App\model\SubjLevelYear;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class LevelYearSubjController extends Model
{

    public function validater()
    {
        return Validator::make(request()->all(), [
            'levelyear_id' => 'required|exists:levelyears,id',
            'subj_id' => 'required|exists:subjs,id',

        ]);
    }

    public function index()
    {
        $Levelyear = Levelyear::with('subjs')->paginate(20);
        return BaseController::successData($Levelyear, "تم جلب البيانات بنجاح");
    }


    public function getById(Request $request)
    {
        $user = Levelyear::find($request->id);
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
        $user = SubjLevelYear::create($validator->validate());
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
        $Levelyear = Levelyear::findOrFail($request->id);
        $Levelyear->update($request->all());
        if ($Levelyear) {
            return response()->json(['message' => 'updated', 'data' =>  $Levelyear], 200);
        }
        return response()->json(['message' => 'Error Ocurred', 'data' => null], 400);
    }

    public  function deleteLevelyear(Request $request)
    {

        $Levelyear = Levelyear::destroy($request->id);
        return BaseController::successData($Levelyear, "تمت العملية بنجاح");
    }


    //
}
