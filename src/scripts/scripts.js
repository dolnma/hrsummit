import '../styles/index.scss'
import $ from 'jquery'
import jQuery from 'jquery'
import 'image-map-resizer'

(function (ns, $) {

    ns.VideoBox = (function () {

        var SELECTOR = {
                self: '.video-box',
                wrapper: '.video-box__wrapper',
                video: '.video-box__video',
                toggle: '.video-box__toggle'
            },

            VOLUME = 0.1,

            $self,
            $video,
            $toggle,
            $wrapper,

            close = function () {

                $toggle.removeAttr('checked').change()
            },

            setInitVolume = function (volume) {

                if ($video[0].tagName.toLowerCase() === 'video') {

                    $video[0].volume = typeof volume === 'number' ? volume : VOLUME
                }
            },

            initAutoplay = function () {

                if ($video[0].tagName.toLowerCase() === 'video') {

                    if ($video[0].autoplay && !$toggle[0].checked) {

                        $video[0].pause()
                    }
                }

                $toggle[$.fn.on ? 'on' : 'bind']('change.' + ns, function () {

                    if ($video[0].tagName.toLowerCase() === 'video') {

                        if (!this.checked) {

                            $video[0].pause()

                        } else if (this.checked && $video[0].autoplay) {

                            $video[0].play()
                        }

                    } else if ($video[0].tagName.toLowerCase() === 'iframe') {

                        if (!this.checked) {

                            $video.remove()

                        } else {

                            if (!$video[0].parentNode) {

                                $wrapper.append($video)
                            }
                        }
                    }
                })
            },

            initAutoclose = function () {

                if ($video[0].tagName.toLowerCase() === 'video') {

                    $video[$.fn.on ? 'on' : 'bind']('ended.' + ns, close)
                }
            },

            initCloseOnOverlay = function () {

                $self[$.fn.on ? 'on' : 'bind']('click.' + ns, function (event) {

                    if (event.target === event.currentTarget) {

                        close()
                    }
                })
            },

            init = function (volume, noautoclose) {

                $self = $(SELECTOR.self)

                if ($self.length) {

                    $wrapper = $(SELECTOR.wrapper)

                    $video = $wrapper.find(SELECTOR.video)

                    $toggle = $(SELECTOR.toggle)

                    if ($toggle[0].checked) {

                        $toggle[0].focus()
                    }

                    setInitVolume(volume)

                    initAutoplay()

                    if (!noautoclose) {

                        initAutoclose()
                    }

                    initCloseOnOverlay()
                }
            }

        return {
            init: init
        }

    }())

}((function (ns) {
    window[ns] = window[ns] || {
        toString: function () {
            return ns
        }
    }
    return window[ns]
}('MJNS')), jQuery));

(function (ns, $) {

    ns.ImageBox = (function () {

        var SELECTOR = {
                self: '.image-box',
                wrapper: '.image-box__wrapper',
                image: '.image-box__image',
                toggle: '.image-box__toggle'
            },

            $self,
            $toggle,

            close = function () {

                $toggle.removeAttr('checked').change()
            },

            initCloseOnOverlay = function () {

                $self[$.fn.on ? 'on' : 'bind']('click.' + ns, function (event) {

                    if (event.target === event.currentTarget) {

                        close()
                    }
                })
            },

            init = function () {

                $self = $(SELECTOR.self)

                if ($self.length) {

                    $toggle = $(SELECTOR.toggle)

                    if ($toggle[0].checked) {

                        $toggle[0].focus()
                    }

                    initCloseOnOverlay()
                }
            }

        return {
            init: init
        }

    }())

}((function (ns) {
    window[ns] = window[ns] || {
        toString: function () {
            return ns
        }
    }
    return window[ns]
}('MJNS')), jQuery))

$(function () {
    // $('map').imageMapResize()

    $('#mobile-menu-link').click(function () {
        if ($('#main-header .main-nav').hasClass('open')) {
            $('#main-header .main-nav').removeClass('open')
        } else {
            $('#main-header .main-nav').addClass('open')
        }
        return false
    })

    $('.contact-form input, .contact-form textarea').focus(function () {
        $(this).prev('label').addClass('hide')
    })

    $('.contact-form input, .contact-form textarea').blur(function () {
        if ($(this).val() == '') {
            $(this).prev('label').removeClass('hide')
        }
    })

    $('.main-nav a, .scroll-to-href').click(function () {
        var href = $(this).attr('href')
        if (href == '#') {
            $('html, body').animate({scrollTop: 0})
        } else {
            var section = $($(this).attr('href'))
            var pos = section.offset()

            if (href == '#prednasejici') var minus = 77
            if (href == '#kontakt') var minus = 77
            if (href == '#vstupenka') var minus = 150
            else var minus = 120

            $('html, body').animate({scrollTop: pos.top - minus})
        }

        $('.main-nav').removeClass('open')

        return false
    })

    $(window).scroll(function () {
        var opacity = 0.8 + $(window).scrollTop() / $('#home').height() * 0.2
        $('#main-header').css('background', 'rgba(255,184,0,' + opacity + ')')
    }).trigger('scroll')

    $('#buy-ticket-button').click(function () {
        $('#buy-ticket-form').delay(200).fadeIn(300)
        $('#black').fadeIn(400)
        return false
    })

    $('#buy-ticket-form-close').click(function () {
        $('#buy-ticket-form').fadeOut(300)
        $('#black').fadeOut(300)
        return false
    })

    if ($('#alert').length != 0) {
        $('#black').fadeIn()
        $('#alert').fadeIn()
    }

    $('#alert-close, #alert-button').click(function () {
        $('#alert').fadeOut(300)
        $('#black').fadeOut(300)
        return false
    })

    $('.program-nav a').click(function () {
        if ($(this).hasClass('active')) return false

        $('.program-nav a').removeClass('active')
        $(this).addClass('active')

        $('.program-tab').hide()
        $('.program-tab[data-tab=' + $(this).attr('data-tab') + ']').fadeIn(500)

        return false
    })

    $('#company-data-show').click(function () {
        $(this).hide()
        $('#company-data .row').fadeIn(200)
        $('#buy-ticket-form').addClass('plussize')
        return false
    })


    $('.speaker').click(function () {
        $(this).find('a').trigger('click')
    })

    $('.speaker a, #moderator').click(function () {
        $('#' + $(this).attr('data-id')).delay(200).fadeIn(300)
        $('#black').fadeIn(400)
        return false
    })

    $('.profil-close').click(function () {
        $('.profil').fadeOut(300)
        $('#black').fadeOut(300)
        return false
    })

    $('#black').click(function () {
        $('.profil').fadeOut(300)
        $('#black').fadeOut(300)
        $('#alert').fadeOut(300)
    })

    if (window.MJNS.VideoBox) {

        window.MJNS.VideoBox.init()
    }

    if (window.MJNS.ImageBox) {

        window.MJNS.ImageBox.init()
    }
})