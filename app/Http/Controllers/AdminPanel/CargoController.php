<?php

namespace App\Http\Controllers\AdminPanel;

use App\Http\Controllers\Controller;
use App\Model\CargoCompany;
use App\User;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;

class CargoController extends Controller
{
    public $view = 'admin.cargo';
    public function __construct()
    {
      $this->middleware('auth');
    }
    public function index()
    {
      $models = CargoCompany::orderBy('id', 'desc');
      $models = $models->paginate(User::LIST_ADMIN);

      return view($this->view.'.index', compact('models'));
    }

    public function create(){
        $model = new CargoCompany();

        return view($this->view.'.form', compact('model'));
    }

    public function update($uuid){
        $model = CargoCompany::where('uuid' , $uuid)
            ->first();

        return view($this->view.'.form', compact('model'));
    }

    public function save() {
        $errors = [];

        if(request()->post()) {
            $category = new CargoCompany();
            if(request()->post('id')) {
                $category = CargoCompany::where('uuid', request()->post('id'))->first();
            }

            $inputs = request()->all();

            $rules = [
                'name' => 'required|max:150|min:3',
                'description' => 'nullable|max:255',
                'folow_url' => 'required_without:is_special|max:255',
                'is_special' => 'nullable|in:0,1',
                'contact' => 'required_if:is_special,=,1|max:255',
                'full_name' => 'required_if:is_special,=,1|max:75',
            ];

            $validator = Validator::make($inputs, $rules, [
                'unique' => 'Bu alan benzersiz olmalıdır!',
                'required' => 'Bu alan boş olamaz!',
                'required_if' => 'Bu alan boş olamaz!',
                'required_without' => 'Bu alan boş olamaz!',
            ]);
            $errors = $validator->getMessageBag()->toArray();

            if ($errors){
                Session::flash('error_message', 'Lütfen hataları düzeltip tekrar dene!');
                return redirect()->back()->withErrors($errors)->withInput();
            }else {
                unset($inputs['id']);
                unset($inputs['_token']);
                $inputs['order'] = 5;

                if((!$category->id && $category = $category->create($inputs)) || ($category->id && $category->update($inputs))) {
                    Session::flash('success_message', 'Kargo firması başarılı bir şekilde kaydedildi!');
                    return redirect(route('admin.cargo.update', ['uuid' => $category->uuid]));
                }else {
                    Session::flash('error_message', 'Lütfen hataları düzeltip tekrar dene!');
                    return redirect()->back()->withErrors($errors)->withInput();
                }
            }


        }else {
            Session::flash('error_message', 'İşlemi yapmaya yetkiniz yok!');
            return redirect()->back()->withErrors($errors)->withInput();
        }
    }


}
