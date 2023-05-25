<?php

namespace App\Http\Controllers;

use App\Model\Base;
use App\Model\Support;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;

class SupportController extends Controller
{
  
  public function index()
  {
    return view('site.contact');
  }
  
  public function save()
  {
    $request= \request();
    if($request->post()) {
      $check = $this->check();
      $result = $check->getData();
      $inputs = Base::js_xss($request);
      if(@$result->success) {
  
        $data['name'] = $inputs['name'];
        if($this->user){
          $data['name'] = $this->user->name;
          $data['surname'] = $this->user->surname;
          $data['user_id'] = $this->user->id;
        }
        
        $data['email'] = $inputs['email'];
        $data['subject'] = $inputs['subject'];
        $data['detail'] = $inputs['detail'];
        
        Support::create($data);
        Session::flash('success_message', 'Bize ulaştığın için teşekkürler! En kısa zamanda seninle iletişime geçeceğiz.');
  
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
  public function check()
  {
    $request = \request();
    $data['success'] = false;
    if($request->post()) {
      $data['success'] = true;
      $inputs = Base::js_xss($request);
      $rule = [
        'name' => 'required|max:50',
        'email' => 'required|max:150|min:10|email',
        'subject' => 'required|max:50|min:5',
        'detail' => 'required|max:255|min:20',
      ];
      
      $validator = Validator::make($inputs, $rule);
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
