<x-guest-layout>
    <x-authentication-card>
        <x-slot name="logo">
            <x-authentication-card-logo />
        </x-slot>
        @if($errors->has('otp'))
            <div class="alert alert-danger">{{ $errors->first('otp') }}</div>
        @endif
        <form method="POST" action="{{ route('otp.verifi') }}">
            @csrf
            <div class="form-group">
                <label for="otp">Entrez OTP</label>
                <input id="otp" type="text" class="form-control" name="otp" required>
            </div>

            <button type="submit" class="btn btn-primary">Vérifier OTP</button>
        </form>
    </x-authentication-card>
</x-guest-layout>
