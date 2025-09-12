<?php

namespace App\Http\Middleware;

use Illuminate\Http\Middleware\TrustProxies as Middleware;
use Illuminate\Http\Request;

class TrustProxies extends Middleware
{
    /**
     * Os proxies confiáveis para esta aplicação.
     *
     * @var array<int, string>|string|null
     */
    protected $proxies;

    /**
     * Os cabeçalhos que devem ser usados para detectar proxies.
     *
     * @var int
     */
    }
