<?php

namespace App\Http\Controllers\AdminPanel;

use App\Http\Controllers\Controller;
use App\Model\Page;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;

class PageController extends Controller
{
  public $view = 'admin.page';
  public function __construct()
  {
    $this->middleware('auth');
  }

  public function index()
  {
    $models = Page::orderBy('id', 'desc')
      ->paginate(Page::PAGINATION_LIST_ADMIN);
    return view($this->view.'.index', compact('models'));
  }

    public function create(){
        $model = new Page();
        $statues = Page::statues();
        return view($this->view.'.form', compact('model',  'statues'));
    }

    public function update($id){
        $model = Page::where('id' , $id)
            ->first();
        $statues = Page::statues();
        return view($this->view.'.form', compact('model',  'statues'));
    }

    public function publish($id){
        $model = Page::where('id' , $id)
            ->first();
        $model->status = Page::STATUS_PUBLISH;

        if($model && $model->save()) {
            Session::flash('success_message', 'Sözleşme başarılı bir şekilde kaydedildi!');
        }else {
            Session::flash('error_message', 'Beklenmedik bir hata meydana geldi!');
        }

        return redirect(route('admin.page.index'));
    }

    public function unpublish($id){
        $model = Page::where('id' , $id)
            ->first();
        $model->status = Page::STATUS_UNPUBLISH;

        if($model && $model->save()) {
            Session::flash('success_message', 'Sözleşme başarılı bir şekilde kaydedildi!');
        }else {
            Session::flash('error_message', 'Beklenmedik bir hata meydana geldi!');
        }

        return redirect(route('admin.page.index'));
    }

    public function save() {
        $errors = [];

        if(request()->post()) {

            $inputs = request()->all();
            $rules = [
                'title' => 'required|max:100|min:3',
                'sorting' => 'required|integer',
                'status' => 'required|in:'.implode(',', array_keys(Page::statues())),
                'url' => 'nullable|max:150',
                'detail' => 'required',
            ];

            $validator = Validator::make($inputs, $rules);
            $errors = $validator->getMessageBag()->toArray();

            if ($errors){
                Session::flash('error_message', 'Lütfen hataları düzeltip tekrar dene!');
                return redirect()->back()->withErrors($errors)->withInput();
            }else {
                $page= null;
                if(request()->post('id')) {
                    $page = Page::where('id', request()->post('id'))->first();
                }
                unset($inputs['id']);
                unset($inputs['_token']);

                if($page) {
                    $page->update($inputs);
                }else {
                    $page = new Page();
                    $page = $page->create($inputs);
                    $page->refresh();
                }

                if($page->id) {
                    Session::flash('success_message', 'Sözleşme başarılı bir şekilde kaydedildi!');
                    return redirect(route('admin.page.update', ['id' => $page->id]));

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
