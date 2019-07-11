<?php
namespace App\Http\Controllers\Auth;

use App\Models\Users;
use Firebase\JWT\JWT;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Laravel\Lumen\Routing\Controller;

class AuthController extends Controller
{
    private $request;

    /**
     * generate a jwt token based on given \App\User object
     *
     * @param Users $user
     * @return Illuminate\Http\Response;
     */
    protected function jwt(Users $user)
    {
        $payload = [
            'iss' => "lumen-jwt", // Issuer of the token
            'sub' => $user->id, // Subject of the token
            'iat' => time(), // Time when JWT was issued.
            'exp' => time() + 60 * 60, // Expiration time
        ];
        return JWT::encode($payload, env('JWT_SECRET'));
    }

    /**
     * constructor
     *
     * @param Request $request
     */
    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    /**
     * authenticates user and gives a json response
     *
     * @param Users $user
     * @return \Response
     */
    public function login(Users $user)
    {
        $this->validate($this->request, [
            'email' => 'required|email',
            'password' => 'required',
        ]);
        $user = Users::where('email', $this->request->json('email'))->first(); // Find the user by email
        if (!$user) {
            return response()->json([
                'responseType' => 'error',
                'error' => 'User does not exist',
            ], 400);
        }

        // Verify the password and generate the token
        if (Hash::check($this->request->json('password'), $user->password)) {
            return response()->json([
                'responseType' => 'success',
                'status' => 200,
                'token' => $this->jwt($user), // return token
            ], 200);
        }
        return response()->json([
            'responseType' => 'error',
            'error' => 'Invalid Credentials',
        ], 400);
    }
}
