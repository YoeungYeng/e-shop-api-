<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class NoticationController extends Controller
{
    public function getNotifications(Request $request)
{
    return response()->json([
        'notifications' => $request->user()->notifications,
    ]);
}

}
