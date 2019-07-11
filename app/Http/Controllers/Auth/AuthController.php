<?php
namespace App\Http\Controllers\Auth;

use App\User;
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
     * @param User $user
     * @return Illuminate\Http\Response;
     */
    protected function jwt(User $user)
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
     * @param void
     * @return \Response
     */
    public function login()
    {
        $this->validate($this->request, [
            'email' => 'required|email',
            'password' => 'required',
        ]);
        $user = User::where('email', $this->request->input('email'))->first(); // Find the user by email
        if (!$user) {
            return response()->json([
                'responseType' => 'error',
                'error' => 'User does not exist',
            ], 400);
        }

        // Verify the password and generate the token
        if (Hash::check($this->request->input('password'), $user->password)) {
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

    /**
     * creates user and gives a json response
     *
     * @param void
     * @return \Response
     */
    public function register()
    {
        $this->validate($this->request, [
            'email' => 'required|string|email',
            'password' => 'required|string',
            'name' => 'required|string'
        ]);
        try {
            $data = $this->request->only(['email', 'password', 'name']);
            $data['password'] = Hash::make($data['password']);
            $user = User::create($data);
            return response()->json([
                'responseType' => 'success',
                'status' => 200,
                'token' => $this->jwt($user), // return token
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'responseType' => 'error',
                'error' => $e->getMessage(),
            ], 400);
        }
    }
}
