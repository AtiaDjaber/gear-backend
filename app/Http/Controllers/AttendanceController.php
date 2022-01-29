<?php

namespace App\Http\Controllers;

use App\model\Attendance;
use App\model\Product;
use App\model\ProductGroup;
use Carbon\Carbon;
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
            'Product_id' => 'required|exists:Products,id',
            'Client_id' => 'required|exists:Clients,id',
            'date' => 'required|date',
            'ClientBenefit' => 'required|numeric',
            'schoolBenefit' => 'required|numeric',
            'isPresent' => 'required|boolean',
            'isJsutified' => 'nullable|boolean'
        ]);
    }
    public function index(Request $request)
    {
        $Attendances = Attendance::orderBy('id', 'desc');
        if ($request->name) {
            $Attendances = $Attendances->where(function ($q) use ($request) {
                $q->orWhere('ProductName', 'LIKE', '%' . $request->name . '%')
                    ->orWhere('ProductBarcode', 'LIKE', '%' . $request->name . '%')
                    ->orWhere('ClientName', 'LIKE', '%' . $request->name . '%');
            });
        }
        if ($request->group_id != null)
            $Attendances = $Attendances->where('group_id', '=', $request->group_id);
        if ($request->Client_id != null)
            $Attendances = $Attendances->where('Client_id', '=', $request->Client_id);
        if ($request->Product_id != null)
            $Attendances = $Attendances->where('Product_id', '=', $request->Product_id);
        if ($request->from != null)
            $Attendances = $Attendances->where('date', '>=', $request->from);
        if ($request->to != null)
            $Attendances = $Attendances->where('date', '<=', $request->to);
        if ($request->isPresent != null)
            $Attendances = $Attendances->where('isPresent', '=', $request->isPresent);

        $Attendances = $Attendances->paginate(10);
        return response()->json($Attendances, 200);
    }


    public function getGroupSubjsByProduct(Request $request)
    {
        $Attendances = Attendance::getGroupSubjsByProduct($request->Product_id);
        //        if ($request->ProductFirstname)
        //            $Attendances =  $Attendances->where('Products.firstname', 'LIKE', '%' . request()->ProductFirstname . '%');
        //        if ($request->ProductLastname != null)
        //            $Attendances =  $Attendances->where('Products.lastname', 'LIKE', '%' . request()->ProductLastname . '%');

        $Attendances = $Attendances->paginate(10);
        return response()->json($Attendances, 200);
    }

    public function getClientsBenifitsChart(Request $request)
    {
        $dataset = [];
        $user = Attendance::select(
            'attendances.ClientName',
            'attendances.Client_id',
            DB::raw("SUM(attendances.ClientBenefit) as total")
        )->whereBetween(
            'created_at',
            [$request->from, $request->to]
        )->where("isPresent", true)
            ->groupBy('Client_id')->get();

        if ($user) {
            $dataset["data"] = $user->pluck("total");
            $dataset["Clients_ids"] = $user->pluck("Client_id");
            $dataset["labels"] = $user->pluck("ClientName");
            return response()->json($dataset, 200);
        }
        return BaseController::errorData($user, "السجل غير موجود");
    }


    public function getClientsBenifits(Request $request)
    {
        $attendances = Attendance::select(
            'attendances.ClientName',
            'attendances.Client_id',
            DB::raw("SUM(attendances.ClientBenefit) as total")
        )->whereBetween(
            'created_at',
            [$request->from, $request->to]
        )->where("isPresent", true)->groupBy('Client_id')
            ->orderBy("total", "desc")->get();

        if ($attendances) {
            return response()->json($attendances, 200);
        }
        return BaseController::errorData(null, "السجل غير موجود");
    }


    public function getSchoolBenifitChart(Request $request)
    {
        $dataset = [];
        $user = Attendance::select(
            "id",
            DB::raw("(sum(attendances.schoolBenefit)) as total"),
            DB::raw("(DATE_FORMAT(created_at, '%Y-%m')) as month_year")
        )
            ->orderBy('created_at')
            ->groupBy(DB::raw("DATE_FORMAT(created_at, '%Y-%m')"))
            // ->where("created_at", ">=", Carbon::now()->subYear())
            ->where("isPresent", true)
            ->get();

        if ($user) {
            $dataset["data"] = $user->pluck("total");
            $dataset["labels"] = $user->pluck("month_year");
            return response()->json($dataset, 200);
        }
        return BaseController::errorData($user, "السجل غير موجود");
    }

    public function getSchoolBenifitPeriod(Request $request)
    {
        $data = Attendance::select(
            DB::raw("(sum(attendances.schoolBenefit)) as total")
        )->whereBetween(
            'created_at',
            [$request->from, $request->to]
        )->orderBy('created_at')->where("isPresent", true)->first();

        if ($data) {
            return response()->json($data->total, 200);
        }
        return BaseController::errorData(null, "السجل غير موجود");
    }


    public function getClientBenifitById(Request $request)
    {
        $user = Attendance::select(
            'attendances.group_id',
            'attendances.Client_id',
            'attendances.subjName',
            'attendances.groupName',
            DB::raw("SUM(attendances.Clientbenefit) as 'total'")
        )
            ->where('attendances.Client_id', $request->Client_id)
            ->whereBetween(
                'created_at',
                [$request->from, $request->to]
            )->where("isPresent", true)
            ->groupBy(['group_id'])->get();

        if ($user) {
            return response()->json($user, 200);
        }
        return BaseController::errorData($user, "السجل غير موجود");
    }



    public function store(Request $request)
    {
        $validator = $this->validater();
        if ($validator->fails()) {
            return response()->json(['message' => $validator->getMessageBag(), 'data' => null], 400);
        }
        DB::beginTransaction();
        try {
            $Product =  Product::find($request->Product_id);
            $attendence =  Request()->all();
            $attendence["ProductName"] = $Product->firstname . " " . $Product->lastname;
            $user = Attendance::create($attendence);
            if ($user) {

                $ProductGroup = ProductGroup::where('Product_id', $request->Product_id)
                    ->where('group_id', $request->group_id)->first();

                if ($request->has("isPresent")) {
                    if ($request->isPresent == 1) {

                        $newQuotas =  $ProductGroup->quotas - 1;
                        ProductGroup::where('Product_id', $request->Product_id)
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

                $ProductGroup = ProductGroup::where('Product_id', $attendance->Product_id)
                    ->where('group_id', $attendance->group_id)->first();

                $newQuotas =  $ProductGroup->quotas + 1;
                ProductGroup::where('Product_id', $attendance->Product_id)
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
