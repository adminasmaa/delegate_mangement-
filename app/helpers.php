<?php

use Intervention\Image\Facades\Image;




function validationErrorsToString($errArray)
{
    $valArr = array();
    foreach ($errArray->toArray() as $key => $value) {
        $newVal = (isset($valArr[$value[0]])) ? $valArr[$value[0]] . ',' : '';
        $key = __('validation.attributes.' . $key);
        $valArr[$value[0]] = (!empty($valArr[$value[0]])) ? $newVal . $key : $key;

    }
    if (!empty($valArr)) {
        $errorArr = array();
        foreach ($valArr as $errorMsg => $attributes) {

            $errorArr[] = __('validation.attributes.field') . " (" . $attributes . ") " . $errorMsg;
        }
        $errStrFinal = implode(',', $errorArr);
    }
    return $errStrFinal;
}


function UploadImage($path, $image, $model, $request)
{


    $thumbnail = $request;
    $destinationPath = $path;
    $filename = time() . '.' . $thumbnail->getClientOriginalExtension();
    $thumbnail->move($destinationPath, $filename);
    $model->$image = $filename;
    $model->save();
}

if (!function_exists('whats_send')) {
    function whats_send($mobile, $message, $country_code)
    {

        $mobile = $country_code . $mobile;
        $body = $message;
        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => "https://api.ultramsg.com/instance19640/messages/chat",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_SSL_VERIFYHOST => 0,
            CURLOPT_SSL_VERIFYPEER => 0,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "POST",
            CURLOPT_POSTFIELDS => "token=duu4f0ww69kk44us&to=%2B$mobile&body=$body&priority=1&referenceId=",
            CURLOPT_HTTPHEADER => array(
                "content-type: application/x-www-form-urlencoded"
            ),
        ));
        $response = curl_exec($curl);
        $err = curl_error($curl);

        curl_close($curl);

        if ($err) {
            echo "cURL Error #:" . $err;
        } else {

        }
    }
}
if (!function_exists('send_sms_code_msg')) {
    function send_sms_code_msg($msg, $phone, $country_code)
    {
        $phone = $country_code . $phone;
        $url = "http://62.150.26.41/SmsWebService.asmx/send";
        $params = array(
            'username' => 'Electron',
            'password' => 'LZFDD1vS',
            'token' => 'hjazfzzKhahF3MHj5fznngsb',
            'sender' => '7agz',
            'message' => $msg,
            'dst' => $phone,
            'type' => 'text',
            'coding' => 'unicode',
            'datetime' => 'now'
        );
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($params));
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 60);
        curl_setopt($ch, CURLOPT_TIMEOUT, 60);
        $result = curl_exec($ch);
        if (curl_errno($ch) !== 0) {
            error_log('cURL error when connecting to ' . $url . ': ' . curl_error($ch));
        }
        curl_close($ch);

    }
}

if (!function_exists('send_sms_code')) {
    function send_sms_code($msg, $phone, $country_code)
    {

        whats_send($phone, $msg, $country_code);
        send_sms_code_msg($msg, $phone, $country_code);
    }
}

?>
