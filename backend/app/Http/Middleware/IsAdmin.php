<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class IsAdmin
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        // Проверяем, что пользователь авторизован и имеет роль 'admin'
        if ($request->user() && $request->user()->role === 'admin') {
            return $next($request);  // Переходим к следующему шагу
        }

        // Если нет, возвращаем ошибку 401 Unauthorized
        return response()->json(['message' => 'Unauthorized'], 401);
    }
}
