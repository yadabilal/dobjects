@extends('admin.layouts.app')
@section('content')
    <div class="row">
        <div class="col-sm-4 col-4">
            <h4 class="page-title">Aboneler</h4>
        </div>
    </div>
    @include('admin.subscribe.table', ['items' => $models ?: []])

    <div class="row staff-grid-row mt-3">
        <div class="col-sm-12">
            <div class="see-all text-center">
                {{ $models->appends(request()->input())->links() }}
            </div>
        </div>
    </div>
@endsection
