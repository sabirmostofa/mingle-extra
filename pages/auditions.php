<?php


$user_id= $mngl_user->id;
$user_profile_page=trim(get_permalink( $mngl_options->profile_page_id),'/');

$this_page_link = trim(get_permalink( $mngl_options->auditions_page_id),'/');
$allauds_page_link = trim(get_permalink( $mngl_options->my_auditions_page_id),'/');

$new_query=new WP_Query("post_type=audition&post_status=publish");
$field_id=$wpdb->get_var($wpdb->prepare("SELECT id FROM wp_mngl_custom_fields where name='Status';"));
$role=$wpdb->get_var($wpdb->prepare("SELECT value FROM wp_mngl_custom_field_values where user_id='$user_id' and field_id='$field_id';"));

$role=trim($role);

if(!$user_id){	


 $redirect_to = ( (isset($redirect_to) and !empty($redirect_to) )?$redirect_to:get_permalink( $mngl_options->auditions_page_id ) );

$login_url = "{$mngl_blogurl}/wp-login.php?redirect_to=$redirect_to";

echo "<div class='updated'>You need To be logged In to view this page</div>";
}

if($role == 'Actor' || $role == 'Actress'):
echo '<table class="naked">';
	
foreach($new_query->posts as $single)
echo "<tr><td>{$single->post_title}</td><td><a href='{$this_page_link}/?action=edit&id={$single->ID}'>Edit</a></td><td><a href='{$this_page_link}/?action=delete&id={$single->ID}'>Delete</a></td></tr>";


echo '</table>';

endif;


if($role == 'Producer' || $role == 'Director'):

echo '<table class="naked"><th>Title</th><th>Producer/Director</th>';
	
foreach($new_query->posts as $single){
	
	$screenname =$mngl_user->get_stored_profile_by_id($single->post_author)->screenname;
	
echo "<tr><td>{$single->post_title}</td><td><a href=\"{$user_profile_page}/?u={$screenname} \">{$mngl_user->screenname}</a></td></tr>";
}

echo '</table>';

endif;


	var_dump($mngl_user->get_stored_profile_by_id(2));
	var_dump($screenname);
	//var_dump($mngl_blogurl);


?>
