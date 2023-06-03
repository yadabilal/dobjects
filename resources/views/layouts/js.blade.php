
<script src="{{ asset('theme/deekobjects/libs/popper/js/popper.min.js?'.\Carbon\Carbon::now()->getTimestamp()) }}"></script>
<script src="{{ asset('theme/deekobjects/libs/jquery/js/jquery.min.js?'.\Carbon\Carbon::now()->getTimestamp()) }}"></script>
<script src="{{ asset('theme/deekobjects/libs/bootstrap/js/bootstrap.min.js?'.\Carbon\Carbon::now()->getTimestamp()) }}"></script>
<script src="{{ asset('theme/deekobjects/libs/slick/js/slick.min.js?'.\Carbon\Carbon::now()->getTimestamp()) }}"></script>
<script src="{{ asset('theme/deekobjects/libs/countdown/js/jquery.countdown.min.js?'.\Carbon\Carbon::now()->getTimestamp()) }}"></script>
<script src="{{ asset('theme/deekobjects/libs/mmenu/js/jquery.mmenu.all.min.js?'.\Carbon\Carbon::now()->getTimestamp()) }}"></script>
<script src="{{ asset('theme/deekobjects/libs/slider/js/tmpl.js?'.\Carbon\Carbon::now()->getTimestamp()) }}"></script>
<script src="{{ asset('theme/deekobjects/libs/slider/js/jquery.dependClass-0.1.js?'.\Carbon\Carbon::now()->getTimestamp()) }}"></script>
<script src="{{ asset('theme/deekobjects/libs/slider/js/draggable-0.1.js?'.\Carbon\Carbon::now()->getTimestamp()) }}"></script>
<script src="{{ asset('theme/deekobjects/libs/slider/js/jquery.slider.js?'.\Carbon\Carbon::now()->getTimestamp()) }}"></script>

<script src="{{ asset('theme/deekobjects/js/app.js?'.\Carbon\Carbon::now()->getTimestamp()) }}"></script>
<script src="{{ asset('theme/deekobjects/libs/elevatezoom/js/jquery.elevatezoom.js?'.\Carbon\Carbon::now()->getTimestamp()) }}"></script>

<script src="{{ asset('theme/deekobjects/js/btn-proccess.js?'.\Carbon\Carbon::now()->getTimestamp()) }}"></script>
{!! @$settings['extraJs'] !!}
@stack('page-scripts')

<script>
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
            url: '{{auth()->id() ? route('basket.add') : route('tempbasket.add')}}',
            data: {_token:csrf_token, id:id, quantity: quantity},
            type: 'POST',
            success: function (data) {
                var msg = data.message ? data.message :'Beklenmedik bir hata meydana geldi!';
                var status = "error";

                if(data.success) {
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
            url: '{{auth()->id() ? route('basket.list') : route('tempbasket.list')}}',
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
                        '<a class="go-shop" href="{{route('home')}}">Alışverişe Devam Et<i aria-hidden="true" class="arrow_right"></i></a>'+
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
            url: '{{auth()->id() ? route('basket.list') : route('tempbasket.delete')}}',
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
            url: '{{auth()->id() ? route('basket.list') : route('tempbasket.delete')}}',
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
      url: '{{url('ilce-bul')}}',
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
        if($(this).val() == '{{\App\Model\Address::BILLING_TYPE_COMPANY}}') {
            $('.billing-personal').hide();
            $('.billing-company').show();
        }else{
            $('.billing-company').hide();
            $('.billing-personal').show();
        }
    });
  @if(session()->has('success_message'))
      message("success", '{{ session()->get('success_message') }}')
  @endif
  @if(session()->has('error_message'))
    message("error", '{{ session()->get('error_message') }}')
  @endif
</script>
