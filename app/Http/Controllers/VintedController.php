<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class VintedController extends Controller
{
    public function searchProducts(Request $request)
    {
        $timeoutInSeconds = 120;
        $maxRedirects = 10;

        $cookie = $this->getCookie("https://www.vinted.fr/");

        $response = Http::withHeaders([
            'Cookie' => '_vinted_fr_session=' . $cookie,
        ])->timeout($timeoutInSeconds)->maxRedirects($maxRedirects)->get('https://www.vinted.pl/api/v2/catalog/items', [
            'search_text' => $request->input('search_text'),
        ]);

        $products = $response->json();

        $sortOption = $request->input('sort_by');

        // Sortowanie produktów
        if ($sortOption === 'price_asc') {
            usort($products['items'], function($a, $b) {
                return floatval($a['price']) - floatval($b['price']);
            });
        } elseif ($sortOption === 'price_desc') {
            usort($products['items'], function($a, $b) {
                return floatval($b['price']) - floatval($a['price']);
            });
        } elseif ($sortOption === 'favorite_asc') {
            usort($products['items'], function($a, $b) {
                return intval($a['favourite_count']) - intval($b['favourite_count']);
            });
        } elseif ($sortOption === 'favorite_desc') {
            usort($products['items'], function($a, $b) {
                return intval($b['favourite_count']) - intval($a['favourite_count']);
            });
        }

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
