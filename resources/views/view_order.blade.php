<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
        {{ __('Détails de la commande (:orderId)', ['orderId' => $orderId]) }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-xl sm:rounded-lg detail-set-div">
                
                @php
                $services_with_price = json_decode($order_details['services_with_price'], true);
                @endphp
                <table class="order-details">
                    <tr>
                        <td class="order-detils-tit">Numéro de commande : </td>
                        <td>{{$order_details['order_number']}}</td>
                    </tr>
                    <tr>
                        <td class="order-detils-tit" >Fournisseur de services : </td>
                        <td>{{ \App\Models\User::find($order_details['service_provider_id'])->name }}</td>
                    </tr>
                    <tr>
                        <td class="order-detils-tit">Prestations de service : </td>
                        <td>
                            <table>
                                <thead>
                                    <tr class="iner-detils">
                                        <td>Nom</td>
                                        <td>Quantité</td>
                                        <td>Prix</td>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($services_with_price as $services)
                                    <tr>
                                        <td>{{$services['name']}}</td>
                                        <td>{{$services['quantity']}}</td>
                                        <td>€{{$services['price']}}</td>
                                    </tr>
                                    @endforeach
                                    <tr>
                                        <td></td>
                                        <td>Total de la commande : </td>
                                        @if($order_details['payment_info'] == 'cod')
                                        <td>{{$order_details['total_price']}}</td>
                                        @else
                                        <td>€{{$order_details['total_price']}}</td>
                                        @endif
                                    </tr>
                                </tbody>
                            </table>
                        </td>
                    </tr>
                    <tr>
                        <td class="order-detils-tit">Date de réservation : </td>
                        <td>{{date('Y-m-d', strtotime($order_details['appointment_date']))}}</td>
                    </tr>
                    <tr>
                        <td class="order-detils-tit">Numéro de téléphone : </td>
                        <td>{{$order_details['phone_number']}}</td>
                    </tr>
                    <tr>
                        <td class="order-detils-tit">Adresse : </td>
                        <td>{{$order_details['customer_address']}}</td>
                    </tr>
                    <tr>
                        <td class="order-detils-tit">Mode de paiement : </td>
                        <td>{{$order_details['payment_info']}}</td>
                    </tr>
                    <tr>
                        <td class="order-detils-tit">Statut de la commande : </td>
                        <td>{{$order_details['status']}}</td>
                    </tr>
                    <tr>
                        <td class="order-detils-tit">Date de réservation : </td>
                        <td>{{date('Y-m-d', strtotime($order_details['created_at']))}}</td>
                    </tr>
                </table>     
            </div>
        </div>
    </div>
</x-app-layout>
