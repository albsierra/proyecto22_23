<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

use App\Http\Resources\ArtworkResource;

class ArtworkController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // Inicialmente, vamos a utilizar un autor constante
        $author = 'Pedro+Cano';
        // La key la cogeremos de las variables de entorno
        $key = env("API_KEY");
        $urlEuropeanaAPI = "https://api.europeana.eu/record/v2/search.json?";
        $queryString = "wskey=$key&query=who:\"$author\"";
        $urlConsulta = $urlEuropeanaAPI . $queryString;
        // Consultamos a la API
        $response = Http::get($urlConsulta);
        return ArtworkResource::collection($response->collect()->toArray()['items']);
    }
}
