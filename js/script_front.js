	if(typeof tinyMCE !='undefined'){
	tinyMCE.init({
		mode : "textareas",		
		theme : "simple",
		width: "500"
	});
}
jQuery(document).ready(function($){

		
		$("#lastdate").datepicker({ dateFormat: 'yy-mm-dd' });
		
		$(".vid_preview").click(function(){
			var url = $(this).attr('href');
			
			window.open(url,"DesWindow","outerWidth=600,width=500,innerWidth=400,resizable,scrollbars,status");
			 
			
			return false;
			
			})
		
	})
