@extends('admin.layouts.app')
@section('content')
    <div class="row">
        <div class="col-sm-4 col-4">
            <h4 class="page-title">Anaysafa Ayarları</h4>
        </div>
    </div>
    <div class="row">
        <div class="contact-box">
            <div class="row">
                <div class="contact-cat col-sm-4 col-lg-3">
                    <a href="{{route('admin.menu.create', ['type' => $type])}}" class="btn btn-primary btn-block"><i class="fa fa-plus"></i> İçerik Ekle</a>
                    <div class="roles-menu">
                        <ul>
                            <li class="{{route('admin.menu.index', ['type' => \App\Model\HomePage::TYPE_1]) == route('admin.menu.index', ['type' => $type]) ? 'active' : ''}}"><a href="{{route('admin.menu.index', ['type' => \App\Model\HomePage::TYPE_1])}}">Slider Alanı</a></li>
                            <li class="{{route('admin.menu.index', ['type' => \App\Model\HomePage::TYPE_2]) == route('admin.menu.index', ['type' => $type]) ? 'active' : ''}}"><a href="{{route('admin.menu.index', ['type' => \App\Model\HomePage::TYPE_2])}}">Dönen Yazı Alanı</a></li>
                            <li class="{{route('admin.menu.index', ['type' => \App\Model\HomePage::TYPE_3]) == route('admin.menu.index', ['type' => $type]) ? 'active' : ''}}"><a href="{{route('admin.menu.index', ['type' => \App\Model\HomePage::TYPE_3])}}">Ön Bilgilendirme Alanı</a></li>
                            <li class="{{route('admin.menu.index', ['type' => \App\Model\HomePage::TYPE_4]) == route('admin.menu.index', ['type' => $type]) ? 'active' : ''}}"><a href="{{route('admin.menu.index', ['type' => \App\Model\HomePage::TYPE_4])}}">Kategori Alanı</a></li>
                            <li class="{{route('admin.menu.index', ['type' => \App\Model\HomePage::TYPE_5]) == route('admin.menu.index', ['type' => $type]) ? 'active' : ''}}"><a href="{{route('admin.menu.index', ['type' => \App\Model\HomePage::TYPE_5])}}">Abone Olma Alanı</a></li>
                            <li class="{{route('admin.menu.index', ['type' => \App\Model\HomePage::TYPE_6]) == route('admin.menu.index', ['type' => $type]) ? 'active' : ''}}"><a href="{{route('admin.menu.index', ['type' => \App\Model\HomePage::TYPE_6])}}">Size Özel Tasarımlar Alanı</a></li>
                            <li class="{{route('admin.menu.index', ['type' => \App\Model\HomePage::TYPE_7]) == route('admin.menu.index', ['type' => $type]) ? 'active' : ''}}"><a href="{{route('admin.menu.index', ['type' => \App\Model\HomePage::TYPE_7])}}">Markalar Alanı</a></li>
                            <li class="{{route('admin.menu.index', ['type' => \App\Model\HomePage::TYPE_8]) == route('admin.menu.index', ['type' => $type]) ? 'active' : ''}}"><a href="{{route('admin.menu.index', ['type' => \App\Model\HomePage::TYPE_8])}}">Popup Alanı</a></li>
                        </ul>
                    </div>
                </div>
                <div class="contacts-list col-sm-8 col-lg-9">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="table-responsive">
                                <table class="table table-border custom-table m-b-0">
                                    <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>Görsel</th>
                                        <th>Başlık</th>
                                        <th>Durum</th>
                                        <th>Sıra</th>
                                        <th class="text-right">İşlemler</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @foreach($models as $model)
                                        <tr>
                                            <td>#{{$model->id}}</td>
                                            <td>
                                                <div class="product-det">
                                                    <img src="{{$model->getPic()}}" alt="" style="max-height: 100%;">
                                                </div>
                                            </td>
                                            <td>{{$model->title}}</td>
                                            <td>
                                                <span class="{{$model->readableStatusColor()}}">{{$model->readableStatus()}}</span>
                                            </td>
                                            <td>{{$model->sorting}}</td>

                                            <td class="text-right">
                                                <div class="dropdown dropdown-action">
                                                    <a href="#" class="action-icon dropdown-toggle" data-toggle="dropdown" aria-expanded="false"><i class="fa fa-ellipsis-v"></i></a>
                                                    <div class="dropdown-menu dropdown-menu-right">
                                                        <li>
                                                            <a class="dropdown-item" href="{{route('admin.menu.update', ['id' => $model->id])}}">
                                                                <i class="fa fa-pencil m-r-5"></i> Güncelle</a>
                                                        </li>
                                                        @if($model->status == \App\Model\HomePage::STATUS_PUBLISH)
                                                            <li>
                                                                <a class="dropdown-item" href="{{route('admin.menu.unpublish', ['id' => $model->id])}}">
                                                                    <i class="fa fa-trash-o m-r-5"></i> Yayından Kaldır</a>
                                                            </li>
                                                        @else
                                                            <li>
                                                                <a class="dropdown-item" href="{{route('admin.menu.publish', ['id' => $model->id])}}">
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
                </div>
            </div>
        </div>
    </div>
@endsection
