<?php

namespace App\Http\Controllers\AdminPanel;

use App\Http\Controllers\Controller;
use App\Model\Base;
use App\Model\Category;
use App\Model\Product;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;


class ProductController extends Controller
{
  public $view = 'admin.product';
  public function __construct()
  {
    $this->middleware('auth');
  }

  public function index()
  {
      $title = request()->get('type')=='accesorio' ? 'Aksesuar Ürünleri' : 'Ürünler';
    $models = Product::list_all(Product::PAGINATION_LIST_ADMIN, true, false, false, request()->get('type') ?: 'no_accesorio');

    return view($this->view.'.index', compact('models', 'title'));
  }

  public function show($uuid){
      $model = Product::where('uuid' , $uuid)
          ->with('baskets', 'waitingOrders', 'newOrders')
          ->with('avgRating', 'comments', 'category')
          ->withCount('baskets', 'waitingOrders', 'comments')
          ->first();
      return view($this->view.'.show', compact('model'));
  }

    public function create(){
        $model = new Product();
        $categories = Category::list(true);
        $statues = Product::statues();
        return view($this->view.'.form', compact('model', 'categories', 'statues'));
    }

  public function update($uuid){
        $model = Product::where('uuid' , $uuid)
            ->with('baskets', 'waitingOrders', 'newOrders', 'files')
            ->with('avgRating', 'comments', 'category')
            ->withCount('baskets', 'waitingOrders', 'comments')
            ->first();
        $categories = Category::list(true);
        $statues = Product::statues();
        return view($this->view.'.form', compact('model', 'categories', 'statues'));
  }

    public function publish($uuid){
        $model = Product::where('uuid' , $uuid)
            ->first();
        $model->status = Product::STATUS_PUBLISH;

        if($model && $model->save()) {
            Session::flash('success_message', 'Ürün başarılı bir şekilde kaydedildi!');
        }else {
            Session::flash('error_message', 'Beklenmedik bir hata meydana geldi!');
        }

        return redirect(route('admin.product.index'));
    }

    public function unpublish($uuid){
        $model = Product::where('uuid' , $uuid)
            ->first();
        $model->status = Product::STATUS_NOT_PUBLISH;

        if($model && $model->save()) {
            Session::flash('success_message', 'Ürün başarılı bir şekilde kaydedildi!');
        }else {
            Session::flash('error_message', 'Beklenmedik bir hata meydana geldi!');
        }

        return redirect(route('admin.product.index'));
    }

  public function save() {
      $errors = [];

      if(request()->post()) {

          $inputs = request()->all();
          $rules = [
              'name' => 'required|max:100|min:3',
              'stock' => 'required|integer',
              'sorting' => 'required|integer',
              'show_home_page' => 'nullable|integer',
              'is_accesorio' => 'nullable|integer',
              'category_id' => 'required|exists:categories,id',
              'price' => 'required',
              'discount_rate' => 'required|integer',
              'discount_price' => 'required',
              'status' => 'required|in:'.implode(',', array_keys(Product::statues())),
              'tags' => 'nullable|max:255',
              'url' => 'nullable|max:150',
              'short_description' => 'nullable|max:255',
              'description' => 'required',
              'additional_information' => 'nullable|max:500',
          ];

          $validator = Validator::make($inputs, $rules);
          $errors = $validator->getMessageBag()->toArray();

          if ($errors){
              Session::flash('error_message', 'Lütfen hataları düzeltip tekrar dene!');
              return redirect()->back()->withErrors($errors)->withInput();
          }else {
              $product = null;
              if(request()->post('id')) {
                  $product = Product::where('uuid', request()->post('id'))->first();
              }

              $inputs['is_accesorio'] = 0;
              if(request()->post('is_accesorio')) {
                  $inputs['is_accesorio'] = 1;
              }

              unset($inputs['id']);
              unset($inputs['_token']);

              if($product) {
                  $product->update($inputs);
              }else {
                  $product = new Product();
                  $product = $product->create($inputs);
                  $product->refresh();
              }

              if($product->uuid) {
                  $files = $product->files()->get();
                  $shortings = @$inputs['shorting'] ?: [];
                  $removed = @$inputs['removed'] ?: [];
                  foreach ($files as $file) {
                      if(@$removed[$file->uuid] == 'removed') {
                          $file->delete();
                      }else if(@$shortings[$file->uuid]) {
                          $file->shorting = $shortings[$file->uuid];
                          $file->save();
                      }
                  }
                  Session::flash('success_message', 'Ürün başarılı bir şekilde kaydedildi!');
                  return redirect(route('admin.product.update', ['uuid' => $product->uuid]));

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


    public function calculate() {
        $price = request()->post('price');
        $rate = @request()->post('rate') ?: 0;

        $discountPrice = $price - ($price*$rate/100);
        $data['success'] = true;
        $data['discount_price'] = $discountPrice;

        return Response::json($data);
    }

    public function enableHomePage($id){
        $model = Product::where('id' , $id)
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
        $model = Product::where('id' , $id)
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
