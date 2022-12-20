<?php

namespace App\Helpers;

use App\Models\{
    EmailTemplate,
    UserDevice
};
use App\Mail\CommonMail;
use Illuminate\Support\Facades\Mail;
class LoginHelper
{

    public static function checkLogin($user_data, $request)
    {
        //IF USER HAS CREATED ACCOUNT BUT NOT VERIFIED
        try {

            if ($request->device_token != '') {
                $user_data->UserDevice()->delete();
                $user_data->UserDevice()->create(['user_id' => $user_data->user_id, 'device_type' => $request->device_type, 'device_token' => $request->device_token]);
            }
            $user_data->tokens()->delete();

            $return['code'] = 200;
            return $return;
        } catch (\Exception $e) {
            $return['code'] = 422;
            return $return;
        }
    }

    public static function sendEmail($email_template_id, $request, $otp, $email = '')
    {

        $email_data = EmailTemplate::where('title', $email_template_id)->first();
        $full_name = $request->name;
        $message_content = str_replace(['{{USERNAME}}', '{{OTP}}'], [$full_name, $otp], $email_data->content);
        try {
            if ($email == '') {
                $email = $request->email;
            }
            Mail::to($email)->send(new CommonMail($email_data->subject, $message_content));
            return ['code' => 200];
        } catch (\Exception $e) {
            return ['message' => $e->getMessage(), 'code' => 201];
        }
    }
}
