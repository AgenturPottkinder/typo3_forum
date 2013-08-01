$(document).ready(function () {
////////////////////// MM_FORUM_AJAX RELOADER


	$.ajax({
		type: "POST",
		url: "index.php?id="+currentPageUid+"&eID=mm_forum&language=de&tx_mmforum_ajax[controller]=Ajax&tx_mmforum_ajax[action]=loginbox",
		async: true,
		success: function (data) {
					$('.loginbox').html(data);
		}
	});

	// forum_last_post_summary
	var postSummarys  = new Array();
	var postSummarysCount  = 0;

	$('.post_summary_box').each(function (index) {
		postSummarys[postSummarysCount] = new Object();
		postSummarys[postSummarysCount]['type'] = $(this).data('type');
		postSummarys[postSummarysCount]['hiddenimage'] = $(this).data('hiddenimage');
		postSummarys[postSummarysCount]['uid'] = $(this).data('uid');
		postSummarysCount = postSummarysCount + 1;
	});

	// onlinepoint
	var displayedUser = new Array();
	var displayedUserCount = 0;
	$('.user_onlinepoint').each(function (index) {
		displayedUser[displayedUserCount] = $(this).data('uid');
		displayedUserCount = displayedUserCount + 1;
	});

	// topicIcons
	var displayedTopicIcons = new Array();
	var displayedTopicIconsCount = 0;
	$('.topic_icon').each(function (index) {
		displayedTopicIcons[displayedTopicIconsCount] = $(this).data('uid');
		displayedTopicIconsCount = displayedTopicIconsCount + 1;
	});

	// forumIcons
	var displayedForumIcons = new Array();
	var displayedForumIconsCount = 0;
	$('.forum_icon').each(function (index) {
		displayedForumIcons[displayedForumIconsCount] = $(this).data('uid');
		displayedForumIconsCount = displayedForumIconsCount + 1;
	});

	// forumMenus
	var displayedForumMenus = new Array();
	var displayedForumMenusCount = 0;
	$('.forum_menu').each(function (index) {
		displayedForumMenus[displayedForumMenusCount] = $(this).data('uid');
		displayedForumMenusCount = displayedForumMenusCount + 1;
	});


	// topicIcons
	var displayedTopics = new Array();
	var displayedTopicsCount = 0;
	$('.topic_entry').each(function (index) {
		displayedTopics[displayedTopicsCount] = $(this).data('uid');
		displayedTopicsCount = displayedTopicsCount + 1;
	});

	var displayOnlinebox = 0;
	if($('.user_online_box').length > 0){
		displayOnlinebox = 1;
	}

	// post_item
	var displayedPosts = new Array();
	var displayedPostCount = 0;
	$('.post_item').each(function (index) {
		displayedPosts[displayedPostCount] = $(this).data('uid');
		displayedPostCount = displayedPostCount + 1;
	});


	// ads
	var displayedAds = new Object;
	var displayedAdsIteration = 0;
	$('.ad-topic').each(function (index) {
		displayedAdsIteration = displayedAdsIteration + 1;
	});
	$('.ad-topicdetail').each(function (index) {
		displayedAdsIteration = displayedAdsIteration + 1;
	});
	displayedAds['count'] = displayedAdsIteration;
	displayedAds['mode'] = 0;
	$('.userInfo').each(function (index) {
		displayedAds['mode'] = 1;
		return;
	});


	$.ajax({
		type: "POST",
		url: "index.php?id="+currentPageUid+"&eID=mm_forum&language=de&tx_mmforum_ajax[controller]=Ajax&tx_mmforum_ajax[action]=main&tx_mmforum_ajax[format]=json",
		async: true,
		data: {
			"tx_mmforum_ajax[displayedUser]": JSON.stringify(displayedUser),
			"tx_mmforum_ajax[postSummarys]": JSON.stringify(postSummarys),
			"tx_mmforum_ajax[topicIcons]": JSON.stringify(displayedTopicIcons),
			"tx_mmforum_ajax[forumIcons]": JSON.stringify(displayedForumIcons),
			"tx_mmforum_ajax[displayedTopics]": JSON.stringify(displayedTopics),
			"tx_mmforum_ajax[displayOnlinebox]": JSON.stringify(displayOnlinebox),
			"tx_mmforum_ajax[displayedForumMenus]": JSON.stringify(displayedForumMenus),
			"tx_mmforum_ajax[displayedPosts]": JSON.stringify(displayedPosts),
			"tx_mmforum_ajax[displayedAds]": JSON.stringify(displayedAds)
		},
		success: function (data) {
			var json = $.parseJSON(data);
			if(json.topicIcons){
				json.topicIcons.forEach(function (entry) {
					$('.topic_icon[data-uid="' + entry.uid + '"]').html(entry.html);
				});
			}
			if(json.forumMenus){
				json.forumMenus.forEach(function (entry) {
					$('.forum_menu[data-uid="' + entry.uid + '"]').html(entry.html);
				});
			}if(json.forumIcons){
				json.forumIcons.forEach(function (entry) {
					$('.forum_icon[data-uid="' + entry.uid + '"]').html(entry.html);
				});
			}
			if(json.postSummarys){
				json.postSummarys.forEach(function (entry) {
					$('.post_summary_box[data-uid="' + entry.uid + '"][data-type="' + entry.type + '"]').html(entry.html);
				});
			}
			if(json.topics){
				json.topics.forEach(function (entry) {
					$('.topic_reply_count[data-uid="' + entry.uid + '"]').html(entry.replyCount);
					$('.topic_list_menu[data-uid="' + entry.uid + '"]').html(entry.topicListMenu);
				});
			}
			if(json.posts){
				json.posts.forEach(function (entry) {
					$('.postHelpfulCount_'+entry.uid+'').html(entry.postHelpfulCount);
					$('.postUserHelpfulCount_'+entry.author.uid+'').html(entry.postUserHelpfulCount);
					$('.post_helpful_button[data-uid="' + entry.uid + '"]').html(entry.postHelpfulButton);
					$('.post_edit_link[data-uid="' + entry.uid + '"]').html(entry.postEditLink);
				});
			}
			if (json.onlineUser) {
				json.onlineUser.forEach(function (entry) {
					$('.user_onlinepoint[data-uid="' + entry+ '"]').removeClass('iconset-14-user-offline');
					$('.user_onlinepoint[data-uid="' + entry+'"]').addClass('iconset-14-user-online');
				});
			}
			if (json.onlineBox) {
				$('.user_online_box .items').html(json.onlineBox.html);
				$('.user_online_count').html(json.onlineBox.count);
			}

			if (json.ads) {
				$('#infomation-'+json.ads.position).html(json.ads.html);
			}

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
					url: "index.php?id="+currentPageUid+"&eID=" + $(this).attr('data-eid') + "&tx_mmforum_ajax[controller]=Post&tx_mmforum_ajax[action]=" + type + "Supporter&tx_mmforum_ajax[post]=" + $(this).attr('data-post'),
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
		}
	});


});



