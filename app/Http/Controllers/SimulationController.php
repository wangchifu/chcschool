<?php

namespace App\Http\Controllers;

use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SimulationController extends Controller
{
    public function impersonate(User $user)
    {
        Auth::user()->impersonate($user);
        return redirect()->route('index');
    }
    public function impersonate_leave()
    {
        Auth::user()->leaveImpersonation();
        return redirect()->route('index');
    }
}
