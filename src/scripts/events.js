import $ from 'jquery'

$(function () {
    $('#tab1-top').click(function () {
        $('#tab1-bottom').addClass('active')
        $('#tab2-bottom').removeClass('active')
    })

    $('#tab2-top').click(function () {
        $('#tab2-bottom').addClass('active')
        $('#tab1-bottom').removeClass('active')
    })

    $('#tab1-bottom').click(function () {
        $('#tab1-top').trigger('click')
        let position = $('#program').position()
        window.scrollTo(0, position.top - $('#main-header').height())
    })

    $('#tab2-bottom').click(function () {
        $('#tab2-top').trigger('click')
        let position = $('#program').position()
        window.scrollTo(0, position.top - $('#main-header').height())
    })
})
