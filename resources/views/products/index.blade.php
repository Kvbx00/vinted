
    <h1>Vinted Products</h1>

    <ul>
        @foreach ($products as $product)
            <li>
                <h3>{{ $product['title'] }}</h3>
                <img src="{{ $product['images'][0]['url'] }}" alt="{{ $product['title'] }}">
                <p>{{ $product['price'] }}</p>
            </li>
        @endforeach
    </ul>
