<?php

namespace App\Http\Controllers\AdminPanel;

use App\Http\Controllers\Controller;
use App\Model\Category;
use App\Model\Comment;
use App\User;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;

class CategoryController extends Controller
{
    public $view = 'admin.category';
    public function __construct()
    {
      $this->middleware('auth');
    }
    public function index()
    {
      $models = Category::orderBy('id', 'desc');
      $models = $models->paginate(User::LIST_ADMIN);

      return view($this->view.'.index', compact('models'));
    }

    public function create(){
        $model = new Category();

        return view($this->view.'.form', compact('model'));
    }

    public function update($uuid){
        $model = Category::where('uuid' , $uuid)
            ->first();

        return view($this->view.'.form', compact('model'));
    }

    public function save() {
        $errors = [];

        if(request()->post()) {
            $category = new Category();
            if(request()->post('id')) {
                $category = Category::where('uuid', request()->post('id'))->first();
            }

            $inputs = request()->all();
            $rules = [
                'sorting' => 'required|integer',
                'name' => 'required|max:100|min:3',
                'url' => $category ? 'required|max:255|min:3|unique:categories,url,'.$category->id : 'required|max:255|min:3|unique:categories,url',
            ];

            $validator = Validator::make($inputs, $rules, [
                'unique' => 'Bu alan benzersiz olmalıdır!',
                'required' => 'Bu alan boş olamaz!'
            ]);
            $errors = $validator->getMessageBag()->toArray();

            if ($errors){
                Session::flash('error_message', 'Lütfen hataları düzeltip tekrar dene!');
                return redirect()->back()->withErrors($errors)->withInput();
            }else {
                unset($inputs['id']);
                unset($inputs['_token']);

                if((!$category->id && $category = $category->create($inputs)) || ($category->id && $category->update($inputs))) {
                    Session::flash('success_message', 'Ürün başarılı bir şekilde kaydedildi!');
                    return redirect(route('admin.category.update', ['uuid' => $category->uuid]));
                }else {
                    Session::flash('error_message', 'Lütfen hataları düzeltip tekrar dene!');
                    return redirect()->back()->withErrors($errors)->withInput();
                }
            }


        }else {
            Session::flash('error_message', 'İşelmi yapmaya yetkiniz yok!');
            return redirect()->back()->withErrors($errors)->withInput();
        }
    }
}
