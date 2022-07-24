<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class UserController extends Controller
{
    private $validationRules = [];
    
    public function __construct()
    {
        $this->validationRules = [
            'name' => 'required|min:3|string|max:255',
            'email' => 'required|string|max:255',
            'password' => 'required|string|min:6|max:255'
        ];
    }

    public function index(Request $request)
    {
        $data = (new User)->newQuery();
        $data = $request->page? $data->paginate(10) : $data->get();
        return $this->message("Operação realizado com sucesso", $data);
    }
    public function create(Request $request)
    {
        $validated = $this->validate($request, array_merge($this->validationRules, ['name' => 'required']));
        $user = User::create(array_merge($validated, ['password' => Hash::make($request->password)]));
        return $this->message("Operação realizado com sucesso", $user);
    }
    public function show(Request $request, $id)
    {
        $user = User::findOrFail($id);
        return $this->message("Operação realizado com sucesso", $user);
     
    }
    public function update(Request $request, $id)
    {
        $user = User::findOrFail($id);
        $validated = $this->validate($request, array_merge($this->validationRules, ['name' => 'required']));
        $user = $user->fill(array_merge($validated, ['password' => Hash::make($request->password)]));
        if ($user->save()){
            return $this->message("Operação realizado com sucesso", $user);
        }
        return $this->message("Erro ao realizar a operação", [], false);
    }
    public function destroy(Request $request, $id)
    {
        $user = User::findOrFail($id);
        if ($user->delete()){
            return $this->message("Operação realizado com sucesso", []);
        }
        return $this->message("Erro ao realizar a operação", [], false);
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
