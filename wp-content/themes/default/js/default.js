if (typeof flowplayer != "undefined")
	flowplayer.conf.swf = '//releases.flowplayer.org/5.4.3/flowplayer.swf';
var code = "38,38,40,40,37,39,37,39,66,65", keys = [];
jQuery(document).ready(function($){
	$('body').removeClass('no-js');
	$(document).on('click', function(e){
		if ($(e.target).closest('nav').length > 0)
			return true;

		$('nav').removeClass('active');
	});
	$(document).on('click', 'nav', function(e){
		if ($('nav').hasClass('active') && $(e.target).closest('.text').length > 0) {
			$('nav').removeClass('active');
			return true;
		}

		$('nav').addClass('active');
	});
	$(document).on('click', '[print]', function() {
		var print = $(this).attr('print'),
			$obj = $(this);
		if (print != "" && print) {
			$obj = $(print);
		}

		if ($obj.length == 0)
			return false;

		$('body').html($obj.clone()).css('background', 'none');
		$('html').css('background', 'none');

		window.print();
	});
	$(document).on('click', '.accordion h2, .accordion>div>b', function(){
		var $parent = $(this).parent(),
			$hidden = $parent.closest('.accordion').children('.hidden').html($parent.html());
		if ($parent.hasClass('open'))
			$parent.removeClass('open').css('max-height','');
		else
			$parent.addClass('open').css('max-height', $hidden.height('auto').height());
		$hidden.height(0);
	});
	$(window).keydown(function(e){
		switch (e.keyCode) {
			default: //add to keys array
				keys.push(e.keyCode);
				if (keys.toString().indexOf(code) >= 0) {
					keys = [];
					$('blockquote').html("\x3C\x64\x69\x76\x3E\x22\x54\x6F\x20\x4B\x69\x6E\x67\x20\x5A\x61\x6E\x65\x21\x22\x3C\x2F\x64\x69\x76\x3E");
				}
			break;

			case 27: //esc
				$('[popup=close]').trigger('click');
			break;
		}
	});
	$(window).on('resize',function(){
		var w = window,
			d = document,
			e = d.documentElement,
			g = d.getElementsByTagName('body')[0],
			wWidth = w.innerWidth || e.clientWidth || g.clientWidth,
			wHeight = w.innerHeight|| e.clientHeight|| g.clientHeight,
			$popup = $('#popup'),
			width = $popup.outerWidth(),
			height = $popup.outerHeight();
		$popup.css({
			left: (wWidth-width)/2 + 'px',
			top: (wHeight-height)/2 + 'px'
		});
	});

	$.fn.extend({
		autoClear: function(){
			return this.each(function(){
				if (!$(this).hasClass('std') && !$(this).attr('readonly')) {
					$(this).attr('data-text',$(this).val());
					$(this).focus(function() {
						$(this).removeClass('req');
						if ($(this).val() == $(this).attr('data-text')) {
							$(this).val('');
						}
					}).blur(function() {
						if ($(this).val() == '') {
							$(this).val($(this).attr('data-text'));
						}
					});
				}
			});
		}
	});

	$('input[type=text]').autoClear();
	
	$("#send-link").on("click", function(){
		var phone_num = document.getElementById("send-link-form")['phone'].value;
		var action = document.getElementById("send-link-form")['action'].value;
		
		$.ajax({
			url: '/wp-content/plugins/SMS.php',
			type: 'POST',
			dataType: 'html',
			data: {"phone_num" : phone_num, "action" : action},
			success: function(data, textStatus, xhr) {
				$("#send-link-form-status").html(data);
			},
			error: function(xhr, textStatus, errorThrown) {
				console.log(xhr);
				console.log(textStatus);
				console.log(errorThrown);
			}
		});
	});
})