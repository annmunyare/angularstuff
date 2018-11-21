<?php

namespace App\Http\Middleware;

use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken as Middleware;

class VerifyCsrfToken extends Middleware
{
    /**
     * Indicates whether the XSRF-TOKEN cookie should be set on the response.
     *
     * @var bool
     */
    protected $addHttpCookie = true;

    /**
     * The URIs that should be excluded from CSRF verification.
     *
     * @var array
     */
    protected $except = [
        //
       'https://nanyukiafann-stuff.azurewebsites.net/api/v1/flows',
        'http://*',
        'http://example.com/foo/*',
        'https://nanyukiafann-stuff.azurewebsites.net/*',
        'http://localhost:3000',
        'api/*',
        '/wts',
        'https://powerful-hamlet-82891.herokuapp.com/*'
        
    ];
}
