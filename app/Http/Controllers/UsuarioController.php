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
        $content = ['message' => 'Internal server error.'];
        $status = 500;
        try {
            DB::beginTransaction();
//            if (!$this->isValid($request)) {
//                $status = 422;
//                throw new Exception('Invalid user data input.');
//            }
            $user = $this->fillModel(new Usuario(), $request);
/* ==========================================================================================================
            IF ELOQUENT DOESNT WORK USE THIS... $request->all()
            $newUser = new Usuario([
                'name' => $request->get('name'),
                'password' => bcrypt($request->get('password')),
                etc...
            ]);
============================================================================================================= */
            if ($user->save()) {
                $content += ['token' => $user->createToken('token')->plainTextToken];
                $content->message = 'User created successfully.';
                $status = 201;
            } else {
                $status = 503;
                throw new Exception('External service unavailable.');
            }
            DB::commit();
        } catch (Exception $error) {
            DB::rollBack();
            $content += ['error' => $error->getMessage()];
        } finally {
            return response()->json($content, $status);
        }
    }

    public function login(Request $request)
    {
        $content = ['message' => 'Internal server error.'];
        $status = 500;
        try {
            if (!$this->isValid($request)) {
                $status = 422;
                throw new Exception('Invalid user data input.');
            }
            $user = Usuario::where('email', $request->email)->first();
            if ($user && Hash::check($request->password, $user->password)) {
                $content += ['token' => $user->createToken('token')->plainTextToken];
                $content += ['location' => '/'];
                $content->message = 'User authenticated successfully.';
                $status = 302;
            } else {
                $content->message = 'Invalid credentials.';
                $status = 400;
            }
        } catch (Exception $error) {
            $content += ['error' => $error->getMessage()];
        } finally {
            return response()->json($content, $status);
        }
    }

    public function logout(Request $request)
    {
        $content = ['message' => 'Internal server error.'];
        $status = 500;
        try {
            $user = Usuario::find($request->id);
            if ($user) {
                $user->tokens()->delete();
                $content->message = 'User logged out correctly.';
                $status = 404;
            } else {
                $content->message = 'User not found.';
                $status = 404;
            }
        } catch (Exception $error) {
            $content += ['error' => $error->getMessage()];
        } finally {
            return response()->json($content, $status);
        }
    }

    private function fillModel(Usuario $user, Request $request): Usuario
    {
        $user->Nombre = $request->name;
        $user->Nick = $request->username;
        $user->Email = $request->email;
        $user->Password = bcrypt($request->password);
        $user->FechaNacimiento = Carbon::parse($request->birthdate);
        return $user;
    }

    private function isValid(Request $request): bool
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|regex:/^[A-Z][a-z]{1,9}\s[A-Z][a-z]{1,9}$/',
            'birthdate' => 'required|date_format:Y-m-d|before:'.Carbon::now()->subYears(12)->format('Y-m-d'),
            'username' => 'required|regex:/^[a-zA-Z]\w{4,11}$/|unique:App/Models/Usuario,Nick',
            'email' => 'required|email|unique:App/Models/Usuario,Email',
            'password' => 'required|regex:/^[A-Z]+[a-zA-Z]*$/|confirmed'
        ]);
        return !$validator->stopOnFirstFailure()->fails();
    }
}
