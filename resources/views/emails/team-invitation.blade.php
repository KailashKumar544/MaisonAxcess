@component('mail::message')
{{ __('Vous avez été invité à rejoindre l'équipe :team !', ['team' => $invitation->team->name]) }}

@if (Laravel\Fortify\Features::enabled(Laravel\Fortify\Features::registration()))
{{ __('Si vous n'avez pas de compte, vous pouvez en créer un en cliquant sur le bouton ci-dessous. Après avoir créé un compte, vous pouvez cliquer sur le bouton d'acceptation de l'invitation dans cet e-mail pour accepter l'invitation de l'équipe.:') }}

@component('mail::button', ['url' => route('register')])
{{ __('Create Account') }}
@endcomponent

{{ __('Si vous possédez déjà un compte, vous pouvez accepter cette invitation en cliquant sur le bouton ci-dessous:') }}

@else
{{ __('Vous pouvez accepter cette invitation en cliquant sur le bouton ci-dessous:') }}
@endif


@component('mail::button', ['url' => $acceptUrl])
{{ __('Accept Invitation') }}
@endcomponent

{{ __('Si vous ne vous attendiez pas à recevoir une invitation à cette équipe, vous pouvez supprimer cet e-mail.') }}
@endcomponent
