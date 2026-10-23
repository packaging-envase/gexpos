<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\MessageBag;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;

class Controller extends Controller
{

    public function asd(Request $request)
    {
        try {
            // Create a new MessageBag instance flashed to the session.
            $messageBag = session()->get('messageBag') ?? new MessageBag;
            request()->session()->flash('messageBag', $messageBag);

            //$messageBag->add('success', '');
        } catch (\Throwable $th) {
            throw $th;
        }
    }
}
