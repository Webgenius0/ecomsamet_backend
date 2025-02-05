<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class HairHomeController extends Controller
{
    public function index(){
        return ('welcome to hairhome');
    }

    public function store(){
        $user=Auth::user();
        if($user){
            //then he can store the new service
            
        }
    }
}
