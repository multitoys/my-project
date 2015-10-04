jQuery.fn.autocomplete = function(url, settings ) 
{
	return this.each( function()//do it for each matched element
	{
		var input = $(this);
		var list = $('<ul class="autocomplete"></ul>');
		$("body").append(list);
		list.css({top: input.offset().top + input.height(), left: input.offset().left, width: input.width()});
		var oldText = '';
		var typingTimeout;
		var size = 0;
		var selected = 0;

		settings = jQuery.extend({
			minChars : 1,
			limit: 10,
			timeout: 500,
			parameters : {'name' : 'text'},
			selection: "<b>$1</b>",
			insert: function (elem, text) {
				elem.val(text);
			}, 
			value: function (elem) {
				return elem.val();
			}
		}, settings);

		var data_json;
				
		var updateList = function(data)
		{
			data_json = data;
			selected = -1;
			var items = '';
			if (data) {
				size = data.length;
				if (!size) {
					list.hide();
				}
				for (i = 0; i < data.length; i++)
				{
					var key = data[i];
					var value = data[i].replace(/</g, "&lt;").replace(/>/g, "&gt;").replace(/"/g, "&quot;");
					value = value.replace(new RegExp("(( |&lt;)(" + settings.value(input) + "))","gi"), "$2" + settings.selection.replace(/1/i, "3"));
					value = value.replace(new RegExp("^(" + settings.value(input) + ")","i"), settings.selection);
					items += '<li value="' + key + '">' + value + '</li>';

				  list.html(items);
				  list.show().css({top: input.offset().top + input.outerHeight(), left: input.offset().left, width: input.width()}).children().
				  hover(function() { 
				  	$(this).addClass("selected").siblings().removeClass("selected");
				  }, function() { 
				  	$(this).removeClass("selected") 
				  }).
				  click(function () {
				  	settings.insert(input, $(this).text());
				  	input.focus();
			  		clear();
				  });
				  list.children(":first").addClass("selected");		
				  selected = 0;
				  if ($.browser.msie) {
				  	$("select:visible").hide().addClass("autoHide");
				  }
				}
			}
		} 
		
		function getData(text)
		{
			window.clearInterval(typingTimeout);
			if (text != oldText && (settings.minChars != null && text.length >= settings.minChars))
			{
				var parameters = {};
				parameters[settings.parameters.name] = text;
				oldText = text;
				$.getJSON(url, parameters, updateList);
				
			}
		}
		
		function clear()
		{
			list.html("");
			list.hide();
			if ($.browser.msie) {
				$("select.autoHide").show().removeClass("autoHide");
			}
			size = 0;
			selected = -1;
		}	
		
		input.keydown(function (e) {
			if(e.which == 13)//enter 
			{ 
				if ( list.css("display") == "none")
				{ 
					getData(settings.value(input));
				} else {
					settings.insert(input, list.children().eq(selected).text());
					clear();
				}
				e.preventDefault();
				return false;
			}
			else if (e.which == 9) {
				clear();
			}
			
		});
		input.keyup(function(e) 
		{
			window.clearInterval(typingTimeout);
			if(e.which == 27 || e.which == 9)//escape
			{
				clear();
			}
			else if (e.which == 13) {
				return false;
			}
			else if(e.which == 40 || e.which == 38)//move up, down 
			{
			  switch(e.which) {
				case 40: 
				  selected = selected >= size - 1 ? 0 : selected + 1; break;
				case 38:
				  selected = selected <= 0 ? size - 1 : selected - 1; break;
				default: break;
			  }
			  list.children().removeClass('selected').eq(selected).addClass('selected').text();	        
			} else 
			{ 
				if (settings.value(input).length == 0) {
					clear();
				}
				if (e.which == 46 || e.which == 8 || list.children().length == 0 || list.children().length >= 10) {
					typingTimeout = window.setTimeout(function() { getData(settings.value(input)) },settings.timeout);
				} else {
					for (i = 0; i < data_json.length; i++) {
						if (data_json[i].toLowerCase().indexOf(settings.value(input).toLowerCase()) == -1) {
							data_json.splice(i, 1);
							i--;
						}
					}
					updateList(data_json);
				}
			}
		});
	});
};
