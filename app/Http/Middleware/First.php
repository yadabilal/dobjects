<?php

namespace App\Http\Middleware;

use App\User;
use Closure;
use Illuminate\Support\Facades\Auth;

class First
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
    
    if (@Auth::user()->status == User::STATUS_STEP_SECOND) {
      return redirect('kayit-ol/telefon-onayla');
    }else if(@Auth::user()->status == User::STATUS_STEP_THIRD) {
      return redirect('kayit-ol/kullanici-adi-belirle');
    }
    
    return $next($request);
  }
}
