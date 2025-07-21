<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Authentication Defaults
    |--------------------------------------------------------------------------
    |
    | This option controls the default authentication "guard" and password
    | reset options for your application. You may change these defaults
    | as required, but they're a perfect start for most applications.
    |
    */

    'defaults' => [
        'guard' => env('AUTH_GUARD', 'web'),
        'passwords' => env('AUTH_PASSWORD_BROKER', 'admin'),
    ],

    /*
    |--------------------------------------------------------------------------
    | Authentication Guards
    |--------------------------------------------------------------------------
    |
    | Here you may define every authentication guard for your application.
    | A great default configuration has been defined for you here which
    | uses session storage and the Eloquent user provider.
    |
    | All authentication drivers have a user provider. This defines how the
    | users are retrieved out of your database or other storage mechanisms.
    |
    | Supported: "session"
    |
    */

    'guards' => [
        'web' => [
            'driver' => 'session',
            'provider' => 'admin',
        ],

        'admin' => [
            'driver' => 'session',
            'provider' => 'admin',
        ],

        'api_token' => [
            'driver' => 'token',
            'provider' => 'api_tokens',
            'input_key' => 'api_token',       // the request input key to check
            'storage_key' => 'token',         // the database column to match
            'hash' => true,                  // set true if you store hashed tokens
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | User Providers
    |--------------------------------------------------------------------------
    |
    | All authentication drivers have a user provider. This defines how the
    | users are retrieved from your database or other storage mechanisms.
    |
    | If you have multiple user tables or models you may configure multiple
    | sources which represent each model/table. These sources can then
    | be assigned to any extra authentication guards you have defined.
    |
    | Supported: "database", "eloquent"
    |
    */

    'providers' => [
        'admin' => [
            'driver' => 'eloquent',
            'model' => env('AUTH_MODEL', App\Models\Admin::class),
        ],

        'api_tokens' => [
            'driver' => 'eloquent',
            'model' => App\Models\ApiToken::class,
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Resetting Passwords
    |--------------------------------------------------------------------------
    |
    | Here you may define the password reset configurations for your app.
    | The expire time is in minutes, which defines how long the reset token
    | will be considered valid.
    |
    */

    'passwords' => [
        'admin' => [
            'provider' => 'admin',
            'table' => env('AUTH_PASSWORD_RESET_TOKEN_TABLE', 'password_reset_tokens'),
            'expire' => 60,
            'throttle' => 60,
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Password Confirmation Timeout
    |--------------------------------------------------------------------------
    |
    | Defines the number of seconds before a password confirmation times out.
    | Defaults to 3 hours.
    |
    */

    'password_timeout' => env('AUTH_PASSWORD_TIMEOUT', 10800),

];
