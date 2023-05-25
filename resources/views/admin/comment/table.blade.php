<div class="row">
    <div class="col-md-12">
        <div class="table-responsive">
            <table class="table table-border custom-table m-b-0">
                <thead>
                <tr>
                    <th>ID</th>
                    <th>Kullanıcı</th>
                    <th>Ürün</th>
                    <th>Yorun Ve Puan</th>
                    <th>Durum</th>
                    <th>Tarih</th>
                    <th class="text-right">İşlemler</th>
                </tr>
                </thead>
                <tbody>
                @foreach($items as $item)
                    <tr>
                        <td>#{{$item->id}}</td>
                        <td>
                            {{$item->user->full_name()}}
                            <br>
                            ({{'@'.$item->user->username}})
                        </td>

                        <td>
                            {{$item->product->name}}
                        </td>

                        <td>
                            @for($i=0; $i< $item->rate ; $i++)
                                <i class="fa fa-star"></i>
                            @endfor
                                ({{$item->rate}} Puan)
                            <br>
                                {{$item->review}}
                        </td>
                        <td>
                            <span class="{{$item->readableStatusColor(true)}}">
                                {{$item->readableStatus(true)}}
                            </span>
                        </td>
                        <td>{{$item->created_at()}}</td>
                        <td class="text-right">
                            <div class="dropdown dropdown-action">
                                <a href="#" class="action-icon dropdown-toggle" data-toggle="dropdown" aria-expanded="false"><i class="fa fa-ellipsis-v"></i></a>
                                <div class="dropdown-menu dropdown-menu-right">
                                    @if($item->status == \App\Model\Comment::STATUS_UNPUBLISH)
                                        <li>
                                        <a class="dropdown-item" href="{{route('admin.comment.publish', ['id' => $item->id])}}">
                                            <i class="fa fa-check m-r-5"></i> Yayınla</a>
                                        </li>
                                    @else
                                        <li>
                                            <a class="dropdown-item" href="{{route('admin.comment.unpublish', ['id' => $item->id])}}">
                                                <i class="fa fa-trash m-r-5"></i> Yayından Kaldır</a>
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
