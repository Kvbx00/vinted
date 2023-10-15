<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class VintedController extends Controller
{
    public function searchProducts(Request $request)
    {
        $cookie = $this->getCookie("https://www.vinted.fr/");

        $response = Http::withHeaders([
            'Cookie' => '_vinted_fr_session=' . $cookie,
        ])->get('https://www.vinted.pl/api/v2/catalog/items', [
            'search_text' => $request->input('search_text'),
        ]);

        $products = $response->json();

        return view('products.index', [
            'products' => $products,
        ]);
    }

    private function getCookie($url)
    {
        $response = Http::get($url);
        $cookies = $response->cookies();

        $vintedCookie = null;
        foreach ($cookies as $cookie) {
            if ($cookie->getName() === '_vinted_fr_session') {
                $vintedCookie = $cookie->getValue();
                break;
            }
        }

        return $vintedCookie;
    }
}
