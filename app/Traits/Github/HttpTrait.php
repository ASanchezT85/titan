<?php

namespace App\Traits\Github;

use Exception;
use App\Traits\Github\Environment;
use Illuminate\Support\Facades\Http;
use Symfony\Component\HttpFoundation\Response;

class HttpTrait
{
    public function get(string $path)
    {
        try {

            Environment::initialize();

            $url = Environment::$url;
            $token = Environment::$token;

            $url = "{$url}/{$path}";
            echo $url . PHP_EOL;

            $response = Http::timeout(3600)
                ->retry(3, 3600)
                ->withHeaders([
                    'Accept' => "application/vnd.github+json",
                ])
                ->withToken($token)
                ->get($url);

            return $response;
        } catch (Exception $e) {
            return false;
        }
    }

    // private static function post(string $path, $body)
    // {
    //     try {

    //         Environment::initialize();

    //         $dtone_url = Environment::$url;
    //         $dtone_key = Environment::$key;
    //         $dtone_secret = Environment::$secret;

    //         $url = "{$dtone_url}{$path}";
    //         $response = Http::withBasicAuth($dtone_key, $dtone_secret)->post($url, $body);

    //         if ($response->status() == Response::HTTP_UNAUTHORIZED) {
    //             throw new Exception("Unauthorized: Credentials missing or invalid", Response::HTTP_UNAUTHORIZED);
    //         }

    //         if ($response->serverError()) {
    //             throw new Exception("Server Error (cashship)", Response::HTTP_INTERNAL_SERVER_ERROR);
    //         }

    //         return $response->json();
    //     } catch (Exception $e) {
    //         return [
    //             'status'    => false,
    //             'message'   => $e->getMessage(),
    //         ];
    //     }
    // }
}
