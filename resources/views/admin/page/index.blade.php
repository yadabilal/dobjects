@extends('admin.layouts.app')
@section('content')
  <div class="row">
      <div class="col-sm-7 col-7">
      <h4 class="page-title">Sözleşmeler</h4>
    </div>
      <div class="col-sm-5 text-right m-b-30">
          <a href="{{route('admin.page.create')}}" class="btn btn-primary btn-rounded"><i class="fa fa-plus"></i> Sözleşme Ekle</a>
      </div>
  </div>

  <form>
    <div class="row filter-row">
      <div class="col-sm-6 col-md-6">
        <div class="form-group form-focus">
          <label class="focus-label">Sözleşme başlığı</label>
          <input type="text" class="form-control floating" name="title" value="{{request('title')}}">
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
                <th>Başlık</th>
                <th>Url</th>
                <th>Durum</th>
                <th>Tarih</th>
                  <th class="text-right">İşlemler</th>
              </tr>
          </thead>
          <tbody>
          @foreach($models as $model)
            <tr>
              <td>#{{$model->id}}</td>
              <td>{{$model->title}}</td>
              <td>{{$model->url}}</td>
                <td>
                    <span class="{{$model->readableStatusColor()}}">{{$model->readableStatus()}}</span>

                </td>
              <td>{{$model->created_at()}}</td>
                <td class="text-right">
                    <div class="dropdown dropdown-action">
                        <a href="#" class="action-icon dropdown-toggle" data-toggle="dropdown" aria-expanded="false"><i class="fa fa-ellipsis-v"></i></a>
                        <div class="dropdown-menu dropdown-menu-right">
                            <li>
                                <a class="dropdown-item" href="{{route('admin.page.update', ['id' => $model->id])}}">
                                    <i class="fa fa-eye m-r-5"></i> Güncelle
                                </a>
                            </li>
                            @if($model->status == \App\Model\Page::STATUS_PUBLISH)
                                <li>
                                    <a class="dropdown-item" href="{{route('admin.page.unpublish', ['id' => $model->id])}}">
                                        <i class="fa fa-trash-o m-r-5"></i> Yayından Kaldır</a>
                                </li>
                            @else
                                <li>
                                    <a class="dropdown-item" href="{{route('admin.page.publish', ['id' => $model->id])}}">
                                        <i class="fa fa-check m-r-5"></i> Yayınla</a>
                                </li>
                            @endif

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
