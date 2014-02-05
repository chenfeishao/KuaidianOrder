(function($) {
    $.StartScreen = function(){
        var plugin = this;

        plugin.init = function(){
            setTilesAreaSize();
            addMouseWheel();
        };

        var setTilesAreaSize = function(){
            var groups = $(".tile-group");
            var tileAreaWidth = 160;
            $.each(groups, function(i, t){
                tileAreaWidth += $(t).outerWidth()+46;
            });
            $(".tile-area").css({
                width: tileAreaWidth
            });
        };

        var addMouseWheel = function (){
            $("body").mousewheel(function(event, delta){
                var scroll_value = delta * 50;
                $(document).scrollLeft($(document).scrollLeft() - scroll_value);
                return false;
            });
        };

        plugin.init();
    }
})(jQuery);

$(function(){
    $.StartScreen();
});

function getCookie(c_name)
{
    if (document.cookie.length>0)
      {
          var c_start = document.cookie.indexOf(c_name + "=");
          if (c_start != -1)
            {
                c_start = c_start + c_name.length + 1;
                var c_end = document.cookie.indexOf(";",c_start);
                if (c_end == -1) c_end = document.cookie.length;
                return unescape(document.cookie.substring(c_start,c_end));
            }
      }
    return "";
}

function inputPanel()
{
    this.init = function()
    {
        this.tmpValue = "";
        this.output = "";
    }

    this.setValue = function(input)
    {
        this.tmpValue += input;
        this.output.value = this.tmpValue;
    }

    this.getKey = function(input)
    {
        this.output = document.getElementsByTagName("input")[0];

        regExpPattern = /\D/g;
        if (input == "tuige")
        {
            if (this.tmpValue[this.tmpValue.length - 2] == ".")
                this.tmpValue = this.tmpValue.substring(0,this.tmpValue.length - 2);
            else
                this.tmpValue = this.tmpValue.substring(0,this.tmpValue.length - 1);
            this.output.value = this.tmpValue;
        }
        else if (input == "c")
        {
            this.tmpValue = "";
            this.output.value = this.tmpValue;
        }
        else if ( (input == ".") || (!regExpPattern.test(input)) )//为数字或者小数点
        {
            this.setValue(input);
        }
    }
}
var inputPanel = new inputPanel();
inputPanel.init();

