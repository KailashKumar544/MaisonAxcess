<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class ServiceProviderController extends Controller
{
    public function updateServiceProviderId(Request $request)
    {
        $serviceProviderId = $request->input('service_provider_id');
        // Update the session with the new service provider ID
        Session::put('service_provider_id', $serviceProviderId);

        return response()->json(['success' => true]);
    }
}
