@extends('layouts.auth')
@section('meta')
    <title> Deek Objects | Kaydol</title>
    <meta name="keywords" content="">
    <meta name="description" content="" />
@endsection
@section('content')
    <div class="signup-wrapper">

        <div class="fake-nav">
            <a href="{{url('/')}}" class="logo">
                <img src="{{ asset('theme/assets/img/logo/friendkit.svg') }}" width="112" height="28" alt="">
            </a>
        </div>

        <div class="process-bar-wrap">
            <div class="process-bar">
                <div class="progress-wrap">
                    <div class="track"></div>
                    <div class="bar"></div>
                    <div id="step-dot-1" class="dot is-first" data-step="0">
                        <i data-feather="smile"></i>
                    </div>
                    <div id="step-dot-2" class="dot is-second" data-step="25">
                        <i data-feather="user"></i>
                    </div>
                    <div id="step-dot-3" class="dot is-third" data-step="50">
                        <i data-feather="lock"></i>
                    </div>
                    <div id="step-dot-4" class="dot is-fourth" data-step="75">
                        <i data-feather="image"></i>
                    </div>
                    <div id="step-dot-5" class="dot is-fifth" data-step="100">
                        <i data-feather="flag"></i>
                    </div>
                </div>
            </div>
        </div>

        <div class="outer-panel">
            <div class="outer-panel-inner">
                <div class="process-title">
                    <h2 id="step-title-1" class="step-title">Hoşgeldin, hemen tarzınını seç.</h2>
                    <h2 id="step-title-2" class="step-title">Kendin hakkında bir şeyler söyle.</h2>
                    <h2 id="step-title-3" class="step-title">Telefon Numaranı Onayla!</h2>
                    <h2 id="step-title-4" class="step-title">Kitap Okuduğun Adresi Paylaş!</h2>
                    <h2 id="step-title-5" class="step-title">İşte, şimdi hazırsın!</h2>
                </div>

                <div id="signup-panel-1" class="process-panel-wrap">
                    <div class="columns">
                        <div class="column is-2">
                        </div>
                        <div class="column is-4">
                            <div class="account-type">
                                <img src="{{ asset('theme/assets/img/illustrations/signup/company.svg') }}" alt="">
                                <h3>Kurumsal</h3>
                                <p>Kitap bağışı alabilmek için kurumsal hesap oluşturun. ( Kütüphaneler, okullar vb. )</p>
                                <a class="button is-fullwidth is-rounded">
                                    Çok Yakında
                                </a>
                            </div>
                        </div>
                        <div class="column is-4">
                            <div class="account-type">
                                <img src="{{ asset('theme/assets/img/illustrations/signup/personal.svg') }}" alt="">
                                <h3>Kişisel</h3>
                                <p>Ücretsiz kitap okuyabilmek, kitaplarınızı paylaşma için kişisel hesap oluşturun. ( Herkes )</p>
                                <a class="button is-fullwidth is-rounded process-button" data-step="step-dot-2">
                                    Devam Et
                                </a>
                            </div>
                        </div>

                    </div>
                </div>

                <div id="signup-panel-2" class="process-panel-wrap is-narrow">
                    <div class="form-panel">
                        <div class="field">
                            <label>Kullanıcı Adın</label>
                            <div class="control">
                                <input type="text" class="input" maxlength="50" id="username" name="username" placeholder="Sana kısaca ne diye hitap edelim?">
                            </div>
                        </div>
                        <div class="field">
                            <label>Telefon Numaran</label>
                            <div class="control">
                                <input type="text" class="input" id="phone" name="phone" placeholder="Mutlu haberleri sana nasıl ulaştıralım?">
                            </div>
                        </div>

                        <div class="field">
                            <label>Şifren</label>
                            <div class="control">
                                <input type="password" class="input" id="password" name="password" placeholder="Seni nasıl koruyalım?">
                            </div>
                        </div>
                    </div>

                    <div class="buttons">
                        <a class="button is-rounded process-button is-next" data-step="step-dot-3">Kaydol</a>
                    </div>
                </div>

                <div id="signup-panel-3" class="process-panel-wrap is-narrow">
                    <div class="form-panel">
                        <div class="field">
                            <label>Telefon Numaran</label>
                            <div class="control">
                                <input type="text" class="input" id="phone_confirm" name="phone_confirm" disabled placeholder="Mutlu haberleri sana nasıl ulaştıralım?" value="{{@$user ? $user->phone : ''}}">
                                <div id="phone_confirm_process" class="text-info">
                                    <a class="change-phone">Değiştir</a> ya da <a class="again-code">Yeni Kod Gönder</a>
                                </div>
                            </div>
                        </div>
                        <div class="field">
                            <label>Onay Kodun</label>
                            <div class="control">
                                <input type="text" class="input" id="phone_code" name="phone_code" maxlength="4" placeholder="Onay kodunu kimse ile paylaşmamalısın.">
                            </div>
                        </div>
                    </div>

                    <div class="buttons">
                        <a class="button is-rounded process-button is-next" data-step="step-dot-4">Onayla</a>
                    </div>
                </div>

                <div id="signup-panel-4" class="process-panel-wrap is-narrow">
                    <div class="form-panel">
                        <div class="field">
                            <label>Şehir</label>
                            <div class="control">
                                <select class="input city" name="city_id" id="city_id">
                                    <option value="">Seçiniz</option>
                                    @foreach($cities as $city)
                                        <option value="{{$city->uuid}}">{{$city->name}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="field">
                            <label>İlçe</label>
                            <div class="control">
                                <select class="input town" name="town_id" id="town_id">
                                    <option value="">Önce Şehir Seçiniz</option>
                                </select>
                            </div>
                        </div>

                        <div class="field">
                            <label>Detay</label>
                            <div class="control">
                                <input type="text" class="input" id="detail" name="detail" placeholder="Okumak istediğin kitaplar nereye gelsin?">
                            </div>
                        </div>
                    </div>
                    <div class="buttons">
                        <a class="button is-rounded process-button is-next" data-step="step-dot-5">İleri</a>
                    </div>
                </div>

                <div id="signup-panel-5" class="process-panel-wrap is-narrow">
                    <div class="form-panel">
                        <img class="success-image" src="{{ asset('theme/assets/img/illustrations/signup/mailbox.svg') }}" alt="">
                        <div class="success-text">
                            <h3>Tebrikler, artık ücretsiz kitap okumak için hazırsın.</h3>
                            <p> Ücretsiz kitap okumanın keyfini çıkarmaya hazır mısın?.</p>
                            <a id="signup-finish" href="{{url('kitaplar')}}" class="button is-fullwidth is-rounded">Başla</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!--Edit Credit card Modal-->
        <div id="crop-modal" class="modal mdl-modal is-small crop-modal is-animated">
            <div class="modal-background"></div>
            <div class="modal-content">
                <div class="modal-card">
                    <header class="modal-card-head">
                        <div class="modal-card-title">
                            <span>Görünüşünü Ayarla</span>
                        </div>
                        <button class="mdl-modal-close" aria-label="close">
                            <i data-feather="x"></i>
                        </button>
                    </header>
                    <div class="modal-card-body">
                        <div id="cropper-wrapper" class="cropper-wrapper">

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script>
        var stepValue = '{{!@$user ? 0 : ($user->status == \App\User::STATUS_STEP_FIRST ? 25 : ($user->status == \App\User::STATUS_STEP_TWO ? 50 : ($user->status== \App\User::STATUS_STEP_THREE ? 75 : 100)))}}';
        var step = '{{!@$user ? 1 : ($user->status == \App\User::STATUS_STEP_FIRST ? 2 : ($user->status == \App\User::STATUS_STEP_TWO ? 3 : ($user->status== \App\User::STATUS_STEP_THREE ? 4 : 5)))}}';
    </script>
@endsection
