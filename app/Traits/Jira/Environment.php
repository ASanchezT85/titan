<?php

namespace App\Traits\Jira;

use Exception;
use App\Models\Provider;
use Symfony\Component\HttpFoundation\Response;

class Environment
{
    /**
     * The default scope.
     *
     * @var string
     */
    public static $url;

    /**
     * The default scope.
     *
     * @var string
     */
    public static $user;

    /**
     * The default scope.
     *
     * @var string
     */
    public static $token;

    /**
     * Initializes the credential capture process
     */
    public static function initialize(): bool
    {
        try {

            $providerKeys = Provider::query()->select('provider_keys.*')->join('provider_keys', 'provider_keys.provider_id', 'providers.id')->where('slug', 'jira')->where('status', 'ON')->get();
            if ($providerKeys->count() <= 0)
                throw new Exception("Provider keys no found.", Response::HTTP_NOT_FOUND);

            $keys = [];
            foreach ($providerKeys as $value) {
                $keys[$value->key] = $value->value;
            }

            self::$url = $keys['URL'];
            self::$user = $keys['USER'];
            self::$token = $keys['TOKEN'];

            return true;
        } catch (Exception $e) {
            return false;
        }
    }
}
