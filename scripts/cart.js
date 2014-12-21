var addToCart = function(wrapper, qty){
	AjaxService({
		url:BasePath+"/services/cartService.php",
		service:'add',
		data:{code:$(wrapper).attr('code'), qty:qty},
		success:function(data){
			var oldqtyel = $(wrapper).attr('qty');
			if(oldqtyel!=undefined){
				var oldqty = parseInt(oldqtyel);
				var added = data;
				var newqty = oldqty + added;
				$(wrapper).attr('qty', newqty);
				$(wrapper).find('.qty').html(newqty);
				if(newqty==0)
				{
					$(wrapper).remove();
				}
			} else{
				if(data<=0){
					alert("Not enough stock!");
				}
			}
		}
	});
};
