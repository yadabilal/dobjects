@extends('layouts.app')
@section('meta')
  <title> Deek Objects | İletişim</title>
  <meta name="keywords" content="{{@$settings['meta_keywords']}}">
  <meta name="description" content="{{@$settings['meta_description']}}" />
@endsection
@section('content')
    @include('layouts.breadcrumb', ['title' => 'İletişim'])
    <div id="content" class="site-content" role="main">
        <div class="page-contact">
            @if(\App\Model\Setting::by_key('maps'))
                <section class="section section-padding">
                    <div class="section-container small">
                        <div class="block block-contact-map">
                            <div class="block-widget-wrap">
                                @if(@$settings['maps'])
                                    {!! $settings['maps'] !!}
                                @endif
                            </div>
                        </div>
                    </div>
                </section>
            @endif


            <section class="section section-padding m-b-70">
                <div class="section-container">
                    <div class="block block-contact-info">
                        <div class="block-widget-wrap">
                            <div class="info-icon">
                                <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" class="svg-icon2 plant" x="0" y="0" viewBox="0 0 512 512" style="enable-background:new 0 0 512 512" xml:space="preserve"><g><path xmlns="http://www.w3.org/2000/svg" d="m320.174 28.058a8.291 8.291 0 0 0 -7.563-4.906h-113.222a8.293 8.293 0 0 0 -7.564 4.907l-66.425 148.875a8.283 8.283 0 0 0 7.564 11.655h77.336v67.765a20.094 20.094 0 1 0 12 0v-67.765h27.7v288.259h-48.441a6 6 0 0 0 0 12h108.882a6 6 0 0 0 0-12h-48.441v-288.259h117.04a8.284 8.284 0 0 0 7.564-11.657zm-103.874 255.567a8.094 8.094 0 1 1 8.094-8.093 8.1 8.1 0 0 1 -8.094 8.093zm-77.61-107.036 63.11-141.437h108.4l63.11 141.437z" fill="" data-original="" style=""></path></g></svg>
                            </div>
                            <div class="info-title">
                                <h2>Yardıma mı ihtiyacınız var?</h2>
                            </div>
                            <div class="info-items">
                                <div class="row">
                                    <div class="col-md-4 sm-m-b-30">
                                        <div class="info-item">
                                            <div class="item-tilte">
                                                <h2>Telefon</h2>
                                            </div>
                                            <div class="item-content">
                                                {{@$settings['mobile_phone']}}
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-4 sm-m-b-30">
                                        <div class="info-item">
                                            <div class="item-tilte">
                                                <h2>Email Adresi</h2>
                                            </div>
                                            <div class="item-content">
                                                <p>
                                                    {{@$settings['email']}}</p>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="info-item">
                                            <div class="item-tilte">
                                                <h2>Adres</h2>
                                            </div>
                                            <div class="item-content small-width">
                                                {{@$settings['address']}}
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </section>

            <section class="section section-padding contact-background m-b-0">
                <div class="section-container small">
                    <div class="block block-contact-form">
                        <div class="block-widget-wrap">
                            <div class="block-title">
                                <h2>Bizimle İletişime Geçin</h2>
                                <div class="sub-title">İstek, öneri, şikayet ve sorunlarınız için bizimle buradan iletişime geçebilirsin.</div>
                            </div>
                            <div class="block-content">
                                <form action="#" method="post" class="contact-form" novalidate="novalidate">
                                    @csrf
                                    <div class="contact-us-form">
                                        <div class="row">
                                            <div class="col-sm-12 col-md-4">
                                                <label class="required">Adın Soyadın</label><br>
                                                <span class="form-control-wrap {{ $errors->has('name') ? ' has-validation has-error' : '' }}">
                                                    <input type="text" placeholder="Adın Soyadın*" name="name" value="{{old('name') ? : ($user ? $user->full_name() : '')}}" maxlength="50" class="form-control required" aria-required="true">
                                                </span>
                                                @error('name')
                                                <p class="text-error">{{$message}}</p>
                                                @enderror
                                            </div>
                                            <div class="col-sm-12 col-md-4">
                                                <label class="required">Email Adresin</label><br>
                                                <span class="form-control-wrap {{ $errors->has('email') ? ' has-validation has-error' : '' }}">
                                                    <input type="email" placeholder="Email Adresin*" name="email" value="{{old('email') ? : ($user ? $user->email : '')}}" maxlength="150" class="required form-control" aria-required="true">
                                                </span>
                                                @error('email')
                                                <p class="text-error">{{$message}}</p>
                                                @enderror
                                            </div>

                                            <div class="col-sm-12 col-md-4">
                                                <label class="required">Konu</label><br>
                                                <span class="form-control-wrap  {{ $errors->has('subject') ? ' has-validation has-error' : '' }}">
                                                    <input type="text" placeholder="Konu*" name="subject" value="{{old('subject') ? : ''}}" maxlength="50" class="required form-control" aria-required="true">
                                                </span>
                                                @error('subject')
                                                <p class="text-error">{{$message}}</p>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-sm-12">
                                                <label class="required">Mesajın</label><br>
                                                <span class="form-control-wrap {{ $errors->has('detail') ? ' has-validation has-error' : '' }}">
                                                    <textarea name="detail" placeholder="Mesajını yaz..." maxlength="255" cols="40" rows="10" class="required form-control" aria-required="true"></textarea>
                                                </span>
                                                @error('detail')
                                                <p class="text-error">{{$message}}</p>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="form-button">
                                            <input data-action="{{route('support.check')}}" data-after-message="true" type="button"
                                                   class="button is-solid accent-button is-fullwidth raised buttonDisable btn-save" value="Gönder">
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </section>
        </div>
    </div><!-- #content -->
@endsection
