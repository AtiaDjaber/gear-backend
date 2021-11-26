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

    public function index(Request $request)
    {

        $groups = Session::with(['group.teacher', 'group.subj'])
            ->whereBetween(
                "start",
                [$request->start . " 00:00:00", $request->end . " 23:59:00"]
            )

            ->get();
        return response()->json($groups, 200);
    }

    public function getById(Request $request)
    {
        $session = Session::find($request->id)->with(['group.teacher', 'group.subj'])
            ->first();
        if ($session) {
            return BaseController::successData($session, "تم جلب البيانات بنجاح");
        }
        return BaseController::errorData("error", "السجل غير موجود");
    }

    public function store(Request $request)
    {

        $validator = $this->validater();
        if ($validator->fails()) {
            return response()->json(['message' => $validator->getMessageBag(), 'data' => null], 400);
        }
        // $selectedDay = 'first ' . $request->day . ' of this month';

        // $endDate = $date;
        $allRecoredsAdded = [];

        if ($request->numberMonth == 0) {
            $sessionAddedd = Session::create($validator->validate());

            if ($sessionAddedd) {
                $session = Session::where('id', $sessionAddedd->id)->with(['group.teacher', 'group.subj'])->get();
                return response()->json(['message' => 'Created', 'data' => $session], 200);
            }
            return response()->json(['message' => 'Error Ocurred', 'data' => null], 400);
        }
        $endDate = $request->start;
        for ($i = 0; $i < $request->numberMonth; $i++) {
            $endDate =   Carbon::parse($endDate, 'Africa/Tunis')->addMonth();
        }
        $mondays = new DatePeriod(
            Carbon::parse($request->start, 'Africa/Tunis'),
            CarbonInterval::week(),
            Carbon::parse($endDate, 'Africa/Tunis')
        );
        $allDays = [];

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

            $sessionAddedd = Session::create($requestData);
            $session = Session::where('id', $sessionAddedd->id)->with(['group.teacher', 'group.subj'])->first();
            $allRecoredsAdded[] = $session;
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
        $Group = Session::find($request->id);
        $Group->update($request->all());
        if ($Group) {
            return response()->json(['message' => 'updated', 'data' =>  $Group], 200);
        }
        return response()->json(['message' => 'Error Ocurred', 'data' => null], 400);
    }

    public  function remove(Request $request)
    {

        $Group = Session::destroy($request->id);
        return BaseController::successData($Group, "تمت العملية بنجاح");
    }


    public  function removeSubj(Request $request)
    {
        $Group = Session::where('start', "LIKE", "%" . $request->start . "%")
            ->where('group_id', $request->id)
            ->delete();
        return BaseController::successData($Group, "تمت العملية بنجاح");
    }


    //
}
