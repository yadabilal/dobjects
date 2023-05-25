<?php

namespace App\Http\Controllers\MemberShipPanel;

use App\Http\Controllers\Controller;
use App\Model\Order;
use Illuminate\Support\Facades\Session;

class OrderController extends Controller
{

  public function __construct()
  {
    $this->middleware('auth');
  }

  public function index()
  {
      $items = $this->user->orders()->paginate(Order::PAGINATION_LIST, ['*'], 'sayfa');

    return view('site.membership.order.index', compact('items'));
  }

    public function downloadBill($uuid)
    {
        $model = $this->user->orders()->where(
            'uuid', $uuid
        )->where('status', Order::STATUS_COMPLETED)
            ->first();

        if($model && $model->lastFile) {
            $file= public_path('/uploads/'.$model->lastFile->path);

            return response()->download($file, $model->uuid.'.pdf', ['Content-Type: application/pdf']);
        }else {
            Session::flash('error_message', 'Geçersiz İşlem!');
            return redirect()->back();
        }
    }
}
