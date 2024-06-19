<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Vos commandes') }}
        </h2>
    </x-slot>

    <div class="py-12 order-box-service">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-xl sm:rounded-lg order-data">
                
               @if($orders->isEmpty())
                <h3>Aucune commande trouvée ! Aller à <a href="/">Page d'accueil</a> et passez une commande.</h3>
               @else
               <table class="table-set">
                <thead>
                    <tr class="title-order-set">
                        <th>N ° de commande.</th>
                        <th>Prestations de service</th>
                        <th>Prix ​​total</th>
                        <th>Fournisseur de services</th>
                        <th>Date de rendez-vous</th>
                        <th>Action</th>
                    </tr>
                </thead>
               <tbody>
                @foreach($orders as $order)
                @php 
                $services_with_price = json_decode($order['services_with_price'], true);
                $numberofservices = count($services_with_price) - 1;
                if($numberofservices > 0){
                    $servicename = $services_with_price[0]['name'] .' + '. $numberofservices;
                }else{
                    $servicename = $services_with_price[0]['name'];
                }
                
                @endphp
                <tr>
                    <td class="id-set">{{$order['order_number']}}.</td>
                    <td>{{$servicename}}</td>
                    @if($order['payment_info'] == 'cod')
                    <td>{{$order['total_price']}}</td>
                    @else
                    <td>€{{$order['total_price']}}</td>
                    @endif
                    <td>{{ \App\Models\User::find($order['service_provider_id'])->name }}</td>
                    <td>{{date('Y-m-d', strtotime($order['appointment_date']))}}</td>
                    <td class="order-view-btn"><a href="{{ route('view', ['order_id' => $order['order_number']]) }}">Voir</a></td>
                </tr>
                @endforeach
                </tbody>
                </table>
               @endif
            </div>
        </div>
    </div>
</x-app-layout>
