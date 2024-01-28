var timeOut = 5000;
var send_at;

// Daha fazla yükle
var progress= false;
setTimeout(function() {
    $('.page-preloader')['fadeOut']()
}, 1500);

function message(status, message) {
    if(status == 'success') {
        $('body')['append']('<div class="cart-product-added success-message"><div class="added-message">'+message+'</div></div>');
    }else {
        $('body')['append']('<div class="cart-product-added error-message"><div class="added-message">'+message+'</div></div>');
    }

    setTimeout(function() {
        $('.cart-product-added')['remove']()
    }, 3000)
}

$(document).on("keyup","#identity_number",function() {
    $("#Billing_identity_number2").val($(this).val());
});

$(document).on("click","#ship_to_different_address",function() {
    var element = $(this);
    if(element.is(':checked')) {
        $(".shipping-address").show();
    }else {
        $(".shipping-address").hide();
    }
});

$(document).on("click",".review-star",function() {
    var element = $(this);
    var id = element.data('id');
    $("#reviewRate").val(id);
    $(".review-star").removeClass('reviewed');
    $( ".review-star:lt("+id+")" ).addClass('reviewed');

    $("#reviewRate").trigger("change");
});

$('.btn-add-to-cart .button')['on']('click', function(e) {
    var quantity = 1;
    var element = $(this);
    var id= element.data('id');
    element['addClass']('loading');

    if($("#quantity").length>0) {
        if($("#quantity").data('id') == id) {
            quantity = $("#quantity").val();
        }
    }

    $.ajax({
        url: basketAddUrl,
        data: {_token:csrf_token, id:id, quantity: quantity},
        type: 'POST',
        success: function (data) {
            var msg = data.message ? data.message :'Beklenmedik bir hata meydana geldi!';
            var status = "error";

            if(data.success) {
                fbq('track', 'AddToCart');
                status = "success";
                element['addClass']('added');
                element['closest']('div')['append'](
                    '<a href="javascript:void(0)" class="added-to-cart product-btn" title="Sepetinde" tabindex="0">Sepetinde</a>');

                $('.cart-count').html(data.count);
            }

            message(status, msg);
            element['removeClass']('loading');

        },
        error: function (request, status, error) {
            if(request.status == 401) {
                message("error", "Lütfen Giriş Yapın ya da Kaydolun!");
            }else {
                message("error", "Beklenmedik bir hata meydana geldi!");
            }
            element['removeClass']('loading');
        }
    });

});

$('.cart-icon')['on']('click', function() {
    var cartBody = $(".cart-popup");
    $.ajax({
        url: basketList,
        data: {_token:csrf_token},
        type: 'POST',
        success: function (data) {
            if(data.success) {
                cartBody.html(data.list);
            }
        },
        error: function (request, status, error) {
            if(request.status == 401) {
                cartBody.html('<div class="cart-empty-wrap"><ul class="cart-list"><li class="empty"><span>'+
                    'Gösterilecek bir şey yok!</span>'+
                    '<a class="go-shop" href="'+homeUrl+'">Alışverişe Devam Et<i aria-hidden="true" class="arrow_right"></i></a>'+
                '</li></ul></div>');
            }else {
                message("error", "Beklenmedik bir hata meydana geldi!");
            }
            cartBody['removeClass']('loading');
        }
    });
});

$('.quantity .plus')['on']('click', function(e) {
    var val = parseInt($(this)['closest']('.quantity')['find']('.qty')['val']());
    $(this)['closest']('.quantity')['find']('.qty')['val'](val + 1)
});
$('.quantity .minus')['on']('click', function(e) {
    var val = parseInt($(this)['closest']('.quantity')['find']('.qty')['val']());
    if (val > 1) {
        $(this)['closest']('.quantity')['find']('.qty')['val'](val - 1)
    }
});

$(document).on("click",".product-remove a.remove",function() {
    var element = $(this);
    var id= element.data('id');
    var cart = element['closest']('.cart-items');
    console.log(cart)
    $.ajax({
        url: basketDelete,
        data: {_token:csrf_token, id:id},
        type: 'POST',
        success: function (data) {

            var msg = data.message ? data.message :'Beklenmedik bir hata meydana geldi!';
            var status = "error";

            if(data.success) {
                status = "success";
                $('.cart-count').html(data.count);
                $('.totalPrice').html(data.totalPrice);
                $('.discountPrice').html(data.discountPrice);
                $('.totalDiscountPrice').html(data.totalDiscountPrice);

                $("#"+id)['remove']();
                if (!cart['find']('.cart-item')['length']) {
                    $('.shop-cart').addClass('hidden')
                    $('.shop-cart-empty').removeClass('hidden')
                }
            }

            message(status, msg);
            element['removeClass']('loading');
        },
        error: function (request, status, error) {
            if(request.status == 401) {
                message("error", "Lütfen Giriş Yapın ya da Kaydolun!");
            }else {
                message("error", "Beklenmedik bir hata meydana geldi!");
            }
            element['removeClass']('loading');
        }
    });
});

$(document).on("click",".mini-cart-item a.remove",function() {
    var element = $(this);
    var id= element.data('id');
    var cart = element['closest']('.mini-cart');

    $.ajax({
        url: basketDelete,
        data: {_token:csrf_token, id:id},
        type: 'POST',
        success: function (data) {

            var msg = data.message ? data.message :'Beklenmedik bir hata meydana geldi!';
            var status = "error";

            if(data.success) {
                status = "success";
                $('.cart-count').html(data.count);

                $(this)['closest']('li')['remove']();
                //cart['find']('.cart-count')['text'](cart['find']('.cart-list-wrap .cart-list li')['length']);
                if (!cart['find']('.cart-list-wrap .cart-list li')['length']) {
                    cart['find']('.cart-empty-wrap')['show']();
                    cart['find']('.cart-list-wrap')['hide']()
                }
            }

            message(status, msg);
            element['removeClass']('loading');
        },
        error: function (request, status, error) {
            message("error", "Beklenmedik bir hata meydana geldi!");
            element['removeClass']('loading');
        }
    });
});

// Şehir Seçme
$('.city').on('change',function () {
    var element = $(this);
    var city = element.val();
    $.ajax({
        url: cityChangeUrl,
        data: {city:city, _token:csrf_token},
        type: 'POST',
        success: function (data) {
            var parent =  element.closest('p').nextAll(':has(.town):first');
            var town = parent.find('.town');
            town.html('');
            var newOption = new Option('Şimdi İlçe Seç', '', false, false);
            town.append(newOption);
            if(data.towns) {
                var towns = JSON.parse(data.towns);
                $.each(towns, function(k, v) {
                    var newOption = new Option(v.name, v.uuid, false, false);
                    town.append(newOption);
                });
            }
        },
    });
});

$('.billing-types').on('change',function () {
    if($(this).val() == billingCompanyType) {
        $('.billing-personal').hide();
        $('.billing-company').show();
    }else{
        $('.billing-company').hide();
        $('.billing-personal').show();
    }
});
$('.close-search', '.search-overlay')['on']('click.break', function(e) {
    $('.page-wrapper')['toggleClass']('opacity-style');
    var search = $('.search-overlay');
    search['toggleClass']('search-visible')
});

$('.search-toggle')['on']('click.break', function(e) {
    $('.page-wrapper')['toggleClass']('opacity-style');
    var search = $('.search-overlay');
    search['toggleClass']('search-visible')
});

// Kaydet Buton İşlemleri
$(document).on("click",".btn-save",function() {
  var _this = $(this);
  _this.addClass('is-loading');
  var action = $(this).attr('data-action');
  var after_message = ($(this).attr('data-after-message') == 'true');
  var after_message_content = ($(this).attr('data-after-message-content') == 'true');
  var form = $(this).closest('form')[0];
  var formData = new FormData(form);
  formData.append('_token', csrf_token);

  $.ajax({
    url: action,
    data: formData,
    type: 'POST',
    processData: false,
    contentType: false,
    success: function (data) {

      $('.error-message').remove();
      $('.text-error').each(function(i, obj) {
        $(this).remove();
      });
      $('.has-error').each(function(i, obj) {
        $(this).removeClass('has-validation has-error');
      });
      // Hata varsa ekrana bas!
      if(!data.success) {
        if(data.message) {
            message('error', data.message);
        }else {
            message('error', 'Lütfen hataları düzeltip yeniden deneyin!');
        }

        $.each(data.errors, function( index, value ) {
            var input = $("[name='"+index+"']");
            input.parent().addClass('has-validation has-error');

            if($('.without-message').length>0) {
                $('.without-message').after('<p class="text-error">'+value+'</p>');
            }else {
                input.parent().after('<p class="text-error">'+value+'</p>');
            }

        });
        _this.removeClass('is-loading');
      }else {
        form.submit();
      }
    },
    error: function (request, status, error) {
      _this.removeClass('is-loading');
    }
  });

});
// Sipariş Ver
$(document).on("click",".btn-payment",function() {
  var _this = $(this);
  _this.addClass('is-loading');
  var action = $(this).attr('data-action');
  var form = $(this).closest('form')[0];
  var formData = new FormData(form);
  formData.append('_token', csrf_token);

  if(action) {
    $.ajax({
      url: action,
      data: formData,
      type: 'POST',
      processData: false,
      contentType: false,
      success: function (data) {

        $('.error-message').remove();
        $('.text-error').each(function(i, obj) {
          $(this).remove();
        });

        $('.has-error').each(function(i, obj) {
          $(this).removeClass('has-validation has-error');
        });

        // Sepet Hataları
        $('.border-red').each(function(i, obj) {
          $(this).removeClass('border-red');
        });
        $('.cart-item-error').remove();
        // Hata varsa ekrana bas!
        if(!data.success) {
          if(data.message) {
              message('error', data.message)
          }else {
              message('error', 'Lütfen hataları düzeltip yeniden deneyin!')
          }

          $.each(data.errors, function( index, value ) {
              var input = $("[name='"+index+"']");
              input.parent().addClass('has-validation has-error');
              input.parent().after('<p class="text-error">'+value+'</p>');
          });
          _this.removeClass('is-loading');
        }else {
          form.submit();
        }

      },
      error: function (request, status, error) {
        _this.removeClass('is-loading');
      }
    });
  }

});

$(document).ready(function() {
    var body = $('body');
    $('.slick-sliders')['each'](function() {
        slider($(this))
    });

    $('a[data-toggle="tab"]')['on']('shown.bs.tab', function(e) {
        $(this)['closest']('.block')['find']('.slick-sliders')['slick']('refresh')
    });
    $('.shop-details .slick-carousel')['each'](function() {
        slider($(this))
    });



    function slider(element) {
        element['slick']({
            arrows: element['data']('nav') ? !0 : !1,
            dots: element['data']('dots') ? !0 : !1,
            draggable: element['data']('draggable') ? !1 : !0,
            infinite: element['data']('infinite') ? !1 : !0,
            autoplay: element['data']('autoplay') ? !0 : !1,
            prevArrow: '<i class="slick-arrow fa fa-angle-left"></i>',
            slidesToScroll: element['data']('slidestoscroll') ? element['data']('columns') : 1,
            nextArrow: '<i class="slick-arrow fa fa-angle-right"></i>',
            slidesToShow: element['data']('columns'),
            asNavFor: element['data']('asnavfor') ? element['data']('asnavfor') : !1,
            vertical: element['data']('vertical') ? !0 : !1,
            verticalSwiping: element['data']('verticalswiping') ? element['data']('verticalswiping') : !1,
            rtl: (body['hasClass']('rtl') && !element['data']('vertical')) ? !0 : !1,
            centerMode: element['data']('centermode') ? element['data']('centermode') : !1,
            centerPadding: element['data']('centerpadding') ? element['data']('centerpadding') : !1,
            focusOnSelect: element['data']('focusonselect') ? element['data']('focusonselect') : !1,
            fade: (element['data']('fade') && !element['hasClass']('rtl')) ? !0 : !1,
            cssEase: 'linear',
            autoplaySpeed: 5000,
            pauseOnHover: !1,
            pauseOnFocus: !1,
            responsive: [{
                breakpoint: 1441,
                settings: {
                    slidesToShow: element['data']('columns1440') ? element['data']('columns1440') : element['data']('columns'),
                    slidesToScroll: element['data']('columns1440') ? element['data']('columns1440') : element['data']('columns')
                }
            }, {
                breakpoint: 1200,
                settings: {
                    slidesToShow: element['data']('columns1'),
                    slidesToScroll: element['data']('columns1')
                }
            }, {
                breakpoint: 1024,
                settings: {
                    slidesToShow: element['data']('columns2'),
                    slidesToScroll: element['data']('columns2')
                }
            }, {
                breakpoint: 768,
                settings: {
                    slidesToShow: element['data']('columns3'),
                    slidesToScroll: element['data']('columns3'),
                    vertical: !1,
                    verticalSwiping: !1
                }
            }, {
                breakpoint: 480,
                settings: {
                    slidesToShow: element['data']('columns4'),
                    slidesToScroll: element['data']('columns4'),
                    vertical: !1,
                    verticalSwiping: !1
                }
            }]
        });
        slickArrow(element);
        var details = $('.shop-details');
        if (details['length'] > 0 && details['hasClass']('zoom')) {
            var detailsData = details['data']();
            var currents = $('.img-item.slick-current', '.shop-details .image-additional');
            if (($(window)['width']()) >= 768) {
                thumbnail($('img', currents), detailsData)
            }
        };
        element['on']('afterChange', function(e, a, b, c) {
            if (details['length'] > 0 && details['hasClass']('zoom')) {
                $('.zoomContainer')['remove']();
                var detailsData = details['data']();
                var currents = $('.img-item.slick-current', '.shop-details .image-additional');
                if (($(window)['width']()) >= 768) {
                    thumbnail($('img', currents), detailsData)
                }
            }
        })
    }

    function slickArrow(e) {
        if ($('.slick-arrow', e)['length'] > 0) {
            if ($('.fa-angle-left', e)['length'] > 0) {
                var clone = $('.fa-angle-left', e)['clone']();
                $('.fa-angle-left', e)['remove']();
                if (e['parent']()['find']('.fa-angle-left')['length'] == 0) {
                    clone['prependTo'](e['parent']())
                };
                clone['click'](function() {
                    e['slick']('slickPrev')
                })
            };
            if ($('.fa-angle-right', e)['length'] > 0) {
                var clone = $('.fa-angle-right', e)['clone']();
                $('.fa-angle-right', e)['remove']();
                if (e['parent']()['find']('.fa-angle-right')['length'] == 0) {
                    clone['appendTo'](e['parent']())
                };
                clone['click'](function() {
                    e['slick']('slickNext')
                })
            }
        } else {
            $('.fa-angle-left', e['parent']())['remove']();
            $('.fa-angle-right', e['parent']())['remove']()
        }
    }

    function thumbnail(slick, element) {
        if ($('.image-thumbnail')['length'] > 0) {
            var galery = 'image-thumbnail'
        } else {
            var galery = !1
        };
        slick['elevateZoom']({
            zoomType: element['zoomtype'],
            scrollZoom: element['zoom_scroll'],
            lensSize: element['lenssize'],
            lensShape: element['lensshape'],
            containLensZoom: element['zoom_contain_lens'],
            gallery: galery,
            cursor: 'crosshair',
            galleryActiveClass: 'active',
            lensBorder: element['lensborder'],
            borderSize: element['bordersize'],
            borderColour: element['bordercolour']
        })
    }

  disabled();
  // Zorunlu alanlar dolmadan buton disable olmalı
  $('.required').on('keyup', function () {
    disabled();
  });
  // Zorunlu alanlar dolmadan buton disable olmalı
  $('.required').on('change', function () {
    disabled();
  });
  // Zorunlu alanlar dolmadan buton disable olmalı
  function disabled() {
    var disable= false;
    var all = $('.required').map((_,el) => el.value).get();
    $.each(all, function( index, value ) {
      if(!value) {
        disable = true;
      }
    });
    var data_disable = $('.buttonDisable').data('disable');
    if(data_disable) {
      $('.buttonDisable').prop('disabled', false);
      $('.buttonDisable').data('disable', false)
    }else{
      $('.buttonDisable').prop('disabled', disable);
    }

    $('.buttonDisable').prop('disabled', disable);
  }
  // Telefon numarası değiştirme
  $('.phone-switch').on('change', function () {
    var disable = true;
    if($(this).is(':checked')) {
      disable = false;
    }
    $( "input[type=password]" ).prop('disabled', !disable);
    $('.buttonDisable').prop('disabled', disable);
    $('.phone').prop('disabled', disable);
    if(disable) {
      disabled();
    }

  });
});

// Yeni Kod İsteme
$(document).on("click",".new-code",function() {
  var action = $(this).attr('data-action');
  if(action && !$(this).hasClass("isDisabled")) {
    $.ajax({
      url: action,
      data: {_token:csrf_token},
      type: 'POST',
      success: function (data) {
        // Hata varsa ekrana bas!
        if(!data.success) {

            if(data.message) {
                message("error", data.message)
            }
          $.each(data.errors, function( index, value ) {
              message("error", value)
          });
        }else {
          send_at =new Date();
            message("success", 'Yeni kod telefonuna gönderildi!')
        }
      },
      error: function (request, status, error) {
      }
    });
  }
});

// Kaydetme Adımları
$(document).on("click",".btn-register",function() {
  var element = $(this);
  var form = $(this).closest('form')[0];
  var formData = new FormData(form);
  var action = $(this).attr('data-action');
  element.addClass('is-loading');
  if(action) {
    $.ajax({
      url: action,
      data: formData,
      type: 'POST',
      processData: false,
      contentType: false,
      success: function (data) {

        $('.error-message').remove();
        $('.text-error').each(function(i, obj) {
          $(this).remove();
        });
        $('.has-error').each(function(i, obj) {
          $(this).removeClass('has-validation has-error');
        });
        // Hata varsa ekrana bas!
        if(!data.success) {
            if(data.message) {
                message('error', data.message);
            }else {
                message('error', 'Lütfen hataları düzeltip yeniden deneyin!');
            }
          $.each(data.errors, function( index, value ) {
            var input = $("[name='"+index+"']");
            input.after('<p class="text-error">'+value+'</p>');
            input.parent().addClass('has-validation has-error');
          });
          element.removeClass('is-loading');
        }else {
          form.submit();
        }
      },
      error: function (request, status, error) {
          message("error", 'Beklenmedik bir hata meydana geldi!')
        element.removeClass('is-loading');
      }
    });
  }
});

$(document).on('click', '.modal-login-register', function () {
  $('#login-register-modal').addClass('is-active');
});

if($('.new-code').length) {
  function countdownTimer() {
    const difference = +new Date() - +send_at;
    let remaining = "00:00";
    const parts = {
      minutes: 5-Math.floor((difference / 1000 / 60) % 60),
      seconds: 59-Math.floor((difference / 1000) % 60)
    };

    if(parts['minutes']>=0 && parts['seconds']>=0) {
      var second = parts['seconds'];
      if(second<10) {
        second ='0'+second;
      }
      remaining='0'+parts['minutes']+':'+second;
    }else if(parts['minutes'] <0) {
        console.log("girdi!")
        $(".new-code").removeClass('isDisabled');
    }

    document.getElementById("timeBack").innerHTML = remaining;

  }
  countdownTimer();
  setInterval(countdownTimer, 1000);
}
