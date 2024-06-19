@include('components.header', ['menu_items' => App\Models\Menu::all()])
<div class="main-content">
<div class="title-head {{ $content->title }}">
<h1>{{ $content->title }}</h1>
</div>
<div class="container">
    <div class="body-content">
        {!! $content->content !!} <!-- Render HTML content -->
    </div>
</div>
</div>
@include('components.footer')