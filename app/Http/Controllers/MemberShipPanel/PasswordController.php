<?php

namespace App\Http\Controllers\MemberShipPanel;

use App\Http\Controllers\Controller;
use App\Model\Base;
use App\Model\Sms;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;

class PasswordController extends Controller
{

  public function __construct()
  {
    $this->middleware('auth');
  }

  public function index()
  {
    return view('site.membership.password.index');
  }

  public function edit(Request $request)
  {
    if($request->post()) {
      $model = Auth::user();
      $inputs = Base::js_xss($request);
      $check = $this->check($request);
      $result = $check->getData();

      if(@$result->success) {

        if(@$inputs['change_phone']) {
          Session::flash('success_message', 'Telefon numaran başarılı bir şekilde güncellendi!');
          $data['phone'] = $inputs['phone'];
        }else {
          Session::flash('success_message', 'Şifren başarılı bir şekilde güncellendi!');
          $data['password'] = Hash::make($inputs['new_password']);
        }

        $model->update($data);
        return redirect()->back();

      }else {
        if(@$result->message) {
          Session::flash('error_message', $result->message);
        }else{
          Session::flash('error_message', 'Lütfen hataları düzeltip tekrar dene!');
        }
        return redirect()->back()->withErrors($result->errors)->withInput();
      }
    }
  }
  public function check(Request $request)
  {
    $data['success'] = false;
    if($request->post()) {
      $data['success'] = true;
      $model = Auth::user();
      $inputs = Base::js_xss($request);
      $rules = [
        'password' => 'required|min:6|now_password:'.$model->password,
        'new_password' => 'required|min:6',
        'confirm_password' => 'required|confirm_password'
      ];

      if(@$inputs['change_phone']) {
        $rules = [
          'phone' => 'required|min:10|max:14|phone_custom_unique|phone:TR',
        ];
      }
      $validator = Validator::make($inputs, $rules);
      $errors = $validator->getMessageBag()->toArray();
      if ($errors){
        $data['success'] = false;
        $data['errors'] = $errors;
      }
    }else {
      $data['message'] = 'Geçersiz İstek!';
    }

    return Response::json($data, 200);
  }
}
