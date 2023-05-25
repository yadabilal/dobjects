@extends('admin.layouts.app')
@section('content')
  <div class="row">
    <div class="col-sm-4 col-4">
      <h4 class="page-title">Kullanıcılar</h4>
    </div>
  </div>

    <form>
      <div class="row filter-row">
        <div class="col-sm-6 col-md-6">
          <div class="form-group form-focus">
            <label class="focus-label">Ad Soyad</label>
            <input type="text" class="form-control floating" name="name" value="{{request('name')}}">
          </div>
        </div>
        <div class="col-sm-6 col-md-3">
          <div class="form-group form-focus select-focus">
            <label class="focus-label">Durum</label>
            <select class="select floating" name="status">
              <option value="">Tümü</option>
              <option value="{{\App\User::STATUS_COMPLETED}}">Onaylanan</option>
              <option value="{{\App\User::STATUS_STEP_THIRD}}">Kullanıcı Adı Bekleyen</option>
              <option value="{{\App\User::STATUS_STEP_SECOND}}">Telefon Onayı Bekleyen</option>
            </select>
          </div>
        </div>
        <div class="col-sm-6 col-md-3">
          <button type="submit" class="btn btn-primary btn-block"> Ara </button>
        </div>
      </div>
    </form>

  <div class="row">
    <div class="col-md-12">
      <div class="table-responsive">
        <table class="table table-border custom-table m-b-0">
          <thead>
          <tr>
            <th>ID</th>
            <th>Ad Soyad</th>
            <th>Kullanıcı Adı</th>
            <th>Telefon Numarası</th>
            <th>Durum</th>
            <th><a href="{{route('admin.user.index', ['order_by' => 'waitingOrders_count', 'dir' => @$_GET['dir']=='desc' ? 'asc':'desc'])}}">Bekleyen Sipariş</a></th>
            <th>Tarih</th>
            <th class="text-right">İşlemler</th>
          </tr>
          </thead>
          <tbody>
          @foreach($models as $model)
          <tr>
            <td>#{{$model->id}}</td>
            <td>{{$model->full_name()}}</td>
            <td>{{$model->username}}</td>
            <td>{{$model->phone}}</td>
            <td>{{$model->status()}}</td>
            <td>
                @if($model->waiting_orders_count)
                    <a href="{{route('admin.order.index').'?user_id='.$model->id.'&status='.\App\Model\Order::STATUS_NEW}}" class="badge badge-danger">
                        {{$model->waiting_orders_count}} Bekleyen Sipariş
                    </a>
                @else
                    -
                @endif


            </td>
            <td>{{$model->created_at()}}</td>
            <td class="text-right">
              <div class="dropdown dropdown-action">
                <a href="#" class="action-icon dropdown-toggle" data-toggle="dropdown" aria-expanded="false"><i class="fa fa-ellipsis-v"></i></a>
                <div class="dropdown-menu dropdown-menu-right">
                  </div>
              </div>
            </td>
          </tr>
            @endforeach
          </tbody>
        </table>
      </div>
    </div>
  </div>
  <div class="row staff-grid-row mt-3">
    <div class="col-sm-12">
      <div class="see-all text-center">
        {{ $models->appends(request()->input())->links() }}
      </div>
    </div>
  </div>
@endsection
