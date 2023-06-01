<?php

namespace App\Http\Controllers\AdminPanel;

use App\Http\Controllers\Controller;
use App\Model\CargoCompany;
use App\Model\File;
use App\Model\Order;
use App\Model\Product;
use App\Model\Sms;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;

class OrderController extends Controller
{
  public $view = 'admin.order';
  public function __construct()
  {
    $this->middleware('auth');
  }

  public function index()
  {
    $models = Order::with('user', 'items', 'address', 'address.city', 'address.town')
        ->withCount('items')
        ->with('address', 'billing_address')
      ->orderBy('id', 'desc');

    $statues = Order::status_list(true);

      if(@$_GET['status']) {
        $val = $_GET['status'];
        $models = $models->where('status', $val);
      }

      $models = $models->paginate(Order::PAGINATION_LIST_ADMIN);
    return view($this->view.'.index', compact('models', 'statues'));
  }

    public function proccess($uuid) {
        $model = Order::where('uuid', $uuid)->where('status', Order::STATUS_NEW)->first();

        if($model) {
            $model->status = Order::STATUS_PROCCESS;
            $model->update();
            Session::flash('success_message', 'İşlem başarılı!');
        }else {
            Session::flash('error_message', 'Lütfen hataları düzeltip tekrar dene!');
        }

        return redirect()->back();
    }

  public function show($uuid) {
      $model = Order::where('uuid', $uuid)
          ->with('user', 'items', 'items.product','address', 'address.city', 'address.town', 'billing_address')
          ->withCount('items')
          ->orderBy('id', 'desc')
      ->first();

      if($model) {
          return view($this->view.'.show', compact('model'));
      }else {
          Session::flash('error_message', 'Lütfen hataları düzeltip tekrar dene!');
          return redirect()->back();
      }
  }

    public function completed($uuid) {
        $model = Order::where('uuid', $uuid)->where('status', Order::STATUS_CARGO)->first();

        if($model) {
            $model->status = Order::STATUS_COMPLETED;
            $model->update();
            Session::flash('success_message', 'İşlem başarılı!');
        }else {
            Session::flash('error_message', 'Lütfen hataları düzeltip tekrar dene!');
        }

        return redirect()->back();
    }

    public function cargo($uuid) {
        $model = Order::where('uuid', $uuid)
            ->whereIn('status', [Order::STATUS_CARGO, Order::STATUS_PROCCESS, Order::STATUS_NEW])
            ->with('user', 'items', 'items.product','address', 'address.city', 'address.town')
            ->withCount('items')
            ->orderBy('id', 'desc')
            ->first();

        if($model) {
            $cargos = CargoCompany::all_list();
            return view($this->view.'.form', compact('model', 'cargos'));
        }else {
            Session::flash('error_message', 'Lütfen hataları düzeltip tekrar dene!');
            return redirect()->back();
        }
    }

    public function billing($uuid) {
        $model = Order::where('uuid', $uuid)
            ->whereIn('status', [Order::STATUS_COMPLETED])
            ->with('user', 'items', 'items.product','address', 'address.city', 'address.town')
            ->withCount('items')
            ->orderBy('id', 'desc')
            ->first();

        if($model) {
            return view($this->view.'.billing_form', compact('model'));
        }else {
            Session::flash('error_message', 'Lütfen hataları düzeltip tekrar dene!');
            return redirect()->back();
        }
    }

    public function downloadBill($uuid)
    {
        $model = Order::where('uuid', $uuid)
            ->whereIn('status', [Order::STATUS_COMPLETED])
            ->with('lastFile')
            ->orderBy('id', 'desc')
            ->first();

        if($model && $model->lastFile) {
            $file= public_path('/uploads/'.$model->lastFile->path);

            return response()->download($file, $model->uuid.'.pdf', ['Content-Type: application/pdf']);
        }else {
            Session::flash('error_message', 'Geçersiz İşlem!');
            return redirect()->back();
        }
    }

    public function billing_save($uuid) {
        $errors = [];
        $model = Order::where('uuid', $uuid)
            ->whereIn('status', [Order::STATUS_COMPLETED])
           ->orderBy('id', 'desc')
            ->first();

        if(request()->post() && $model) {

            $inputs = request()->all();
            $rules = [
                "billing" => "required|mimes:pdf"
            ];

            $validator = Validator::make($inputs, $rules);
            $errors = $validator->getMessageBag()->toArray();

            if ($errors){
                Session::flash('error_message', 'Lütfen hataları düzeltip tekrar dene!');
                return redirect()->back()->withErrors($errors)->withInput();
            }else {
                $billing = request()->file('billing');
                $files[] = $billing;

                if(File::upload($files, $model)) {
                    Sms::order_billing($model);
                    Session::flash('success_message', 'Fatura başarılı bir şekilde kaydedildi!');
                    return redirect(route('admin.order.show', ['uuid' => $model->uuid]));
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
    public function cargo_save($uuid) {
        $errors = [];
        $model = Order::where('uuid', $uuid)
            ->whereIn('status', [Order::STATUS_CARGO, Order::STATUS_PROCCESS, Order::STATUS_NEW])
            ->with('user', 'items', 'items.product','address', 'address.city', 'address.town')
            ->withCount('items')
            ->orderBy('id', 'desc')
            ->first();

        if(request()->post() && $model) {

            $inputs = request()->all();
            $rules = [
                'cargo_id' => 'required|exists:cargo_companies,id',
                'folow_number' => 'required|max:155|min:3'
            ];

            $validator = Validator::make($inputs, $rules);
            $errors = $validator->getMessageBag()->toArray();

            if ($errors){
                Session::flash('error_message', 'Lütfen hataları düzeltip tekrar dene!');
                return redirect()->back()->withErrors($errors)->withInput();
            }else {

                unset($inputs['id']);
                unset($inputs['_token']);

                $model->status = Order::STATUS_CARGO;
                $model->cargo_id = $inputs['cargo_id'];
                $model->folow_number = $inputs['folow_number'];

                if($model->save()) {
                    Session::flash('success_message', 'Ürün başarılı bir şekilde kaydedildi!');
                    return redirect(route('admin.order.show', ['uuid' => $model->uuid]));
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
