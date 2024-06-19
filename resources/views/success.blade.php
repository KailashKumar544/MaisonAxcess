@include('components.header', ['menu_items' => App\Models\Menu::all()])
<div class="container">
<div class="row">
<h1>Merci d'avoir confirmé votre rendez-vous. Nous vous contacterons bientôt!</h1>

@if($order_details->payment_info == 'cod')
<h2>Détails du devis : </h2>
<p><b>Numéro : </b>{{$order_details->order_number}}</p>
<p><b>Date de rendez-vous : </b>{{date('Y-m-d',strtotime($order_details->appointment_date))}}</p>
<p><b>Fournisseur de services : </b>{{$service_provider_name}}</p>
<p><b>Adresse : </b>{{$order_details->customer_address}}</p>
<p><b>Numéro de téléphone : </b>{{$order_details->phone_number}}</p>
<p><b>Délais d’annulation : </b> {{ $order_details->cancellation_comment }}</p>
<p><b>Lieu de livraison du service : </b>{{ $order_details->location_for_service }}</p>
<p><b>Détails du devis : </b></p>
<table style="border-collapse: collapse; width: 100%;">
    <thead>
        <tr>
            <th style="border: 1px solid black; padding: 8px;">Articles</th>
            <th style="border: 1px solid black; padding: 8px;">Quantité</th>
            <th style="border: 1px solid black; padding: 8px;">Prix</th>
        </tr>
    </thead>
    <tbody>
        @foreach(json_decode($order_details->services_with_price) as $services_with_price)
        <tr>
            <td style="border: 1px solid black; padding: 8px;">{{$services_with_price->name}}</td>
            <td style="border: 1px solid black; padding: 8px; text-align:center;">{{$services_with_price->quantity}}</td>
            <td style="border: 1px solid black; padding: 8px;">{{$services_with_price->price}}</td>
        </tr>
        @endforeach
        <tr>
            <td style="border: 1px solid black; padding: 8px;"></td>
            <td style="border: 1px solid black; padding: 8px;">Total du devis :</td>
            <td style="border: 1px solid black; padding: 8px;">{{ $order_details->total_price }}</td>
        </tr>
        <tr>
            <td style="border: 1px solid black; padding: 8px;"></td>
            <td style="border: 1px solid black; padding: 8px;">Mode de paiement :</td>
            <td style="border: 1px solid black; padding: 8px;">{{ strtoupper($order_details->payment_info) }}</td>
        </tr>
    </tbody>
</table>
<p></p>
@else
<h2>Détails de la commande : </h2>
<p><b>Numéro de commande : </b>{{$order_details->order_number}}</p>
<p><b>Date de rendez-vous : </b>{{date('Y-m-d',strtotime($order_details->appointment_date))}}</p>
<p><b>Fournisseur de services : </b>{{$service_provider_name}}</p>
<p><b>Adresse : </b>{{$order_details->customer_address}}</p>
<p><b>Numéro de téléphone : </b>{{$order_details->phone_number}}</p>
<p><b>Détails de la commande : </b></p>
<table style="border-collapse: collapse; width: 100%;">
    <thead>
        <tr>
            <th style="border: 1px solid black; padding: 8px;">Articles</th>
            <th style="border: 1px solid black; padding: 8px;">Quantité</th>
            <th style="border: 1px solid black; padding: 8px;">Prix</th>
        </tr>
    </thead>
    <tbody>
        @foreach(json_decode($order_details->services_with_price) as $services_with_price)
        <tr>
            <td style="border: 1px solid black; padding: 8px;">{{$services_with_price->name}}</td>
            <td style="border: 1px solid black; padding: 8px; text-align:center;">{{$services_with_price->quantity}}</td>
            <td style="border: 1px solid black; padding: 8px;">{{$services_with_price->price}}</td>
        </tr>
        @endforeach
        <tr>
            <td style="border: 1px solid black; padding: 8px;"></td>
            <td style="border: 1px solid black; padding: 8px;">Total de la commande :</td>
            <td style="border: 1px solid black; padding: 8px;">€{{ $order_details->total_price }}</td>
        </tr>
        <tr>
            <td style="border: 1px solid black; padding: 8px;"></td>
            <td style="border: 1px solid black; padding: 8px;">Mode de paiement :</td>
            <td style="border: 1px solid black; padding: 8px;">{{ strtoupper($order_details->payment_info) }}</td>
        </tr>
    </tbody>
</table>
<p></p>
@endif
</div>
</div>


@include('components.footer')