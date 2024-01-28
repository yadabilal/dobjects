<div class="row">
    <div class="col-md-12">
        <div class="table-responsive">
            <table class="table table-border custom-table m-b-0">
                <thead>
                <tr>
                    <th>ID</th>
                    <th>Kullanıcı</th>
                    <th>Tarih</th>
                </tr>
                </thead>
                <tbody>
                @foreach($items as $item)
                    <tr>
                        <td>#{{$item->id}}</td>
                        <td>
                            {{$item->email}}
                        </td>
                        <td>{{$item->created_at()}}</td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
