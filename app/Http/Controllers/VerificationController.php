<?php

namespace App\Http\Controllers;

class VerificationController extends Controller
{
    public function __invoke()
    {
        return redirect()->route('public.verify');
    }
}
