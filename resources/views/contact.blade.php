@include('components.header', ['menu_items' => App\Models\Menu::all()])

<div id="contact-section">
	<div class="contact-form">
    <h1>Contactez-nous</h1>
    <p>Contactez notre équipe dédiée pour toute demande de renseignements, assistance ou information dont vous avez besoin.</p>
    @if(session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif
    <form action="{{ route('contact.submit') }}" method="post">
        @csrf
        <div class="form-group">
            <label for="name">Nom</label>
            <input type="text" name="name" id="name" class="form-control" required>
        </div>
        <div class="form-group">
            <label for="email">E-mail</label>
            <input type="email" name="email" id="email" class="form-control" required>
        </div>
        <div class="form-group">
            <label for="subject">Sujet</label>
            <input type="subject" name="subject" id="subject" class="form-control" required>
        </div>
        <div class="form-group">
            <label for="message">Message</label>
            <textarea name="message" id="message" class="form-control" rows="5"></textarea>
        </div>
        <button type="submit" class="btn btn-primary">Soumettre</button>
    </form>
</div>
</div>
@include('components.footer')

