var notificationTimeout;
var updateNotification = function(task, notificationText, newClass) {
    var notificationPopup = $('.notification-popup ');
    notificationPopup.find('.task').text(task);
    notificationPopup.find('.notification-text').text(notificationText);
    notificationPopup.removeClass('hide success');
    if (newClass)
        notificationPopup.addClass(newClass);
    if (notificationTimeout)
        clearTimeout(notificationTimeout);
    notificationTimeout = setTimeout(function() {
        notificationPopup.addClass('hide');
    }, 3000);
};

// Sidebar
! function($) {
    "use strict";
    var Sidemenu = function() {
        this.$menuItem = $("#sidebar-menu a");
    };

	Sidemenu.prototype.init = function() {
		var $this = this;
		$this.$menuItem.on('click', function(e) {
		if ($(this).parent().hasClass("submenu")) {
			e.preventDefault();
		}
		if (!$(this).hasClass("subdrop")) {
			$("ul", $(this).parents("ul:first")).slideUp(350);
			$("a", $(this).parents("ul:first")).removeClass("subdrop");
			$(this).next("ul").slideDown(350);
			$(this).addClass("subdrop");
		} else if ($(this).hasClass("subdrop")) {
			$(this).removeClass("subdrop");
			$(this).next("ul").slideUp(350);
		}
	});
		$("#sidebar-menu ul li.submenu a.active").parents("li:last").children("a:first").addClass("active").trigger("click");
	},
	$.Sidemenu = new Sidemenu;

}(window.jQuery),


$(document).ready(function($) {
	
	// Sidebar Initiate
	
	$.Sidemenu.init();

    // Sidebar overlay
	
    var $sidebarOverlay = $(".sidebar-overlay");
    $("#mobile_btn, .task-chat").on("click", function(e) {
        var $target = $($(this).attr("href"));
        if ($target.length) {
            $target.toggleClass("opened");
            $sidebarOverlay.toggleClass("opened");
            $("html").toggleClass("menu-opened");
            $sidebarOverlay.attr("data-reff", $(this).attr("href"));
        }
        e.preventDefault();
    });

    $sidebarOverlay.on("click", function(e) {
        var $target = $($(this).attr("data-reff"));
        if ($target.length) {
            $target.removeClass("opened");
            $("html").removeClass("menu-opened");
            $(this).removeClass("opened");
            $(".main-wrapper").removeClass("slide-nav");
        }
        e.preventDefault();
    });
	
    // Select 2

    if ($('.select').length > 0) {
        $('.select').select2({
            width: '100%'
        });
    }

    // Modal

    if ($('.modal').length > 0) {
        var modalUniqueClass = ".modal";
        $('.modal').on('show.bs.modal', function(e) {
            var $element = $(this);
            var $uniques = $(modalUniqueClass + ':visible').not($(this));
            if ($uniques.length) {
                $uniques.modal('hide');
                $uniques.one('hidden.bs.modal', function(e) {
                    $element.modal('show');
					$("body").addClass("modal-open");
                });
                return false;
            }
        });
    }

    // Floating Label

    if ($('.floating').length > 0) {
        $('.floating').on('focus blur', function(e) {
            $(this).parents('.form-focus').toggleClass('focused', (e.type === 'focus' || this.value.length > 0));
        }).trigger('blur');
    }

    // Right Sidebar Scroll

    if ($('.msg-list-scroll').length > 0) {
        $('.msg-list-scroll').slimscroll({
            height: '100%',
            color: '#878787',
            disableFadeOut: true,
            borderRadius: 0,
            size: '4px',
            alwaysVisible: false,
            touchScrollStep: 100
        });
        var h = $(window).height() - 124;
        $('.msg-list-scroll').height(h);
        $('.msg-sidebar .slimScrollDiv').height(h);

        $(window).resize(function() {
            var h = $(window).height() - 124;
            $('.msg-list-scroll').height(h);
            $('.msg-sidebar .slimScrollDiv').height(h);
        });
    }

    // Left Sidebar Scroll

    if ($('.slimscroll').length > 0) {
        $('.slimscroll').slimScroll({
            height: 'auto',
            width: '100%',
            position: 'right',
            size: "7px",
            color: '#ccc',
            wheelStep: 10,
            touchScrollStep: 100
        });
        var hei = $(window).height() - 60;
        $('.slimscroll').height(hei);
        $('.sidebar .slimScrollDiv').height(hei);

        $(window).resize(function() {
            var hei = $(window).height() - 60;
            $('.slimscroll').height(hei);
            $('.sidebar .slimScrollDiv').height(hei);
        });
    }

    // Page wrapper height

    if ($('.page-wrapper').length > 0) {
        var height = $(window).height();
        $(".page-wrapper").css("min-height", height);
    }

    $(window).resize(function() {
        if ($('.page-wrapper').length > 0) {
            var height = $(window).height();
            $(".page-wrapper").css("min-height", height);
        }
    });

    // Datetimepicker

    if ($('.datetimepicker').length > 0) {
        $('.datetimepicker').datetimepicker({
            format: 'DD/MM/YYYY'
        });
    }

    if ($('.datetimepicker-min-today').length > 0) {
        $('.datetimepicker-min-today').datetimepicker({
            format: 'DD/MM/YYYY',
            minDate: new Date(),
        });
    }
    if ($('.datetimepicker-max-today').length > 0) {
        $('.datetimepicker-max-today').datetimepicker({
            format: 'DD/MM/YYYY',
            maxDate: new Date(),
        });
    }
    if ($('.select-book-list').length > 0) {
        $('.select-book-list').on('click', function () {
            var url= $( this ).data( "action" );
            if($('.active-book-search').length > 0) {
                $('.active-book-search').removeClass('active-book-search bg-primary');
            }
            $(this).addClass('active-book-search bg-primary');
            $(".continue-link").attr("href", url);
            $(".continue-link").removeClass('disabled')
        });
    }

    // Bootstrap Tooltip
    if ($('[data-toggle="tooltip"]').length > 0) {
        $('[data-toggle="tooltip"]').tooltip();
    }

    // Toggle Button

    if ($('.btn-toggle').length > 0) {
        $('.btn-toggle').click(function() {
            $(this).find('.btn').toggleClass('active');
            if ($(this).find('.btn-success').size() > 0) {
                $(this).find('.btn').toggleClass('btn-success');
            }
        });
    }

    // Mobile Menu

    if ($('.main-wrapper').length > 0) {
        var $wrapper = $(".main-wrapper");
        $('#mobile_btn').click(function() {
            $wrapper.toggleClass('slide-nav');
            $('#chat_sidebar').removeClass('opened');
            $(".dropdown.open > .dropdown-toggle").dropdown("toggle");
            return false;
        });
        $('#open_msg_box').click(function() {
            $wrapper.toggleClass('open-msg-box');
            $('.themes').removeClass('active');
            $('.dropdown').removeClass('open');
            return false;
        });
    }

    // Product thumb images

    if ($('.proimage-thumb li a').length > 0) {
        var full_image = $(this).attr("href");
        $(".proimage-thumb li a").click(function() {
            full_image = $(this).attr("href");
            $(".pro-image img").attr("src", full_image);
            return false;
        });
    }

    // Lightgallery

    if ($('#pro_popup').length > 0) {
        $('#pro_popup').lightGallery({
            thumbnail: true,
            selector: 'a'
        });
    }
	
    if ($('#lightgallery').length > 0) {
        $('#lightgallery').lightGallery({
			thumbnail: true,
			selector: 'a'
		});
    }
	
	// Incoming call popup
	
    if ($('#incoming_call').length > 0) {
		$(window).on('load',function(){
			$('#incoming_call').modal('show');
			$("body").addClass("call-modal");
		});
    }

    // Summernote

    if ($('.summernote').length > 0) {
        $('.summernote').summernote({
            height: 200,
            minHeight: null,
            maxHeight: null,
            focus: false
        });
    }

    // Will Delete

    if ($('.themes-icon').length > 0) {
        $(".themes-icon").click(function() {
            $('.themes').toggleClass("active");
            if ($('.main-wrapper').hasClass('open-msg-box')) {
                $('.main-wrapper').removeClass('open-msg-box');
            }
        });
    }

    if ($('.dropdown-toggle').length > 0) {
        $('.dropdown-toggle').click(function() {
            if ($('.main-wrapper').hasClass('open-msg-box')) {
                $('.main-wrapper').removeClass('open-msg-box');
            }
        });
    }
	
	/* Custom Modal */
	
	if ($('.custom-modal').length > 0) {
		$(".custom-modal .modal-content").prepend('<button data-dismiss="modal" class="close" type="button">×</button>');
	}

    // Custom Backdrop for modal popup

    $('a[data-toggle="modal"]').on('click', function() {
        setTimeout(function() {
            if ($(".modal.custom-modal").hasClass('show')) {
                $(".modal-backdrop").addClass('custom-backdrop');

            }
        }, 500);
    });

    $('.btn-delete-item').on('click',function () {
        var title = $(this).data('title');
        var url = $(this).data('url');
        var model_id = $(this).data('id');
        var modal = $('#delete_item');
        modal.find('#model_id').val(model_id);
        modal.find('.modal-title').html(title);
        modal.find('form').data('url', url);
    });
    $('.btn-approve-item').on('click',function () {
        var url = $(this).data('url');
        var modal = $('#approve_request');
        if(url) {
            $.ajax({
                url: url,
                data: {_token:csrf_token},
                type: 'POST',
                success: function (data) {
                    modal.find('.request-card-info').remove();
                    modal.find('.modal-body').find('form').prepend(data);
                    var title= modal.find('#title_modal').val();
                    modal.find('.modal-title').html(title);
                },
            });
        }
    });
    $('.btn-cancel-item').on('click',function () {
        var url = $(this).data('url');
        var modal = $('#cancel_request');
        if(url) {
            $.ajax({
                url: url,
                data: {_token:csrf_token},
                type: 'POST',
                success: function (data) {
                    console.log(data);
                    modal.find('.request-card-info').remove();
                    console.log(modal.find('.modal-body').find('form'));
                    modal.find('.modal-body').find('form').prepend(data);
                    var title= modal.find('#title_modal').val();
                    modal.find('.modal-title').html(title);
                },
            });
        }
    });
    // Cancel
    $('.btn-cancel').on('click', function () {
        var url = $('#cancel_url').val();
        var modal = $('#cancel_request');
        var data = modal.find('form').serialize();
        data += "&_token="+csrf_token;
        if(url) {
            $.ajax({
                url: url,
                data: data,
                type: 'POST',
                success: function (data) {
                    $('.error-message').remove();
                    $('.border-danger').each(function(i, obj) {
                        $(this).removeClass('border-danger');
                    });

                    $('.notification-popup').removeClass('error success');
                    if(!data.success) {
                        updateNotification('Hata!', 'Lütfen hataları düzeltip yeniden dene :(', 'error');
                        $.each(data.errors, function( index, value ) {
                            var input = $("[name='"+index+"']");
                            input.addClass('border-danger');
                            input.after('<small class="form-text text-danger error-message">'+value+'</small>');
                        });
                    }else {
                        $('.change-status').removeClass('btn-warning');
                        $('.change-status').addClass(data.class);
                        $('.change-status').text(data.text);
                        modal.modal('toggle');
                        updateNotification('Başarılı!', 'İşlemin başarılı bir şekilde gerçekleşti :)', 'success');
                        $('.after-remove').remove();
                        modal.find('.request-card-info').remove();
                    }
                },
            });
        }
    });
    // Approve request
    $('.btn-approve').on('click', function () {
        var url = $('#approve_url').val();
        var modal = $('#approve_request');
        if(url) {
            $.ajax({
                url: url,
                data: {_token:csrf_token},
                type: 'POST',
                success: function (data) {
                    if(!data.success) {
                        updateNotification('Hata!', 'Beklenmedik bir hata meydana geldi!', 'error');
                    }else {
                        $('.change-status').removeClass('btn-warning');
                        $('.change-status').addClass(data.class);
                        $('.change-status').text(data.text);
                        modal.modal('toggle');
                        updateNotification('Başarılı!', 'İşlemin başarılı bir şekilde gerçekleşti :)', 'success');
                        $('.after-remove').remove();
                        modal.find('.request-card-info').remove();
                    }
                },
            });
        }
    });
    // Detele Item
    $('.btn-delete').on('click',function () {
        var modal = $('#delete_item');
        var url = modal.find('form').data('url');
        var data = modal.find('form').serialize() + "&_token="+csrf_token;
        if(url) {
            $.ajax({
                url: url,
                data: data,
                type: 'POST',
                success: function (data) {
                    $('.error-message').remove();
                    $('.border-danger').each(function(i, obj) {
                        $(this).removeClass('border-danger');
                    });

                    $('.notification-popup').removeClass('error success');
                    if(!data.success) {
                        $.each(data.errors, function( index, value ) {
                            updateNotification('Hata!', value, 'error');
                        });
                    }else {
                        modal.modal('toggle');
                        updateNotification('Başarılı!', 'İşlemin başarılı bir şekilde gerçekleşti :)', 'success');
                        $('*[data-url="'+url+'"]').closest('.card-item').remove();
                    }
                },
            });
        }

    })
    $('.btn-submit').on('click', function () {
        var data = $(this).closest('form').serialize();
        data += "&_token="+csrf_token;
        $.ajax({
            url: request_url,
            data: data,
            type: 'POST',
            success: function (data) {
                $('.error-message').remove();
                $('.border-danger').each(function(i, obj) {
                    $(this).removeClass('border-danger');
                });

                $('.notification-popup').removeClass('error success');
                if(!data.success) {
                    updateNotification('Hata!', 'Lütfen hataları düzeltip yeniden dene :(', 'error');
                    $.each(data.errors, function( index, value ) {
                        var input = $("[name='"+index+"']");
                        input.addClass('border-danger');
                        input.after('<small class="form-text text-danger error-message">'+value+'</small>');
                    });
                }else {
                    updateNotification('Başarılı!', 'İşlemin başarılı bir şekilde gerçekleşti :)', 'success');
                    //window.location=data.url;
                }
            },
        });
    });

    // maps

    if($('#map_canvas').length > 0) {
        var geocoder = new google.maps.Geocoder();
        var infowindow = new google.maps.InfoWindow({
            size: new google.maps.Size(150, 50)
        });
        function geocodePosition(pos) {
            geocoder.geocode({
                latLng: pos
            }, function(responses) {
                console.log(responses);
                if (responses && responses.length > 0) {
                    myMarker.formatted_address = responses[0].formatted_address;
                    $('#address_detail').val('');
                    $('#address_detail').val(myMarker.formatted_address);
                } else {
                    myMarker.formatted_address = 'Adres Bulunamadı';
                }
                infowindow.setContent(myMarker.formatted_address + "<br>Koordinatlar: " + myMarker.getPosition().toUrlValue(6));
                infowindow.open(map, myMarker);
            });
        }
        var map = new google.maps.Map(document.getElementById('map_canvas'), {
            zoom: 9,
            center: new google.maps.LatLng(lat, lng),
            mapTypeId: google.maps.MapTypeId.ROADMAP,
        });

        var myMarker = new google.maps.Marker({
            position: new google.maps.LatLng(center_lat, center_lng),
            draggable: true
        });


        google.maps.event.addListener(myMarker, 'dragend', function (evt) {
            geocodePosition(myMarker.getPosition());
            map.setCenter(myMarker.position);
            myMarker.setMap(map);
            $('#latitude').val(evt.latLng.lat().toFixed(3));
            $('#longitude').val(evt.latLng.lng().toFixed(3));
        });

        map.setCenter(myMarker.position);
        myMarker.setMap(map);
    }
    // City
    $('.city').on('change',function () {
        var city = $(this).val();
        $.ajax({
            url: selected_city_url,
            data: {city:city, _token:csrf_token},
            type: 'POST',
            success: function (data) {
                $('.town').html('');
                var newOption = new Option('Seç', '', false, false);
                $('.town').append(newOption);
                if(data.towns) {
                    var towns = JSON.parse(data.towns);
                    $.each(towns, function(k, v) {
                        var newOption = new Option(v.name, v.id, false, false);
                        $('.town').append(newOption);
                    });
                }
            },
        });
    });
    // Dropfiles

    if ($("#drop-zone").length > 0) {
        var dropZone = document.getElementById('drop-zone');
        var uploadForm = document.getElementById('js-upload-form');
        var startUpload = function(files) {
            console.log(files)
        }

        uploadForm.addEventListener('submit', function(e) {
            var uploadFiles = document.getElementById('js-upload-files').files;
            e.preventDefault()

            startUpload(uploadFiles)
        })

        dropZone.ondrop = function(e) {
            e.preventDefault();
            this.className = 'upload-drop-zone';

            startUpload(e.dataTransfer.files)
        }

        dropZone.ondragover = function() {
            this.className = 'upload-drop-zone drop';
            return false;
        }

        dropZone.ondragleave = function() {
            this.className = 'upload-drop-zone';
            return false;
        }

    }
	
    // Coming Soon

    function getTimeRemaining(endtime) {
        var t = Date.parse(endtime) - Date.parse(new Date());
        var seconds = Math.floor((t / 1000) % 60);
        var minutes = Math.floor((t / 1000 / 60) % 60);
        var hours = Math.floor((t / (1000 * 60 * 60)) % 24);
        var days = Math.floor(t / (1000 * 60 * 60 * 24));
        return {
            'total': t,
            'days': days,
            'hours': hours,
            'minutes': minutes,
            'seconds': seconds
        };
    }

    function initializeClock(id, endtime) {
        var clock = document.getElementById(id);
        var daysSpan = clock.querySelector('.days');
        var hoursSpan = clock.querySelector('.hours');
        var minutesSpan = clock.querySelector('.minutes');
        var secondsSpan = clock.querySelector('.seconds');

        function updateClock() {
            var t = getTimeRemaining(endtime);

            daysSpan.innerHTML = t.days;
            hoursSpan.innerHTML = ('0' + t.hours).slice(-2);
            minutesSpan.innerHTML = ('0' + t.minutes).slice(-2);
            secondsSpan.innerHTML = ('0' + t.seconds).slice(-2);

            if (t.total <= 0) {
                clearInterval(timeinterval);
            }
        }

        updateClock();
        var timeinterval = setInterval(updateClock, 1000);
    }

    var deadline = new Date(Date.parse(new Date()) + 256 * 24 * 60 * 60 * 1000);
    if ($("#countdown").length > 0)
        initializeClock('countdown', deadline);

});