<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\User;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{

    /**
     * Obtener personas por nombre y porcentaje de coincidencia
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function getToken(Request $request): Response
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required',
            'password' => 'required'
        ]);
        if($validator->fails()){
            return response($validator->messages(), Response::HTTP_UNAUTHORIZED);
        }

        try{
            // Obtener app url
            $hostname = env("APP_URL");

            // Consultar usuario por email
            $user = User::whereEmail($request->email)->first();
            if(!isset($user)){
                return response([
                    "message" => "El usuario ingresado no esta registrado."
                ], Response::HTTP_UNAUTHORIZED);
            }

            // Obtener token de autenticación
            $response = Http::asForm()->post($hostname . '/oauth/token', [
                'client_id' => env("PASSWORD_CLIENT_ID"),
                'client_secret' => env("PASSWORD_CLIENT_SECRET"),
                'grant_type' => 'password',
                'username' => $request->email,
                'password' => $request->password,
                'scope' => ''
            ]);
            if($response->status() != Response::HTTP_OK){
                return response([
                    "message" => "Credenciales invalidas."
                ], Response::HTTP_UNAUTHORIZED);
            }

            return response($response->json(),Response::HTTP_OK);
        }catch (Exception $e){
            return response([
                "message" => "Credenciales invalidas."
            ], Response::HTTP_UNAUTHORIZED);
        }
    }

}
