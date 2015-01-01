var completeOrder = function(wrapper){
	AjaxService({
		url:BasePath+"/services/adminService.php",
		service:'complete-order',
		data:$(wrapper).attr('order-no'),
		success:function(data){
			console.log(data);
			$(wrapper).remove();
		}
	});
};
var cancelOrder = function(wrapper){
	AjaxService({
		url:BasePath+"/services/adminService.php",
		service:'cancel-order',
		data:$(wrapper).attr('order-no'),
		success:function(data){
			$(wrapper).remove();
		}
	});
};
