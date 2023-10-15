<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use GuzzleHttp\Client;

class VintedController extends Controller
{
    public function searchProducts(Request $request)
    {
        $client = new Client();

        $response = $client->request('GET', 'https://www.vinted.pl/api/v2/catalog/items', [
            'query' => [
                'search_text' => $request->input('search_text'),
            ],
        ]);

        $products = json_decode($response->getBody(), true);

        return view('products.index', [
            'products' => $products,
        ]);
    }
}
