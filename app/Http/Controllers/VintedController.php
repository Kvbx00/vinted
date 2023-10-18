<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Http;

class VintedController extends Controller
{
    public function searchProducts()
    {
        $timeoutInSeconds = 120;
        $maxRedirects = 10;

        // Sprawdź, czy produkty są już zapisane w sesji
        if (!session()->has('products')) {
            $cookie = $this->getCookie("https://www.vinted.fr/");
            $response = Http::withHeaders([
                'Cookie' => '_vinted_fr_session=' . $cookie,
            ])->timeout($timeoutInSeconds)->maxRedirects($maxRedirects)->get('https://www.vinted.pl/api/v2/catalog/items');

            // Zapisz pobrane produkty w sesji, ale tylko jeśli udało się je pobrać
            if ($response->successful()) {
                session(['products' => $response->json()]);
            } else {
                // Jeśli pobieranie produktów nie powiodło się, obsłuż ten przypadek
                return view('error.view'); // Stwórz widok błędu
            }
        }

        // Pobierz produkty z sesji
        $products = session('products');

        // Sortowanie według preferencji użytkownika
        $sortowanie = request('sortowanie', 'default'); // Domyślnie sortuj wg. oryginalnego porządku

        if ($sortowanie === 'nazwa') {
            usort($products['items'], function ($a, $b) {
                return strcmp($a['brand_title'], $b['brand_title']);
            });
        } elseif ($sortowanie === 'cena') {
            usort($products['items'], function ($a, $b) {
                return $a['price'] - $b['price'];
            });
        }

        // Paginacja
        $produktyNaStronie = 10; // Określ ilość produktów na jednej stronie
        $currentPage = request('page', 1);
        $pagedProducts = array_slice($products['items'], ($currentPage - 1) * $produktyNaStronie, $produktyNaStronie);

        return view('products.index', [
            'products' => $pagedProducts,
            'currentPage' => $currentPage,
            'totalPages' => ceil(count($products['items']) / $produktyNaStronie),
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
