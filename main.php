<?php   

/*
Plugin Name: mingle-shootaudition
Plugin URI: http://sabirul-mostofa.blogspot.com
Description: A child plugin of mingle for shootaudition.com
Version: 1.0
Author: Sabirul Mostofa
Author URI: http://sabirul-mostofa.blogspot.com
*/


$wpMingleAudition = new wpMingleAudition();
if(isset($wpMingleAudition)) {
	//add_action('init', array($wpMingleAudition,'redirect'), 1);
	add_action('admin_menu', array($wpMingleAudition,'CreateMenu'),50);
		
	
}   
class wpMingleAudition{
	
	function __construct(){
		add_action('admin_enqueue_scripts' , array($this,'add_scripts'));	
		add_action('wp_enqueue_scripts' , array($this,'front_scripts'));	
		add_action('wp_print_styles' , array($this,'front_css'));
		add_action('the_content',array($this,'content_generate'),101);
		
		add_action('init',array($this,'register_post_type'));
			
		add_action( 'wp_ajax_myajax-submit', array($this,'ajax_handle' ));
		add_action( 'wp_ajax_ajax_toggle', array($this,'ajax_toggle' ));
		add_action( 'wp_ajax_ajax_remove', array($this,'ajax_remove' ));
		add_action( 'wp_ajax_show_next', array($this,'ajax_next_page_show'));
		add_action( 'wp_ajax_ajax_getId', array($this,'ajax_process_insert'));
		register_activation_hook(__FILE__, array($this, 'create_table'));
		
		}
		
		function add_scripts(){
		if(preg_match('/wpMingleAudition/',$_SERVER['REQUEST_URI'])){
					
			wp_enqueue_script('jquery');
            wp_enqueue_script('add_audition_script',plugins_url('/' , __FILE__).'js/script.js');	
            wp_localize_script('add_audition_script', 'addAuditionSettings',
array(
'ajaxurl'=>admin_url('admin-ajax.php'),
'pluginurl' => plugins_url('/' , __FILE__)

)
);	

  wp_register_style('add_audition_css', plugins_url('/' , __FILE__).'css/style.css', false, '1.0.0');
    wp_enqueue_style('add_audition_css');
    
 }
	
		
			}
			
			// front scripts
			
				function front_scripts(){
				

					
					wp_enqueue_script('jquery');
if(isset($_POST['new_aud'])){
wp_enqueue_script('jquery_ui_picker',plugins_url('/' , __FILE__).'js/jquery-ui-1.8.11.custom.min.js');

wp_enqueue_script('custom_tiny_mce', plugins_url('/' , __FILE__).'js/tiny_mce/tiny_mce.js');
	}	
					
				if(!(is_admin())){
				wp_enqueue_script('add_audition_front_script',plugins_url('/' , __FILE__).'js/script_front.js');
				
					wp_localize_script('add_audition_front_script', 'addAuditionSettings',
						array(
						'ajaxurl'=>admin_url('admin-ajax.php'),
						'pluginurl' => plugins_url('/' , __FILE__)

						)
						);	
			     }
	
	
	}
				
				
				
			function front_css(){					
					if(!(is_admin())):
					wp_enqueue_style('audition_front_css', plugins_url('/' , __FILE__).'css/style_front.css');
					endif;
					
					if(isset($_POST['new_aud']))
	wp_enqueue_style('jq_date_front_css', plugins_url('/' , __FILE__).'css/jquery-ui-1.8.11.custom.css');

			}
			
		

	function CreateMenu(){
		add_submenu_page('mingle-options','Auditions','Video Auditions','activate_plugins','wpMingleAudition',array($this,'OptionsPage'));
	  


	}
	
	function register_post_type(){		
		
		register_post_type( 'audition',
		array(
			'labels' => array(
				'name' => __( 'Auditions' ),
				'singular_name' => __( 'Audition' )
			),
			'public' => true,
			'has_archive' => true,
			'rewrite' => array('slug' => 'auditions'),
			'supports' => array('title','editor','author','thumbnail','excerpt')

		)
	);
		
		
		
		}
	
	function content_generate($content){
		global $post, $mngl_options,$mngl_user,$wpdb,$mngl_blogurl;
		if(!is_page())return $content;
		
		
		
		if($post->ID == $mngl_options -> auditions_page_id):
		ob_start();
		include 'pages/auditions.php'; 	
		$content = ob_get_contents();
		ob_end_clean();
		return $content;
		
		
		elseif( $post->ID == $mngl_options -> my_auditions_page_id ):		
		ob_start();
		include 'pages/my_auditions.php'; 	
		$content = ob_get_contents();
		ob_end_clean();
		return $content;
		
		endif;
		
		return $content;
		
}


function create_audition(){
	
	}
	
function submit_audition(){
	}
function list_auditions(){
	return 'Al auds list';
	}
	

	
					
					
					
		
						
						

		
		
	function create_table(){
	
   $sql = "CREATE TABLE IF NOT EXISTS `wp_audition_list` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT, 
  `post_id` int(10)  NOT NULL,
  `user_id` int(10)  NOT NULL, 
   PRIMARY KEY (`id`), 
   key `post_id`(`post_id`)
   
);";

$sql1 = "CREATE TABLE IF NOT EXISTS `wp_actor_videos` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT, 
  `user_id` int(10)  NOT NULL,
  `audition_id` int(10)  NOT NULL,
  `video` text  NOT NULL,  
   PRIMARY KEY (`id`),  
   key `user_id`(`user_id`)
   
)";


global $wpdb;
require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
dbDelta($sql);
dbDelta($sql1);


	}	
		
		
/*
 * Options Page
 * 
 * */	
	
	
	
	
	
	
	
	
	function OptionsPage( ){
		global $mngl_options;
		
		//var_dump($mngl_options);
		
	
		
		//update_option('mngle_options', $mngl_options);
		


		/*	
		if(!isset($mngl_options->my_auditions_page_id ))
		$mngl_options->my_auditions_page_id = 0;
		
		$mngl_options->my_auditions_page_id _str='mingle-my-auditions-page-id';
		
		
		*/
		
		
		if(isset($_POST['action'])):
		
				if( ! isset($mngl_options->auditions_page_id ) )
		$mngl_options -> auditions_page_id = 0;
		$mngl_options -> auditions_page_id_str = 'auditions-page-id';
		
		if( ! isset($mngl_options->my_auditions_page_id ) )
		$mngl_options -> my_auditions_page_id = 0;
		$mngl_options -> my_auditions_page_id_str = 'my-auditions-page-id';
		
		$this->process_form();
		
		
		endif;
		
	unset($_POST);
		?>
		<form name="mngl_options_form" method="post" action="">
<input type="hidden" name="action" value="process-form">
<?php wp_nonce_field('update-options'); ?>

<h3><?php _e('Mingle Pages', 'mingle'); ?>:</h3>
<span class="description"><?php printf(__('Before you can get going with Mingle, you must configure where Mingle pages on your website will appear. You\'ll want to %1$screate a new page%2$s for each of these pages that mingle needs to work. You should give your page a title and optionally put some content into the page ... just know that once you set the page up here, the page\'s content will not display.', 'mingle'), '<a href="page-new.php">', '</a>'); ?></span>
<table class="form-table">
  <tr class="form-field">
    <td valign="top" style="text-align: right; width: 150px;"><?php _e('Auditions Page', 'mingle'); ?>*: </td>
    <td style="width: 150px;">
      <?php $this -> wp_pages_dropdown( $mngl_options->auditions_page_id_str, $mngl_options->auditions_page_id, __("Auditions") )?>
    </td>
    <td valign="top" style="text-align: right; width: 150px;"><?php _e('My auditions Page', 'mingle'); ?>*: </td>
    <td style="width: 150px;">
      <?php $this-> wp_pages_dropdown( $mngl_options->my_auditions_page_id_str, $mngl_options->my_auditions_page_id, __("My auditions") )?>
    </td>
    <td>&nbsp;</td>
  </tr>
  
 
</table>

<p class="submit">
<input class='button-primary' type="submit" name="Submit" value="<?php _e('Update Options', 'mingle') ?>" />
</p>
		
<?php		
	}//endof options page
	
	
function process_form(){
    global $mngl_options, $mngl_app_helper ;
    
    if(MnglUser::is_logged_in_and_an_admin())
    {
		
  
      
      $this->update($_POST);
       
       
  }
}


  function update($params)
  {
    $this->update_page('auditions_page', $params);
    $this->update_page('my_auditions_page', $params);
    //MnglOptions::update_page('audition_page', $params);
    
    global $mngl_options;
    //var_dump($mngl_options);
 
 update_option('mngl_options',$mngl_options);
   }
   
     function update_page($page_name, &$params)
  {
	   global $mngl_options;
	   
	  
    $page_name_id = $page_name . "_id";
    $page_name_str = $page_name_id . "_str";
    
  //  var_dump($params[$this->$page_name_str]);

    if( !is_numeric($params[$mngl_options->$page_name_str]) and
        preg_match("#^__auto_page:(.*?)$#",$params[$mngl_options->$page_name_str],$matches) )
      $mngl_options->$page_name_id = $params[$mngl_options->$page_name_str] = $this->auto_add_page($matches[1]);
    else
       $mngl_options->$page_name_id = (int)$params[$mngl_options->$page_name_str];
       
       //var_dump($mngl_options);
      // exit;
       
  }
  
    function auto_add_page($page_name)
  {
    return wp_insert_post(array('post_title' => $page_name, 'post_type' => 'page', 'post_status' => 'publish', 'comment_status' => 'closed'));
  }
  
  
  //Dropdown pages
    function wp_pages_dropdown($field_name, $page_id=0, $auto_page='', $include_disabled=false)
  {
    $pages = MnglAppHelper::get_pages();
    $selected_page_id = (isset($_POST[$field_name])?$_POST[$field_name]:$page_id);
  
    ?>
      <select name="<?php echo $field_name; ?>" id="<?php echo $field_name; ?>" class="wafp-dropdown wafp-pages-dropdown">
      <?php if($include_disabled) { ?>
        <option value=""><?php _e('- Disable Page -', 'mingle'); ?>&nbsp;</option>
      <?php } ?>
      <?php if(!empty($auto_page)) { ?>
        <option value="__auto_page:<?php echo $auto_page; ?>"><?php _e('- Auto Create New Page -', 'mingle'); ?>&nbsp;</option>
      <?php }
  
        foreach($pages as $page)
        {    
          $selected = (((isset($_POST[$field_name]) and $_POST[$field_name] == $page->ID) or (!isset($_POST[$field_name]) and $page_id == $page->ID))?' selected="selected"':'');
          ?>
          <option value="<?php echo $page->ID; ?>" <?php echo $selected; ?>><?php echo $page->post_title; ?>&nbsp;</option>
          <?php
        }
      ?>
      </select>
    <?php
  
    if($selected_page_id) {
        $permalink = get_permalink($selected_page_id);
    ?>
  &nbsp;<a href="<?php echo $permalink; ?>" target="_blank"><?php _e('View', 'mingle'); ?></a>
    <?php
    }
  }
   

		   
		   

       

   
 
   
   
   
   //Crude functions
        function exists_in_table($video_id){
			global $wpdb;
			//$wpdb = new wpdb( DB_USER, DB_PASSWORD, DB_NAME, DB_HOST );
			$result=$wpdb->get_results( "SELECT video_title FROM wp_video_list where  video_id='$video_id'" );
			if(empty($result))return false;
			else return true;			

			}
			
		function insert(){
			
			}
			
		function delete($vido_id){
				
				}
				
		function suspend(){			
			
		}
		
	
	  


}


?>
