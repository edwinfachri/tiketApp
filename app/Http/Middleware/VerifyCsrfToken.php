<?php

namespace App\Http\Middleware;

use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken as Middleware;

class VerifyCsrfToken extends Middleware
{
    /**
     * The URIs that should be excluded from CSRF verification.
     *
     * @var array
     */
    protected $except = [
        '/event/create',
        '/location/create',
        '/event/ticket/create',
        '/event/get_info',
        '/transaction/purchase',
        '/transaction/get_info',
        '/customer/create'
    ];
}
