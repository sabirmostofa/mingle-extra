<?php


$user_id=$mngl_user->id;
$this_page_link = trim(get_permalink( $mngl_options->my_auditions_page_id),'/');

$field_id=$wpdb->get_var($wpdb->prepare("SELECT id FROM wp_mngl_custom_fields where name='Status';"));
$role=$wpdb->get_var($wpdb->prepare("SELECT value FROM wp_mngl_custom_field_values where user_id='$user_id' and field_id='$field_id';"));

$role=trim($role);

// If the User is an Actor or Actress
if($role == 'Actor' || $role == 'Actress'):

if(isset($_GET['action'])):

switch($_GET['action']){
	
	case 'delete':
	$id = $_GET['id'];
 if($wpdb->query("delete from wp_actor_videos where id='$id'"))
 echo '<div class="updated">Audtion Has been Deleted successfully</div>';
	
	break;
	
	}

endif;

$all = $wpdb->get_results("select * from wp_actor_videos where user_id='$user_id'","ARRAY_A");

echo '<table class="naked">';
foreach($all as $single):

$title =  get_the_title($single['audition_id']);
$page_lilnk = $this_page_link."/?view_video={$single['id']}";
echo "<tr><td>{$title}</td><td><a class='vid_preview' href='{$page_lilnk}'>Video Preview</a></td><td><a href='{$this_page_link}/?action=delete&id={$single["id"]}'>Delete</a></td></tr>";

endforeach;
echo '</table>';


endif;

if($role == 'Producer' || $role == 'Director'):

	if(isset($_POST['new_aud'])):
?>
	 <form action='' method='POST'>
	<table class="naked">
	<tr>
	<td>Audition Title</td>
	</tr>

	<tr>
	<td><input style="width:90%" type="text" name="aud_title"/></td>
	</tr>

	<tr>
	<td>Audition Description</td>
	</tr>

	<tr>
	<td><textarea  rows='10' cols='30' name="aud_des"></textarea></td>
	</tr>

	<tr>
	<td>Audition End Date</td>
	</tr>

	<tr>
	<td><input id="lastdate" type="text" name="aud_date"/></td>
	</tr>

	<tr>
	<td style="width:100px"><input type="submit" name="aud_save" value="Publish Audition"/></td>
	<td style="width:70%"><input type="submit" name="aud_cancel" value="Cancel"/></td>
	</tr>

	</table>	

	</form>
	<?php
	
	elseif(isset($_GET['action'])):
	     $post_to_edit= $_GET['id'];
	     
		switch ($_GET['action']):
	
			case 'edit':
		$content=$wpdb->get_var($wpdb->prepare("SELECT post_content FROM wp_posts where ID='$post_to_edit';"));

	     $meta=get_post_meta($post_to_edit,'aud_date');
	     //var_dump($meta);
			?>
			<form action='<?php echo $this_page_link?>' method='POST'>
	<table class="naked">
	<tr>
	<td>Audition Title</td>
	</tr>

	<tr>
	<td><input style="width:90%" type="text" name="aud_title" value="<?php echo get_the_title($post_to_edit)?>"/></td>
	</tr>

	<tr>
	<td>Audition Description</td>
	</tr>

	<tr>
	<td><textarea  rows='10' cols='30' name="aud_des"><?php echo $content ?></textarea></td>
	</tr>

	<tr>
	<td>Audition End Date</td>
	</tr>

	<tr>
	<td><input id="lastdate" type="text" value="<?php echo $meta[0]?>" name="aud_date"/></td>
	<td><input type="hidden" name="post_id" value="<?php echo $post_to_edit ?>"/></td>
	</tr>

	<tr>
	<td><input type="submit" name="aud_edit_save" value="Save"/></td>
	
	<td><input type="submit" name="aud_cancel" value="Cancel"/></td>
	</tr>

	</table>	

			<?php
			unset($_GET);
			break;
			case 'delete':
			 wp_delete_post($post_to_edit,true);
			 
			 echo '<div class="updated">Audtion Has been Deleted successfully</div>';
			unset($_GET);
			break;
		
		
		
		endswitch;
	
	
	elseif( isset($_POST['aud_edit_save'])):
	//var_dump($_POST);
		$new_post['ID']=$_POST['post_id'];
		$new_post['post_title']=$_POST['aud_title'];
		$new_post['post_content']=$_POST['aud_des'];
		
		wp_update_post($new_post);
		
		if(update_post_meta($_POST['post_id'],'aud_date', $_POST['aud_date']))		
		echo '<div class="updated">Audtion Has been updated successfully</div>';
		unset($_POST);
	else:

	
	if(isset($_POST['aud_save'])):
	/*
	if(! preg_match ('/\S/', $_POST['aud_title']))
	echo '<p>Title Needed</p>';

	if(! preg_match ('/\S/', $_POST['aud_des']))
	echo '<p>Description Needed</p>';

	if(! preg_match ('/\S/', $_POST['aud_date']))
	echo '<p>Date Needed</p>';
	*/

	$aud_id= wp_insert_post(array('post_title' => $_POST['aud_title'], 'post_type' => 'audition', 'post_content' =>$_POST['aud_des'], 'post_author'=>$user_id, 'post_status' => 'publish', 'comment_status' => 'closed'));

	if($aud_id)
	add_post_meta($aud_id, 'aud_date', $_POST['aud_date']);
	
	echo '<div class="updated">Audtion Has been Added successfully</div>';
	
unset($_POST);
	endif;//end save logic

	endif;// end if post new_aud

if(!isset($_POST['aud_save']) && !isset($_POST['aud_edit_save']) && !isset($_POST['new_aud']) && !isset($_GET['action'])):
?>
<form action='' method="POST">
	<input type="submit" name="new_aud" value="New Audition"/>
	</form>
<?php

$new_query=new WP_Query("post_author=$user_id&post_type=audition");

echo '<table class="naked">';
	
foreach($new_query->posts as $single)
echo "<tr><td>{$single->post_title}</td><td><a href='{$this_page_link}/?action=edit&id={$single->ID}'>Edit</a></td><td><a href='{$this_page_link}/?action=delete&id={$single->ID}'>Delete</a></td></tr>";


echo '</table>';


endif;

endif;//end role if



if($role == 'Site Administrator' || current_user_can('administrator')):
endif;
?>
