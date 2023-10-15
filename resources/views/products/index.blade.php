<!-- resources/views/products.blade.php -->
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Products</title>
</head>
<body>
<h1>Products</h1>
<ul>
        @foreach($products as $product)
            <li>{{ $product['name'] }}</li>
            <!-- Dodaj więcej pól produktu, jeśli są dostępne w odpowiedzi API -->
        @endforeach
</ul>
</body>
</html>
