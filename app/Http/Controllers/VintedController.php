<?php

namespace App\Http\Controllers;

use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Pagination\Paginator;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Session;

class VintedController extends Controller
{
    public function searchProducts(Request $request)
    {
        $perPage = 216;
        $cookie = $this->getCookie("https://www.vinted.fr/");

        $searchText = $request->input('search_text');

        if (!empty($searchText)) {
            $response = Http::withHeaders([
                'Cookie' => '_vinted_fr_session=' . $cookie,
            ])->get('https://www.vinted.pl/api/v2/catalog/items', [
                'search_text' => $searchText,
                'per_page' => $perPage,
            ]);

            $products = $response->json();

            Session::put('produkty', $products);

            return redirect()->route('search.products');
        }

        $products = Session::get('produkty', []);

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

    public function sortProducts()
    {
        $products = Session::get('produkty', []);

        if (empty($products)) {
            return view('products.index');
        }

        $sort = request()->input('sort_by', 'default');
        Session::put('sort_by', $sort);

        $products['items'] = collect($products['items'])
            ->when($sort === 'cena', fn($collection) => $collection->sortByDesc('price'))
            ->when($sort === 'serduszka', fn($collection) => $collection->sortByDesc('favourite_count'))
            ->values()
            ->all();

        $perPage = 24;
        $currentPage = request()->input('page', 1);

        $currentPageItems = array_slice($products['items'], ($currentPage - 1) * $perPage, $perPage);
        $products = new LengthAwarePaginator($currentPageItems, count($products['items']), $perPage, $currentPage, [
            'path' => Paginator::resolveCurrentPath(),
            'pageName' => 'page',
            'query' => ['sort_by' => $sort],
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
