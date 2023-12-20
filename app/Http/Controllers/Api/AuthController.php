<?php

namespace App\Http\Controllers\Api;


use Illuminate\Support\Facades\Hash;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Validator;


class AuthController extends Controller
{

    public function register(Request $request)
    {


        $rule = [
            'email' => 'max:254|unique:users|email|required',
            'name' => 'required',
            'phone' => 'required|min:9|unique:users',
            'password' => 'required|min:6',
            'c_password' => 'nullable|same:password',

        ];
        $customMessages = [
            'required' => __('validation.attributes.required'),
        ];

        $validator = validator()->make($request->all(), $rule, $customMessages);

        if ($validator->fails()) {

            return response()->json(['status' => 422, 'message' => validationErrorsToString($validator->errors())], 422);

        } else {
            $input = $request->all();
            $input['password'] = bcrypt($input['password']);

            $user = User::create($input);


            $user['token'] = $user->createToken('MyApp')->accessToken;


            return $this->responseJson(200, trans('message.User register successfully.'), $user);
        }
    }


    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [

            'phone' => 'required_without:email',
            'email' => 'required_without:phone',
            'password' => 'required|min:6'
        ]);

        if ($validator->fails()) {
            return response()->json(['status' => 422, 'message' => validationErrorsToString($validator->errors())], 422);
        }
        if ($request->phone) {
            if (auth()->attempt(['phone' => $request->phone, 'password' => $request->password])) {
                $user = Auth::user();

                $user['token'] = $user->createToken('MyApp')->accessToken;
                return $this->responseJson(200, trans('message.User login successfully.'), $user);


            } else {
                return $this->responseJson(403, trans('message.wrong credientials'), null);
            }
        } else {

            if (auth()->attempt(['email' => $request->email, 'password' => $request->password])) {
                $user = Auth::user();

                $user['token'] = $user->createToken('MyApp')->accessToken;
                return $this->responseJson(200, trans('message.User login successfully.'), $user);


            } else {
                return $this->responseJson(403, trans('message.wrong credientials'), null);
            }
        }

    }


    public function resetpassword(Request $request)
    {
        $rule = [
            'phone' => 'required|min:9',
            'password' => 'required|min:6',
            'c_password' => 'nullable|same:password',

        ];

        $customMessages = [
            'required' => __('validation.attributes.required'),
        ];

        $validator = validator()->make($request->all(), $rule, $customMessages);

        if ($validator->fails()) {

            return response()->json(['status' => 422, 'message' => validationErrorsToString($validator->errors())], 422);

        }
        if (isset($request->phone)) {
            $user = User::Where('phone', $request->phone)->where('phone', '!=', NULL)->first();
        }
        if ($user) {

            $user->fill([
                'password' => Hash::make($request->password)
            ])->save();
            return $this->responseJson(1, __('message.password reset successfully.'));


        } else {
            return $this->responseJson(0, __('message.Data not found.'));

        }
    }


    public function forgetPassword(Request $request)
    {
        $rule = [
            'phone' => 'required|min:9',

        ];

        $customMessages = [
            'required' => __('validation.attributes.required'),
        ];

        $validator = validator()->make($request->all(), $rule, $customMessages);

        if ($validator->fails()) {

            return response()->json(['status' => 422, 'message' => validationErrorsToString($validator->errors())], 422);

        }
        $user = User::Where('phone', $request->phone)->where('phone', '!=', NULL)->first();
        if ($user) {

            $set = '123456789';
            $code = substr(str_shuffle($set), 0, 4);
            $msg = trans('message.for change password') . "\n";
            $msg = $msg . trans('message.your code ') . "\n" . $code;
            send_sms_code($msg, $request->phone,$code);
            $user->code = $code;
            $user->save();
            return $this->responseJson(200, trans('message.message sent successfully.'));

        } else {
            return $this->responseJson(404, trans('message.user not found'));
        }
    }

    public function logout()
    {

        Auth::logout();


        return $this->responseJson(200, trans('message.logout'), null);


    }


}
