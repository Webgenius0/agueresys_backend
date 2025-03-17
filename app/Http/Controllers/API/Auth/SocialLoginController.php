<?php

namespace App\Http\Controllers\API\Auth;

use App\Helpers\Helper;
use App\Http\Controllers\Controller;
use App\Models\User;
use App\Notifications\OrderNotification;
use App\Notifications\UserRegistrationNotification;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Laravel\Socialite\Facades\Socialite;

class SocialLoginController extends Controller
{
    public function RedirectToProvider($provider)
    {
        // if ($provider === 'instagram') {
        //     return Socialite::driver($provider)->scopes(['user_profile', 'user_media'])->redirect();
        // }
        return Socialite::driver($provider)->redirect();
    }


    public function HandleProviderCallback($provider)
    {
        // try {
        $socialUser = Socialite::driver($provider)->stateless()->user();
        dd($socialUser);
        // } catch (Exception $e) {
        //     return response()->json([
        //         'status'  => false,
        //         'message' => 'Unable to authenticate with ' . ucfirst($provider),
        //         'error'   => $e->getMessage()
        //     ], 500);
        // }
    }

    public function SocialLogin(Request $request)
    {
        $request->validate([
            'token'    => 'required',
            'provider' => 'required|in:google,facebook,apple,instagram',
            'role' => 'required|in:customer,contractor',
        ]);

        try {
            $provider   = $request->provider;
            $socialUser = Socialite::driver($provider)->stateless()->userFromToken($request->token);
            // return response()->json($socialUser);

            if ($socialUser) {
                $user = User::where('email', $socialUser->email)->first();
                if ($user && $request->role !== $user->role) {
                        switch ($user->role) {
                            case 'contractor':
                                return Helper::jsonErrorResponse('you are not authorized as a customer.', 401);
                            case 'customer':
                                return Helper::jsonErrorResponse('you are not authorized as a contractor.', 401);
                            default:
                                return Helper::jsonErrorResponse('invalid specified.', 401);
                        }
                }

                $isNewUser = false;

                if (!$user) {
                    $password = Str::random(16);
                    $user     = User::create([
                        'name'              => $socialUser->getName(),
                        'email'             => $socialUser->getEmail(),
                        'password'          => bcrypt($password),
                        'avatar'            => $socialUser->getAvatar(),
                        'role'              => $request->role,
                        'email_verified_at' => now(),
                    ]);
                    $isNewUser = true;
                }

                Auth::login($user);
                $token = auth('api')->login($user);

                return response()->json([
                    'status'     => true,
                    'message'    => 'User logged in successfully.',
                    'code'       => 200,
                    'token_type' => 'bearer',
                    'token'      => $token,
                    'expires_in' => auth('api')->factory()->getTTL() * 60,
                    'data' => $user
                ], 200);
            } else {
                return Helper::jsonResponse(false, 'Unauthorized', 401);
            }
        } catch (Exception $e) {
            return Helper::jsonResponse(false, 'Something went wrong', 500, ['error' => $e->getMessage()]);
        }
    }
}
