@extends('layouts.app')
@section('meta')
    <title>Deek Objects | Siparişlerim</title>
    <meta name="keywords" content="">
    <meta name="description" content="" />
@endsection
@section('content')
    @include("layouts.breadcrumb", ["title" => "Siparişlerim"])
    <div class="section-padding">
        <div class="section-container p-l-r">
            <div class="page-my-account">
                <div class="my-account-wrap clearfix">
                    @include("layouts.lef_menu")
                    <div class="my-account-content tab-content">
                        <div class="tab-pane fade show active">
                            <div id="shop-page" class="my-account-orders stats-wrapper shop-wrapper">
                                <div class="table-responsive">
                                    <table class="table">
                                        <thead>
                                        <tr>
                                            <th>Sipariş</th>
                                            <th>Tarih</th>
                                            <th>Durum</th>
                                            <th>Toplam</th>
                                            <th>İşlem</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        @if(!$items->total())
                                            <tr>
                                               <td colspan="5">
                                                   <div class="alert alert-danger fullwidth" role="alert">
                                                       Herhangi bir sonuç bulunamadı!
                                                   </div>
                                               </td>
                                            </tr>

                                        @endif

                                        @foreach($items as $item)

                                            <tr>
                                                <td>{{$item->number}}</td>
                                                <td>{{$item->created_at()}}</td>
                                                <td>{{$item->status()}}</td>
                                                <td> {{$item->readableTotalDiscountPrice().' ('.$item->total_quantity.' ürün için)'}} </td>
                                                <td>
                                                    @if($item->status == \App\Model\Order::STATUS_CARGO)
                                                    <a href="{{$item->cargo_url()}}" target="_blank" class="btn-small d-block">Kargo</a>
                                                        <br>
                                                        <p>Takip Numarası: {{$item->folow_number}}</p>
                                                    @elseif($item->status == \App\Model\Order::STATUS_COMPLETED && $item->lastFile)
                                                    <a href="{{route('order.downloadBilling', ['uuid' => $item->uuid])}}" class="btn-small d-block">
                                                        <i class="fa fa-download"></i> Faturayı İndir</a>
                                                    @else
                                                        -
                                                    @endif
                                                </td>
                                            </tr>
                                        @endforeach

                                        </tbody>
                                    </table>
                                </div>
                                {{ $items->links('layouts.pagination') }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection

