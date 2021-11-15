<?php

namespace App\Http\Controllers;

use App\model\StudentGroup;
use App\model\Subscription;
use App\model\SubSubscription;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class SubscriptionController extends Model
{

    public function validater()
    {
        return Validator::make(request()->all(), [
            'price' => 'required|numeric|gt:0|regex:/^-?[0-9]+(?:.[0-9]{1,2})?$/',
            'quotas' => 'required|numeric|gt:0',
            'date' => 'required|date',
            'student_id' => 'required|exists:students,id',
            'group_id' => 'required|exists:groups,id',
            'teacher_id' => 'required|exists:teachers,id',
        ]);
    }

    public function index()
    {
        $subscriptions = Subscription::with(['group', 'student', 'teacher'])
            ->orderBy('id', 'desc')
            ->paginate(10);
        return response()->json($subscriptions, 200);
    }

    public function getById(Request $request)
    {
        $subscriptions = Subscription::with(['group.subj', 'teacher'])
            ->where("student_id", $request->student_id);
        if ($request->has('from') && $request->has('to')) {
            $subscriptions = $subscriptions->whereBetween(
                'date',
                [$request->from, $request->to]
            );
        }
        if ($request->has('group_id')) {
            $subscriptions = $subscriptions->where("group_id", $request->group_id);
        }
        $subscriptions = $subscriptions->orderBy('id', 'desc')->paginate(10);
        if ($subscriptions) {
            return response()->json($subscriptions, 200);
        }
        return BaseController::errorData($subscriptions, "السجل غير موجود");
    }

    public function getGrouped(Request $request)
    {
        $subscriptions = Subscription::with(['student', 'group.subj', 'teacher'])->select(
            'subscriptions.student_id',
            'subscriptions.teacher_id',
            'subscriptions.group_id',
            DB::raw("SUM(subscriptions.price) as 'total'")
            // DB::raw("COUNT(attendances.student_id) as 'numberStudents'")
        );
        if ($request->has('from') && $request->has('to')) {
            $subscriptions =   $subscriptions->whereBetween(
                'date',
                [$request->from, $request->to]
            );
        }
        $subscriptions = $subscriptions->groupBy('student_id')->get();
        if ($subscriptions) {
            return response()->json($subscriptions, 200);
        }
        return BaseController::errorData($subscriptions, "السجل غير موجود");
    }

    public function store(Request $request)
    {

        $validator = $this->validater();
        if ($validator->fails()) {
            return response()->json(['message' => $validator->getMessageBag(), 'data' => null], 400);
        }
        $subscription = Subscription::create($validator->validate());
        if ($subscription) {
            $studentGroup = StudentGroup::where('student_id', $request->student_id)
                ->where('group_id', $request->group_id)->first();

            $newQuotas =  $studentGroup->quotas + $request->quotas;
            $updateQutas =  StudentGroup::where('student_id', $request->student_id)
                ->where('group_id', $request->group_id)->update(['quotas' => $newQuotas]);
            if ($updateQutas) {
                return response()->json(['message' => 'Created', 'data' => $subscription], 200);
            } else {
                return response()->json(['message' => 'Error Add To StdGrouo', 'data' => null], 400);
            }
        }
        return response()->json(['message' => 'Error Add To Subscription', 'data' => null], 400);
    }

    public function put(Request $request)
    {
        $validator = $this->validater();
        if ($validator->fails()) {
            return response()->json(['message' => $validator->getMessageBag(), 'data' => null], 400);
        }
        $SubSubscription = Subscription::find($request->id);
        $SubSubscription->update($request->all());
        if ($SubSubscription) {
            return response()->json(['message' => 'updated', 'data' =>  $SubSubscription], 200);
        }
        return response()->json(['message' => 'Error Ocurred', 'data' => null], 400);
    }

    public  function remove(Request $request)
    {

        $SubSubscription = Subscription::destroy($request->id);
        return BaseController::successData($SubSubscription, "تمت العملية بنجاح");
    }


    //
}
