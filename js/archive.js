$(document).ready(function(){
	
	$('.date-picker').datepicker();
	
	$(".add_field").on("click",function(){
		var $this = $(this),
			field_id = "#" + $this.data("field");
		
		
		//$(field_id).removeClass('hide');
		
		$(field_id).toggleClass('hide');
		
		//$this.addClass("remove_field");
		//$this.removeClass('add_field');
	});

});