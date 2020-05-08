var afloader = '';

function afRunjs(target) {
	var scriptElements = target.getElementsByTagName("script");
    var i;
    for(i = 0; i < scriptElements.length; i++)
        eval(scriptElements[i].innerHTML);    
}

const aretha = (q) => ({

	css: (attribute, value) => {
		el = document.querySelectorAll(q);
		if (el.length >= 1) {
			for (var item of el) {
  				item.style[attribute] = value;
			}
		}
		return aretha(q);
	},

	hide: () => {
		el = document.querySelectorAll(q);
		if (el.length >= 1) {
			for (var item of el) {
  				item.style['display'] = 'none';
			}
		}
		return aretha(q);
	},

	show: () => {
		el = document.querySelectorAll(q);
		if (el.length >= 1) {
			for (var item of el) {
  				item.style['display'] = 'block';
			}
		}
		return aretha(q);
	},

	val : (v) => {
		el = document.querySelectorAll(q);
  		if(el.length > 1) {
			if (v) {
				for (var item of el) {
					item.value = v;
				}
			}
		} else {
			if (aretha(q).is('input')) {
				if (v) {
					el[0].value = v;
				}
				return el[0].value;
			}
			if (aretha(q).is('select')) {
				if (v) {
					el[0].value = v;
				}
				return el[0].options[el[0].selectedIndex].value;
			}
		}
		return aretha(q);
	},

  	data: (data, val = null) => {
  		if (val == null) {
	  		if (typeof q === 'object') {
	  			return aretha().targetize(q).getAttribute("data-" + data);
	  		}
			el = document.querySelectorAll(q);
			if(el.length > 1) {
				return "";
			}
			return el[0].getAttribute("data-" + data);
		} else {
			if (typeof q === 'object') {
				aretha().targetize(q).setAttribute("data-" + data, val);
			}

			el = document.querySelectorAll(q);
			if(el.length > 1) {
				return false;
			}
			return el[0].setAttribute("data-" + data, val);
		}
	},

	attr: (attr, val) => {
		if (typeof q === 'object') {
			if(val) {
				aretha().targetize(q).setAttribute("" + attr, val);
			}
  			return aretha().targetize(q).getAttribute("" + attr);
  		}
  		el = document.querySelectorAll(q);
		if(el.length > 1) {
			if(val) {
				for (var item of el) {
	  				item.setAttribute("" + attr, val);
				}
			}
			return "";
		}
		return el[0].getAttribute("" + attr);
	},

	is: (tag) => {
		if (typeof q === 'object') {
  			return (aretha().targetize(q).tagName.toLowerCase() == tag || el[0].tagName.toUpperCase() == tag);
  		}
		el = document.querySelectorAll(q);
		if(el.length > 1) {
			return false;
		}
		return (el[0].tagName.toLowerCase() == tag || el[0].tagName.toUpperCase() == tag);
	},

	off: (ev, fn) => {
		el = document.querySelectorAll(q);
		if(el.length > 1) {
			for (var item of el) {
  				item.removeEventListener(ev, fn);
			}
		}
		el[0].removeEventListener(ev, fn);
		return aretha(q);
	},

	on: (ev, fn) => {
		el = document.querySelectorAll(q);
		if(el.length > 1) {
			for (var item of el) {
  				item.addEventListener(ev, fn);
			}
		} else {
			el[0].addEventListener(ev, fn);	
		}
		return aretha(q);
	},

	click: () => {
		el = document.querySelectorAll(q);
		if(el.length > 1) {
			for (var item of el) {
  				item.click();
			}
		} else {
			el[0].click();	
		}
		return aretha(q);
	},

	submit: () => {
		el = document.querySelectorAll(q);
		if(el.length > 1) {
			return false;
		}

		if (aretha(q).is('form')) {
			el[0].submit();
		}
		return false;
	},

	text:(string) => {
		if (typeof q === 'object') {
  			aretha().targetize(q).textContent = string;
  			return aretha().targetize(q);
  		}

		el = document.querySelectorAll(q);
		if(el.length > 1) {
			return aretha(q);
		}

		el[0].textContent = string;
		return aretha(q);
	},
	get:(json) => {
		var xhr = new XMLHttpRequest();
		xhr.onload = function (data) {
			if(xhr.readyState === XMLHttpRequest.DONE && xhr.status === 200) {
				json.success(xhr.responseText);
        	}
        	if (xhr.readyState === XMLHttpRequest.DONE && xhr.status === 404) {
        		
        		if (json.useNotFoundPage != null && json.useNotFoundPage) {
        			if (json.notFoundPage != null && json.notFoundPage != "") {
	        			fetch(json.notFoundPage).then(response => response.text()).then(text => json.notfound(text));
        			}
        		} else {
        			json.notfound(xhr.responseText);
        		}
        	}
		}
		xhr.onerror = function() {
			
		}
		xhr.open('GET', json.url, true);
		xhr.send();
	},

	html:(val) => {
		if (typeof q === 'object') {
			if (val) {
				aretha().targetize(q).innerHTML = val;
				return aretha().targetize(q);
			}
  			return aretha().targetize(q).innerHTML;
  		}

  		el = document.querySelectorAll(q);
		if(el.length > 1) {
			if(val) {
				for (var item of el) {
					item.innerHTML = val;
					afRunjs(item);
				}
				return aretha(q);
			}
			return "";
		}

		if(val) {
			el[0].innerHTML = val;
			afRunjs(el[0]);
			return aretha(q);
		}

		return el[0].innerHTML;
	},

	post: (u, d, s) => {
		var params = typeof d == 'string' ? d : Object.keys(d.data).map(function(k) {
    		return encodeURIComponent(k) + '=' + encodeURIComponent(d.data[k])
		}).join('&');
		

		var xhr = new XMLHttpRequest();
		xhr.onreadystatechange = function (data) {
			if(xhr.readyState === XMLHttpRequest.DONE && xhr.status === 200) {
				s(xhr.responseText);
        	}
		}
		xhr.onerror = function() {

		}

		xhr.open('POST', u);
		xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
		xhr.setRequestHeader("Content-length", params.length);
		xhr.send(params);
	},

	ready: (fn) => {
		if (document.attachEvent ? document.readyState === "complete" : document.readyState !== "loading"){
		    fn();
		} else {
		    document.addEventListener('DOMContentLoaded', fn);
		}
	},

	targetize: (e) => {
		e = e || window.event;
    	var targ = e.target || e.srcElement;
    	if (targ.nodeType == 3) 
    		targ = targ.parentNode;
    	return targ;
	},

	removeClass: (cl) => {
		el = document.querySelectorAll(q);
		if (el.length > 1) {
			for (var item of el) {
  				item.className = item.className.replace(cl, "").replace("  ", " ");
			}
		} else {
			el[0].className = el[0].className.replace(cl, "").replace("  ", " ");
		}
	},

	addClass: (cl) => {
		if (typeof q === 'object') {
  			aretha().targetize(q).classList.add(cl);
  			return;
  		}

		el = document.querySelectorAll(q);
		if (el.length > 1) {
			for (var item of el) {
				if (!item.classList.contains(cl)) {
					item.classList.add(cl);
				}
			}
		} else {
			el[0].classList.add(cl);
		}
	},

	addClassToParent: (cl) => {
		if (typeof q === 'object') {
  			aretha().targetize(q).parentNode.classList.add(cl);
  			return;
  		}

		el = document.querySelectorAll(q);
		if (el.length > 1) {
			for (var item of el) {
				if (!item.parentNode.classList.contains(cl)) {
					item.parentNode.classList.add(cl);
				}
			}
		} else {
			el[0].parentNode.classList.add(cl);
		}
	},

	targetSuffix:'',
	serializeTargetSuffix: (s) => {
		targetSuffix = s;
	},

	serialize: () => {
		var serialized = [];
		var val = "";
		var name = "";
		var fields;
		el = document.querySelectorAll(q);

		if ( el.length == 1 && aretha(q).is('form')) {
			fields = el[0].elements;
		} else {
			fields = el;
		}

		if (el.length >= 1) {
			for (var item of fields) {
				val = "";
				switch(item.tagName.toLowerCase()) {
					case 'input' : val = item.value; break;
					case 'select': val = item.options[item.selectedIndex].value; break;
				}

				if (item.hasAttribute('name')) {
					name = item.name;
				} else {
					if (item.hasAttribute('id')) {
						name = item.id;
					} else {
						continue;
					}
				}

				if (targetSuffix != null && targetSuffix != "") {
					if (name.endsWith(targetSuffix)) {
						serialized.push(encodeURIComponent(name) + "=" + encodeURIComponent(val));
					}
				} else {
					serialized.push(encodeURIComponent(name) + "=" + encodeURIComponent(val));
				}
			}
			return serialized.join('&');
		} else {
			return "";
		}

	},

	labelText: (id, val = null, to = false) => {
		label = document.querySelector('label[for="' + id + '"]');
		if (val == null) {
			if (to) 
				return (label.textContent).replace(/<[^>]*>/g, '').replace("*", "").trim();
			else
				return label.textContent.replace("*", "").trim();
		} else {
			label.textContent = val;
		}
	},

	interval: (f, m) => {
		window.setInterval(f, m);
	},

	addScript: (f) => {
		var s = document.createElement( 'script' );
  		s.setAttribute('src', f );
  		document.body.appendChild(s);
	},

	loadScripts: (a, c, h = false) => {
	    var loader = function(src,handler) {
	        var s = document.createElement("script");
	        s.src = src;
	        s.onload = s.onreadystatechange = function(){
	            s.onreadystatechange = s.onload = null;
	            handler();
	        }

	        if (h) {
	        	var head = document.getElementsByTagName("head")[0];
	        	head.appendChild(s);
	        } else {
	        	document.body.appendChild(s);
	        }
	    };
	    (function run() {
	        if(a.length!=0){
	            loader(a.shift(), run);
	        } else {
	        	// callback
	            c && c();
	        }
	    })();
	},

	loadCSS: (f) => {
		var h = document.getElementsByTagName("head")[0];
		var r = f.split(); 
		var c = null;
		for (var i of r) {
			c = document.createElement("link");
	        c.setAttribute("rel", "stylesheet");
	        c.setAttribute("type", "text/css");
	        c.setAttribute("href", i);
			h.appendChild(c);
		}
	}
});

aretha().ready(function() {
	aretha('body').on('click', function(e) {
		
		if ( (" " + e.target.className + " ").replace(/[\n\t]/g, " ").indexOf(" af-link ") > -1 ) {
			e.preventDefault();
			var _url    = "";
			var _target = aretha(e).data('target');

			if ( aretha(e).is('a') )     _url = aretha(e).attr('href');
			if ( aretha(e).is('input') ) _url = aretha(e).data('url');
			if ( aretha(e).is('label') ) _url = aretha(e).data('url');
			//console.log("URL: " + _url + " DATA: " + _target);

			var fncall = aretha(e).data('fncall');
			if (fncall != null && fncall != "") {
				window[fncall]();
			}

			aretha(_target).html(afloader);
			aretha().get({
		        "url"             : _url,
		        "data"            : {},
		        "useNotFoundPage" : true,
		        "notFoundPage"    :'arethafw/html/404.html',
		        success: function(data) {
		            aretha(_target).html(data);
		        },
		        notfound : function(xhr) {
		        	aretha(_target).html(xhr);	
		        }
		    });
		    e.stopPropagation();
		}

		// Fullview Bootstrap Modal
		if ( (" " + e.target.className + " ").replace(/[\n\t]/g, " ").indexOf(" aretha-fullview ") > -1 ) {
			e.preventDefault();
			var _target = aretha(e).data('target') + "-dialog";

			if (aretha(e).data('state') == "normal") {
				aretha(_target).addClass('aretha-fullview-dialog');
				_target = aretha(e).data('target') + "-content";
				aretha(_target).addClass('aretha-fullview-content');
				
				_target = aretha(e).data('target') + "-icon";
				aretha(_target).removeClass('mdi-fullscreen');
				aretha(_target).addClass('mdi-fullscreen-exit');
				
				aretha(e).data('state', 'fullsize');
			} else {
				aretha(_target).removeClass('aretha-fullview-dialog');
				_target = aretha(e).data('target') + "-content";
				aretha(_target).removeClass('aretha-fullview-content');
				
				_target = aretha(e).data('target') + "-icon";
				aretha(_target).removeClass('mdi-fullscreen-exit');
				aretha(_target).addClass('mdi-fullscreen');
				
				aretha(e).data('state', 'normal');
			}
			e.stopPropagation();
		}
	});
});