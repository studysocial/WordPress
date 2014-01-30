jQuery(document).ready(function($){
	/** TODO: Make this modular, i.e. some popup function with callbacks */
	$(document).on('click', '[popup]', function(){
		var popup = $(this).attr('popup');
		if (popup == 'close') {
			$('#popup-overlay, #popup').removeClass('visible');
			$('#popup>div').html('');
			return false;
		}

		$('#popup-overlay').addClass('visible');
		$('#popup').attr('class', 'visible ' + popup);

		var html = '<a class="close" popup="close"></a>';
		switch (popup) {
			case 'change-school': //uses html = instead of html += so change school does not have a close button
				html = '<h1>Change School</h1>';
			break;
			case 'with-x': //will have a close button
				html += '<h1>Change School</h1>';
			break;
		}

		console.log(html);

		$('#popup>div').html(html);

		try {
			var $flowplayer = $('#popup .flowplayer');
			if ($flowplayer.length > 0)
				$flowplayer.flowplayer();
		} catch(e){}

		$(window).trigger('resize');
		return false;
	});
});