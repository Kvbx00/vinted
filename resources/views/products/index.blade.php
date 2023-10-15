<h1>Vinted Products</h1>

<ul>
    @foreach ($products['items'] as $product)
        <li>
            <p>{{ $product['brand_title'] }}</p>
            <p>{{ $product['title'] }}</p>
            <p>Price: {{ $product['price'] }} {{ $product['currency'] }}</p>
            <p>Size: {{ $product['size_title'] }}</p>
            <a href="{{ $product['url'] }}">Link to product</a>
        </li>
    @endforeach
</ul>
