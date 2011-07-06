	if(typeof tinyMCE !='undefined'){
	tinyMCE.init({
		mode : "textareas",		
		theme : "simple",
		width: "500"
	});
}
jQuery(document).ready(function($){

		
		$("#lastdate").datepicker({ dateFormat: 'yy-mm-dd' });
		
		$(".vid_preview").click(function(evt){
			evt.preventDefault();

			var url = $(this).attr('href');
			
			
			var re=/\?view_video=(\d+)/;
			var ar = re.exec(url);
			
					$.ajax(
		{
			type:"post",
			url:addAuditionSettings.ajaxurl,
		    timeout:5000,
		    data:{
			 'action':'ajax_getvideo',
			 'video_id': ar[1],	  
			},
			
		    success: function(data){
							
				$('#aud_video').css('display','inline').html(data).fadeIn('slow');
		
			}
	   });
			 
			
			return false;
			
			})
			
				$(".view_des").click(function(evt){
			evt.preventDefault();

			var url = $(this).attr('href');
			
			
			var re=/\?aud_id=(\d+)/;
			var ar = re.exec(url);
			alert(ar[1]);
					$.ajax(
		{
			type:"post",
			url:addAuditionSettings.ajaxurl,
		    timeout:5000,
		    data:{
			 'action':'ajax_getdes',
			 'video_id': ar[1],	  
			},
			
		    success: function(data){
							
				$('#aud_des').css('display','inline').html(data).fadeIn('slow');
		
			}
	   });
			 
			
			return false;
			
			})
			
		
		
	})
