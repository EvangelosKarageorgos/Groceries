
$('.searchbox').each(function(jjj, searchbox){
	$searchbox = $(searchbox);
	console.log(searchbox);
	$searchbox.find('.search-button').click(function(){
		var searchText = $searchbox.find("input[name='searchText']").val().trim();
		var priceFrom = $searchbox.find("input[name='priceFrom']").val().trim();
		var priceTo = $searchbox.find("input[name='priceTo']").val().trim();
		var sortField = $searchbox.find("select[name='sort-field']").val();
		var sortOrder = $searchbox.find("select[name='sort-order']").val();
		var groups = '';
		var gf = true;
		$searchbox.find("input[name='group']:checked:enabled").each(function(jj, groupel){
			groups = groups+(gf?'':",")+$(groupel).attr('value');
			gf = false;
		});
		var querystring = '';
		var qf = true;
		if(searchText.length>0){
			querystring += (qf?'?':'&') + 'text='+searchText;
			qf = false;
		}
		if(priceFrom.length>0){
			querystring += (qf?'?':'&') + 'price-from='+priceFrom;
			qf = false;
		}
		if(priceTo.length>0){
			querystring += (qf?'?':'&') + 'price-to='+priceTo;
			qf = false;
		}
		if(sortField.length>0){
			querystring += (qf?'?':'&') + 'sort-field='+sortField;
			qf = false;
		}
		if(sortOrder.length>0){
			querystring += (qf?'?':'&') + 'sort-order='+sortOrder;
			qf = false;
		}
		if(groups.length>0){
			querystring += (qf?'?':'&') + 'groups='+groups;
			qf = false;
		}
		window.location = location.pathname+querystring;
	});
});