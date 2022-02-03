jQuery(document).ready(function() {

    jQuery('#banners-home').slick({
        arrows: true,
        infinite: true,
        dots: false,
        prevArrow: '<button type="button" class="slick-prev"></button>',
        nextArrow: '<button type="button" class="slick-next"></button>'
    });

    jQuery('#cursos-home-slider').slick({
        arrows: true,
        infinite: false,
        dots: false,
        slidesToShow: 2,
        slidesToScroll: 2,
        prevArrow: '<button type="button" class="slick-prev"></button>',
        nextArrow: '<button type="button" class="slick-next"></button>',
        responsive: [
            {
                breakpoint: 768,
                settings: {
                    slidesToShow: 1,
                    slidesToScroll: 1
                }
            }
        ]
    });

    jQuery('#projetos-home-slider').slick({
        arrows: true,
        infinite: false,
        dots: false,
        slidesToShow: 3,
        slidesToScroll: 3,
        prevArrow: '<button type="button" class="slick-prev"></button>',
        nextArrow: '<button type="button" class="slick-next"></button>',
        responsive: [
            {
                breakpoint: 995,
                settings: {
                    slidesToShow: 2,
                    slidesToScroll: 2
                }
            },
            {
                breakpoint: 768,
                settings: {
                    slidesToShow: 1,
                    slidesToScroll: 1
                }
            }
        ]
    });

    jQuery('#eventos-home-slider').slick({
        arrows: true,
        infinite: false,
        dots: false,
        prevArrow: '<button type="button" class="slick-prev"></button>',
        nextArrow: '<button type="button" class="slick-next"></button>'
    });

    jQuery('#textos-alunes-slider').slick({
        arrows: true,
        infinite: false,
        dots: false,
        slidesToShow: 3,
        slidesToScroll: 3,
        prevArrow: '<button type="button" class="slick-prev"></button>',
        nextArrow: '<button type="button" class="slick-next"></button>',
        responsive: [
            {
                breakpoint: 1024,
                settings: {
                    slidesToShow: 2,
                    slidesToScroll: 2
                }
            },
            {
                breakpoint: 768,
                settings: {
                    slidesToShow: 1,
                    slidesToScroll: 1
                }
            }
        ]
    });

    jQuery('#textos-profes-slider').slick({
        arrows: true,
        infinite: false,
        dots: false,
        slidesToShow: 3,
        slidesToScroll: 3,
        prevArrow: '<button type="button" class="slick-prev"></button>',
        nextArrow: '<button type="button" class="slick-next"></button>',
        responsive: [
            {
                breakpoint: 1024,
                settings: {
                    slidesToShow: 2,
                    slidesToScroll: 2
                }
            },
            {
                breakpoint: 768,
                settings: {
                    slidesToShow: 1,
                    slidesToScroll: 1
                }
            }
        ]
    });

    jQuery('#apoie-slider').slick({
        arrows: true,
        infinite: false,
        dots: false,
        slidesToShow: 1,
        slidesToScroll: 1,
        prevArrow: '<button type="button" class="slick-prev"></button>',
        nextArrow: '<button type="button" class="slick-next"></button>',
        adaptiveHeight: true,
        responsive: [
            {
                breakpoint: 768,
                settings: {
                    arrows: false,
                    dots: true,
                }
            }
        ]
    });

    jQuery('form').attr('autocomplete','off');

    jQuery('#abrir-comentarios, #fechar-comentarios').on('click', function(e) {
        jQuery('#comentarios').toggleClass('active');
        e.preventDefault();
    });

    var maskBehavior = function (val) {
            return val.replace(/\D/g, '').length === 11 ? '(00) 00000-0000' : '(00) 0000-00009';
        },
        options = {onKeyPress: function(val, e, field, options) {
            field.mask(maskBehavior.apply({}, arguments), options);
        }
        };

    jQuery('.telefone').mask(maskBehavior, options);
    jQuery('.cnpj').mask('00.000.000/0000-00');
    jQuery('.cpf').mask('000.000.000-00');

    var $accordionSection = jQuery(window.location.hash + '-collapse');
    if ($accordionSection.length) {
      $accordionSection.collapse('show');
    }

    // if ( jQuery(".start-course").length ) {
    //     jQuery(".start-course").hide();
    //     var linkStart = jQuery('.start-course').attr('href');

    //     jQuery(".stm-lms-buy-buttons").html($("<a href='"+linkStart+"' class='start-course btn btn-secondary py-4'>começar curso</a>"));
    // } else {
    //     if($("body").hasClass("postid-5185")) {
    //         return;
    //     }
    //     var price = jQuery(".price").html();
    //     var priceHTML = price ? '<h4 class="text-center">' + price + '</h4> <br> ' : '';
    //     var urlEscrevase = "https://form.typeform.com/to/WYdlNHuV?typeform-medium=embed-snippet";

    //     jQuery(".stm-lms-buy-buttons").append($(priceHTML+"<a href='"+urlEscrevase+"' data-mode='popup' data-size='70' class='typeform-share btn btn-default py-4'>escreva-se</a>"));

        // var btn = document.querySelector('.typeform-share');
        // btn.onclick = function(e) {
        //   e.preventDefault();

        //   if(!jQuery('[data-buy-course]').length) {
        //     $('.typeform-share').addClass('loading');
        //     $('.stm-lms-buy-buttons .buy-button').trigger('click');
        //     return
        //   }

        //   var item_id = $('[data-buy-course]').attr('data-buy-course');

        //   if (typeof item_id === 'undefined') {
        //     window.location = $('[data-buy-course]').attr('href');
        //     return false;
        //   }

        //   $.ajax({
        //     url: stm_lms_ajaxurl,
        //     dataType: 'json',
        //     data: {
        //       action: 'stm_lms_add_to_cart',
        //       nonce: stm_lms_nonces['stm_lms_add_to_cart'],
        //       item_id: item_id
        //     },
        //     beforeSend: function beforeSend() {
        //       $('.typeform-share').addClass('loading');
        //     },
        //     complete: function complete(data) {
        //       var data = data['responseJSON'];
        //       $('.typeform-share').removeClass('loading');
        //       window.location = urlEscrevase;
        //     }
        //   });
        // }
    // }

    $('a[href="#description"]').html('O curso');
    $('a[href="#faq"]').html('Dúvidas');
    $('a[href="#announcement"]').html('Quanto');
    $('a[href="#reviews"]').html('Depoimentos');
    
    // Add smooth scrolling to all links
    jQuery("a.ancora").on('click', function(event) {

        // Make sure this.hash has a value before overriding default behavior
        if (this.hash !== "") {
            // Prevent default anchor click behavior
            event.preventDefault();

            // Store hash
            var hash = this.hash;

            // Using jQuery's animate() method to add smooth page scroll
            // The optional number (800) specifies the number of milliseconds it takes to scroll to the specified area
            jQuery('html, body').animate({
                scrollTop: jQuery(hash).offset().top
            }, 800, function(){

                // Add hash (#) to URL when done scrolling (default click behavior)
                window.location.hash = hash;
            });
        } // End if
    });

});

jQuery(window).scroll(function() {
    var scroll = jQuery(window).scrollTop();

    var body = document.body,
        html = document.documentElement;

    var height = Math.max( body.scrollHeight, body.offsetHeight,
        html.clientHeight, html.scrollHeight, html.offsetHeight );

    var limiteAltura = height-1000;
    var largura = ((limiteAltura - scroll)*100)/limiteAltura;
    largura = 100-largura;

    jQuery("#barra-leitura").css("width", largura+"%");

});