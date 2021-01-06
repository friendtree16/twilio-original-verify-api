<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class VerificationsController extends Controller
{

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function verifications(Request $request)
    {
        return response()->json([
            'message' => 'send success'
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function verificationCheck(Request $request)
    {
        return response()->json([
            'message' => 'verification success'
        ]);
    }
}
