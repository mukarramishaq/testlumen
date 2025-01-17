<?php
/**
 * this file contains AuthController Class only
 * @author Mukarram Ishaq <mukarramishaq189@gmail.com>
 */

namespace App\Http\Controllers\Auth;

use App\User;
use Firebase\JWT\JWT;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Laravel\Lumen\Routing\Controller;

/**
 * AuthController class controlls the routes relating to login and register only
 * @author Mukarram Ishaq <mukarramishaq189@gmail.com>
 */
class AuthController extends Controller
{
    /**
     * $request variable will contain the instance of lluminate\Http\Request only
     *
     * @var lluminate\Http\Request
     */
    private $request;

    /**
     * generate a jwt token based on given \App\User object
     *
     * @param User $user
     * @return string;
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
     * This parameteric onstructor requires you to give an 
     * instance of lluminate\Http\Request to instantiate this class
     *
     * @param Request $request
     */
    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    /**
     * authenticates user and gives a json response.
     * 
     * request validation is also done here. So, on encountering
     * malformed request, it would give you error in response
     * 
     * The request should contain the email and password in its body
     *
     * @param void
     * @return lluminate\Http\Response
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
     * request validation is also done here. So, on encountering
     * malformed request, it would give you error in response
     * 
     * The request should contain the name, email and password in its body
     *
     * @param void
     * @return lluminate\Http\Response
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
