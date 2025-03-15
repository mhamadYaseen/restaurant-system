<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class PendingApprovalController extends Controller
{
    public function index()
    {
        return view('auth.pending-approval');
    }
}
