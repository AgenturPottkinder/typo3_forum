$(document).ready(function () {
	$('.tx-mmforum-helpfull-btn').click(function () {
		var targetElement = this;
		var counttargetVal = $('.' + $(targetElement).attr('data-counttarget')).html();
		var countusertargetVal = $('.' + $(targetElement).attr('data-countusertarget')).html();
		$.ajax({
			type: "GET",
			url: "index.php?id=2&eID=" + $(this).attr('data-eid') + "&tx_mmforum_ajax[controller]=Post&tx_mmforum_ajax[action]=helpful&tx_mmforum_ajax[post]=" + $(this).attr('data-post'),
			async: false,
			beforeSend: function(msg){
				$('.' + $(targetElement).attr('data-counttarget')).html('<div class="tx-mmforum-ajax-loader"></div>');
				$('.' + $(targetElement).attr('data-countusertarget')).html('<div class="tx-mmforum-ajax-loader"></div>');
			},
			success: function (data) {
				var json = $.parseJSON(data);
				if (json.error) {
					$('.' + $(targetElement).attr('data-counttarget')).html(counttargetVal);
					$('.' + $(targetElement).attr('data-countusertarget')).html(countusertargetVal);
				}
				$('.' + $(targetElement).attr('data-counttarget')).html(json.postHelpfulCount);
				$('.' + $(targetElement).attr('data-countusertarget')).html(json.userHelpfulCount);
			}
		});
	});
});