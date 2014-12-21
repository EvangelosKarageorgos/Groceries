$('.checkout-form').each(function(jjj, wrapper){

	$(wrapper).find("input.proceed-button").click(function(){
		var cardno = $(wrapper).find("input[name='cardno']").val().trim();
		
		if(cardno.length==0){
			$(wrapper).find('.error.cardno').removeClass("hidden").html("You must enter your credit card number.");
		}
		$(wrapper).find("input[name='cardno']").val(cardno);
		
		//$(wrapper).find("form").submit(function(){alert("fgh");});
		//$(wrapper).find("form").submit();
		$('#proceed-form')[0].submit();
		console.log("sgdfg");
		//$(wrapper).find('form')[0].submit();
		//document.getElementById('#proceed-form"').submit();
	});
});

