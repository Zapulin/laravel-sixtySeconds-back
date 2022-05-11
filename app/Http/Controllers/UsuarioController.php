<?php

namespace App\Http\Controllers;

use App\Models\Usuario;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UsuarioController extends Controller
{
    public function register(Request $request)
    {
        $content = ['message' => 'Internal server error.'];
        $status = 500;
        try {
            $underAge = 12;
            $request->validate([
                'name' => 'required|regex:/^[A-Z][a-z]{1,9}\s[A-Z][a-z]{1,9}$/',
                'birthdate' => 'required|date_format:d-m-Y|before:'.Carbon::now()->subYears($underAge)->format('Y-m-d'),
                'username' => 'required|regex:/^[a-zA-Z]\w{4,11}$/|unique:App\Models\Usuario,Nick',
                'email' => 'required|email|unique:App\Models\Usuario,Email',
                'password' => 'required|regex:/^[a-zA-Z0-9]\w{4,9}$/|confirmed'
            ]);
            $user = $this->fillModel(new Usuario(), $request);
            DB::beginTransaction();
            if ($user->save()) {
                $content += ['token' => $user->createToken('token')->plainTextToken];
                $content['message'] = 'User created successfully.';
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
            $request->validate([
//                'username' => 'regex:/^[a-zA-Z]\w{4,11}$/',
                'email' => 'required|email',
                'password' => 'required|regex:/^[a-zA-Z0-9]\w{4,9}$/'
            ]);
            $user = Usuario::where('Email', $request->email)->first();
            if ($user && Hash::check($request->password, $user->Password)) {
                $content += ['token' => $user->createToken('token')->plainTextToken];
                $content += ['location' => '/'];
                $content['message'] = 'User authenticated successfully.';
                $status = 303;
            } else {
                $content['message'] = 'Invalid credentials.';
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
            $request->user()->tokens()->delete();
            $content['message'] = 'User logged out correctly.';
            $status = 200;
        } catch (Exception $error) {
            $content += ['error' => $error->getMessage()];
        } finally {
            return response()->json($content, $status);
        }
    }

    public function myProfile(Request $request)
    {
        $content = ['message' => 'Internal server error.'];
        $status = 500;
        try {
            $content += ['data' => $request->user()];
            $content['message'] = 'User profile fetched successfully.';
            $status = 200;
        } catch (Exception $error) {
            $content += ['error' => $error->getMessage()];
        } finally {
            return response()->json($content, $status);
        }
    }

    public function profile(Request $request)
    {
        $content = ['message' => 'Internal server error.'];
        $status = 500;
        try {
            $user = Usuario::find($request->id);
            if ($user) {
                $content += ['data' => $user];
                $content['message'] = 'User profile fetched successfully.';
                $status = 200;
            } else {
                $content['message'] = 'User profile not found.';
                $status = 404;
            }
        } catch (Exception $error) {
            $content += ['error' => $error->getMessage()];
        } finally {
            return response()->json($content, $status);
        }
    }

    public function editProfile(Request $request)
    {
        $content = ['message' => 'Internal server error.'];
        $status = 500;
        try {
            $user = $request->user();
            DB::beginTransaction();
            $underAge = 12;
            $request->validate([
                'name' => 'regex:/^[A-Z][a-z]{1,9}\s[A-Z][a-z]{1,9}$/',
                'birthdate' => 'date_format:d-m-Y|before:'.Carbon::now()->subYears($underAge)->format('d-m-Y'),
                'password' => 'confirmed|regex:/^[a-zA-Z0-9]\w{4,9}$/'
            ]);
            if (isset($request->name)) $user->Nombre = $request->name;
            if (isset($request->birthdate)) $user->FechaNacimiento = Carbon::parse($request->birthdate);
            if (isset($request->password)) $user->Password = bcrypt($request->password);
            if ($user->save()) {
                $content += ['data' => $user];
                $content['message'] = 'User profile edited successfully.';
                $status = 200;
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

    public function destroyProfile(Request $request)
    {
        $content = ['message' => 'Internal server error.'];
        $status = 500;
        try {
            DB::beginTransaction();
            $user = $request->user();
            $user->delete();
            $content['message'] = 'User erased successfully.';
            $status = 200;
            DB::commit();
        } catch (Exception $error) {
            DB::rollBack();
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
}
