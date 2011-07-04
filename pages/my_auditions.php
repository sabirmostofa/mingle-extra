<?php


 $user_id=$mngl_user->id;
 $field_id=$wpdb->get_var($wpdb->prepare("SELECT id FROM wp_mngl_custom_fields where name='Status';"));
$role=$wpdb->get_var($wpdb->prepare("SELECT value FROM wp_mngl_custom_field_values where user_id='$user_id' and field_id='$field_id';"));

if($role == 'Actor' || $role == 'Actress'):
endif;

if($role == 'Producer' || $role == 'Director'):

if(isset($_POST['new_aud'])){
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
<td><textarea style="width:90%"  rows='10' name="aud_des"></textarea></td>
</tr>

<tr>
<td>Audition End Date</td>
</tr>

<tr>
<td><input id="lastdate" type="text" name="aud_date"/></td>
</tr>

<tr>
<td><input type="submit" name="aud_save" value="Publish Audition"/></td>
</tr>

</table>	
</form>
	<?php
	}

?>
<form action='' method="POST">
<input type="submit" name="new_aud" value="New Audition"/>
</form>
<?php


endif;

if($role == 'Site Administrator' || current_user_can('administrator')):
endif;
?>
