<?php

namespace App\Traits\Jira;

use Exception;
use App\Traits\Jira\Environment;
use Illuminate\Support\Facades\Http;
use Illuminate\Http\Client\RequestException;

class HttpTrait
{
    public function get(string $path)
    {
        try {

            Environment::initialize();

            $url = Environment::$url;
            $user = Environment::$user;
            $token = Environment::$token;

            $url = "{$url}/{$path}";
            $response = Http::timeout(3600)
                ->retry(3, 3600)
                ->withHeaders([
                    'Accept' => "application/json",
                ])
                ->withBasicAuth($user, $token)
                ->get($url);

            return $response->json();
        } catch (RequestException $e) {
            return [
                'status'            => false,
                'error_messages'    => $e->response->json()['errorMessages'],
                'errors'            => $e->response->json()['errors'],
            ];
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
