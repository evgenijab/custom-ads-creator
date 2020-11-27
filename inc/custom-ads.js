//Send a request and receive result data
//It will send the request when the form is submitted.
jQuery(function($){
	$('#filter').submit(function(){
		var filter = $('#filter');
		$.ajax({
			url:filter.attr('action'),
			data:filter.serialize(), 
			type:filter.attr('method'), 
			
			success:function(data){
				$('#response').html(data);
			}
		});
		return false;
	});
});