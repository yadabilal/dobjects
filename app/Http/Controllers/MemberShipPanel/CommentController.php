<?php

namespace App\Http\Controllers\MemberShipPanel;

use App\Http\Controllers\Controller;
use App\Model\Address;
use App\Model\Base;
use App\Model\City;
use App\Model\Comment;
use App\Model\Order;
use App\Model\Product;
use App\Model\Town;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;

class CommentController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }


    // Validasyon Kontrolü
    public function check() {
        // Post varsa
        if(\request()->post()){
            $inputs = Base::js_xss(\request());
            $data['success'] = true;

            if($this->user->can_comment()) {
                $rules = [
                    'id' => 'required|exists:products,uuid',
                    'review' => 'required|min:10|max:255',
                    'rate' => 'required|in:1,2,3,4,5'
                ];
                $message = '';
                $validator = Validator::make($inputs, $rules);
                $validator->after(function ($validator) use ($inputs, $message){
                    $product = Product::where('uuid', $inputs['id'])->where('status', Product::STATUS_PUBLISH)
                        ->first();

                    if(!@$product->can_comment()) {
                        $message = 'Bu ürüne, sadece ürünü satın alanlar yorum yapabilir!';
                        $validator->errors()->add('review', 'Bu ürüne, sadece ürünü satın alanlar yorum yapabilir!');
                    }
                });

                $errors = $validator->getMessageBag()->toArray();
                if ($errors){
                    $data['success'] = false;
                    $data['message'] = $message;
                    $data['errors'] = $errors;
                }
            }else {
                $data['message'] = "Bu işlemi yapmaya yetkiniz yok!";
            }

            return Response::json($data, 200);
        }
    }

    // Yorumu Kaydeder
    public function save() {

        $errors=[];
        if(\request()->post() && $this->user->can_order()) {
            $check = $this->check();
            $result = $check->getData();
            if(@$result->success) {
                $inputs = Base::js_xss(\request());

                $comment = new Comment();
                $comment->product_id = Product::where('uuid', $inputs['id'])->where('status', Product::STATUS_PUBLISH)->first()->id;
                $comment->rate = $inputs['rate'];
                $comment->review = $inputs['review'];

                if($comment->save()) {
                    Session::flash('success_message', 'Yorumunuz başarılı bir şekilde sisteme kaydedildi!');
                }else {
                    Session::flash('error_message', 'İşlemini yaparken bir hata meydana geldi!');
                }

                return redirect()->back();
            }else {
                Session::flash('error_message', 'Lütfen hataları düzeltip tekrar dene!');

                foreach ($result->errors as $key => $values) {
                    $errors[$key]= $values;
                }
            }
        }else {
            Session::flash('error_message', 'Geçersiz İşlem!');
        }

        return redirect()->back()->withErrors($errors)->withInput();
    }


}
