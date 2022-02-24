<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Http\Requests\RegisterRequest;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Carbon\Carbon;
use Illuminate\Support\Facades\Mail;
use App\Mail\RegisterMail;
use Illuminate\Support\Facades\Log;



class UserController extends Controller
{
    public function store(RegisterRequest $request)
    {
        // バリデーション
        $input = $request->validated();


        $user = User::create([
            'name' => $input['name'],
            'email' => $input['email'],
            'password' => Hash::make($input['password']),
            'type_id' => 3
        ]);


        //仮登録処理

        // 1.メールアドレスを仮登録へ移動する
        $user->verify_email_address = $user->email;
        // 2.仮のメールアドレスを入れる。　被らないためにユニークで。
        $user->email = Str::random(32) . '@temp.com';


        // 3.仮登録確認用の設定
        $user->verify_email = false;
        $user->verify_token = Str::random(32);
        $user->verify_date = Carbon::now()->toDateTimeString();

        // 4.保存
        $user->save();


        // 5.メール送信処理
        $data = [
            'type' => "register",
            'email' => $user->verify_email_address,
            'token' => $user->verify_token,
            'title' => 'register'
        ];
        Mail::to($user->verify_email_address)->send(new RegisterMail($data));

        return response()->json(['data' => $user], 200);
    }

    public function verify($token)
    {
        //登録メールURLクリック後の処理

        $params['result'] = "error";

        // トークンの有効期限を30分とするため有効な時間を算出
        // 現在時間 -30分
        $verify_limit = Carbon::now()->subMinute(1)->toDateTimeString();

        $user = User::where('verify_token', $token)->where('verify_date', '>', $verify_limit)->first();

        if ($user) {
            // 本登録されていない
            if (User::where("email", $user->verify_email_address)->first()) {
                $params['result'] = "exist";
            } else {
                // 仮メールアドレスを本メールに移動
                $user->email = $user->verify_email_address;
                // 仮メールアドレスを削除
                $user->verify_email_address = null;
                // 有効なユーザーにする
                $user->verify_email = true;
                // その他クリーニング
                $user->verify_token = null;
                $user->verify_date = null;
                // 承認日登録
                $user->email_verified_at = Carbon::now()->toDateTimeString();

                // テーブル保存
                $user->save();
                $params['result'] = "success";
                // Log::info('Verify Success: ' . $user);
            }
        } else {
            // dd($token);
            $params['token'] = $token;
            return view('verify', $params);
            Log::info('Verify Not Found: token=' . $token);
        }

        return view('verify', $params);
    }

    public function resend($token)
    {
        $user = User::where('verify_token', $token)->first();

        $user->verify_token = Str::random(32);
        $user->verify_date = Carbon::now()->toDateTimeString();

        $user->save();

        $data = [
            'type' => "register",
            'email' => $user->verify_email_address,
            'token' => $user->verify_token,
            'title' => 'resend'
        ];
        Mail::to($user->verify_email_address)->send(new RegisterMail($data));
        return view('resend');
    }
}
