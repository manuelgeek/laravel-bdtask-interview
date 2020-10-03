<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Services\SocialAccountsService;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Laravel\Socialite\Facades\Socialite;

class AuthController extends Controller
{
    protected $providers = [
        'google',
        'facebook'
    ];
    /**
     * @param  Request  $request
     * @param $provider
     * @return \Illuminate\Http\JsonResponse
     */
    public function socialLogin(Request $request, $provider)
    {
        if( ! $this->isProviderAllowed($provider) ) {
            return response()->json([
                'message' => "{$provider} is not currently supported"
            ], 403);
        }
        $request->validate([
            'access_token' => 'required|string',
            'user_type' => ['required', 'string', Rule::in(['B', 'C', 'D', 'E', 'F'])],
        ]);

        $providerUser = null;
        try {
            $providerUser = Socialite::driver($provider)->userFromToken($request->access_token);
        } catch (\Exception $exception) {
            if($exception->getCode() === 401){
                return response()->json([
                    'message' => 'Invalid Access Token'
                ], 401);
            }
            return response()->json([
                'message' => $exception->getMessage()
            ], 500);
        }

        if ($providerUser) {
            $user =  (new SocialAccountsService())->findOrCreate($providerUser, $provider, $request->user_type);
            if($user){
                return response()->json([
                    'token' => $user->createToken(env('APP_NAME'))->plainTextToken,
                    'user' => $user
                ]);
            }
        }

        return response()->json([
            'message' => 'Ann error occurred ! Try again'
        ], 500);
    }

    public function logout()
    {
        $user = auth()->user();
        $user->currentAccessToken()->delete();
        return response()->json([
            'message' => 'Logged out!'
        ]);
    }

    private function isProviderAllowed($driver)
    {
        return in_array($driver, $this->providers) && config()->has("services.{$driver}");
    }
}
