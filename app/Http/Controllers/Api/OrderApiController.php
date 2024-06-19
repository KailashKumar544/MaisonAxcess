<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\Order;
use App\Models\User;
use App\Models\Appointment;
use App\Mail\OrderSuccessNotification;
use App\Mail\WelcomeNewUser;
use App\Mail\OrderNotification;
use Illuminate\Support\Facades\Mail;
use Stripe\StripeClient;
use Stripe;

class OrderApiController extends Controller
{
    public function index(){
        $orders = Order::all();
        if(!$orders->isEmpty()){
            return response()->json(['status' => true,
            'message' => 'Orders list','orders' => $orders], 200);
        }else{
            return response()->json(['status' => false,
            'message' => 'Orders not found','orders' => $orders], 200);
        }
        return response()->json(['orders' => $orders], 200);
    }

    public function getOrdersByUser($user_id = null){
        $orders = Order::where('user_id', $user_id)->get();
        if(!$orders->isEmpty()){
            return response()->json(['status' => true,
            'message' => 'Order Detail','orders' => $orders], 200);
        }else{
            return response()->json(['status' => false,
            'message' => 'Order not found','orders' => $orders], 200);
        }
    }

    public function getOrderById($order_id = null){
        $orders = Order::where('order_number', $order_id)->get();
        if(!$orders->isEmpty()){
            return response()->json(['status' => true,
            'message' => 'Order Detail','orders' => $orders], 200);
        }else{
            return response()->json(['status' => false,
            'message' => 'Order not found','orders' => $orders], 200);
        }
    }

    public function saveOrder(Request $request){
        $service_provider_name = '';
        $validator = Validator::make($request->all(), [
            'email' => ['required'],
            'payment_info' => ['required'],
            'appointment_date' => ['required'],
            'main_service_id' => ['required'],
            'service_image' => ['required'],
            'address' => ['required'],
            'state' => ['required'],
            'country' => ['required'],
            'postal_code' => ['required']
        ]);
        
        if ($validator->fails()) {
            return response()->json(['status' => false, 'message' => 'Validation Error','errors' => $validator->errors()], 400);
        }
        $service_provider_id = $request->input('service_provider_id');   
        if(!empty($service_provider_id)){
            $service_provider_detail = User::find($service_provider_id);
            $service_provider_name = $service_provider_detail->name;
        }
        $services_data = $request->input('services_detail');
        
        $services_and_price = array();
        $total_price = 0;
        foreach($services_data as $service_data){
            $services_and_price[] = array(
                'name'=>$service_data['title'],
                'price'=> $service_data['price'],
                'quantity' => $service_data['quantity']
            );
            if($request->input('payment_info') == 'cod'){
                $total_price = 'Sur devis';
            }else{
                $total_price += ($service_data['price'] *  $service_data['quantity']);
            }
        }
    
        $logoUrl = asset('uploads/AXCESS_Logo.png');
        $loginUrl = 'https://maisonaxcess.com/login';
        $userExists = User::where('email', $request->input('email'))->exists();

        if ($userExists) {
            $user = User::where('email', $request->input('email'))->first();
            $userId = $user->id;
        }
        if(!empty($request->input('stripeToken'))){
            $order = new Order();
            
            $order->user_id = $userId;
            $order->service_provider_id = $service_provider_id;
            $order->appointment_date = $request->input('appointment_date');
            $order->main_service_id = $request->input('main_service_id');
            $order->service_image = $request->input('service_image');
            $order->service_provider_name = $service_provider_name ?? '';
            $order->services_with_price = json_encode($services_and_price);
            $order->phone_number = $request->input('phone_number');
            $order->customer_address = $request->input('address') .','.$request->input('state') .','.$request->input('country') .','.$request->input('postal_code');
            $order->payment_info = $request->input('payment_info');
            $order->total_price =  $total_price;
            $order->status = 'pending';
            $order->save();
            $order->order_number = $order->id;
            $order->save();
            $order_id = $order->id;

            // Charge the customer using Stripe API
            $stripe_secret_key = '';
            \Stripe\Stripe::setApiKey($stripe_secret_key);
        
            $stripe = new \Stripe\StripeClient('');
            try {
                $stripeToken = strval($request->input('stripeToken'));
                $customer = $stripe->customers->create([
                    'email' => $request->input('email'), 
                    'name' => $user->name,
                    'address' => [
                        'line1' => $request->input('address'),
                        'postal_code' => $request->input('postal_code'),
                        'state' => $request->input('state'),
                        'country' => $request->input('country'),
                    ],
                ]);
                $customer_id = $customer->id;
                $payment_method = \Stripe\PaymentMethod::create([
                    'type' => 'card',
                    'card' => [
                        'token' => $stripeToken, // Token received from Stripe.js
                    ],
                ]);
                $payment_method->attach(['customer' => $customer_id]);
        
                
                $charge = $stripe->paymentIntents->create([
                    'shipping' => [
                        'name' => $user->name,
                        'address' => [
                            'line1' => $request->input('address'),
                            'postal_code' => $request->input('postal_code'),
                            'state' => $request->input('state'),
                            'country' => $request->input('country'),
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
                $appointment->date = $request->input('appointment_date');
                $appointment->save();
                $appointment_id = $appointment->id;
                $order->appointment_id = $appointment_id;
                $order->status = 'success';
                $order->save();
                $service_provider = User::find($service_provider_id);
            } catch (\Stripe\Exception\CardException $e) {
                // Handle card error
                return response()->json(['success' => false, 'message' => 'Card error: ' . $e->getError()->message], 400);
            } catch (\Stripe\Exception\RateLimitException $e) {
                // Handle rate limit error
                return response()->json(['success' => false, 'message' => 'Rate limit error: ' . $e->getError()->message], 400);
            } catch (\Stripe\Exception\InvalidRequestException $e) {
                // Handle invalid request error
                return response()->json(['success' => false, 'message' => 'Invalid request error: ' . $e->getError()->message], 400);
            } catch (\Stripe\Exception\AuthenticationException $e) {
                // Handle authentication error
                return response()->json(['success' => false, 'message' => 'Authentication error: ' . $e->getError()->message], 400);
            } catch (\Stripe\Exception\ApiConnectionException $e) {
                // Handle API connection error
                return response()->json(['success' => false, 'message' => 'API connection error: ' . $e->getError()->message], 400);
            } catch (\Stripe\Exception\ApiErrorException $e) {
                // Handle generic API error
                return response()->json(['success' => false, 'message' => 'API error: ' . $e->getError()->message], 400);
            }

        }else{
            $appointment = new Appointment();
            $appointment->service_provider_id = $service_provider_id;
            $appointment->date = $request->input('appointment_date');
            $appointment->save();
            $appointment_id = $appointment->id;
    
            $order = new Order();
            
            $order->user_id = $userId;
            $order->appointment_id = $appointment_id;
            $order->service_provider_id = $service_provider_id;
            $order->appointment_date = $request->input('appointment_date');
            $order->main_service_id = $request->input('main_service_id');
            $order->service_image = $request->input('service_image');
            $order->service_provider_name = $service_provider_name ?? '';
            $order->services_with_price = json_encode($services_and_price);
            $order->phone_number = $request->input('phone_number');
            $order->customer_address = $request->input('address') .','.$request->input('state') .','.$request->input('country') .','.$request->input('postal_code');
            $order->payment_info = $request->input('payment_info');
            $order->total_price =  $total_price;
            $order->location_for_service = $request->input('location_for_service');
            $order->cancellation_comment = $request->input('cancellation_comment');
            $order->status = 'pending';
            $order->save();
            $order->order_number = $order->id;
            $order->save();
            $order_id = $order->id;        
           
            $service_provider = User::find($service_provider_id);
        }
        

        if ($service_provider) {
            $service_provider_name = $service_provider->name; // Assuming 'name' is the attribute containing the username
        } else {
            // Handle the case where the user is not found
            $service_provider_name = 'Unknown';
        }
        
        Mail::to($request->input('email'))->send(new OrderSuccessNotification($order, $logoUrl, $service_provider_name));
        Mail::to('conciergerie-VB@urbanstation.fr')->send(new OrderNotification($order, $logoUrl, $service_provider_name));
        /* save appointment date in appointment table end */
        return response()->json(['success' => true, 'appointment_id' => $appointment_id, 'userid' => $userId, 'orderid' => $order_id]);
    }
}
