<div class="row">
    <div class="col-md-12">
        <div class="table-responsive">
            <table class="table table-border custom-table m-b-0">
                <thead>
                <tr>
                    <th>ID</th>
                    <th>Kategori</th>
                    <th>Url</th>
                    <th>Tarih</th>
                    <th class="text-right">İşlemler</th>
                </tr>
                </thead>
                <tbody>
                @foreach($items as $item)
                    <tr>
                        <td>#{{$item->id}}</td>
                        <td>
                            {{$item->name}}
                        </td>

                        <td>
                            {{$item->url}}
                        </td>

                        <td>{{$item->created_at()}}</td>
                        <td class="text-right">
                            <div class="dropdown dropdown-action">
                                <a href="#" class="action-icon dropdown-toggle" data-toggle="dropdown" aria-expanded="false"><i class="fa fa-ellipsis-v"></i></a>
                                <div class="dropdown-menu dropdown-menu-right">
                                    <li>
                                    <a class="dropdown-item" href="{{route('admin.category.update', ['uuid' => $item->uuid])}}">
                                        <i class="fa fa-pencil m-r-5"></i> Güncelle</a>
                                    </li>
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
