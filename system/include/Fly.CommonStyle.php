<?php
if (function_exists('FlyIncludeRegister')) {
	FlyIncludeRegister('FLY.COMMONSTYLE');
}
?>
<style>
body {
	background-color: #fff;
	padding-top: 8px;
	padding-bottom: 24px;
}
h1,h2,h3,h4,h5,h6,p {
	padding-left: 6%;
	padding-right: 6%;
}
div.FlyCSTitle {
	font-size: 1.2em;
	font-weight: bold;
	padding-top: 18px;
	padding-bottom: 16px;
	padding-left: 6%;
	padding-right: 6%;
}
div.FlyCSTitle.FlyCSSectionTitle {
    background-image: linear-gradient(to left, rgba(180,180,255,0.5) 0%,rgba(180,180,255,0) 100%);
    margin-top: -8px;
	padding-top: 24px;
	position: relative;
	overflow: hidden;
}
p.FlyCSDescription {
	margin-top: -12px;
}
p.FlyCSDescriptionHint {
	font-size: 0.8em;
	opacity: 0.8;
	margin-top: -10px;
}
p.FlyCSHint {
	font-size: 0.8em;
	opacity: 0.8;
	margin-top: -14px;
	padding-left: calc(6% + 26px);
}
p.FlyCSParagraphTitle {
	font-size: 0.8em;
	opacity: 0.8;
	margin-bottom: -12px;
}
img.FlyCSTitleIcon {
	width: 20px;
	height: 20px;
	vertical-align: middle;
	margin: 0px;
	margin-right: 8px;
	margin-top: -4px;
}
img.FlyCSSectionIcon {
	z-index: -1;
	position: absolute;
	right: 6%;
	width: 79px;
	height: 79px;
	top: 0px;
	filter: contrast(150%) saturate(0);
}
div.FlyCSBox {
	background-color: rgba(0,0,0,0.05);
	width: 100%;
	box-sizing: border-box;
	padding-top: 16px;
	padding-bottom: 16px;
	text-align: center;
}
div.FlyCSDivider {
	padding: 0;
	width: 100%;
	height: 0;
	border-top: 1px solid rgba(0,0,0,0.1);
	margin-top: 24px;
	margin-bottom: 24px;
}
div.FlyCSSticky {
	background-color: #fff;
	width: 100%;
	box-sizing: border-box;
	padding-top: 8px;
	padding-bottom: 8px;
	padding-left: 6%;
	padding-right: 6%;
	position: sticky;
}
div.FlyCSSticky.FlyCSStickyBottom {
	border-top: 1px solid rgba(0,0,0,0.1);
	bottom: 0px;
	margin-top: 24px;
}
div.FlyCSSticky.FlyCSStickyTop {
	border-bottom: 1px solid rgba(0,0,0,0.1);
	top: 0px;
	margin-bottom: 24px;
}
button,input[type=submit],input[type=reset],input[type=button] {
	min-width: 100px;
}
input[type=text],select,input[type=date],input[type=number],input[type=date] {
	min-width: 200px;
	width: 30%;
	max-width: 600px;
}
input[type=checkbox].FlyCSInlineIcon {
	width: 18px;
	height: 18px;
}
.FlyCSInlineIcon {
	width: 18px;
	height: 18px;
	vertical-align: middle;
	margin: 0px;
	margin-right: 8px;
	margin-top: -4px;
}
img.FlyCSButtonIcon {
	width: 16px;
	height: 16px;
	vertical-align: middle;
}
img.FlyCSButtonIcon.FlyCSButtonIconText {
	margin-right: 6px;
}
@media (max-height: 300px) {
	div.FlyCSTitle.FlyCSSectionTitle {
		padding-top: 12px;
		padding-bottom: 8px;
	}
	img.FlyCSSectionIcon {
		width: 55px;
		height: 55px;
	}
}
</style>