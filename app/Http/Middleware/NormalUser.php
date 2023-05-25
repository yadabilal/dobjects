<?php

namespace App\Http\Middleware;

use App\User;
use Closure;
use Illuminate\Support\Facades\Auth;

class NormalUser
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
    if (auth()->user() && auth()->user()->type == User::TYPE_ADMIN) {
      return redirect(route('admin.user.index'));
    }
    
    return $next($request);
  }
}
