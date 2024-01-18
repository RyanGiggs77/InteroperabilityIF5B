<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use App\Models\Post;

class AuthController extends Controller
{
/**
 * @OA\Post(
 *     path="/auth/register",
 *     summary="Register a new user",
 *     @OA\RequestBody(
 *         @OA\MediaType(
 *             mediaType="application/json",
 *             @OA\Schema(
 *                 @OA\Property(
 *                     property="name",
 *                     type="string"
 *                 ),
 *                 @OA\Property(
 *                     property="email",
 *                     type="string"
 *                 ),
 *                 @OA\Property(
 *                     property="password",
 *                     type="string"
 *                 ),
 *                 @OA\Property(
 *                     property="password_confirmation",
 *                     type="string"
 *                 ),
 *  *                 @OA\Property(
 *                     property="role",
 *                     type="admin"
 *                 ),
 *                 example={"name": "User Name", "email": "user@gmail.com", "password": "123456", "password_confirmation": "123456","role": "admin"}
 *             )
 *         )
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="OK"
 *     )
 * )
 */

    public function index()
    {
        return view('dashboard');
    }

    public function register(Request $request) 
    {
        $this->validate($request, [
            'name' => 'required|string',
            'email' => 'required|email|unique:users|string',
            'password' => 'required|confirmed'
        ]);

        $input = $request->all();

        $validationRules = [
            'name' => 'required|string',
            'email' => 'required|email|unique:users|string',
            'password' => 'required|confirmed',
            'role' => 'required|string'
        ];

        $validator = Validator::make($input, $validationRules);

        if($validator->fails()) {
            return response()->json($validator->errors(), 400);
        }

        $user = new User();
        $user->name = $request->input('name');
        $user->email = $request->input('email');
        $user->password = app('hash')->make($request->input('password'));
        $user->role = $request->input('role');
        $user->save();

        return response()->json($user, 200);
    }

    public function login(Request $request)
    {
        $input = $request->all();

        $validationRules = [
            'email' => 'required|email|string',
            'password' => 'required|string'
        ];

        $validator = Validator::make($input, $validationRules);

        if($validator->fails()) {
            return response()->json($validator->errors(), 400);
        }

        $credentials = $request->only(['email', 'password']);

        if(!$token = Auth::attempt($credentials)) {
            return response()->json(['message' => 'Unauthorized'], 401);
        }

        return response()->json([
            'token' => $token,
            'token_type' => 'bearer',
            'expires_in' => Auth::factory()->getTTL()*60
        ], 200);
    }
}
