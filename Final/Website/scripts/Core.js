
function AjaxService(options)
{
		var opts = {url:"", service:"", data:"", async:true,
			params:{},
			success:function(data, status, error){console.log(data);},
			error:function(error){console.log('Ajax error: '+error);}
		};
		if (typeof(options.url) !== 'undefined' && typeof(options.url) == 'string')
			opts.url = options.url;
		if (typeof(options.service) !== 'undefined' && typeof(options.service) == 'string')
			opts.service= options.service;
		if (typeof(options.async) !== 'undefined' && typeof(options.async) == 'bool')
			opts.async= options.async;
		if (typeof(options.data) !== 'undefined')
			opts.data= options.data;
		if (typeof(options.success) !== 'undefined' && typeof(options.success) == 'function')
			opts.success= options.success;
		if (typeof(options.error) !== 'undefined' && typeof(options.error) == 'function')
			opts.error= options.error;
		if(opts.url.length==0){
			opts.error('Service url is not defined');
			return;
		}
		if(opts.service.length==0){
			opts.error('Service code is not defined');
			return;
		}
		if(opts.service.length==0){
			opts.error('Service code is not defined');
			return;
		}
		if (typeof(options.params) !== 'undefined'){
			opts.params = options.params;
		}
		var data = {service:opts.service, data:JSON.stringify(opts.data)};
		for (var attrname in opts.params) { data[attrname] = opts.params[attrname]; }
		$.ajax({
			url:opts.url,
			async:opts.async,
			data:data,
			type:"POST",
			dataType:"json",
			success:function(data, status, xhr){
				opts.success(data.data, data.status, data.error);
			},
			error:function(xhr, status, error){
				opts.error(error);
			}
		});
}

function getGetParameter(val, defaultValue) {
	var result = defaultValue,
		tmp = [];
	var items = location.search.substr(1).split("&");
	for (var index = 0; index < items.length; index++) {
		tmp = items[index].split("=");
		if (tmp[0] === val) result = decodeURIComponent(tmp[1]);
	}
	return result;
}

function validateEmail(email) { 
    var re = /^(([^<>()[\]\\.,;:\s@\"]+(\.[^<>()[\]\\.,;:\s@\"]+)*)|(\".+\"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
    return re.test(email);
} 

function growDiv(element) {
    var growDiv = element;
    if (growDiv.clientHeight) {
      growDiv.style.height = 0;
    } else {
      var wrapper = $(growDiv).childNodes()[0];
      growDiv.style.height = wrapper.clientHeight + "px";
    }
  }
function toggleExpander(element){
	var content = $(element).siblings('.expandable-content');
//	content.toggleClass('expanded');
	content.slideToggle(500, function(){});
}
