<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    <title>Produkty Vinted</title>
</head>
<body>

<header class="vinted-header">
    <div class="container">
        <div class="logo-bar">
            <a href="/">
                <img src="https://static.vinted.com/assets/web-logo/default/logo.svg" alt="Vinted logo" class="logo">
            </a>
        </div>
        <div class="search-bar">
            <form action="{{ route('search.products') }}" method="get" class="form-group" id="form-group">
                <input type="text" class="form-control" id="search_text" name="search_text" placeholder="Wyszukaj produkty...">
            </form>
        </div>
    </div>
</header>
<main class="container">

    <div class="row">
        <div class="sort">
            <form id="sortForm" action="{{ route('search.products') }}" method="get">
                <label for="sortowanie">Sortuj według:</label>
                <select id="sortowanie" name="sortowanie">
                    <option value="default" {{ request('sortowanie') === 'default' ? 'selected' : '' }}>Domyślne</option>
                    <option value="nazwa" {{ request('sortowanie') === 'nazwa' ? 'selected' : '' }}>Nazwa</option>
                    <option value="cena" {{ request('sortowanie') === 'cena' ? 'selected' : '' }}>Cena</option>
                    <option value="serduszka" {{ request('sortowanie') === 'serduszka' ? 'selected' : '' }}>serduszka</option>
                </select>
            </form>
        </div>

    <div class="col-12">
        <div class="product-container">
            @foreach ($products as $product)
                <div class="product-item">
                    <div class="product-user">
                        @if(isset($product['user']['photo']['thumbnails'][4]['url']))
                            <img src="{{ $product['user']['photo']['thumbnails'][4]['url'] }}"
                                 alt="{{ $product['brand_title'] }} {{ $product['title'] }}" id="user-photo">
                        @else
                            <img id="user-photo">
                        @endif
                        <a id="user-nick">{{ $product['user']['login']}}</a>
                    </div>
                    <div class="product-image">
                        <a href="{{ $product['url'] }}"><img src="{{ $product['photo']['thumbnails'][1]['url'] }}"
                                                             alt="{{ $product['brand_title'] }} {{ $product['title'] }}"></a>
                    </div>
                    <div class="product-details">
                        <div class="details-row">
                            <p class="price">{{ $product['price'] }} {{ $product['currency'] }}</p>
                            <div id="fav2">
                                <div id="fav" class="favorite-icon">
                                    <svg id="heart" viewBox="0 0 16 16" aria-label="Add to favourites">
                                        <path
                                            d="M11.73 1C9.58 1 8 2.74 8 2.74S6.42 1 4.27 1c-.7 0-1.48.2-2.28.7-2.92 1.88-2.47 5.86.34 8.5A83.4 83.4 0 0 0 8 15s3.04-2.32 5.67-4.8c2.81-2.64 3.26-6.62.34-8.5-.8-.5-1.57-.7-2.28-.7m0 1.5c.5 0 .98.15 1.47.47.77.5 1.2 1.2 1.29 2.07.12 1.33-.59 2.88-1.85 4.07A79.25 79.25 0 0 1 8 13.1a79.1 79.1 0 0 1-4.64-3.98C2.1 7.93 1.39 6.37 1.51 5.05c.09-.89.52-1.59 1.3-2.08.48-.32.96-.47 1.46-.47 1.44 0 2.62 1.25 2.62 1.25L8 4.97l1.1-1.22c.02-.01 1.21-1.25 2.63-1.25"></path>
                                    </svg>
                                </div>
                                <p class="favorites">{{ $product['favourite_count'] }}</p>
                            </div>
                        </div>

                        <div class="details-row2">
                            <p class="price-fee">{{ $product['total_item_price'] }} zł, w tym</p>
                            <div id="fav" class="favorite-icon">
                                <svg id="shield" viewBox="0 0 16 16">
                                    <path
                                        d="m7.34 8.3 3.56-3.58 1.07 1-4.62 4.67-2.89-2.82 1.06-1Zm7.33-5.9V8c0 5.6-6.67 8-6.67 8s-6.67-2.4-6.67-8V2.4L8 0Zm-1.5 1.05L8 1.59 2.83 3.45V8a6.31 6.31 0 0 0 2.76 4.92 13.09 13.09 0 0 0 2 1.28l.37.17.37-.17a13.09 13.09 0 0 0 2-1.28A6.31 6.31 0 0 0 13.17 8Z"></path>
                                </svg>
                            </div>
                        </div>

                        <p class="size"> {{ $product['size_title'] }}</p>
                        <p class="brand_title"> {{ $product['brand_title'] }}</p>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
        <div class="pagination">
            {{ $products->links() }}
        </div>
    </div>

</main>


</body>

<script>
    document.getElementById('sortowanie').addEventListener('change', function () {
        document.getElementById('sortForm').submit();
    });
    document.getElementById('search_text').addEventListener('keydown', function (event) {
        if (event.key === 'Enter') {
            event.preventDefault();
            document.getElementById('form-group').submit();
        }
    });
</script>
</html>
