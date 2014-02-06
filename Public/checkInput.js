function onKeyDownCheckNum(e)
{
	var input;
	
	if(window.event) // IE
    {
    	input = e.keyCode;
    }
	else if(e.which) // Netscape/Firefox/Opera
    {
		input = e.which;
    }
	regExpPattern = /\D/g;
	//退格：8；del：46；.：190；tab：9；方向键：37-40
	if ( (input == 13) || (input == 190) || (input == 46) || (input == 8) || (input == 9) || ( (input>=37)&&(input<=40) ) || (!regExpPattern.test(String.fromCharCode(input))) )//为数字或者小数点
		return true;
	else
		return false;
}

$(document).ready(function () {
    $(this).keypress(function (e) {
   		switch (e.which)
   		{ 
           	case 13:check();break;
       	}
    });
});