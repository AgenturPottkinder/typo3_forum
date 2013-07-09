$(document).ready(function () {
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

	$("#tx-mmforum-tag-autocomplete")
	.bind( "keydown", function( event ) {
		if ( event.keyCode === $.ui.keyCode.TAB &&
			$( this ).data( "ui-autocomplete" ).menu.active ) {
			event.preventDefault();
		}
	})
	.autocomplete({
		minLength: 0,
		source: function( request, response ) {
			$.getJSON( "search.php", {
				term: extractLast( request.term )
			}, response );
		},
		search: function() {
			// custom minLength
			var term = extractLast( this.value );
			if ( term.length < 2 ) {
				return false;
			}
		},
		focus: function() {
			// prevent value inserted on focus
			return false;
		},
		select: function( event, ui ) {
			var terms = split( this.value );
			// remove the current input
			terms.pop();
			// add the selected item
			terms.push( ui.item.value );
			// add placeholder to get the comma-and-space at the end
			terms.push( "" );
			this.value = terms.join( ", " );
			return false;
		}
	});
});


function split( val ) {
	return val.split( /,\s*/ );
}
function extractLast( term ) {
	return split( term ).pop();
}


/**
	$("#tx-mmforum-tag-autocomplete").autocomplete({
		source: function (request, response) {
			$.getJSON("http://ws.geonames.org/searchJSON", {
				term: extractLast(request.term)
			}, response);
		},
		search: function () {
			// custom minLength
			var term = extractLast(this.value);
			if (term.length < 1) {
				return false;
			}
		},
		focus: function () {
			// prevent value inserted on focus
			return false;
		},
		select: function( event, ui ) {
			var terms = split(this.value);
			// remove the current input
			terms.pop();
			// add the selected item
			terms.push(ui.item.value);
			// add placeholder to get the comma-and-space at the end
			terms.push("");
			this.value = terms.join(", ");
			return false;
		}
	});**/


