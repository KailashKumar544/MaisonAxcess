@component('mail::message')
![{{ env('APP_NAME') }} Logo]({{$logoUrl}})


@php 
    $customerdetail = $order->customerName;
@endphp

Bonjour {{ $customerdetail->name }},

@if($order->payment_info == 'cod')

Votre devis a été généré avec succès. Voici les détails du devis:
- Identifiant du devis: {{ $order->id }}
- Fournisseur de services : {{ $service_provider_name }}
- Date de rendez-vous : {{date('Y-m-d',strtotime($order->appointment_date))}}
- Adresse : {{ $order->customer_address }}
- Numéro de téléphone : {{ $order->phone_number }}
- Date : {{$order->created_at->format('Y-m-d')}}
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
Votre commande a été passée avec succès. Voici les détails de la commande:
- Numéro de commande: {{ $order->id }}
- Fournisseur de services : {{ $service_provider_name }}
- Date de rendez-vous : {{date('Y-m-d',strtotime($order->appointment_date))}}
- Adresse : {{ $order->customer_address }}
- Numéro de téléphone : {{ $order->phone_number }}
- Date : {{$order->created_at->format('Y-m-d')}}
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


Merci d'avoir magasiné avec nous.
@endif


Regards,<br>
{{ env('APP_NAME') }}
@endcomponent
