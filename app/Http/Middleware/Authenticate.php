<?php

namespace App\Http\Middleware;

use Illuminate\Auth\Middleware\Authenticate as Middleware;

class Authenticate extends Middleware
{
    /**
     * Get the path the user should be redirected to when they are not authenticated.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return string
     */
    protected function redirectTo($request)
    {
        if (! $request->expectsJson()) {
            $checkout_in_progress = $request->get('alisveris');
            return $checkout_in_progress=='devam-et' ? url('giris-yap?alisveris=devam-et') : url('giris-yap');
        }
    }
}
