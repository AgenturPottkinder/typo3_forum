$.fn.extend({
	insertAtCaret: function(myValue){
		this.each(function(i) {
			if (document.selection) {
				this.focus();
				sel = document.selection.createRange();
				sel.text = myValue;
				this.focus();
			}
			else if (this.selectionStart || this.selectionStart == '0') {
				var startPos = this.selectionStart;
				var endPos = this.selectionEnd;
				var scrollTop = this.scrollTop;
				this.value = this.value.substring(0, startPos)+myValue+this.value.substring(endPos,this.value.length);
				this.focus();
				this.selectionStart = startPos + myValue.length;
				this.selectionEnd = startPos + myValue.length;
				this.scrollTop = scrollTop;
			} else {
				this.value += myValue;
				this.focus();
			}
		})
	}
});

$.fn.extend({
	wrapSelection: function(left, right){
		this.each(function(i) {
			if (document.selection) {
				this.focus();
				sel = document.selection.createRange();
				sel.text = left + sel.text + right;
				this.focus();
			}
			else if (this.selectionStart || this.selectionStart == '0') {
				var startPos = this.selectionStart;
				var endPos = this.selectionEnd;
				var scrollTop = this.scrollTop;
				var curSelection = this.value.substring(startPos, endPos);

				this.value = this.value.substring(0, startPos)+left+curSelection+right+this.value.substring(endPos,this.value.length);
				this.focus();
				this.selectionStart = startPos + curSelection.length + left.length;
				this.selectionEnd = startPos + curSelection.length + left.length;
				this.scrollTop = scrollTop;
			} else {
				this.value += left + right;
				this.focus();
			}
		})
	}
});