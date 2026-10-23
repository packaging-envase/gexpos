<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function panel() {

        if (Auth::guard('expositor')->check()) {
            return redirect()->route('expositor.panel-expositor');
        } else
        if (Auth::guard('web')->check()) {
            return redirect()->route('expositor.panel-administracion');
        } else 
        return redirect()->route('login');
    }
}
