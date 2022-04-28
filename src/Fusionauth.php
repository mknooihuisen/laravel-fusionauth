<?php

namespace Mknooihuisen\LaravelFusionauth;

use App\Models\User;
use Couchbase\KeyExistsException;
use FusionAuth\FusionAuthClient;
use Illuminate\Http\Request;

use Illuminate\Validation\Rules\Password;
use Illuminate\Validation\UnauthorizedException;
use Mknooihuisen\LaravelFusionauth\APIResponses\Application;
use Mknooihuisen\LaravelFusionauth\APIResponses\FusionAuthUser;

class Fusionauth
{
    public string|null $app_id;
    private FusionAuthClient $client;


    public function __construct()
    {
        $key = config('fusionauth.api_key', null);
        $url = config('fusionauth.base_url', null);
        $this->app_id = config('fusionauth.app_id', null);

        //Do we have legit keys?
        if ($key === null || $url === null || $this->app_id === null) {
            throw new \Exception("One or more required FusionAuth Config variable is null");
        }

        $this->client = new FusionAuthClient($key, $url);
    }
k
    {
        return $this->client;
    }

    public function registerUser (Request $request, $autologin = true) : bool
    {
        //does the user exist?
        try {
            $application = (new Application($this->client->retrieveApplication($this->app_id)->successResponse));

            $loginId = $request->only('email, username' )[$application->loginIdType];
            $user = FusionAuthUser::FindByLoginId($loginId);

            //if so, are they registered with the app?
            if($user === null || !$this->client->retrieveRegistration($user->id, $this->app_id)->wasSuccessful()) {
                $valid = $request->validate($this->registrationRules());
                $call = $this->client->register(null, $valid);
                if($call->wasSuccessful()) {
                    $user = new FusionAuthUser($call->successResponse);

                }
            }

            if($autologin) {
                return $this->loginUser($loginId, $valid['password']);
            } else {
                return true;
            }
        } catch (\Exception $e) {
            return false;
        }
    }

    public function loginUser ($loginId, $password) : bool
    {
        try {
            $application = (new Application($this->client->retrieveApplication($this->app_id)->successResponse));
        } catch (\Exception $e) {
            return false;
        }
        $valid = \Validator::validate([
            'applicationId' => 'required|string',
            'loginId' => ($application->loginIdType == 'email')? 'required|email' : 'required|string',
            'password' => 'required'
        ], ['loginId' => $loginId, 'password' => $password, 'applicationId' => $this->app_id]);

        $resp = $this->client->login($valid);
        if(! $resp->wasSuccessful()) {
            return false;
        }

        $user = new FusionAuthUser($resp->successResponse);
        \Auth::login(User::firstOrCreate(['id' => $user->id]));
        return \Auth::check();
    }

    public function logoutUser () : void
    {
        $this->client->logout(true);
        \Auth::logout();
    }



    private function registrationRules($application = null) : array {
        if($application == null) {
            $application = (new Application($this->client->retrieveApplication($this->app_id)
                ->successResponse->application));
        }

        $config = $application->registrationConfiguration;

        //Are we actually permitted to do this?
        if(!$config->enabled) {
            throw new UnauthorizedException("self-serve registration is not allowed by this FusionAuth Application.");
        }

        $rules = [];

        if($config->isEmailId) {
            $rules['user.email'] = 'required|email|';
            $rules['user.username'] = 'nullable|string';
        } else {
            $rules['user.email'] = 'nullable|email|';
            $rules['user.username'] = 'required|string';
        }

        $rules['password'] = [
            'required',
            'string',
            Password::min(8)
                ->mixedCase()
                ->numbers()
                ->symbols(),
        ];

        if($config->confirmPassword) {
            $rules['password'][] = 'confirmed';
        }

        foreach($config->fields as $key => $value) {
            if ($value->enabled) {
                $rules[$key] = ($value->required) ? 'required' : 'nullable' . '|string';
            }
        }
        return $rules;
    }

    public function requiresPasswordConfirm ($application = null) : bool
    {
        if($application == null) {
            $application = new Application($this->client->retrieveApplication($this->app_id)->successResponse->application);
        }
        return $application->registrationConfiguration->confirmPassword;
    }

    public function requiresEmailVerification ($application = null) : bool
    {
        if($application == null) {
            $application = new Application($this->client->retrieveApplication($this->app_id)->successResponse->application);
        }
        return $application->verificationRequired;
    }


}
