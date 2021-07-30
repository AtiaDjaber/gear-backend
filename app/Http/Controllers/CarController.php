<?php

namespace App\Http\Controllers;

use App\model\Car;
use Illuminate\Http\Request;

class CarController extends Controller
{
    public function index()
    {
        $cars = Car::all();
        return BaseController::successData($cars, "تم جلب البيانات بنجاح");
    }
}
