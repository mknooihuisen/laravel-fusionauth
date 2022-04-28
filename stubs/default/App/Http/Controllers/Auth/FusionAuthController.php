<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use Mknooihuisen\LaravelFusionauth\Fusionauth;

class FusionAuthController extends Controller
{
    private Fusionauth $fusionauth;

    public function __construct(Fusionauth $fusionauth)
    {

        $this->fusionauth = $fusionauth;
    }

    public function registerUser (Request $request) : void
    {



    }

}
