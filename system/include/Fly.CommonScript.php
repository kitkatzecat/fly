<?php
if (function_exists('FlyIncludeRegister')) {
	FlyIncludeRegister('FLY.COMMONSCRIPT');
}
?>
<script>
if (typeof Fly == 'undefined') {
	var Fly = {};
}

Fly.CS = {}

Fly.CS.toggle = function(onshow=function(){},onhide=function(){},initial=true) {
	this.state = !!initial;
	this.show = function() {
		this.state = true;
		onshow();
	};
	this.on = this.show;
	this.hide = function() {
		this.state = false;
		onhide();
	};
	this.off = this.hide;
	this.toggle = function() {
		if (this.state) {
			this.hide();
		} else {
			this.show();
		}
	}

	if (this.state) {
		this.show();
	} else {
		this.hide();
	}
}
</script>
