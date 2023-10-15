<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class VintedController extends Controller
{
    public function getProducts()
    {
        // Pobierz sesję cookie
        $cookie = $this->getCookie("https://www.vinted.fr/");

        // Wywołaj API Vinted z wykorzystaniem sesji cookie
        $response = $this->getWebPage("https://www.vinted.pl/api/v2/catalog/items?search_text=cash", '_vinted_fr_session=' . $cookie);

        // Przetwórz odpowiedź do formatu JSON
        $products = json_decode($response, true);

        // Przekazanie danych do widoku
        return view('products.index', compact('products'));
    }

    private function getCookie($url)
    {
        $options = array(
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_HEADER => true,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_ENCODING => "",
            CURLOPT_AUTOREFERER => true,
            CURLOPT_CONNECTTIMEOUT => 120,
            CURLOPT_TIMEOUT => 120,
            CURLOPT_MAXREDIRS => 10,
            CURLINFO_HEADER_OUT => true,
            CURLOPT_SSL_VERIFYPEER => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1
        );

        $ch = curl_init($url);
        curl_setopt_array($ch, $options);
        $rough_content = curl_exec($ch);
        $err = curl_errno($ch);
        curl_close($ch);

        $pattern = "#_vinted_fr_session=([a-zA-Z0-9]+)#";
        preg_match($pattern, $rough_content, $matches);

        if(isset($matches[1])){
            return $matches[1];
        } else {
            // Jeśli ciasteczko sesji nie zostało znalezione, zwróć pustą tablicę
            return [];
        }
    }

    private function getWebPage($url, $cookiesIn = [])
    {
        // Przekształć ciasteczka z tablicy na łańcuch znaków
        $cookiesString = http_build_query($cookiesIn, '', '; ');

        $options = array(
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_HEADER => true,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_ENCODING => "",
            CURLOPT_AUTOREFERER => true,
            CURLOPT_CONNECTTIMEOUT => 120,
            CURLOPT_TIMEOUT => 120,
            CURLOPT_MAXREDIRS => 10,
            CURLINFO_HEADER_OUT => true,
            CURLOPT_SSL_VERIFYPEER => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_COOKIE => $cookiesString, // Tutaj ustawiamy ciasteczka
        );

        // Utwórz opcje CURL
        $ch = curl_init($url);
        curl_setopt_array($ch, $options);

        // Wykonaj żądanie
        $rough_content = curl_exec($ch);
        $header_size = curl_getinfo($ch, CURLINFO_HEADER_SIZE);
        $header_content = substr($rough_content, 0, $header_size);
        $body_content = trim(str_replace($header_content, '', $rough_content));

        curl_close($ch);

        return $body_content;
    }
}
