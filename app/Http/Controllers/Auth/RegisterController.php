<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Model\Balance;
use App\Model\Base;
use App\Model\PhoneLog;
use App\Model\Job;
use App\Model\Sms;
use App\User;
use Carbon\Carbon;
use Illuminate\Auth\Events\Registered;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;

class RegisterController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Register Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users as well as their
    | validation and creation. By default this controller uses a trait to
    | provide this functionality without requiring any additional code.
    |
    */

    use RegistersUsers;

    /**
     * Where to redirect users after registration.
     *
     * @var string
     */
    protected $redirectTo = '/hesabim';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guestOrWait');
    }
    // Kullanıcı bilgileri
    public function showFirstStepForm()
    {
      return view('auth.register.first_step');
    }
    // Kullanıcı bilgileri kaydet
    public function saveFirstStep(Request $request)
    {
      if($request->post()) {
        $check = $this->check($request);
        $result = $check->getData();
        $user = Auth::user();
        // Eğer Hata yoksa kaydet
        if(@$result->success && !$user) {
          $inputs = Base::js_xss($request);

          event(new Registered($user = $this->create($inputs)));

          $this->guard()->login($user);
          Session::flash('success_message', 'Lütfen telefonunu onayla!');

          // TODO canlıya geçerken aç
          //Sms::confirm_code($user);
          return $this->registered($request, $user)
            ?: redirect($this->redirectPath());

        }else {
          Session::flash('error_message', 'Lütfen hataları düzeltip tekrar dene!');
          return redirect()->back()->withErrors($result->errors)->withInput();
        }
      }
    }
    //Telefon Onayı
    public function showSecondStepForm()
    {
      $now = Carbon::now()->addMinutes(2);
      $diff = Carbon::now()->subMinutes(6);
      $job = Job::where('subject', SMS::SUBJECT_CONFIRM)
        ->where('type', Job::TYPE_SMS)
        ->where('contact', \auth()->user()->phone)
        ->whereDate('send_at', '<=', $now)
        ->whereDate('send_at', '>=',$diff)
        ->orderBy('id', 'desc')
        ->first();
      $time = $job ? Carbon::parse($job->send_at)->startOfMinute() : Carbon::now()->startOfMinute();

      return view('auth.register.second_step', compact('time'));
    }
    // Telefonunu Onaylamayı Bitir
    public function saveSecondStep(Request $request)
    {
      if($request->post()) {
        $check = $this->check($request);
        $result = $check->getData();
        $user = Auth::user();
        // Eğer Hata yoksa kaydet
        if(@$result->success && @$user->status == User::STATUS_STEP_SECOND) {
          $status = User::STATUS_STEP_THIRD;
          if($user->username) {
            $status = User::STATUS_COMPLETED;
          }
          $user->update(['phone_code' => null, 'phone_verified_at' => Carbon::now(), 'status' => $status]);
          Session::flash('success_message', 'Telefonun başarılı bir şekilde onaylandı. Haydi, kendine kullanıcı adı seçerek başla!');

          return redirect('kayit-ol/kullanici-adi-belirle');

        }else {
          Session::flash('error_message', 'Lütfen hataları düzeltip tekrar dene!');
          return redirect()->back()->withErrors($result->errors)->withInput();
        }
      }
    }
    //Yeni Telefon Belirle
    public function showSecondHalfStepForm()
    {
      return view('auth.register.second_half_step');
    }
    //Yeni Telefonunu Kaydet
    public function saveSecondHalfStep(Request $request)
    {
      if($request->post()) {
        $check = $this->check($request);
        $result = $check->getData();
        $user = Auth::user();
        // Eğer Hata yoksa kaydet
        if(@$result->success && @$user->status == User::STATUS_STEP_SECOND) {
          $inputs = Base::js_xss($request);
          $user->update(['phone' => $inputs['phone']]);
          Session::flash('success_message', 'Telefonun başarılı bir şekilde güncellendi.Lütfen telefonunu onayla!');
          return redirect('kayit-ol/telefon-onayla');

        }else {
          Session::flash('error_message', 'Lütfen hataları düzeltip tekrar dene!');
          return redirect()->back()->withErrors($result->errors)->withInput();
        }
      }
    }
    // Kullanıcı adı belirleme
    public function showThirdStepForm()
    {
      return view('auth.register.third_step');
    }
    //Kullanıcı adını kaydet
    public function saveThirdStep(Request $request)
    {
      if($request->post()) {
        $check = $this->check($request);
        $result = $check->getData();
        $user = Auth::user();
        // Eğer Hata yoksa kaydet
        if(@$result->success && @$user->status == User::STATUS_STEP_THIRD) {
          $inputs = Base::js_xss($request);
          $user->update(['username' => $inputs['username'], 'status'=> User::STATUS_COMPLETED, 'total_balance'=> User::DEFAULT_BALANCE]);

          Session::flash('success_message', 'Harika! Artık en özgün tasarımlı mobilyalara sahip olabilirsin!');
          return redirect($this->redirectTo);

        }else {
          Session::flash('error_message', 'Lütfen hataları düzeltip tekrar dene!');
          return redirect()->back()->withErrors($result->errors)->withInput();
        }
      }
    }
    public function newCode(Request $request)
    {
      if($request->post()) {
        $user = Auth::user();
        $data['success'] = true;
        $errors =[];
        // Eğer Hata yoksa kaydet
        if(@$user->status == User::STATUS_STEP_SECOND) {
            $diff = Carbon::now()->diffInMinutes(Carbon::parse($user->created_at));

            if($diff >= 6) {
                $user->update(['phone_code' => rand(1000, 9999)]);
                Sms::confirm_code($user);
            }else {
                $data['message'] = "Yeni kod istemek için yeterli süre geçmemiş!";
            }

        }else {
          $errors['no_permission'] = 'Geçersiz İşlem!';
        }
        if ($errors){
          $data['success'] = false;
          $data['errors'] = $errors;
        }
        return Response::json($data, 200);
      }
    }
    // Validasyon Kontrolü
    public function check(Request $request) {
      // Post varsa
      if($request->post()){
        $user = Auth::user();
        $data['success'] = true;
        $inputs = Base::js_xss($request);

        if(@$user->status == User::STATUS_STEP_SECOND) {
          if(@$inputs['new_phone']) {
            $rules = [
              'phone' => 'required|min:10|max:15|unique:users,phone,'.$user->id.'|phone:TR|regex:/(5)[0-9]/|not_regex:/[a-z]/|',
            ];
          }else {
            $rules = [
              'code' => 'required|phone_code',
            ];
          }

        }else if(@$user->status == User::STATUS_STEP_THIRD) {
          $rules = [
            'username' => 'required|without_spaces|max:50|min:3|unique:users,username,'.$user->id.'|regex:/(^([a-zA-Z._]+)(\d+)?$)/u',
          ];
        }else {
          $rules = [
            'name' => 'required|max:25|min:3',
            'surname' => 'required|max:30|min:2',
            'phone' => 'required|min:10|max:15|phone_custom_unique|phone:TR|regex:/(5)[0-9]/|not_regex:/[a-z]/|',
            'password' => 'required|string|min:6',
          ];
        }
          try {
              $validator = Validator::make($inputs, $rules);
              $errors = $validator->getMessageBag()->toArray();
              if ($errors){
                  $data['success'] = false;
                  $data['errors'] = $errors;
              }
          }catch (\Exception $e) {
              $data['success'] = false;
              $data['errors'] = [
                  'phone' => [
                      'Geçersiz telefon numarası!'
                  ]
              ];
          }


        return Response::json($data, 200);
      }
    }
    // Validasyon kontrolü
    protected function validator(array $data)
    {
        return Validator::make($data, [
            'name' => 'required|max:25|min:3',
            'surname' => 'required|max:30|min:2',
            'phone' => 'required|min:10|max:15|unique:users,phone|phone:TR|regex:/(5)[0-9]/|not_regex:/[a-z]/|',
            'password' => ['required', 'string', 'min:6', 'confirmed'],
        ]);
    }
    // Oluşturma
    protected function create(array $data)
    {
        return User::create([
            'name' => $data['name'],
            'surname' => $data['surname'],
            'phone' => $data['phone'],
            'phone_code' => rand(1000, 9999),
            'status' => User::STATUS_STEP_SECOND,
            'password' => User::hash_pasword($data['password']),
            'type' => User::TYPE_USER
        ]);
    }
}
