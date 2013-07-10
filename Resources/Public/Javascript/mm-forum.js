$(document).ready(function () {

	////////////////////// MM_FORUM_AJAX RELOADER
	// onlinepoint
	var displayedUser = new Array();
	var displayedUserCount = 0;
	$('.user_onlinepoint').each(function (index) {
		displayedUser[displayedUserCount] = $(this).data('uid');
		displayedUserCount = displayedUserCount + 1;
	});

	// forum_last_post_summary
	$('.post_summary_box').each(function (index) {
		var item  = $(this);
		$.ajax({
			type: "GET",
			url: "index.php?id=2&eID=mm_forum&tx_mmforum_ajax[controller]=Ajax&tx_mmforum_ajax[action]=postSummary&tx_mmforum_ajax[type]="+item.data('type')+"&tx_mmforum_ajax[hiddenImage]="+item.data('hiddenimage')+"&tx_mmforum_ajax[uid]="+item.data('uid'),
			async: true,
			success: function (data) {
				item.html(data);
			}
		});
	});

	$.ajax({
		type: "POST",
		url: "index.php?id=2&eID=mm_forum&tx_mmforum_ajax[controller]=Ajax&tx_mmforum_ajax[action]=main&tx_mmforum_ajax[format]=json",
		async: true,
		data: {
			"tx_mmforum_ajax[displayedUser]": JSON.stringify(displayedUser)

		},
		success: function (data) {
			var json = $.parseJSON(data);
			if (json.onlineUser) {
				json.onlineUser.forEach(function (entry) {
					$('.user_onlinepoint[data-uid="' + entry + '"]').removeClass('iconset-14-user-offline');
					$('.user_onlinepoint[data-uid="' + entry + '"]').addClass('iconset-14-user-online');
				});
			}
		}
	});



	$('.tx-mmforum-helpfull-btn').click(function () {
		var targetElement = this;
		var counttargetVal = $('.' + $(targetElement).attr('data-counttarget')).html();
		var countusertargetVal = $('.' + $(targetElement).attr('data-countusertarget')).html();
		var type = 'add';
		if ($(targetElement).hasClass('supported')) {
			type = 'remove';
		}
		$.ajax({
			type: "GET",
			url: "index.php?id=2&eID=" + $(this).attr('data-eid') + "&tx_mmforum_ajax[controller]=Post&tx_mmforum_ajax[action]=" + type + "Supporter&tx_mmforum_ajax[post]=" + $(this).attr('data-post'),
			async: false,
			beforeSend: function (msg) {
				$('.' + $(targetElement).attr('data-counttarget')).html('<div class="tx-mmforum-ajax-loader"></div>');
				$('.' + $(targetElement).attr('data-countusertarget')).html('<div class="tx-mmforum-ajax-loader"></div>');
			},
			success: function (data) {
				var json = $.parseJSON(data);
				if (json.error) {
					$('.' + $(targetElement).attr('data-counttarget')).html(counttargetVal);
					$('.' + $(targetElement).attr('data-countusertarget')).html(countusertargetVal);
					return
				}
				if (type == 'add') {
					$(targetElement).addClass('supported');
				} else {
					if ($(targetElement).hasClass('supported')) {
						$(targetElement).removeClass('supported');
					}
				}
				$('.' + $(targetElement).attr('data-counttarget')).html(json.postHelpfulCount);
				$('.' + $(targetElement).attr('data-countusertarget')).html(json.userHelpfulCount);
			}
		});
	});
});



