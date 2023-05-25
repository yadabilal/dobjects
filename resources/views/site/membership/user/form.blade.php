@extends('layouts.app')
@section('meta')
    <title>Deek Objects | Profil Güncelle</title>
    <meta name="keywords" content="">
    <meta name="description" content="" />
@endsection
@section('content')
    @include("layouts.breadcrumb", ["title" => "Profil Güncelle"])
    <div class="section-padding">
        <div class="section-container p-l-r">
            <div class="page-my-account">
                <div class="my-account-wrap clearfix">
                    @include("layouts.lef_menu")
                    <div class="my-account-content tab-content">
                        <div class="tab-pane fade show active">
                            <div class="my-account-account-details">
                                <form method="post" action="" enctype="multipart/form-data" class="edit-account">
                                    @csrf

                                    <div class="column is-6 field field-group">
                                        <p class="form-row control has-icon {{ $errors->has('username') ? ' has-validation has-error' : '' }}">
                                            <label for="account_first_name">Kullanıcı Adın <span class="required">*</span></label>
                                            <input type="text" class="input-text input is-fade required" value="{{old('username') ? : $user->username}}" name="username" maxlength="50">
                                        @error('username')
                                        <p class="text-error">{{$message}}</p>
                                        @enderror
                                        </p>
                                    </div>

                                    <div class="column is-6 field field-group">
                                        <p class="form-row control has-icon {{ $errors->has('gender') ? ' has-validation has-error' : '' }}">
                                            <label for="account_first_name">Cinsiyetin <span class="required">*</span></label>
                                            <select class="input-text input is-fade required" name="gender">
                                                <option value="">Seçiniz</option>
                                                @foreach($genders as $key => $gender)
                                                    <option value="{{$key}}" {{old('gender')==$key ? 'selected': ($user->gender == $key ? 'selected' : '')}}>{{$gender}}</option>
                                                @endforeach
                                            </select>
                                        @error('gender')
                                        <p class="text-error">{{$message}}</p>
                                        @enderror
                                        </p>
                                    </div>

                                    <div class="column is-6 field field-group">
                                        <p class="form-row control has-icon {{ $errors->has('name') ? ' has-validation has-error' : '' }}">
                                            <label for="account_first_name">Adın <span class="required">*</span></label>
                                            <input type="text" class="input-text input is-fade required" value="{{old('name') ? : $user->name}}" name="name" maxlength="25">
                                        @error('name')
                                        <p class="text-error">{{$message}}</p>
                                        @enderror
                                        </p>
                                    </div>

                                    <div class="column is-6 field field-group">
                                        <p class="form-row control has-icon {{ $errors->has('surname') ? ' has-validation has-error' : '' }}">
                                            <label for="account_first_name">Soyadın <span class="required">*</span></label>
                                            <input type="text" class="input-text input is-fade required" value="{{old('surname') ? : $user->surname}}" name="surname"  maxlength="30">
                                        @error('surname')
                                        <p class="text-error">{{$message}}</p>
                                        @enderror
                                        </p>
                                    </div>

                                    <div class="clear"></div>
                                    <p class="form-row">
                                        <button type="button" data-after-message='true'
                                                data-action="{{route('profile.check')}}"
                                                class="button is-solid accent-button form-button
                        buttonDisable btn-save is-fullwidth"
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

