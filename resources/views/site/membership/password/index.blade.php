@extends('layouts.app')
@section('meta')
    <title>Deek Objects | Güvenlik Ve Şifre</title>
    <meta name="keywords" content="">
    <meta name="description" content="" />
@endsection
@section('content')
    @include("layouts.breadcrumb", ["title" => "Güvenlik Ve Şifre"])
    <div class="section-padding">
        <div class="section-container p-l-r">
            <div class="page-my-account">
                <div class="my-account-wrap clearfix">
                    @include("layouts.lef_menu")
                    <div class="my-account-content tab-content">
                        <div class="tab-pane fade show active">
                            <div class="my-account-account-details">
                                <form method="post" action="" class="edit-account">
                                    @csrf
                                    <div class="column is-6 field field-group">
                                        <p class="form-row control has-icon {{ $errors->has('password') ? ' has-validation has-error' : '' }}">
                                            <label for="account_first_name">Şimdiki Şifren <span class="required">*</span></label>
                                            <input type="password" class="input-text input is-fade required" value="{{old('password')}}" name="password" {{old('change_phone') ? 'disabled' : ''}}>
                                        @error('password')
                                        <p class="text-error">{{$message}}</p>
                                        @enderror
                                        </p>
                                    </div>

                                    <div class="column is-6 field field-group">
                                        <p class="form-row control has-icon {{ $errors->has('new_password') ? ' has-validation has-error' : '' }}">
                                            <label for="account_first_name">Yeni Şifren <span class="required">*</span></label>
                                            <input type="password" class="input-text input is-fade required" value="{{old('new_password')}}" name="new_password" {{old('change_phone') ? 'disabled' : ''}}>
                                        @error('new_password')
                                        <p class="text-error">{{$message}}</p>
                                        @enderror
                                        </p>
                                    </div>

                                    <div class="column is-6 field field-group">
                                        <p class="form-row control has-icon {{ $errors->has('confirm_password') ? ' has-validation has-error' : '' }}">
                                            <label for="account_first_name">Tekrar Yeni Şifren <span class="required">*</span></label>
                                            <input type="password" class="input-text input is-fade required" value="{{old('confirm_password')}}" name="confirm_password" {{old('change_phone') ? 'disabled' : ''}}>
                                        @error('confirm_password')
                                        <p class="text-error">{{$message}}</p>
                                        @enderror
                                        </p>
                                    </div>


                                    <fieldset>
                                        <legend>Telefon Değişikliği</legend>
                                        <div class="column is-6 field field-group">
                                            <p class="form-row control has-icon">Telefon numaran zaten onaylı. Telefon numaranı değiştirirsen onay kodu gelecektir.</p>
                                        </div>

                                        <div class="rememberme-lost">
                                            <div class="remember-me">
                                                <input type="checkbox" value="forever" class="is-switch is-success phone-switch" name="change_phone" value="1" {{old('change_phone') ? 'checked' : ''}}>
                                                <label class="inline">Yine de telefon numaramı değiştirmek istiyorum!</label>
                                            </div>
                                        </div>

                                        <div class="column is-6 field field-group">
                                            <p class="form-row control has-icon {{ $errors->has('phone') ? ' has-validation has-error' : '' }}">
                                                <label for="account_first_name">Telefon Numaran <span class="required">*</span></label>
                                                <input type="text" class="input-text input is-fade phone" value="{{old('phone') ? : $user->phone}}" name="phone" {{old('change_phone') ? '' : 'disabled'}} maxlength="14">
                                                <span>Telefon numarana onay kodu gelecektir. Standart ücrete tabidir.</span>
                                            @error('phone')
                                            <p class="text-error">{{$message}}</p>
                                            @enderror
                                            </p>
                                        </div>
                                    </fieldset>

                                    <div class="clear"></div>
                                    <p class="form-row">
                                        <button t data-disable="{{old('change_phone') ? 'true' : 'false'}}"
                                                type="button"
                                                data-action="{{route('security.check')}}"
                                                data-after-message='true'
                                                class="button is-solid accent-button form-button
                                                 buttonDisable buttonCheck btn-save is-fullwidth"
                                                value="Kaydet"
                                        >Kaydet</button>
                                    </p>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection

