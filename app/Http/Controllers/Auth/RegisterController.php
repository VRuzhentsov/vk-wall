<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\AppBaseController;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Auth\Events\Registered;
use Laravel\Passport\Bridge\RefreshToken;

class RegisterController extends AppBaseController
{

    /*
    |--------------------------------------------------------------------------
    | Register Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users as well as their
    | validation and creation. By default this controller uses a trait to
    | provide this functionality without requiring any additional code.
    |
    */

    use RegistersUsers;

    /**
     * Where to redirect users after registration.
     *
     * @var string
     */
    protected $redirectTo = '/home';


    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        //$this->middleware('guest');
    }


    /**
     * Handle a registration request for the application.
     *
     * @param  \Illuminate\Http\Request $request
     *
     * @return JsonResponse|\Illuminate\Http\Response
     */
    public function register(Request $request)
    {
        $this->validator($request->all())->validate();

        event(new Registered($user = $this->create($request->all())));

        $token = $user->createToken(null);

        $refreshToken = new RefreshToken();

        $expireDateTime = $token->token->expires_at->getTimestamp();

        $jwtAccessToken = $token->accessToken;

        $responseParams = [
            'token_type'   => 'Bearer',
            'expires_in'   => $expireDateTime - (new \DateTime())->getTimestamp(),
            'access_token' => (string)$jwtAccessToken,
        ];

        //if ($refreshToken instanceof RefreshTokenEntityInterface) {
        //    $refreshToken = $this->encrypt(
        //        json_encode(
        //            [
        //                'client_id'        => $token->token->client_id,
        //                'refresh_token_id' => $refreshToken->getIdentifier(),
        //                'access_token_id'  => $token->token->id,
        //                'scopes'           => $token->token->scopes,
        //                'user_id'          => $user->id,
        //                'expire_time'      => $refreshToken->getExpiryDateTime()->getTimestamp(),
        //            ]
        //        )
        //    );
        //
        //    $responseParams['refresh_token'] = $refreshToken;
        //}

        return (new JsonResponse($responseParams, 200))->withHeaders([
            'pragma'        => 'no-cache',
            'cache-control' => 'no-store',
            'content-type'  => 'application/json; charset=UTF-8',
        ]);
    }


    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
        return Validator::make($data, [
            'name'     => 'required|max:255',
            'email'    => 'required|email|max:255|unique:users',
            'password' => 'required|min:6|confirmed',
        ]);
    }


    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array $data
     *
     * @return User|\Illuminate\Database\Eloquent\Model
     */
    protected function create(array $data)
    {
        return User::create([
            'name'     => $data['name'],
            'email'    => $data['email'],
            'password' => bcrypt($data['password']),
        ]);
    }
}
