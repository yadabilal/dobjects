<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Validation Language Lines
    |--------------------------------------------------------------------------
    |
    | The following language lines contain the default error messages used by
    | the validator class. Some of these rules have multiple versions such
    | as the size rules. Feel free to tweak each of these messages here.
    |
    */

    'accepted' => ':attribute onaylanmalıdır.',
    'active_url' => ':attribute geçersiz link.',
    'after' => 'The :attribute must be a date after :date.',
    'after_or_equal' => 'The :attribute must be a date after or equal to :date.',
    'alpha' => 'The :attribute may only contain letters.',
    'alpha_dash' => 'The :attribute may only contain letters, numbers, dashes and underscores.',
    'alpha_num' => 'The :attribute may only contain letters and numbers.',
    'array' => 'The :attribute must be an array.',
    'before' => 'The :attribute must be a date before :date.',
    'before_or_equal' => 'The :attribute must be a date before or equal to :date.',
    'between' => [
        'numeric' => 'The :attribute must be between :min and :max.',
        'file' => 'The :attribute must be between :min and :max kilobytes.',
        'string' => 'The :attribute must be between :min and :max characters.',
        'array' => 'The :attribute must have between :min and :max items.',
    ],
    'boolean' => 'The :attribute field must be true or false.',
    'confirmed' => 'Hatalı eşleşme!',
    'date' => 'The :attribute is not a valid date.',
    'date_equals' => 'The :attribute must be a date equal to :date.',
    'date_format' => 'The :attribute does not match the format :format.',
    'different' => 'The :attribute and :other must be different.',
    'digits_between' => 'The :attribute must be between :min and :max digits.',
    'dimensions' => 'The :attribute has invalid image dimensions.',
    'distinct' => 'The :attribute field has a duplicate value.',
    'ends_with' => 'The :attribute must end with one of the following: :values',
    'file' => ':attribute geçersiz dosya türü.',
    'filled' => 'The :attribute field must have a value.',
    'gt' => [
        'numeric' => 'The :attribute must be greater than :value.',
        'file' => 'The :attribute must be greater than :value kilobytes.',
        'string' => 'The :attribute must be greater than :value characters.',
        'array' => 'The :attribute must have more than :value items.',
    ],
    'gte' => [
        'numeric' => 'The :attribute must be greater than or equal :value.',
        'file' => 'The :attribute must be greater than or equal :value kilobytes.',
        'string' => 'The :attribute must be greater than or equal :value characters.',
        'array' => 'The :attribute must have :value items or more.',
    ],
    'image' => 'The :attribute must be an image.',
    'in' => 'The selected :attribute is invalid.',
    'in_array' => 'The :attribute field does not exist in :other.',
    'integer' => ':attribute sadece sayı olmalıdır.',
    'ip' => 'The :attribute must be a valid IP address.',
    'ipv4' => 'The :attribute must be a valid IPv4 address.',
    'ipv6' => 'The :attribute must be a valid IPv6 address.',
    'json' => 'The :attribute must be a valid JSON string.',
    'lt' => [
        'numeric' => 'The :attribute must be less than :value.',
        'file' => 'The :attribute must be less than :value kilobytes.',
        'string' => 'The :attribute must be less than :value characters.',
        'array' => 'The :attribute must have less than :value items.',
    ],
    'lte' => [
        'numeric' => 'The :attribute must be less than or equal :value.',
        'file' => 'The :attribute must be less than or equal :value kilobytes.',
        'string' => 'The :attribute must be less than or equal :value characters.',
        'array' => 'The :attribute must not have more than :value items.',
    ],
    'max' => [
        'numeric' => 'The :attribute may not be greater than :max.',
        'file' => 'The :attribute may not be greater than :max kilobytes.',
        'string' => ':attribute en fazla :max karakter olabilir.',
        'array' => 'The :attribute may not have more than :max items.',
    ],
    'mimes' => 'The :attribute must be a file of type: :values.',
    'mimetypes' => 'The :attribute must be a file of type: :values.',
    'min' => [
        'numeric' => 'The :attribute must be at least :min.',
        'file' => 'The :attribute must be at least :min kilobytes.',
        'string' => ':attribute için en az :min karakter girmelisin.',
        'array' => 'The :attribute must have at least :min items.',
    ],
    'not_in' => 'The selected :attribute is invalid.',
    'not_regex' => ':attribute türkçe karakter ya da geçersiz karakterler içeriyor.',
    'username.not_regex' => ':attribute türkçe karakter ya da geçersiz karakterler içeriyor.',
    'numeric' => 'The :attribute must be a number.',
    'password' => 'The password is incorrect.',
    'present' => 'The :attribute field must be present.',
    'regex' => ':attribute türkçe karakter ya da geçersiz karakterler içeriyor.',
    'username.regex' => ':attribute türkçe karakter ya da geçersiz karakterler içeriyor.',
    'required' => ':attribute boş olamaz.',
    'required_if' => 'The :attribute field is required when :other is :value.',
    'required_unless' => 'The :attribute field is required unless :other is in :values.',
    'required_with' => 'The :attribute field is required when :values is present.',
    'required_with_all' => 'The :attribute field is required when :values are present.',
    'required_without' => 'The :attribute field is required when :values is not present.',
    'required_without_all' => 'The :attribute field is required when none of :values are present.',
    'same' => 'The :attribute and :other must match.',
    'size' => [
        'numeric' => 'The :attribute must be :size.',
        'file' => 'The :attribute must be :size kilobytes.',
        'string' => 'The :attribute must be :size characters.',
        'array' => 'The :attribute must contain :size items.',
    ],
    'starts_with' => 'The :attribute must start with one of the following: :values',
    'string' => 'The :attribute must be a string.',
    'timezone' => 'The :attribute must be a valid zone.',
    'unique' => ':attribute sana özel, benzersiz olmalıdır.',
    'uploaded' => 'The :attribute failed to upload.',
    'url' => 'The :attribute format is invalid.',
    'uuid' => 'The :attribute must be a valid UUID.',
    'files' => 'Dosya yüklemelisin!',
    'phone' => ':attribute  geçersiz.',
    'dont_request' => 'Bu kitabı başka bir kullanıcı senden önce istediği için geçici olarak talep edemezsin. Sonra tekrar dene!',
    'phone.unique' => 'Telefon numaran sana özel, benzersiz olmalıdır.',
    'phone_custom_unique' => 'Telefon numaran sana özel, benzersiz olmalıdır.',
    'email.unique' => 'Email adresin sana özel, benzersiz olmalıdır.',
    'username.unique' => 'Kullanıcı adın sana özel, benzersiz olmalıdır.',
    'username.alpha_dash' => 'Kullanıcı adın yalnızca harf, rakam, tire ve alt çizgi içerebilir.',
    'city_id.exists' => 'Seçtiğin şehir hatalıdır.',
    'town_id.exists' => 'Seçtiğin ilçe hatalıdır.',
    'exists' => 'Geçersiz seçim',
    'identify' => 'Tc kimlik numaran ile adın soyadın ve doğum tarihin eşleşmiyor.',
    'without_spaces' => 'Boşluk bırakamazsın.',
    'gender' => 'Cinsiyet bilgini doğru seçmedin.',
    'digits' => 'Sadece rakam ve :digits karakter gir.',
    'confirm_password'=> 'Şifreler uyuşmuyor.',
    'now_password'=> 'Hatalı şifre.',
    'email'=> 'Girdiğin email adresi gerçersiz.',
    'phone_code'=> 'Hatalı kod girdin.',
    'confirm_document.required' => 'Kullanıcı sözleşmesini okuyup onaylayın!',
  /*
    |--------------------------------------------------------------------------
    | Custom Validation Language Lines
    |--------------------------------------------------------------------------
    |
    | Here you may specify custom validation messages for attributes using the
    | convention "attribute.rule" to name the lines. This makes it quick to
    | specify a specific custom language line for a given attribute rule.
    |
    */

    'custom' => [
        'attribute-name' => [
            'rule-name' => 'custom-message',
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Custom Validation Attributes
    |--------------------------------------------------------------------------
    |
    | The following language lines are used to swap our attribute placeholder
    | with something more reader friendly such as "E-Mail Address" instead
    | of "email". This simply helps us make our message more expressive.
    |
    */

    'attributes' => [
      'name' => 'Adın',
      'cargo_company' => 'Kargo Firması',
      'folow_number' => 'Takip Numarası',
      'surname' => 'Soyadın',
      'username' => 'Kullanıcı Adın',
      'phone' => 'Telefon Numaran',
      'password' => 'Şifren',
      'now_password' => 'Şifren',
      'new_password' => 'Yeni Şifren',
      'confirm_password' => 'Şifre Tekrar',
      'book_name' => 'Kitap Adı',
      'author' => 'Yazar',
      'category' => 'Kategori',
      'files' => 'Dosya',
      'address' => 'Adresin',
      'email' => 'Email Adresin',
      'identify' => 'T.C Kimlik Numaran',
      'cargo' => 'Kargo',
      'birth_date' => 'Doğum Tarihin',
      'city_id' => 'Yaşadığın Şehir',
      'town_id' => 'Yaşadığın İlçe',
      'subject' => 'Konu',
      'detail' => 'Mesaj',
      'confirm_document' => 'Kullanıcı sözleşmesi',
      'billing_note' => 'Fatura Bilgilerin',
      'note' => 'Sipariş Notun',
      'code' => 'Onay Kodu',
      'rate' => 'Puanın',
      'price' => 'Fiyat',
      'discount_rate' => 'İndirim Oranı',
      'discount_price' => 'Satış Fiyatı',
      'status' => 'Durum',
      'tags' => 'Etiketler',
      'stock' => 'Stok',
      'category_id' => 'Kategori',
    ],

];
