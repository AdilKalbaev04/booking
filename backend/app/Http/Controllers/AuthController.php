<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;
use Pusher\Pusher;

class AuthController extends Controller
{

    public function showRegisterForm()
    {
        return view('auth.register');
    }
    public function register(Request $request)
    {
        $request->validate([
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:6',
        ]);

        $user = User::create([
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        $token = $user->createToken('auth_token')->plainTextToken;

        // Возвращаем JSON-ответ с токеном и кукой
        return response()->json(['message' => 'Registration successful'])->cookie('token', $token, 60*24*30);
    }

    public function login(Request $request)
{
    $request->validate([
        'email' => 'required|email',
        'password' => 'required',
    ]);

    // Проверка аутентификации пользователя
    if (!Auth::attempt($request->only('email', 'password'))) {
        return response()->json(['message' => 'Invalid credentials'], 401);
    }

    $user = Auth::user();

    // Создание токена
    $token = $user->createToken('auth_token')->plainTextToken;

    // Возвращаем JSON-ответ с сообщением и токеном
    return response()->json([
        'message' => 'Login successful',
        'token' => $token
    ])->cookie('token', $token, 60*24*30);

}




    public function showLoginForm()
    {
        return view('auth.login');
    }



    public function logout(Request $request)
    {
        if ($request->user()) {
            $request->user()->tokens()->delete();
        }

        return response()->json(['message' => 'Вы успешно вышли'], 200)->cookie('token', '', -1)->cookie('laravel_session', '', -1);
    }

    public function pusherAuth(Request $request)
    {
        if (Auth::check()) {
            $pusher = new Pusher(
                env('PUSHER_APP_KEY'),
                env('PUSHER_APP_SECRET'),
                env('PUSHER_APP_ID'),
                [
                    'cluster' => env('PUSHER_APP_CLUSTER'),
                    'useTLS' => true,
                ]
            );

            return $pusher->socket_auth($request->channel_name, $request->socket_id);
        } else {
            return response('Forbidden', 403);
        }
    }


}

