<?php


 $user_id=$mngl_user->id;
 $field_id=$wpdb->get_var($wpdb->prepare("SELECT id FROM wp_mngl_custom_fields where name='Status';"));
$role=$wpdb->get_var($wpdb->prepare("SELECT value FROM wp_mngl_custom_field_values where user_id='$user_id' and field_id='$field_id';"));

if($role == 'Actor' || $role == 'Actress'):
endif;

if($role == 'Producer' || $role == 'Director'):

	if(isset($_POST['new_aud'])):
?>
 <form action='' method='POST'>
<table>
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
<td><input type="submit" name="aud_save" value="Publish Audition"/></td>
<td><input type="submit" name="aud_cancel" value="Cancel"/></td>
</tr>

</table>	

</form>
	<?php
	
	
else:

?>
<form action='' method="POST">
<input type="submit" name="new_aud" value="New Audition"/>
</form>
<?php
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

endif;//end save logic

endif;// end if new_aud

endif;//end role if

if($role == 'Site Administrator' || current_user_can('administrator')):
endif;
?>
