<?php

namespace App\Http\Controllers;

use App\model\Group;
use App\model\Session;
use Carbon\Carbon;
use Carbon\CarbonInterval;
use DatePeriod;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use DateTime;

class SessionController extends Model
{

    public function validater()
    {
        return Validator::make(request()->all(), [
            'group_id' => 'required|exists:groups,id',
            'start' => 'required|date',
            'end' => 'required|date',
            'color' => 'required|string',
            'numberMonth' => 'required',
        ]);
    }
    public function getMondays()
    {
        return new \DatePeriod(
            Carbon::parse("first monday of this month"),
            CarbonInterval::week(),
            Carbon::parse("first monday of next month")
        );
    }

    public function index()
    {
        $groups = Session::with(['group.teacher', 'group.subj'])->get();
        return response()->json($groups, 200);
    }

    public function getById(Request $request)
    {
        $user = Group::find($request->id)->with(['subj', 'teacher'])
            ->first();
        if ($user) {
            return BaseController::successData($user, "تم جلب البيانات بنجاح");
        }
        return BaseController::errorData($user, "السجل غير موجود");
    }

    public function store(Request $request)
    {

        $validator = $this->validater();
        if ($validator->fails()) {
            return response()->json(['message' => $validator->getMessageBag(), 'data' => null], 400);
        }
        // $selectedDay = 'first ' . $request->day . ' of this month';

        // $endDate = $date;
        for ($i = 0; $i < $request->numberMonth; $i++) {
            $endDate =  Carbon::parse($request->start, 'Africa/Tunis')->addMonth();
        }
        $mondays = new DatePeriod(
            Carbon::parse($request->start, 'Africa/Tunis'),
            CarbonInterval::week(),
            Carbon::parse($endDate, 'Africa/Tunis')
        );
        $allDays = [];
        $allRecoredsAdded = [];

        foreach ($mondays as $day) {
            $allDays[] = $day;
        }

        foreach ($allDays as $p) {
            $requestData = $request->all();
            $to = Carbon::createFromFormat('Y-m-d H:i:s', $requestData['start'], 'Africa/Tunis');
            $from = Carbon::createFromFormat('Y-m-d H:i:s', $requestData['end'], 'Africa/Tunis');

            $diff = $to->diff($from);
            $requestData['start'] = $p;
            $end = clone ($p);
            $end = Carbon::parse($end->addHours($diff->h)->addMinutes($diff->i), 'Africa/Tunis');
            $requestData['end']  = $end;

            $allRecoredsAdded[] = Session::create($requestData);
        }

        if ($allRecoredsAdded) {
            return response()->json([
                'message' => 'Created',
                'data' => $allRecoredsAdded
            ], 200);
        }
        return response()->json(['message' => 'Error Ocurred', 'data' => null], 400);
    }

    public function put(Request $request)
    {
        $validator = $this->validater();
        if ($validator->fails()) {
            return response()->json(['message' => $validator->getMessageBag(), 'data' => null], 400);
        }
        $Group = Group::find($request->id);
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
