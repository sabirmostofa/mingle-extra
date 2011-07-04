<?php


$user_id= wp_get_current_user()->ID;

if(!$user_id){	


 $redirect_to = ( (isset($redirect_to) and !empty($redirect_to) )?$redirect_to:get_permalink( $mngl_options->auditions_page_id ) );

$login_url = "{$mngl_blogurl}/wp-login.php?redirect_to=$redirect_to";

echo "<p>You need To be logged In to view this page</p>";
}

	//var_dump($mngl_user );
	//var_dump($mngl_blogurl);


?>
