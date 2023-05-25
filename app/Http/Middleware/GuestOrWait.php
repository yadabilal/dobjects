<?php

namespace App\Http\Middleware;

use App\User;
use Closure;
use Illuminate\Support\Facades\Auth;

class GuestOrWait
{
  /**
   * Handle an incoming request.
   *
   * @param  \Illuminate\Http\Request  $request
   * @param  \Closure  $next
   * @param  string|null  $guard
   * @return mixed
   */
  public function handle($request, Closure $next, $guard = null)
  {
    
    if (Auth::user() && Auth::user()->status == User::STATUS_COMPLETED) {
      return redirect('/');
    }
    
    return $next($request);
  }
}
