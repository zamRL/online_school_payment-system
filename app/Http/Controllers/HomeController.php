<?php

namespace App\Http\Controllers;

use App\User;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index()
    {
        $payment_months = config('misc.payment_months');
        $students = User::where('role', 'student')->orderBy('id', 'desc')->get();
        return view('home')->with(compact('students', 'payment_months'));
    }
}
