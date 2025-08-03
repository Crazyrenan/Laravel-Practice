<?php

namespace App\Http\Controllers;

use App\Models\purchase;
use Illuminate\Contracts\View\View;
//use Illuminate\Http\Request;

class PurchaseController extends Controller
{
    public function index(): View
    {
        $purchases = purchase::all();
        return view('testing', compact('purchases')); // this will render testing.blade.php
    }
}
