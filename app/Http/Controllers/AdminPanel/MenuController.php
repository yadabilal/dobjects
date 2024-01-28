<?php

namespace App\Http\Controllers\AdminPanel;

use App\Http\Controllers\Controller;
use App\Model\Basket;
use App\Model\HomePage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;

class MenuController extends Controller
{
  public $view = 'admin.menu';
  public function __construct()
  {
    $this->middleware('auth');
  }

  public function index($type)
  {
    $models = HomePage::orderBy('sorting', 'asc')
      ->where('type', $type)
      ->paginate(Basket::PAGINATION_LIST_ADMIN);
    return view($this->view.'.index', compact('models', 'type'));
  }

  public function create( $type) {
      $statues = HomePage::statues();
      return view($this->view.'.form', compact('type', 'statues'));
  }

    public function update( $id) {
      $model = HomePage::where('id', $id)->first();
      $type = $model->type;
      $statues = HomePage::statues();

      return view($this->view.'.form', compact('type', 'statues', 'model'));
    }

  public function save(Request $request) {
      if($request->isMethod('post')) {

          $inputs = request()->all();
          $rules = [
              'title' => 'nullable|max:100',
              'sub_title' => 'nullable|max:255',
              'sorting' => 'required|integer',
              'status' => 'required|in:'.implode(',', array_keys(HomePage::statues())),
              'url' => 'nullable|max:255',
              'file' => 'nullable',
          ];

          $validator = Validator::make($inputs, $rules);
          $errors = $validator->getMessageBag()->toArray();

          if ($errors){
              Session::flash('error_message', 'Lütfen hataları düzeltip tekrar dene!');
              return redirect()->back()->withErrors($errors)->withInput();
          }else {
              $model = new HomePage();
              if($request->get('id')) {
                  $model = HomePage::where('id', $request->get('id'))->first();
              }

              $model->title = $request->get('title');
              $model->sub_title = $request->get('sub_title');
              $model->type = $request->get('type');
              $model->url = $request->get('url');
              $model->sorting = $request->get('sorting');
              $model->status = $request->get('status');
              $image = $request->file('image');

              if($image) {
                  $model->file = url('uploads/'.$image->store('home_pages',['disk' => 'public']));
              }

              if($model->save()) {
                  Session::flash('success_message', 'İçerik başarılı bir şekilde kaydedildi!');
                  return redirect(route('admin.menu.index', ['type' => $model->type]));
              }else {
                  Session::flash('error_message', 'Lütfen hataları düzeltip tekrar dene!');
                  return redirect()->back();
              }
          }

      }
  }

    public function publish($id){
        $model = HomePage::where('id' , $id)
            ->first();
        $model->status = HomePage::STATUS_PUBLISH;

        if($model && $model->save()) {
            Session::flash('success_message', 'İçerik başarılı bir şekilde kaydedildi!');
        }else {
            Session::flash('error_message', 'Beklenmedik bir hata meydana geldi!');
        }

        return redirect()->back();
    }

    public function unpublish($id){
        $model = HomePage::where('id' , $id)
            ->first();
        $model->status = HomePage::STATUS_NOT_PUBLISH;

        if($model && $model->save()) {
            Session::flash('success_message', 'İçerik başarılı bir şekilde kaydedildi!');
        }else {
            Session::flash('error_message', 'Beklenmedik bir hata meydana geldi!');
        }

        return redirect()->back();
    }

}
