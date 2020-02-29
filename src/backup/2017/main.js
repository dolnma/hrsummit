$(function(){
	$('map').imageMapResize();

	$('#mobile-menu-link').click(function(){
		if( $('#main-header .main-nav').hasClass('open') )
		{
			$('#main-header .main-nav').removeClass('open')
		}
		else
		{
			$('#main-header .main-nav').addClass('open')
		}
		return false;
	});

	$('.contact-form input, .contact-form textarea').focus(function(){
		$(this).prev('label').addClass('hide');
	});

	$('.contact-form input, .contact-form textarea').blur(function(){
		if( $(this).val() == '' )
		{
			$(this).prev('label').removeClass('hide');
		}
	});

	$('.main-nav a, .scroll-to-href').click(function(){
		var href = $(this).attr('href');
		if( href == '#' )
		{
			$('html, body').animate({scrollTop:0});
		}
		else
		{
			var section = $( $(this).attr('href') );
			var pos = section.offset();		

			if(href == '#prednasejici') var minus = 77;
			if(href == '#kontakt') var minus = 77;
			if(href == '#vstupenka') var minus = 150;
			else var minus = 120;

			$('html, body').animate({scrollTop:pos.top - minus});
		}

		$('.main-nav').removeClass('open');

		return false;
	});

	$(window).scroll(function(){
		var opacity = 0.8 + $(window).scrollTop() / $('#home').height() * 0.2;
		$('#main-header').css('background', 'rgba(255,184,0,' + opacity + ')' );
	}).trigger('scroll');

	$('#buy-ticket-button').click(function(){
		$('#buy-ticket-form').delay(200).fadeIn(300);
		$('#black').fadeIn(400);
		return false;
	});

	$('#buy-ticket-form-close').click(function(){
		$('#buy-ticket-form').fadeOut(300);
		$('#black').fadeOut(300);
		return false;
	});

	if( $('#alert').length != 0 )
	{
		$('#black').fadeIn();
		$('#alert').fadeIn();
	}

	$('#alert-close, #alert-button').click(function(){
		$('#alert').fadeOut(300);
		$('#black').fadeOut(300);
		return false;
	});

	$('.program-nav a').click(function(){
		if($(this).hasClass('active')) return false;

		$('.program-nav a').removeClass('active');
		$(this).addClass('active');
	
		$('.program-tab').hide();
		$('.program-tab[data-tab='+$(this).attr('data-tab')+']').fadeIn(500);

		return false;
	});

	$('#company-data-show').click(function(){
		$(this).hide();
		$('#company-data .row').fadeIn(200);
		$('#buy-ticket-form').addClass('plussize');
		return false;
	});


	$('.speaker').click(function(){
		$(this).find('a').trigger('click');
	});

	$('.speaker a, #moderator').click(function(){
		$('#'+$(this).attr('data-id')).delay(200).fadeIn(300);
		$('#black').fadeIn(400);
		return false;
	});

	$('.profil-close').click(function(){
		$('.profil').fadeOut(300);
		$('#black').fadeOut(300);
		return false;
	});

	$('#black').click(function(){
		$('.profil').fadeOut(300);
		$('#black').fadeOut(300);
		$('#alert').fadeOut(300);
	});
});