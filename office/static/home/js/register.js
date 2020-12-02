// JavaScript Document
var countdown=60; 
function settime(val) { 
	if (countdown == 0) { 
		val.removeAttr("disabled");
		val.text="获取到短信验证码"; 
		countdown =60; 
	} else { 
		val.attr("disabled", true);
		val.text=countdown+"秒后可重新发";
		countdown--; 
		setTimeout(function() { 
			settime(val) 
		},1000) 
	} 

} 
/*$(function(){
    $(".tipTimer").trigger("click");
})*/
