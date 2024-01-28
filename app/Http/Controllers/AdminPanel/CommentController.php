<?php

namespace App\Http\Controllers\AdminPanel;

use App\Http\Controllers\Controller;
use App\Model\Comment;
use App\User;
use Illuminate\Support\Facades\Session;

class CommentController extends Controller
{
    public $view = 'admin.comment';
    public function __construct()
    {
      $this->middleware('auth');
    }
    public function index()
    {
      $models = Comment::orderBy('id', 'desc')->with('product');
      $models = $models->paginate(User::LIST_ADMIN);
      $statues = Comment::statues();

      return view($this->view.'.index', compact('models', 'statues'));
    }

    public function publish($id) {
        $model = Comment::where('id', $id)->first();

        if($model) {
            $model->status = Comment::STATUS_PUBLISH;
            $model->save();
            Session::flash('success_message', 'İşlem başarılı!');
        }else {
            Session::flash('error_message', 'Lütfen hataları düzeltip tekrar dene!');
        }

        return redirect()->back();
    }

    public function unPublish($id) {
        $model = Comment::where('id', $id)->first();

        if($model) {
            $model->status = Comment::STATUS_UNPUBLISH;
            $model->save();
            Session::flash('success_message', 'İşlem başarılı!');
        }else {
            Session::flash('error_message', 'Lütfen hataları düzeltip tekrar dene!');
        }

        return redirect()->back();
    }

    public function enableHomePage($id){
        $model = Comment::where('id' , $id)
            ->first();
        $model->show_home_page = 1;

        if($model && $model->save()) {
            Session::flash('success_message', 'Yorum başarılı bir şekilde kaydedildi!');
        }else {
            Session::flash('error_message', 'Beklenmedik bir hata meydana geldi!');
        }

        return redirect()->back();
    }

    public function disableHomePage($id){
        $model = Comment::where('id' , $id)
            ->first();
        $model->show_home_page = 0;

        if($model && $model->save()) {
            Session::flash('success_message', 'Yorum başarılı bir şekilde kaydedildi!');
        }else {
            Session::flash('error_message', 'Beklenmedik bir hata meydana geldi!');
        }

        return redirect()->back();
    }

}
