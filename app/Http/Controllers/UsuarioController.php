<?php

namespace App\Http\Controllers;

use App\Models\Usuario;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class UsuarioController extends Controller
{
    public function register(Request $request)
    {
/* ==========================================================================================================
        IF CANNOT MODIFY RESPONSE, USE THIS...
        $content = ['message' => 'User created successfully.', 'token' => '1234'];
        $status = 200;
        $test = response()->json($content, $status);
============================================================================================================= */
        $response = new Response(['message' => 'Internal server error.'], 500);
        $token = $request->user()->createToken($request->token_name);
//        return ['token' => $token->plainTextToken];
//        $request->user()->currentAccessToken()->delete(); LOG OUT
        try {
            DB::beginTransaction();
            if (!$this->isValid($request)) {
                $response->original->status = 422;
                throw new Exception('Invalid user data input.');
            }
            $user = new Usuario($request->all());
            $user->password = bcrypt($request->password);
/* ==========================================================================================================
            IF ELOQUENT DOESNT WORK USE THIS...
            $newUser = new Usuario([
                'name' => $request->get('name'),
                'password' => bcrypt($request->get('password')),
                etc...
            ]);
============================================================================================================= */
            if ($user->save()) {
                $response->original->token = $user->createToken('token')->plainTextToken;
                $response->original->message = 'User created successfully.';
                $response->original->status = 201;
            } else {
                $response->original->status = 503;
                throw new Exception('External service unavailable.');
            }
            DB::commit();
        } catch (Exception $error) {
            DB::rollBack();
            $response->original->error = $error->getMessage();
        } finally {
            return Response::json($response);
        }
    }

    public function login(Request $request)
    {
        $response = new Response(['message' => 'Internal server error.'], 500);
        try {
            if (!$this->isValid($request)) {
                $response->original->status = 422;
                throw new Exception('Invalid user data input.');
            }
            $user = Usuario::where('email', $request->email)->first();
            if ($user && Hash::check($request->password, $user->password)) {
                $response->original->token = $user->createToken()->plainTextToken;
                $response->withHeaders(['location' => '/']);
                $response->original->message = 'User authenticated successfully.';
                $response->original->status = 302;
            } else {
                $response->original->message = 'Invalid credentials.';
                $response->original->status = 400;
            }
        } catch (Exception $error) {
            $response->original->error = $error->getMessage();
        } finally {
            return Response::json($response);
        }
    }

    public function logout(Request $request)
    {
        $response = new Response(['message' => 'Internal server error.'], 500);
        try {
            $user = Usuario::find($request->id);
            if ($user) {
                $user->tokens()->delete();
                $response->original->message = 'User logged out correctly.';
                $response->original->status = 404;
            } else {
                $response->original->message = 'User not found.';
                $response->original->status = 404;
            }
        } catch (Exception $error) {
            $response->original->error = $error->getMessage();
        } finally {
            return Response::json($response);
        }
    }

    private function isValid(Request $request): bool
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|regex:/^[A-Z][a-z]{1,9}\s[A-Z][a-z]{1,9}$/',
            'birth' => 'required|date_format:Y-m-d|before:'.Carbon::now()->subYears(12)->format('Y-m-d'),
            'username' => 'required|regex:/^[a-zA-Z]\w{4,11}$/|unique:App/Models/Usuario,Nick',
            'email' => 'required|email|unique:App/Models/Usuario,Email',
            'password' => 'required|regex:/^[A-Z]+[a-z A-Z]*$/', // can also use 'confirmed' if repeat_password is named password_confirmation
            'repeat_password' => 'required|same:password' // delete if 'confirmed' is being used above
        ]);
        return !$validator->stopOnFirstFailure()->fails();
    }
}
