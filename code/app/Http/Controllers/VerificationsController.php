<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Verify;
use Twilio\Rest\Client;

class VerificationsController extends Controller
{

    // 認証コードの送信
    public function verifications(Request $request)
    {

        // 送信済確認
        $verifies = Verify::where('to',$request->to)->whereNotIn('status',['verified'])->get();

        $verify;
        $code;
        if(count($verifies) == 0) {
            // 未送信
            $code = $this->generateCode(6);
            $verify = new Verify;
            $verify->to = $request->to;
            $verify->code = $code;
        } else {
            // 送信済
            $verify = $verifies->first();
            $code = $verify->code;
        }

        $result = $this->sendCode($code,$request->channel,$request->to);
        
        if($result) {
            $verify->status = 'send';
            $verify->save();
            return response()->json([
                'message' => 'send success'
            ],200);
        } else {
            return response()->json([
                'message' => 'send success'
            ],400);
        }
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

    // 認証コード生成
    private function generateCode($length)
    {
        $max = pow(10, $length) - 1;
        $rand = random_int(0, $max);
        $code = sprintf('%0'. $length. 'd', $rand);

        return $code;
    }

    // 認証コード送信
    private function sendCode($code,$channel,$to) 
    {
        $client = new Client(env('TWILIO_SID'),env('TWILIO_TOKEN'));
        if($channel == 'sms') {
            $client->messages->create(
                $to,
                [
                    'from' => env( 'TWILIO_FROM' ),
                    'body' => 'あなたの認証コードは '.$code.' です。',
                ]);
            return true;
        } else if($channel == 'voice'){
            $client->calls->create(
                $to,
                env( 'TWILIO_FROM_VOICE' ), // from
                [
                    "twiml" => "<Response><Say language='ja-JP'>あなたの認証コードは".join(',',str_split($code))."です。</Say></Response>"
                ]);
            return true;
        } else {
            return false;
        }
    }
}
