<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;


class AuthController extends Controller
{
    // El frontend ens envia les dades per enregistrar un nou usuari i aqui es tracten
    public function register (Request $request)
    {
        //Validaciò de les dades rebudes
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],     
            'email' => ['required', 'email', 'max:255', 'unique:users,email'],  //Com tenim creada la base de dades es important que no es dupliquin els correus, afegim l'opcio unique per no tenir correus duplicats
            'password' => ['required', 'string', 'min:8'],                      // En la password posem la condiciò de que minim ha de ser de 8 caracters per major seguretat
        ]);
        $user = User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),    // Es genera un hash(metodologia d'encriptacio) per emmagatzemar la password
        ]);
        $user->rover()->create([                            // Creació del rover en el moment de crear el usuari nou
            'x' => 0,
            'y' => 0,
            'direction' => 'N',
        ]);

        $token = $user->createToken('api')->plainTextToken;     // Es genera el token t'autentificació per al usuari-

        return response()->json(['user'=> $user, 'token'=>$token], 201); //Retornem al forntend el usuari i el token amb un codi de que tot es correcte
    }
    // Funció de loggejarnos
    public function login(Request $request)
    {
        // En primner lloc es validen les dades introduides
        $data = $request->validate([
            'email' => ['required','email'],
            'password' => ['required','string'],
        ]);
        
        $user = User::where('email', $data['email'])->first();
        // Es valida la contrsenya introduida per l'usuari, en cas d'error directament retornem un error
        if (!$user || !Hash::check($data['password'], $user->password)) {
            throw ValidationException::withMessages([
                'email' => ['Credencials incorrectes.'],
            ]);
        }
        // Generem el token d'autentificació
        $token = $user->createToken('api')->plainTextToken;
        // Retornem el usuari i el token
        return response()->json(['user' => $user, 'token' => $token]);
    }

    // Funcio extra que ens servira qui som
    public function me(Request $request)
    {
        return response()->json(['user' => $request->user()]);
    }
    // En cas de voler fer un "logout", el usuari deixa de tenir el token d'autentificacio i s'elimina
    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();
        return response()->json(['message' => 'Logout correcte']);
    }

}
