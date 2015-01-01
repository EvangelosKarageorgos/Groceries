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
				if(oldqty!=newqty){
					var price = parseFloat($(wrapper).attr('price'));
					var itemtotal = price*newqty;
					$(wrapper).find('.total-price').html(itemtotal);
					var totalqtyelement = $(wrapper).closest('.cart').find('.total-quantity .qty');
					var totalpriceelement = $(wrapper).closest('.cart').find('.total-price .price');
					totalqtyelement.html(parseInt(totalqtyelement.html())+added);
					totalpriceelement.html(parseFloat(totalpriceelement.html())+added*price);
				}
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
