<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Appointment;
use App\Models\Order;
use Illuminate\Support\Facades\Session;
use App\Models\User;
use App\Models\Role;
use App\Mail\OrderSuccessNotification;
use App\Mail\WelcomeNewUser;
use App\Mail\OrderNotification;
use Illuminate\Support\Facades\Mail;
use Stripe\StripeClient;
use Stripe;


class AppointmentController extends Controller
{
    public function insert_appointment(Request $request){
        $service_provider_id = Session::get('service_provider_id');   
        $service_provider_name = '';
        if(empty($service_provider_id)){
            return response()->json(['success' => false, 'message'=> "Please select service provider and then book appointment."]);
        }
        if(!empty($service_provider_id)){
            $service_provider_detail = User::find($service_provider_id);
            $service_provider_name = $service_provider_detail->name;
        }
        $services_data = $request->input('dataValues');
        $appointment_datas = $request->input('form_data');
        parse_str($appointment_datas, $appointment_datas);
        
        $services_and_price = array();
        $total_price = 0;
        foreach($services_data as $service_data){
            $serviceData = explode('-', $service_data);
            if(count($serviceData)> 2){
                $nameParts = array_slice($serviceData, 0, -1);
                $joinedName = implode('', $nameParts);
                $cleanedJoinedName = preg_replace('/[^a-zA-Z0-9\s]/', '', $joinedName);
                $underscoreSeparatedname = str_replace(' ', '_', $cleanedJoinedName);
            
                // Get the last element
                $lastElement = end($serviceData);
                if($appointment_datas['payment_mode'] == 'online'){
                    $service_key = $underscoreSeparatedname . '-' . str_replace('.', '_', strval($lastElement));
                }else{
                    $formattedServicePrice = preg_replace('/\s+/', '_', strval($lastElement));
                    // Replace dots with underscores in the service price
                    $formattedServicePrice = str_replace('.', '_', $formattedServicePrice);
                    $service_key = $underscoreSeparatedname . '-' . $formattedServicePrice;
                }
            }else{
                $cleanedName = preg_replace('/[^a-zA-Z0-9\s]/', '', $serviceData[0]);
                $underscoreSeparatedname = str_replace(' ', '_', $cleanedName);
                $lastElement = end($serviceData);
                if($appointment_datas['payment_mode'] == 'online'){
                    $service_key = $underscoreSeparatedname .'-'. str_replace('.', '_', strval($serviceData[1]));
                }else{
                    $formattedServicePrice = preg_replace('/\s+/', '_', strval($lastElement));
                    // Replace dots with underscores in the service price
                    $formattedServicePrice = str_replace('.', '_', $formattedServicePrice);
                    $service_key = $underscoreSeparatedname . '-' . $formattedServicePrice;
                }
            }
            $nameParts = array_slice($serviceData, 0, -1);

            // Join the name parts using a space
            $name = implode('-', $nameParts);
            $services_and_price[] = array(
                'name'=>$name,
                'price'=> $serviceData[count($serviceData) -1],
                'quantity' => $appointment_datas[$service_key]
            );
            if($appointment_datas['payment_mode'] == 'online'){
                $total_price += ($serviceData[count($serviceData) -1] *  $appointment_datas[$service_key]);
            }else{
                $total_price = 'Sur devis';
            }
        }
    
        
       
        $logoUrl = asset('uploads/AXCESS_Logo.png');
        $loginUrl = 'https://maisonaxcess.com/login';
        $userExists = User::where('email', $appointment_datas['email'])->exists();

        if ($userExists) {
            $user = User::where('email', $appointment_datas['email'])->first();
            $userId = $user->id;
        } else {
            // User with the provided email does not exist
            $chars = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789!@#$%^&*()-_=+';
            $password = '';
            
            // Generate random password using characters from $chars
            for ($i = 0; $i < 8; $i++) {
                $password .= $chars[rand(0, strlen($chars) - 1)];
            }
            $user = new User();
            $user->name = $appointment_datas['name'];
            $user->email = $appointment_datas['email'];
            $user->password = bcrypt($password);
            $user->phone_number = $appointment_datas['phone_number'];
            
            // Save the new user to the database
            $user->save();
            $userId = $user->id;
            // Find the role "customer" or create it if it does not exist
            $customerRole = Role::where('name', 'customer')->first();
            if (!$customerRole) {
                // Create the role "customer" if it does not exist
                $customerRole = new Role();
                $customerRole->name = 'customer';
                $customerRole->save();
            }
            
            // Assign the "customer" role to the new user
            $user->roles()->attach($customerRole);

            Mail::to($appointment_datas['email'])->send(new WelcomeNewUser($user, $password, $logoUrl, $loginUrl));
            
        }

        /* save appointment date and order in appointment and orders table start */
        if(!empty($appointment_datas['stripeToken'])){
            if(!empty($appointment_datas)){
            

                $order = new Order();
            
                $order->user_id = $userId;
                $order->service_provider_id = $service_provider_id ?? '';
                $order->appointment_date = $appointment_datas['appointment_date'];
                $order->main_service_id = $appointment_datas['main_service_id'];
                $order->service_image = $appointment_datas['service_image'];
                $order->service_provider_name = $service_provider_name ?? '';
                $order->services_with_price = json_encode($services_and_price);
                $order->phone_number = $appointment_datas['phone_number'];
                $order->customer_address = $appointment_datas['address'] .','.$appointment_datas['state'] .','.$appointment_datas['country'] .','.$appointment_datas['postal_code'];
                $order->payment_info = isset($appointment_datas['payment_info']) ? $appointment_datas['payment_info'] : 'stripe';
                $order->total_price =  $total_price;
                $order->status = 'pending';
                $order->save();
                $order->order_number = $order->id;
                $order->save();
                $order_id = $order->id;

                // Charge the customer using Stripe API
                $stripe_secret_key = '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';
                \Stripe\Stripe::setApiKey($stripe_secret_key);
            
                $stripe = new \Stripe\StripeClient('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');
                try {
                
                    $customer = $stripe->customers->create([
                        'email' => $appointment_datas['email'], 
                        'name' => $appointment_datas['name'],
                        'address' => [
                            'line1' => $appointment_datas['address'],
                            'postal_code' => $appointment_datas['postal_code'],
                            'state' => $appointment_datas['state'],
                            'country' => $appointment_datas['country'],
                        ],
                    ]);
                    $customer_id = $customer->id;
                    $payment_method = \Stripe\PaymentMethod::create([
                        'type' => 'card',
                        'card' => [
                            'token' => $appointment_datas['stripeToken'], // Token received from Stripe.js
                        ],
                    ]);
                    
                    $payment_method->attach(['customer' => $customer_id]);
            
                    
                    $charge = $stripe->paymentIntents->create([
                        'shipping' => [
                            'name' => $appointment_datas['name'],
                            'address' => [
                            'line1' => $appointment_datas['address'],
                            'postal_code' => $appointment_datas['postal_code'],
                            'state' => $appointment_datas['state'],
                            'country' => $appointment_datas['country'],
                            ],
                        ],
                        'amount' => (int)($total_price * 100),
                        'currency' => 'usd',
                        'payment_method' => $payment_method->id,
                        'customer' => $customer_id,
                        'automatic_payment_methods' => ['enabled' => true],
                        'off_session' => true,
                        'confirm' => true,
                        'description' => 'Booking Appointment on https://maisonaxcess.com/ for order id : '.$order_id,
                    ]);
                    $appointment = new Appointment();
                    $appointment->service_provider_id = $service_provider_id;
                    $appointment->date = $appointment_datas['appointment_date'];
                    $appointment->save();
                    $appointment_id = $appointment->id;
                    $order->appointment_id = $appointment_id;
                    $order->status = 'success';
                    $order->save();
                    
                } catch (\Stripe\Exception\CardException $e) {
                    // Handle card error
                    return response()->json(['success' => false, 'message' => 'Card error: ' . $e->getError()->message]);
                } catch (\Stripe\Exception\RateLimitException $e) {
                    // Handle rate limit error
                    return response()->json(['success' => false, 'message' => 'Rate limit error: ' . $e->getError()->message]);
                } catch (\Stripe\Exception\InvalidRequestException $e) {
                    // Handle invalid request error
                    return response()->json(['success' => false, 'message' => 'Invalid request error: ' . $e->getError()->message]);
                } catch (\Stripe\Exception\AuthenticationException $e) {
                    // Handle authentication error
                    return response()->json(['success' => false, 'message' => 'Authentication error: ' . $e->getError()->message]);
                } catch (\Stripe\Exception\ApiConnectionException $e) {
                    // Handle API connection error
                    return response()->json(['success' => false, 'message' => 'API connection error: ' . $e->getError()->message]);
                } catch (\Stripe\Exception\ApiErrorException $e) {
                    // Handle generic API error
                    return response()->json(['success' => false, 'message' => 'API error: ' . $e->getError()->message]);
                }

            }
        }
        else{
            if(!empty($appointment_datas)){
                $appointment = new Appointment();
                $appointment->service_provider_id = $service_provider_id;
                $appointment->date = $appointment_datas['appointment_date'];
                $appointment->save();
                $appointment_id = $appointment->id;
                

                $order = new Order();
            
                $order->user_id = $userId;
                $order->service_provider_id = $service_provider_id;
                $order->appointment_date = $appointment_datas['appointment_date'];
                $order->main_service_id = $appointment_datas['main_service_id'];
                $order->service_image = $appointment_datas['service_image'];
                $order->service_provider_name = $service_provider_name ?? '';
                $order->cancellation_comment = $appointment_datas['cancellation'];
                $order->location_for_service = $appointment_datas['location'];
                $order->services_with_price = json_encode($services_and_price);
                $order->phone_number = $appointment_datas['phone_number'];
                $order->customer_address = $appointment_datas['address'] .','.$appointment_datas['state'] .','.$appointment_datas['country'] .','.$appointment_datas['postal_code'];
                $order->payment_info = isset($appointment_datas['payment_info']) ? $appointment_datas['payment_info'] : 'cod';
                $order->total_price =  $total_price;
                $order->status = 'pending';
                $order->appointment_id = $appointment_id;
                $order->save();
                $order->order_number = $order->id;
                $order->save();
                $order_id = $order->id;

            }
        }
       
        $service_provider = User::find($service_provider_id);

        if ($service_provider) {
            $service_provider_name = $service_provider->name; // Assuming 'name' is the attribute containing the username
        } else {
            // Handle the case where the user is not found
            $service_provider_name = 'Unknown';
        }
        
        Mail::to($appointment_datas['email'])->send(new OrderSuccessNotification($order, $logoUrl, $service_provider_name));
        Mail::to('conciergerie-VB@urbanstation.fr')->send(new OrderNotification($order, $logoUrl, $service_provider_name));
        /* save appointment date in appointment table end */
        return response()->json(['success' => true, 'appointment_id' => $appointment_id, 'userid' => $userId, 'orderid' => $order_id]);
    }

    public function order_success($order_id){
        $order_details = Order::find($order_id);
        $service_provider = User::find($order_details->service_provider_id);

        if ($service_provider) {
            $service_provider_name = $service_provider->name; // Assuming 'name' is the attribute containing the username
        } else {
            // Handle the case where the user is not found
            $service_provider_name = 'Unknown';
        }
        return view('success', compact('order_id', 'order_details', 'service_provider_name')); 
    }
}
