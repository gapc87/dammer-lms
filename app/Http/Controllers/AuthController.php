<?php

namespace App\Http\Controllers;

use DB;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    protected $login_error;

    public function __construct()
    {
        $this->login_error = response()->json([
            'message' => 'Email o contraseña incorrectos',
            'status' => 422
        ], 422);
    }

    /**
     * Registro de usuario
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function register()
    {
        User::create([
            'name' => request('name'),
            'email' => request('email'),
            'password' => bcrypt(request('password'))
        ]);

        return response()->json(['status' => 201]);
    }

    /**
     * Iniciar sesión
     *
     * @return mixed
     * @throws \Exception
     */
    public function login()
    {
        if( ! $user = $this->validateUserLogin())
        {
            return $this->login_error;
        }

        if ( ! $data = $this->accessToken($user))
        {
            return $this->login_error;
        }

        return response()->json([
            'token' => $data->access_token,
            'user' => $user,
            'status' => 200
        ]);
    }

    /**
     * Cerrar sesión
     *
     * @return string
     */
    public function logout()
    {
        $accessToken = auth()->user()->token();

        $refreshToken = DB::table('oauth_refresh_tokens')
            ->where('access_token_id', $accessToken->id)
            ->update([
                'revoked' => true
            ]);

        $accessToken->revoke();

        return response()->json(['status' => 200]);
    }


    /**
     * @return User|bool|\Illuminate\Http\JsonResponse
     */
    protected function validateUserLogin()
    {
        $user = User::whereEmail(request('email'))->first();

        return !(!$user || !Hash::check(request('password'), $user->password)) ? $user : false;
    }

    /**
     * @param $user
     * @return bool|\Illuminate\Http\JsonResponse|mixed
     */
    protected function accessToken($user)
    {
        // Send an internal API request to get an access token
        $client = DB::table('oauth_clients')
            ->where('password_client', true)
            ->first();

        // Make sure a Password Client exists in the DB
        if (!$client) {
            return response()->json([
                'message' => 'Laravel Passport no está configurado correctamente',
                'status' => 500
            ], 500);
        }

        $data = [
            'grant_type' => 'password',
            'client_id' => $client->id,
            'client_secret' => $client->secret,
            'username' => request('email'),
            'password' => request('password'),
        ];

        $request = Request::create('/oauth/token', 'POST', $data);

        $response = app()->handle($request);

        return ($response->getStatusCode() === 200) ? json_decode($response->getContent()) : false;
    }
}
