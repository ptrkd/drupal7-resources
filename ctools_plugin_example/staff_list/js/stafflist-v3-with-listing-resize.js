(function ($) {
  Drupal.behaviors.ahclist = {
    attach: function (context, settings) {
      // Do your thing here.
      
      $(window).bind("load", function() {
      		//THIS FINDS THE HEIGHT OF THE TALLEST STAFFLIST BOX AND APPLIES IT TO EVERY BOX
    		var maxHeight = Math.max.apply(null, $("div.stafflist-box").map(function (){
    			return $(this).height();
			}).get());
			 
			$('div.stafflist-box').height(maxHeight);
		});
      
      
      $('.alphabetical-sort-list').on('click', 'li a', function(evt) {

			evt.preventDefault();

			// Grab the letter that was clicked
			//var sCurrentLetter = $(this).text().toUpperCase();
			var sCurrentLetter = $(this).attr('href').replace('#', '');
			 
		 
			//v2
			/*
			var selectorname = "#section-" + sCurrentLetter;
			 
			if( sCurrentLetter == 'ALL' ){
				$('#az-list dl').show('fast');
			}else{
				$('#az-list dl').hide('fast');
    			$(selectorname).show('fast');
    			
    		}
    		*/
    		
    		last_name_search(sCurrentLetter);
    		

});

		$('#show-all').on('click', function(evt) {
			evt.preventDefault();
    		restore_full_list();
      	});
      
		$("#search_field").keyup(function() {
			var search = $.trim(this.value);
			hide_divs(search);
		});
		
		$("#clear-button").click(  function() { restore_full_list(); return false; });
		
		$(".txt-btn-jump").click(  function() { 
			if ( $(this).data("id") != "show-all") {
				show_single_section( $(this).data("id"));
				$('#show-all').show();
			}
			return false;
		});
	
		function restore_full_list() {
			
			$('#search_result_count').text("");
			$('#search_field').val("");
				
			show_all();
			$('.department-head').hide();
			$('#show-all').hide();
			
		}
		
 
		
		function show_all() {
		
			$('#no_results').hide();
			var counter = 1;
			
			$(".alphabetical-sort-list").hide();
    		$("#alphabet-list-0").show();
			
			$('.section').each(
  					function() {
  						
  						$('#section-parent-' + counter).show();
  						$("#row-" + counter + " > div").show();
  						$("#az-list-" + counter).show();
  						 
  						counter = counter + 1;
  					}
				);
				
			
		}

		function hide_divs(search) {
			// hide all divs
			
			search = search.replace(/\s+/g, '-');
			if ( search.length > 0){
				 
				if ( document.getElementById('no_results').style.display == 'block'){
					show_all();
				}
				
				var section_count = $('.section').length;
    			var re =  RegExp( "(^" + search + "|-" + search + ")","i"); 
				
				var counter = 1;
				var number_visible = 0;
				var total_visible = 0;
				
				
				
				
				$('.section').each(
  					function() {
  						
  						$("#row-" + counter + " > div").hide(); 
  						
  						$("#row-" + counter + " > div").filter(function() {
   							return re.test(this.id);
						}).show();
  						
  						
  						number_visible = ($(this).find('.stafflist-box:visible')).length;
    					//alert(($(this).find('.stafflist-box:visible')).length);
    					
    					
    					
    					
    					if (number_visible == 0 ){
    						$('#section-parent-' + counter).hide();
    						$("#az-list-" + counter).hide();
    					}else{
    						$('#section-parent-' + counter).show();
    						$("#az-list-" + counter).show();
    					}
    					
    					total_visible = total_visible + number_visible;
    					counter = counter + 1;
  					}
				);
				
				
				$('#search_result_count').text(" " + total_visible);
				
				if (total_visible == 0){
					$('#no_results').show();
				}else{
					$('#no_results').hide();
				}
				
				
 			}else{
 				restore_full_list();
 			}
		}
		
		function last_name_search(search) {
			// hide all divs
			
			search = search.replace(/\s+/g, '-');
			if ( search.length == 1){
				 
				if ( document.getElementById('no_results').style.display == 'block'){
					show_all();
				}
				
				var section_count = $('.section').length;
    			var re =  RegExp( "(^" + search + "|-" + search + ")","i"); 
				
				var counter = 1;
				var number_visible = 0;
				var total_visible = 0;
				
				
				
				
				$('.section').each(
  					function() {
  						
  						$("#row-" + counter + " > div").hide(); 
  						
  						$("#row-" + counter + " > div").filter(".lastname-" + search).show();
  						
  						
  						number_visible = ($(this).find('.stafflist-box:visible')).length;
    					//alert(($(this).find('.stafflist-box:visible')).length);
    					
    					
    					
    					
    					if (number_visible == 0 ){
    						$('#section-parent-' + counter).hide();
    						$("#az-list-" + counter).hide();
    					}else{
    						$('#section-parent-' + counter).show();
    						$("#az-list-" + counter).show();
    					}
    					
    					total_visible = total_visible + number_visible;
    					counter = counter + 1;
  					}
				);
				
				
				$('#search_result_count').text(" " + total_visible);
				
				if (total_visible == 0){
					$('#no_results').show();
				}else{
					$('#no_results').hide();
				}
				
				
 			}else{
 			
 				 
 				var parts = search.split("-");
 				 
 				if (parts[1] == 0){
 					restore_full_list();
 				}else{
 					restore_full_list();
 					show_single_section(parts[1]);
 				}	
 			
 			}
		}		
		
		function show_single_section(number){
				var counter = 1;
				var number_visible = 0;
				$('#no_results').hide();
				
				$('.section').each(function() {
					if (number == counter){
						$('#section-parent-' + counter).show();
    					$("#az-list-" + counter).show();
    					
    					
    					$(".alphabetical-sort-list").hide();
    					$("#alphabet-list-" + counter).show();
    					
    					
    					number_visible = ($(this).find('.stafflist-box:visible')).length;
    					
    					if (number_visible == 0 ){
    						$('#no_results').show();
    					}
    					
    					
					}else{
						//hide sections
						$('#section-parent-' + counter).hide();
    					$("#az-list-" + counter).hide();
					}
					counter = counter + 1;
				});
		
	 
		}

    }
  };
})(jQuery);

