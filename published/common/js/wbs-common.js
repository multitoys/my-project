WbsData = function() {
	var params = {};
	return {
		set: function (obj) {
			$.extend(params, obj);
		},
		get: function (name, defaultValue) {
			return params[name] || defaultValue || null;
		}
	}
}();

function newClass(parent, prop) {
	  // Dynamically create class constructor.
	  var clazz = function() {
	    // Stupid JS need exactly one "operator new" calling for parent
	    // constructor just after class definition.
	    if (clazz.preparing) return delete(clazz.preparing);
	    // Call custom constructor.
	    if (clazz.constr) {
	      this.constructor = clazz; // we need it!
	      clazz.constr.apply(this, arguments);
	    }
	  }
	  clazz.prototype = {}; // no prototype by default
	  if (parent) {
	    parent.preparing = true;
	    clazz.prototype = new parent;
	    clazz.prototype.constructor = parent;
	    clazz.constr = parent; // BY DEFAULT - parent constructor
	  }
	  if (prop) {
	    var cname = "constructor";
	    for (var k in prop) {
	      if (k != cname) clazz.prototype[k] = prop[k];
	    }
	    if (prop[cname] && prop[cname] != Object)
	      clazz.constr = prop[cname];
	  }
	  clazz.prototype.superclass = function() {return this.constructor.prototype};
	  return clazz;
}

Function.prototype.bind = function(object) {
	var __method = this;
	return function() {
		return __method.apply(object, arguments);
	}
}

function addHandler(element, event, action, scope){
	if (scope) {
		action = action.bind(scope);
	}
	
	if(document.addEventListener) element.addEventListener(event, action, null);
	else if(document.attachEvent) element.attachEvent('on' + event,action);
	else element['on'+event] = action;
}

function createDiv(className) {
	return createElem("div", className);
}

function createElem(tag, className, attributes) {
	var elem = document.createElement(tag);
	$(elem).addClass(className);
	if (attributes) {
		$(elem).attr(attributes);
	}
	return elem;	
}

function htmlspecialchars(str) {
	if (str == null || typeof str != 'string' || str.length == 0) {
		return str;
	}
	return str.replace(/&/g, "&amp;").replace(/</g, "&lt;").replace(/>/g, "&gt;").replace(/"/g, "&quot;").replace(/'/g, "&#039;").replace(/\n/g, "<br/>").replace(/\s/g, "&nbsp;");
}

$().ajaxComplete(function(request, settings){
	if (settings.status == 200) {
		try {
			var response = eval("(" + settings.responseText + ")");
			if (response.errorCode == 'SESSION_TIMEOUT') {
				if (window.parent) {
					var d = window.parent.document;
				} else {
					var d = document;
				}
				d.location.href = response.redirectUrl;
			}
		} catch (e) {
			
		}
	}
});
 