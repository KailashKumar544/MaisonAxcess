<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Appointment;

class AppointmentApiController extends Controller
{
    public function index(){
        $appointments = Appointment::all();
        if(!$appointments->isEmpty()){
            return response()->json(['status' => true,
            'message' => 'Appointment list','appointments' => $appointments], 200);
        }else{
            return response()->json(['status' => false,
            'message' => 'Appointment not found','appointments' => $appointments], 200);
        }
    }

    public function get_appointment($service_provider_id = null){
        $appointments = Appointment::where('service_provider_id', $service_provider_id)->get();
        if(!$appointments->isEmpty()){
            return response()->json(['status' => true,
            'message' => 'Appointment list','appointments' => $appointments], 200);
        }else{
            return response()->json(['status' => false,
            'message' => 'Appointment not found','appointments' => $appointments], 200);
        }
    }
}
