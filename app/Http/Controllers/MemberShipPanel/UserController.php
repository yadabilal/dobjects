<?php

namespace App\Http\Controllers\MemberShipPanel;

use App\Http\Controllers\Controller;
use App\Model\Base;
use App\Model\City;
use App\Model\File;
use App\Model\Town;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{

    public function __construct()
    {
      $this->middleware('auth');
    }
    public function index()
    {
      $last_notifications = $this->user->notifications()->limit(3)->get();

      return view('site.membership.user.index', compact('last_notifications'));
    }

    public function edit()
    {
      $user = $this->user;
      $genders = User::genders();
      $cities = City::all_list();
      $towns = old('city_id') ? Town::all_list(old('city_id'), 'uuid') : ($this->user->city_id ? Town::all_list($this->user->city_id): []);

      return view('site.membership.user.form', compact('genders', 'cities', 'towns', 'user'));
    }

    public function image() {
      $data['success'] = false;
      $data['message'] = 'Geçersiz İstek!';
      if(\request()->file('file')) {
        $data['success'] = true;
        $file = \request()->file('file');
        if(in_array($file->getClientOriginalExtension(), File::image_ext())) {
          $file= File::upload($file, $this->user);
          if($file) {
            $data['message'] = 'Profil fotoğrafın başarılı bir şekilde değiştirildi. Harika görünüyorsun!';
          }else {
            $data['message'] = 'Beklenmeyen bir hata meydana geldi. Lütfen sonra tekrar dene!';
          }
        }else {
          $data['message'] = 'Desteklenmeyen dosya. Lütfen sonra tekrar dene!';
        }
      }

      return Response::json($data, 200);
    }
    public function save()
    {
      $request = \request();
      if($request->post()) {
        $model = $this->user;
        $inputs = Base::js_xss($request);
        $check = $this->check($request);
        $result = $check->getData();
        if(@$result->success) {
          //$city = City::by_uuid($inputs['city_id']);
          //$town = Town::by_uuid($inputs['town_id']);

          $data['name'] = $inputs['name'];
          $data['surname'] = $inputs['surname'];
          $data['username'] = $inputs['username'];
          $data['gender'] = $inputs['gender'];
          /*$data['address'] = $inputs['address'];
          $data['city_id'] = $city->id;
          $data['town_id'] = $town->id;*/

          $model->update($data);
          Session::flash('success_message', 'Bilgilerin başarılı bir şekilde güncellendi!');

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
        $model = $this->user;
        $inputs = Base::js_xss($request);
        $data['success'] = true;
        $validate = [
          'username' => 'required|without_spaces|max:50|min:3|unique:users,username,'.$model->id.'|regex:/(^([a-zA-Z._]+)(\d+)?$)/u',
          'gender' => 'required|gender',
          'name' => 'required|max:25|min:3',
          'surname' => 'required|max:30|min:2',
        ];

        $validator = Validator::make($inputs, $validate);
        $errors = $validator->getMessageBag()->toArray();
        // Hata varsa sonucu döndür
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
