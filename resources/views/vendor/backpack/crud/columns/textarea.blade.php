{{-- regular object attribute --}}
@php
    $column['value'] = $column['value'] ?? data_get($entry, $column['name']);
    $column['escaped'] = $column['escaped'] ?? true;
    $column['prefix'] = $column['prefix'] ?? '';
    $column['suffix'] = $column['suffix'] ?? '';
    $column['text'] = $column['default'] ?? '-';

    if($column['value'] instanceof \Closure) {
        $column['value'] = $column['value']($entry);
    }

    if(!empty($column['value'])) {
        $column['text'] = $column['prefix'].$column['value'].$column['suffix'];
    }

    if($column['name'] == 'services_with_price'){
        $services_with_price = json_decode($column['value']);
    }
    $total_price = 0;
@endphp


<span>
    @includeWhen(!empty($column['wrapper']), 'crud::columns.inc.wrapper_start')
    @if(!empty($services_with_price))
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>Name</th>
                <th>Quantity</th>
                <th>Price</th>
            </tr>
        </thead>
        <tbody>
            @foreach($services_with_price as $service)
            <tr>
                <td>{{ $service->name }}</td>
                <td>{{ $service->quantity }}</td>
                <td>€{{ $service->price }}</td>
            </tr>
          
            @endforeach
            
        </tbody>
    </table>
    
    @else
        @if($column['escaped'])
            {{ $column['text'] }}
        @else
            {!! $column['text'] !!}
        @endif
    @endif
    @includeWhen(!empty($column['wrapper']), 'crud::columns.inc.wrapper_end')
</span>

