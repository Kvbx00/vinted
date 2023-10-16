<h1>Vinted Products</h1>

<form action="{{ route('search.products') }}" method="get">
    <label for="search_text">Wyszukaj produkty:</label>
    <input type="text" id="search_text" name="search_text" >
    <label for="sort_by">Sortuj według:</label>
    <select id="sort_by" name="sort_by">
        <option value="price_asc">Cena rosnąco</option>
        <option value="price_desc">Cena malejąco</option>
        <option value="favorite_asc">Ulubione rosnąco</option>
        <option value="favorite_desc">Ulubione malejąco</option>
    </select>
    <button type="submit">Szukaj</button>
</form>

<ul>
    @foreach ($products as $product)
        <li>
            <p>{{ $product['brand_title'] }}</p>
            <p>{{ $product['title'] }}</p>
            <p>Price: {{ $product['price'] }} {{ $product['currency'] }}</p>
            <p>Size: {{ $product['size_title'] }}</p>
            <p>Serduszka: {{ $product['favourite_count'] }}</p>
            <a href="{{ $product['url'] }}">Link to product</a>
        </li>
    @endforeach
</ul>
<!-- Wyświetlanie paginacji -->
{{ $products->appends(request()->input())->links() }}
