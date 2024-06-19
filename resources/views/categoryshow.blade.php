@include('components.header', ['menu_items' => App\Models\Menu::all()])

<!-- Code to display child category list if available start -->
<div id="categories-sec">
    @if(($category->parent_id != null) && !empty($category->image))
    <div  class="single-categories1">
        <img src="{{ env('APP_URL') }}/{{$category->image}}" alt="category-image" style="width: 100%;" class="{{$category->name}}">
    </div>
    @endif
@if(!empty($childCategories[0]))
    @if($category->name=='COORDONNERIE' || $category->name=='LAVAGE AUTO-MOTO' || $category->name=='Pressing et blanchisserie' || $category->name=='Esthéticienne')
        <div class="popular_categories">
            @foreach($childCategories as $childCategory)
                
                <!-- <div class="withtitle" style="background-image: url('{{ env('APP_URL') }}/{{$childCategory->image}}');">
                
                @if (strpos($childCategory->slug, 'http') !== false) 
                <a href="{{ $childCategory->slug }}" target="_blank"><h2>{{ $childCategory->name }}</h2></a>
                @else
                <a href="/service_types/{{ $childCategory->slug }}"><h2>{{ $childCategory->name }}</h2></a>
                @endif
            
            
                </div> -->
                <div class="popular_cat">
                    <div>
                        <h2>{{ $childCategory->name }}</h2>
                    </div>
                    <div class="cat_service_dtls">
                        <div>
                            <button onclick="showmodal('detailsModal_{{$childCategory->id}}')">Détails</button>
                        </div>
                        <div>
                            <button onclick="showmodal('tarifsModal_{{$childCategory->id}}')">Tarifs</button>
                        </div>
                        <div>
                            @if(!empty($childCategory->articleslug))
                            <a onclick="updateServiceProvider({{ $childCategory->users[0]['id'] }},'{{$childCategory->articleslug}}')">Commander</a>
                            @else
                            <a href="#">Commander</a>
                            @endif
                        </div>
                    </div>

                </div>
                <div id="detailsModal_{{$childCategory->id}}" class="cat_modal modal" style="display:none;">
                    <div class="modal-content">
                        <span class="close" onclick="closemodal('detailsModal_{{$childCategory->id}}')">ok</span>
                        <h2>Détails</h2>
                        <img src="/{{ $childCategory->article->image }}" width="550px" alt="service image">
                        <h3>{{ $childCategory->article->title }}</h3>  
                        <p>{!! $childCategory->article->content !!}</p>    
                    </div>
                </div>
                <div id="tarifsModal_{{$childCategory->id}}" class="cat_modal modal" style="display:none;">
                    <div class="modal-content">
                        <span class="close" onclick="closemodal('tarifsModal_{{$childCategory->id}}')">ok</span>
                        <h2>Tarifs</h2>
                        <table>
                            <thead>
                                <tr>
                                    <td class="name">Prestation sur devis</td>
                                    <td class="price">Sur devis uniquement</td>
                                </tr>
                            </thead>
                            <tbody>
                            @foreach($childCategory->article->services as $service)
                            
                            @if(!empty($service['name']) && !empty($service['price']))
                                <tr>
                                    <td class="name">{{$service['name']}}</td>
                                    <td class="price">€{{$service['price']}}</td>                                    
                                </tr>
                                @endif
                            @endforeach
                            </tbody>
                        </table>                        
                    </div>
                </div>
            @endforeach
        </div>
    @else
        <div class="categories-box">
            @foreach($childCategories as $childCategory)
                    <div>
                    @if (strpos($childCategory->slug, 'http') !== false) 
                    <a href="{{ $childCategory->slug }}" target="_blank">
                    <img src="{{ env('APP_URL') }}/{{$childCategory->image}}" alt="category-image" style="width: 100%; height: 240px;
                max-height: 230px;
                object-fit: cover;">
                
                    </a>
                    @else
                    <a href="/service_types/{{ $childCategory->slug }}">
                    <img src="{{ env('APP_URL') }}/{{$childCategory->image}}" alt="category-image" style="width: 100%; height: 240px;
                max-height: 230px;
                object-fit: cover;">
                
                    </a>
                    @endif
                    </div>
            @endforeach
        </div>
    @endif
@endif

<!-- Code to display child category list if available end -->

<!-- Code to display service provider list if available start -->

@if($category->name != 'COORDONNERIE' && $category->name != 'LAVAGE AUTO-MOTO' && $category->name != 'Pressing et blanchisserie')
    @if(!empty($serviceproviders))
        
        <div class="user-sec">
        @foreach($serviceproviders as $serviceprovider)
            <div class="user-data">
            @if(!empty($articleslug))
                <a onclick="updateServiceProvider({{ $serviceprovider['id'] }},'{{$articleslug}}')">
            
            @endif
            <div class="servicep"> 
        
        
                <img src="{{ env('APP_URL') }}/{{$serviceprovider['profile_photo_path']}}" alt="service-provider-logo">
                <!-- <h2>{{$serviceprovider['name']}}</h2> -->
                
                @php 
                $isHtml = $serviceprovider['description'] !== strip_tags($serviceprovider['description']);
                @endphp
                </div> <div class="servicep">
                @if ($isHtml) 
                    {!! $serviceprovider['description'] !!}
                @else
                <p>{{$serviceprovider['description']}}</p>
                @endif
                
            @if(!empty($articleslug))   
            </a>  
            @endif
            </div>     
            </div>
            
        @endforeach
        
    @endif
@endif

</div>
</div>

<!-- Code to display service provider list if available end -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
    function updateServiceProvider(serviceProviderId, articleslug) {
        // var artslg = "{{ route('service.show', $articleslug) }}";
        var artslg = '{{ route('service.show', ['slug' => ':articleslug']) }}'.replace(':articleslug', articleslug);
        jQuery.ajax({
            url: '{{ route('update.service_provider_id') }}',
            type: 'POST',
            data: {
                _token: '{{ csrf_token() }}',
                service_provider_id: serviceProviderId
            },
            success: function(response) {
                // Handle success, if needed
                console.log(response)
                window.location.href = artslg;
            },
            error: function(xhr, status, error) {
                // Handle error, if needed
            }
        });
    }


    function showmodal(modalid){
        document.getElementById(modalid).style.display = "block";
    }

    function closemodal(modalid){
        document.getElementById(modalid).style.display = "none";
    }
</script>
@include('components.footer')