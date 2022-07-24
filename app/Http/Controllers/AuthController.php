<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    private $validationRules = [];

    public function __construct()
    {
        $this->validationRules = [
            'email' => 'required|string|max:255',
            'password' => 'required|min:6'
        ];
    }
    public function login(Request $request)
    {
        $request->validate($this->validationRules);
     
        $user = User::where('email', $request->email)->first();
     
        if (! $user || ! Hash::check($request->password, $user->password)) {
            throw ValidationException::withMessages([
                'email' => ['E-mail ou senha errada.'],
            ]);
        }
        return $this->message("Login efectuada com sucesso", [
            'user' => $user,
            'token' =>  $user->createToken('token')->plainTextToken,
        ]);
    }
    public function register(Request $request)
    {
        $validated = $this->validate($request, array_merge($this->validationRules, ['name' => 'required']));
        $user = User::create(array_merge($validated, ['password' => Hash::make($request->password)]));

        return $this->message("Conta criada com sucesso", [
            'user' => $user,
            'token' =>  $user->createToken('token')->plainTextToken,
        ]);
    }
    public function message($message, $data, $status=true)
    {
        return [
            'message' => $message,
            'status' => $status,
            'data' => $data
        ];
    }
}
