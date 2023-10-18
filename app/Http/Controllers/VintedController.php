<?php

namespace App\Http\Controllers;

use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Session;

class VintedController extends Controller
{
    public function searchProducts()
    {
        $timeoutInSeconds = 120;
        $maxRedirects = 10;
        $perPage = 192;

        $cookie = $this->getCookie("https://www.vinted.fr/");

        // Sprawdź, czy dane są już w sesji
        if (!Session::has('produkty')) {
            $response = Http::withHeaders([
                'Cookie' => '_vinted_fr_session=' . $cookie,
            ])->timeout($timeoutInSeconds)->maxRedirects($maxRedirects)->get('https://www.vinted.pl/api/v2/catalog/items', [
                'per_page' => $perPage,
            ]);

            $products = $response->json();

            Session::put('produkty', $products);
        } else {
            $products = Session::get('produkty');
        }

        // Sortowanie i paginacja
        $sortowanie = request()->input('sortowanie', 'default'); // Domyślne sortowanie
        if ($sortowanie === 'cena') {
            // Sortuj produkty według ceny
            $products['items'] = collect($products['items'])->sortBy('price')->values()->all();
        } elseif ($sortowanie === 'nazwa') {
            // Sortuj produkty według nazwy
            $products['items'] = collect($products['items'])->sortBy('brand_title')->values()->all();
        }elseif ($sortowanie === 'serduszka') {
            $products['items'] = collect($products['items'])->sortBy('favourite_count')->values()->all();
        }

        $perPage = 24;
        $currentPage = request()->input('page', 1);

        $currentPageItems = array_slice($products['items'], ($currentPage - 1) * $perPage, $perPage);
        $products = new LengthAwarePaginator($currentPageItems, count($products['items']), $perPage, $currentPage, [
            'path' => Paginator::resolveCurrentPath(),
            'pageName' => 'page',
        ]);

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
