<?php

Route::get('home', function () {
    return redirect('/hesabim');
});


Route::get('/cache/clear', function () {
    $exitCode = \Illuminate\Support\Facades\Artisan::call('cache:clear');
});
Route::get('google8aab2772028b6e40.html', function () {
    echo 'google-site-verification: google8aab2772028b6e40.html';
});

Route::get('/', 'HomeController@index')->name('home');
Route::get('urun/{url}', 'HomeController@show')->name('product.show');

Route::get('destek', 'SupportController@index')->name('contact');
Route::post('destek', 'SupportController@save')->name('contact.save');
Route::post('destek/kontrol', 'SupportController@check')->name('support.check');

Route::get('/sozlesme', 'HomeController@contract')->name('contract');
Route::get('/sozlesme/{url}', 'HomeController@contract')->name('contract.sub');


Route::post('ilce-bul', 'HomeController@town');

// Auth İşlemleri
Route::get('giris-yap', 'Auth\LoginController@showLoginForm')->name('login');
Route::post('giris-yap', 'Auth\LoginController@login');
Route::post('cikis-yap', 'Auth\LoginController@logout')->name('logout');
Route::get('sifremi-unuttum', 'Auth\ForgotPasswordController@showLinkRequestForm')->name('forgotpassword');
Route::post('sifremi-unuttum', 'Auth\ForgotPasswordController@sendResetPassword');

Route::post('alisveris/odeme/sonuc/{uuid}', 'MemberShipPanel\ShopController@callbackPayment')->name('shop.callback');
Route::get('alisveris/odeme/sonuc/{uuid}', 'MemberShipPanel\ShopController@callbackPayment')->name('shop.callback');
Route::get('alisveris/odeme/tamamlandi/{uuid}', 'MemberShipPanel\ShopController@resultPayment')->name('shop.result');

Route::prefix('hesabim')->namespace('MemberShipPanel')->middleware('auth', 'normal_user')->group(function () {
  // Profil İşlemleri
  Route::get('/', 'UserController@index')->name('profile');
  Route::get('guncelle', 'UserController@edit')->name('profile.edit');
  Route::post('guncelle', 'UserController@save')->name('profile.save');
  Route::post('/kontrol', 'UserController@check')->name('profile.check');
  Route::post('profil-resmi', 'UserController@image')->name('profile.picture');

  // Güvenlik İşlemleri
  Route::get('/guvenlik', 'PasswordController@index')->name('security');
  Route::post('/guvenlik/kontrol', 'PasswordController@check')->name('security.check');
  Route::post('/guvenlik', 'PasswordController@edit')->name('security.edit');

  // Sipariş İşlemleri
  Route::get('/siparislerim', 'OrderController@index')->name('my_order');

  // İzin İşlemleri
  Route::get('/izin', 'PermissionController@index')->name('permission');
  Route::post('/izin/guncelle', 'PermissionController@edit')->name('permission.more');

  // Sepet İşlemleri
  Route::post('sepet/ekle', 'BasketController@add')->name('basket.add');
  Route::post('sepet/liste', 'BasketController@list')->name('basket.list');
  Route::post('sepet/sil', 'BasketController@delete')->name('basket.delete');
  Route::get('sepet', 'BasketController@index')->name('basket.short_list');
  Route::post('sepet/guncelle', 'BasketController@update')->name('basket.update');


    // Favoriler İşlemleri
    Route::get('favorilerim', 'WishlistController@index')->name('wishlist.index');
    Route::get('favorilerime-ekle/{uuid}', 'WishlistController@add')->name('wishlist.add');
    Route::get('favorilerimden-sil/{uuid}', 'WishlistController@delete')->name('wishlist.delete');



    // Alışveriş İşlemleri
  Route::post('alisveris/kontrol', 'ShopController@check')->name('shop.check');
  Route::get('alisveris/adres', 'ShopController@index')->name('shop');
  Route::post('alisveris/adres', 'ShopController@save_address')->name('shop.save_address');
  Route::get('alisveris/odeme/{uuid}', 'ShopController@payment')->name('shop.payment');
  Route::post('alisveris/odeme', 'ShopController@save_payment')->name('shop.save_payment');


  // Bildirim İşlemleri
  Route::get('bildirim', 'NotificationController@index')->name('notification');
  Route::post('bildirim/liste', 'NotificationController@short_list')->name('notification.short_list');
  Route::post('bildirim/{uuid}', 'NotificationController@show')->name('notification.show');
  Route::get('/bildirim/daha-fazla', 'NotificationController@list');

  // Yorum İşlemleri
    Route::post('yorum/kontrol', 'CommentController@check')->name('comment.check');
    Route::post('yorum/yap', 'CommentController@save')->name('comment.save');

    // Fatura İndir
    Route::get('fatura/indir/{uuid}', 'OrderController@downloadBill')->name('order.downloadBilling');

});

Route::prefix('kayit-ol')->namespace('Auth')->group(function () {
  Route::get('/', 'RegisterController@showFirstStepForm')->middleware('auth.first')->name('auth.first');
  Route::post('/', 'RegisterController@saveFirstStep')->middleware('auth.first');
  Route::get('/telefon-onayla', 'RegisterController@showSecondStepForm')->middleware('auth.second');
  Route::post('/telefon-onayla', 'RegisterController@saveSecondStep')->middleware('auth.second');
  Route::post('/yeni-kod', 'RegisterController@newCode')->middleware('auth.second')->name('auth.second');
  Route::get('/yeni-numara', 'RegisterController@showSecondHalfStepForm')->middleware('auth.second');
  Route::post('/yeni-numara', 'RegisterController@saveSecondHalfStep')->middleware('auth.second');
  Route::get('/kullanici-adi-belirle', 'RegisterController@showThirdStepForm')->middleware('auth.third');
  Route::post('/kullanici-adi-belirle', 'RegisterController@saveThirdStep')->middleware('auth.third');

  Route::post('/kontrol', 'RegisterController@check')->name('auth.check');

});

Route::prefix('job')->group(function () {
  Route::get('monthly/{id}', 'JobController@monthly')->where('id', 'f45a3a9b-a68a-4caa-b66b-c724657e2337-birkitapbul-asdfg');
  Route::get('order-cancel/{id}', 'JobController@order_cancel')->where('id', 'f45a3a9b-a68a-4caa-b66b-c724657e2337-birkitapbul-asdfg');
  Route::get('order-cancel-today/{id}', 'JobController@order_cancel_today')->where('id', 'f45a3a9b-a68a-4caa-b66b-c724657e2337-birkitapbul-asdfg');
  Route::get('order-cancel-tomorrow/{id}', 'JobController@order_cancel_tomorrow')->where('id', 'f45a3a9b-a68a-4caa-b66b-c724657e2337-birkitapbul-asdfg');
  Route::get('order-completed/{id}', 'JobController@order_completed')->where('id', 'f45a3a9b-a68a-4caa-b66b-c724657e2337-birkitapbul-asdfg');
  Route::get('send-sms/{id}', 'JobController@send_sms')->where('id', 'f45a3a9b-a68a-4caa-b66b-c724657e2337-deekobjects-asdfg');
  });

Route::prefix('admin')->namespace('AdminPanel')->middleware('auth', 'super_admin')->group(function () {
  // Profil
  Route::get('password', 'AdminController@index')->name('admin.password');
  Route::post('sifre-kaydet', 'AdminController@save')->name('admin.password.save');
  Route::get('/', 'UserController@index')->name('admin.user.index');

  Route::get('ayarlar', 'SettingController@index')->name('admin.setting.index');
  Route::get('destek', 'SupportController@index')->name('admin.support.index');
  Route::get('ayarlar', 'SettingController@index')->name('admin.setting.index');
    Route::get('sepet', 'BasketController@index')->name('admin.basket.index');
    Route::get('favoriler', 'WishlistController@index')->name('admin.wishlist.index');
    Route::get('arama', 'SearchController@index')->name('admin.search.index');
  Route::post('ayarlar', 'SettingController@save')->name('admin.setting.save');


    Route::get('sozlesme', 'PageController@index')->name('admin.page.index');
    Route::get('sozlesme/yayinla/{id}', 'PageController@publish')->name('admin.page.publish');
    Route::get('sozlesme/yayindan-kaldir/{id}', 'PageController@unpublish')->name('admin.page.unpublish');
    Route::get('sozlesme/guncelle/{id}', 'PageController@update')->name('admin.page.update');
    Route::get('sozlesme/ekle', 'PageController@create')->name('admin.page.create');
    Route::post('sozlesme/kaydet', 'PageController@save')->name('admin.page.save');

    Route::post('admin/url-olustur', 'AdminController@urlGenerator')->name('admin.urlGenerator');

  // Ürün İşlemleri
    Route::get('urunler', 'ProductController@index')->name('admin.product.index');
    Route::get('urun/guncelle/{uuid}', 'ProductController@update')->name('admin.product.update');
    Route::get('urun/ekle', 'ProductController@create')->name('admin.product.create');
    Route::post('urun/kaydet', 'ProductController@save')->name('admin.product.save');
    Route::get('urun/{uuid}', 'ProductController@show')->name('admin.product.show');
    Route::get('urun/yayinla/{uuid}', 'ProductController@publish')->name('admin.product.publish');
    Route::get('urun/yayindan-kaldir/{uuid}', 'ProductController@unpublish')->name('admin.product.unpublish');
    Route::post('admin/urun/hesapla', 'ProductController@calculate')->name('admin.product.calculate');


    // Sipariş İşlemleri
    Route::get('siparisler', 'OrderController@index')->name('admin.order.index');
    Route::get('siparis/{uuid}', 'OrderController@show')->name('admin.order.show');
    Route::get('siparis/hazirla/{uuid}', 'OrderController@proccess')->name('admin.order.proccess');
    Route::get('siparis/kargola/{uuid}', 'OrderController@cargo')->name('admin.order.cargo');
    Route::get('siparis/bitir/{uuid}', 'OrderController@completed')->name('admin.order.completed');
    Route::post('siparis/kargola-kaydet/{uuid}', 'OrderController@cargo_save')->name('admin.order.cargo_save');
    Route::get('siparis/fatura-indir/{uuid}', 'OrderController@downloadBill')->name('admin.order.billing_download');
    Route::get('siparis/fatura/{uuid}', 'OrderController@billing')->name('admin.order.billing_show');
    Route::post('siparis/fatura-kaydet/{uuid}', 'OrderController@billing_save')->name('admin.order.billing');

    Route::get('siparis/iptal/{uuid}', 'OrderController@cancel')->name('admin.order.cancel');
    Route::post('siparis/iptal-et/{uuid}', 'OrderController@cancel_save')->name('admin.order.cancel_save');

    // Kategori İşlemleri
    Route::get('kategoriler', 'CategoryController@index')->name('admin.category.index');
    Route::get('kategori/{uuid}', 'CategoryController@update')->name('admin.category.update');
    Route::get('kategori/ekle', 'CategoryController@create')->name('admin.category.create');
    Route::post('kategori/kaydet', 'CategoryController@save')->name('admin.category.save');

    // Kargo İşlemleri
    Route::get('kargolar', 'CargoController@index')->name('admin.cargo.index');
    Route::get('kargo/{uuid}', 'CargoController@update')->name('admin.cargo.update');
    Route::get('kargo/ekle', 'CargoController@create')->name('admin.cargo.create');
    Route::post('kargo/kaydet', 'CargoController@save')->name('admin.cargo.save');

    // Yorum İşlemleri
    Route::get('yorumlar', 'CommentController@index')->name('admin.comment.index');
    Route::get('yorum/yayinla/{id}', 'CommentController@publish')->name('admin.comment.publish');
    Route::get('yorum/yayindan-kaldir/{id}', 'CommentController@unPublish')->name('admin.comment.unpublish');

});
Route::prefix('sitemap.xml')->group(function () {
  Route::get('/', 'SitemapController@index')->name('sitemap');
  Route::get('/statics', 'SitemapController@statics')->name('sitemap.statics');
  Route::get('/urunler', 'SitemapController@products')->name('sitemap.products');
});

Route::get('/php-laravel-mail',function (){
  $data=[
    'mail_address'=>'yada.bilal@gmail.com',
    'name'=>'Bilal Yada'
  ];
  \Illuminate\Support\Facades\Mail::send('laravel-mail',$data,function($mail) use ($data) {
    $mail->subject('Örnek Mail Gönderimi');
    $mail->sender('info@mail.birkitapbul.com');
    $mail->from('info@mail.birkitapbul.com','Örnek Mail Gönderimi');
    $mail->to($data['mail_address']);
  });
});
