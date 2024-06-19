

<table class="table table-bordered">
    <thead>
        <tr>
            <th>Name</th>
            <th>Price</th>
            <th>Quantity</th>
        </tr>
    </thead>
    <tbody>
        @foreach($services_with_price as $service)
        <tr>
            <td>{{ $service['name'] }}</td>
            <td>{{ $service['price'] }}</td>
            <td>{{ $service['quantity'] }}</td>
        </tr>
        @endforeach
    </tbody>
</table>

