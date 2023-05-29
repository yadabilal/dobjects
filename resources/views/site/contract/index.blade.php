@extends('layouts.app')
@section('meta')
    <title>Deek Objects | {{$page->title}}</title>
    <meta name="keywords" content="{{$page->title}}">
    <meta name="description" content="{{$page->title}}" />
@endsection
@section('content')
    @include('layouts.breadcrumb', ['title' => $page->title])
    <div id="content" class="site-content" role="main">
        <div class="section-padding">
            <div class="section-container p-l-r">
                <div class="page-faq">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="faq-section">
                                <div class="section-title">
                                    <h2>{{$page->title}}</h2>
                                </div>
                                <div class="section-content">
                                    {!! $page->detail !!}
                                </div>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

