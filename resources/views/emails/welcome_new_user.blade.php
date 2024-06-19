@component('mail::message')
![{{ env('APP_NAME') }} Logo]({{$logoUrl}})


Bonjour {{ $user->name }},

Bienvenue sur notre site! Votre compte a été créé avec succès.

Voici vos informations de connexion:

- Email: {{ $user->email }}
- Password: {{ $password }}

Veuillez vous connecter en utilisant ces identifiants et profiter de nos services.
[Login Here]({{ $loginUrl }})



Merci,<br>
{{ env('APP_NAME') }}
@endcomponent
