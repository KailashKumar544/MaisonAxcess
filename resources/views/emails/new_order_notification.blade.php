@component('mail::message')
![{{ env('APP_NAME') }} Logo]({{$logoUrl}})


Bonjour l'administrateur,

@if($order->payment_info == 'cod')

Vous avez reçu un nouveau devis. Voici les détails du devis:
@php 
$customerdetail = $order->customerName;
@endphp
- Identifiant du devis: {{ $order->id }}
- Nom du client: {{ $customerdetail->name }}
- Email client: {{ $customerdetail->email }}
- Fournisseur de services : {{ $service_provider_name }}
- Date de rendez-vous : {{date('Y-m-d',strtotime($order->appointment_date))}}
- Délais d’annulation : {{ $order->cancellation_comment }}
- Lieu de livraison du service : {{ $order->location_for_service }}
- Détails du devis : 

<table style="border-collapse: collapse; width: 100%;">
    <thead>
        <tr>
            <th style="border: 1px solid black; padding: 8px;">Articles</th>
            <th style="border: 1px solid black; padding: 8px;">Quantité</th>
            <th style="border: 1px solid black; padding: 8px;">Prix</th>
        </tr>
    </thead>
    <tbody>
        @foreach(json_decode($order->services_with_price) as $services_with_price)
        <tr>
            <td style="border: 1px solid black; padding: 8px;">{{$services_with_price->name}}</td>
            <td style="border: 1px solid black; padding: 8px;text-align:center;">{{$services_with_price->quantity}}</td>
            <td style="border: 1px solid black; padding: 8px;">{{$services_with_price->price}}</td>
        </tr>
        @endforeach
        <tr>
            <td style="border: 1px solid black; padding: 8px;"></td>
            <td style="border: 1px solid black; padding: 8px;">Total du devis :</td>
            <td style="border: 1px solid black; padding: 8px;">{{ $order->total_price }}</td>
        </tr>
        <tr>
            <td style="border: 1px solid black; padding: 8px;"></td>
            <td style="border: 1px solid black; padding: 8px;">Mode de paiement :</td>
            <td style="border: 1px solid black; padding: 8px;">{{ strtoupper($order->payment_info) }}</td>
        </tr>
    </tbody>
</table>

@else
Une nouvelle commande a été passée. Voici les détails de la commande:
@php 
$customerdetail = $order->customerName;
@endphp
- Numéro de commande: {{ $order->id }}
- Nom du client: {{ $customerdetail->name }}
- Email client: {{ $customerdetail->email }}
- Fournisseur de services : {{ $service_provider_name }}
- Date de rendez-vous : {{date('Y-m-d',strtotime($order->appointment_date))}}
- Détails de la commande : 

<table style="border-collapse: collapse; width: 100%;">
    <thead>
        <tr>
            <th style="border: 1px solid black; padding: 8px;">Articles</th>
            <th style="border: 1px solid black; padding: 8px;">Quantité</th>
            <th style="border: 1px solid black; padding: 8px;">Prix</th>
        </tr>
    </thead>
    <tbody>
        @foreach(json_decode($order->services_with_price) as $services_with_price)
        <tr>
            <td style="border: 1px solid black; padding: 8px;">{{$services_with_price->name}}</td>
            <td style="border: 1px solid black; padding: 8px;text-align:center;">{{$services_with_price->quantity}}</td>
            <td style="border: 1px solid black; padding: 8px;">{{$services_with_price->price}}</td>
        </tr>
        @endforeach
        <tr>
            <td style="border: 1px solid black; padding: 8px;"></td>
            <td style="border: 1px solid black; padding: 8px;">Total de la commande :</td>
            <td style="border: 1px solid black; padding: 8px;">€{{ $order->total_price }}</td>
        </tr>
        <tr>
            <td style="border: 1px solid black; padding: 8px;"></td>
            <td style="border: 1px solid black; padding: 8px;">Mode de paiement :</td>
            <td style="border: 1px solid black; padding: 8px;">{{ strtoupper($order->payment_info) }}</td>
        </tr>
    </tbody>
</table>
@endif




Merci,<br>
{{ env('APP_NAME') }}
@endcomponent
