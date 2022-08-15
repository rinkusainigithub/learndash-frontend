<?php
ob_clean();
ob_start();
/**
 * Plugin Name:       Learndash Frontend
 * Plugin URI:        https://prozoned.com/
 * Description:       Handle the complete course creation from frontend
 * Version:           1.10.3
 * Author:            Prozoned Technologies
 * Author URI:        https://prozoned.com/
 * License:           GPL v2 or later
 * License URI:       https://www.gnu.org/licenses/gpl-2.0.html
 */
 
include(plugin_dir_path( __FILE__ ).'/includes/course/class.course.php');
include(plugin_dir_path( __FILE__ ).'/includes/lesson/class.lesson.php');
include(plugin_dir_path( __FILE__ ).'/includes/topic/class.topic.php');
include(plugin_dir_path( __FILE__ ).'/includes/quiz/class.quiz.php');
include(plugin_dir_path( __FILE__ ).'/includes/question/class.question.php');
add_action('wp_enqueue_scripts', 'fwds_styles2');
function fwds_styles2() {
	$version = rand(10,10000);
wp_register_style('slidesjs_example', plugins_url('/assets/css/style.css', __FILE__),array(),$version);
wp_enqueue_style('slidesjs_example');
wp_register_style( 'astra-mi-css', 'https://fonts.googleapis.com/icon?family=Material+Icons' );
wp_enqueue_style('astra-mi-css');
wp_register_style( 'datatable-css', 'https://cdn.datatables.net/1.10.20/css/jquery.dataTables.min.css' );
wp_enqueue_style('datatable-css');
wp_register_style('multiselect-css', plugins_url('/assets/css/multiselect/multiselect.css', __FILE__),array());
wp_enqueue_style('multiselect-css');
}
add_action('wp_enqueue_scripts', 'fwds_scripts2');
function fwds_scripts2() {
$version = rand(10,10000);
  wp_enqueue_script('jquery');

  wp_register_script('slidesjs_core', plugins_url('/assets/js/tinymce.min.js', __FILE__),array("jquery"));
  wp_enqueue_script('slidesjs_core');
  wp_register_script( 'cst-swal-js', 'https://cdn.jsdelivr.net/npm/sweetalert2@9' );
  wp_enqueue_script('cst-swal-js');
  wp_register_script('slidesjs_core2', plugins_url('/assets/js/custom.js', __FILE__),array("jquery"), $version);
  wp_enqueue_script('slidesjs_core2');
  wp_register_script('slidesjs_core3', plugins_url('/assets/js/repeater.js', __FILE__),array("jquery"));
  wp_enqueue_script('slidesjs_core3');
  wp_register_script( 'astra-bootstrap-js', 'https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js' );
  wp_enqueue_script('astra-bootstrap-js');

  wp_register_script( 'datatable-js', 'https://cdn.datatables.net/1.10.20/js/jquery.dataTables.min.js' );
  wp_enqueue_script('datatable-js');

  wp_register_script('multiselect-js', plugins_url('/assets/js/multiselect/multiselect.min.js', __FILE__),array("jquery"));
  wp_enqueue_script('multiselect-js');
  
	if(!empty(get_option('cst_frnt_page'))){
		$cst_frnt_page = get_option('cst_frnt_page');
	}
	else{
		$cst_frnt_page = get_site_url()."/my-account/create-class/";
	}
  $localize_vars  = apply_filters( 'dokan_admin_localize_param', array(
		'ajaxurl'      => admin_url( 'admin-ajax.php' ),
		'cstsideurl'      => get_site_url(),
		'all_classes' => $cst_frnt_page
	) );

	wp_localize_script( 'slidesjs_core2', 'ajax_vars', $localize_vars );
 

}
function cst_new_role() {  
 
    
    add_role(
        'teacher',
        'Teacher',
        array(
            'read'         => true,
            'delete_posts' => false
        )
    );
	add_role(
        'coach',
        'Coach',
        array(
            'read'         => true,
            'delete_posts' => false
        )
    );
 
}
register_activation_hook( __FILE__, 'cst_new_role' );

add_shortcode( 'render_register_form', 'render_register_form' );

function render_register_form() {
	if(isset($_POST['email'])){
		 $email = $_POST['email'];
		 $username = $_POST['username'];
		 $password = $_POST['password'];
		 
		
		
		
		$user_id = wp_create_user( $username, $password, $email ); 

       
      $user = get_user_by( 'id', $user_id );

      //$user->remove_role( 'customer' );

      
      $user->add_role( 'teacher' );
	  $user->add_role( 'booked_booking_agent' ); 
		
		 $my_techpost_args = array(

			 'post_title'    => $username ,

			'post_content'  => '',

			'post_status'   => 'publish',

			'post_type' => 'teacher_post',
			'post_author' => $user_id,

			);

 

		  $cpt_id = wp_insert_post( $my_techpost_args);

		  update_post_meta( $cpt_id, 'user_id',  $user_id);
		  
		 $newurl = get_site_url().'/my-account/';

		header("Location: ". $newurl);
	}
    echo '<form action=" " class="teacherform" method="post">
  <div class="container">
    <h1>Register</h1>
    <p>Please fill in this form to create an account.</p>
    <hr>

    <label for="email"><b>Email</b></label>
    <input type="text" placeholder="Enter Email" name="email" required>

    <label for="psw"><b>Username</b></label>
    <input type="text" placeholder="Username" name="username" required>

    <label for="password"><b>Password</b></label>
    <input type="password" placeholder="Password" name="password" required>
    <hr>
    <p>By creating an account you agree to our <a href="#">Terms & Privacy</a>.</p>

    <button type="submit" class="registerbtn">Register</button>
  </div>
  
  <div class="container signin">
    <p>Already have an account? <a href="#">Sign in</a>.</p>
  </div>
</form>

';
} 

function cst_my_classes_page_template($page_template)
	{
		wp_enqueue_style( 'astra-bootstrap-css' );
		wp_enqueue_style( 'astra-conditional-css' );
		wp_enqueue_script( 'astra-bootstrap-js' );
	require_once( plugin_dir_path( __FILE__ ) . '/template-parts/my-classes.php');
	}
function cst_create_class_page_template($page_template)
	{
		wp_enqueue_style( 'astra-bootstrap-css' );
		wp_enqueue_style( 'astra-conditional-css' );
		wp_enqueue_script( 'astra-bootstrap-js' );
		if(!session_id())
		session_start();
		if(isset($_SESSION['cst_class_creation']))
		{
			unset($_SESSION['cst_class_creation']);
		}
		if(isset($_SESSION['cst_modules_creation'])){
		    unset($_SESSION['cst_modules_creation']);
		}
		
		
	require_once( plugin_dir_path( __FILE__ ) . '/template-parts/create-class.php');
	}
function cst_create_topic_page_template($page_template)
	{
		
		
		wp_enqueue_style( 'astra-bootstrap-css' );
		wp_enqueue_style( 'astra-conditional-css' );
		wp_enqueue_script( 'astra-bootstrap-js' );
	require_once( plugin_dir_path( __FILE__ ).'/template-parts/create-topic.php');
	
	}	
add_filter( 'woocommerce_account_my-classes_endpoint',  'cst_my_classes_page_template' );
add_filter( 'woocommerce_account_create-class_endpoint',  'cst_create_class_page_template' );
add_filter( 'woocommerce_account_create-topic_endpoint',  'cst_create_topic_page_template' );

function cst_my_classes_endpoint() {
    add_rewrite_endpoint( 'my-classes', EP_ROOT | EP_PAGES );
    add_rewrite_endpoint( 'create-class', EP_ROOT | EP_PAGES );
	add_rewrite_endpoint( 'create-topic', EP_ROOT | EP_PAGES );
}
  
add_action( 'init', 'cst_my_classes_endpoint' );

function cst_my_classes_query_vars( $vars ) {
    $vars[] = 'my-classes';
    $vars[] = 'create-class';
    $vars[] = 'class_id';
	$vars[] = 'create-topic';
    return $vars;
}
  
add_filter( 'query_vars', 'cst_my_classes_query_vars', 0 );

  
function cst_my_classes( $items ) 
{
	
	$items['my-classes'] = 'My Classes';
	return $items;
}
  
add_filter( 'woocommerce_account_menu_items', 'cst_my_classes');

add_shortcode('cst_register', 'cst_register');
function cst_register(){
	if(is_user_logged_in()){
		wp_redirect(get_site_url().'/my-account/create-class/');
		exit();
	}
		wp_enqueue_style( 'astra-bootstrap-css' );
	wp_enqueue_style( 'astra-conditional-css' );
	 if ($_POST['reg_submit']) {
		 $username = $_POST['username'];
		$fname = $_POST['fname'];
		$lname = $_POST['fname'];
            $email = $_POST['inputemail'];
            $password = randomPassword();
            $website = $_POST['inputWebsite'];
            $facebook = $_POST['inputFacebook'];
            $linkedin = $_POST['inputLinkedIn'];
            $mobile = $_POST['mobilenum'];
			if(!empty($_POST['address'])){
				$address = $_POST['address']. ", ";
			}
			else{
				$address = "";
			}
			if(!empty($_POST['city'])){
				$city = $_POST['city']. ", ";
			}
			else{
				$city = "";
			}
			if(!empty($_POST['cst_state'])){
				$state = $_POST['cst_state']. ", ";
			}
			else{
				$state = "";
			}
			if(!empty($_POST['country'])){
				$country = $_POST['country']. ", ";
			}
			else{
				$country = "";
			}
			$complete_address = $address.$city.$state.$country;
            $return = validation();
			if( is_wp_error( $return ) ) {
				$error = $return->get_error_message();
			}
			else{
				$resulreturn = cst_do_register($username, $fname, $lname, $email, $password, $mobile, $complete_address, $website, $facebook, $linkedin);
				if( is_wp_error( $resulreturn ) ) {
					$error = $resulreturn->get_error_message();
				}
				else{
					$success = "Your teacher profile has been created succesfully.";
				}
			}
        }
       return cst_registration_form($error, $success);
}

function cst_do_register($username, $first_name, $last_name, $email, $password, $mobile, $complete_address, $website, $facebook, $linkedin)
    {

        $userdata = array(
            'user_login' => esc_attr($username),
            'user_email' => esc_attr($email),
            'user_pass' => esc_attr($password),
            'user_url' => esc_attr($website),
            'first_name' => esc_attr($first_name),
            'last_name' => esc_attr($last_name),
        );

       
            $register_user = wp_insert_user($userdata);
            if (!is_wp_error($register_user)) {
				wp_update_user( array ('ID' => $register_user, 'role' => 'teacher') ) ;
				wp_new_user_notification($register_user, '', 'user');
				// set the WP login cookie
				$secure_cookie = is_ssl() ? true : false;
				wp_set_auth_cookie( $register_user, true, $secure_cookie );
				wp_safe_redirect( get_site_url().'/my-account/create-class/' );
				exit();
            } else {
                return $register_user;
            }

    }


function validation()
    {

        if (empty($_POST['inputemail']) || empty($_POST['mobilenum']) || empty($_POST['fname']) || empty($_POST['lname']) || empty($_POST['username'])) {
            return new WP_Error('field', 'Required form field is missing');
        }

        if (!is_email($_POST['inputemail'])) {
            return new WP_Error('email_invalid', 'Email is not valid');
        }

        if (email_exists($_POST['inputemail'])) {
            return new WP_Error('email', 'Email Already in use');
        }

    }


function randomPassword() {
	$alphabet = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ1234567890';
	$pass = array(); //remember to declare $pass as an array
	$alphaLength = strlen($alphabet) - 1; //put the length -1 in cache
	for ($i = 0; $i < 8; $i++) {
		$n = rand(0, $alphaLength);
		$pass[] = $alphabet[$n];
	}
	return implode($pass); //turn the array into a string
}

function cst_registration_form($error=false, $success=false)
    {
		
		/* var_dump($_POST);
		exit; */
		if($success){
			foreach($_POST as $key=>$val){
				unset($_POST[$key]);
			}
		}
		$tagline = isset($_POST['tagline']) ? $_POST['tagline'] : null;
		$fname = isset($_POST['fname']) ? $_POST['fname'] : null;
		$lname = isset($_POST['lname']) ? $_POST['lname'] : null;
		$username = isset($_POST['username']) ? $_POST['username'] : null;
		$inputemail = isset($_POST['inputemail']) ? $_POST['inputemail'] : null;
		$inputFacebook = isset($_POST['inputFacebook']) ? $_POST['inputFacebook'] : null;
		$inputLinkedIn = isset($_POST['inputLinkedIn']) ? $_POST['inputLinkedIn'] : null;
		$inputWebsite = isset($_POST['inputWebsite']) ? $_POST['inputWebsite'] : null;
		$address = isset($_POST['address']) ? $_POST['address'] : null;
		$city = isset($_POST['city']) ? $_POST['city'] : null;
		$state = isset($_POST['cst_state']) ? $_POST['cst_state'] : null;
		$country = isset($_POST['country']) ? $_POST['country'] : null;
		$mobilenum = isset($_POST['mobilenum']) ? $_POST['mobilenum'] : null;
		
		global $woocommerce;
$countries_obj   = new WC_Countries();
$countries   = $countries_obj->__get('countries');
wp_enqueue_media();
		
	$out =  '<section class="teacher-registration py-5" id="registration">
    <div class="container">';
		$out .= '<form action="" method="POST">
        <div class="row ">
            <div class="col-md-4 py-5 bg-primary text-white text-center ">
                <div class=" ">
                    <div class="card-body">
                        <img src="'.get_stylesheet_directory_uri().'/assets/images/profile.png" style="width:61%">
						<input type="file" name="propic" id="propic">
                        <h2 class="py-3">Upload Profile Photo</h2>
                        <p><input type="text" placeholder="Enter Tag line" name="tagline" required="required" value="'.$tagline.'"></p>
                    </div>
                </div>
            </div>
            <div class="col-md-8 py-5 border">
                <h2 class="pb-4">Please fill with your details<a href="'.get_site_url().'/my-account" class="reg_login">Already have an account?</a></h2>';
         if($error){
		$out .= '<h3 class="cst-has-error">'.$error.'</h3>';
	}

 if($success){
		$out .= '<h3 class="cst-has-success">'.$success.'</h3>';
	}

                   $out .= '<div class="form-row">
                        <div class="form-group col-md-6">
                          <input id="fname" name="fname" placeholder="First Name" class="form-control" type="text" value="'.$fname.'">
                        </div>
						<div class="form-group col-md-6">
                          <input id="lname" name="lname" placeholder="Last Name" class="form-control" type="text" value="'.$lname.'">
                        </div>
						
                    </div>
					<div class="form-row">
                        <div class="form-group col-md-6">
                          <input type="text" class="form-control" id="username" name="username" placeholder="Username" value="'.$username.'">
                        </div>
						<div class="form-group col-md-6">
                          <input type="email" class="form-control" id="inputEmail4" name="inputemail" placeholder="Email" value="'.$inputemail.'">
                        </div>
						
                    </div>
                    <div class="form-row">
						<div class="form-group col-md-6">
                          <input type="text" class="form-control" id="inputFacebook" name="inputFacebook" placeholder="Facebook" value="'.$inputFacebook.'">
                        </div>
						<div class="form-group col-md-6">
                          <input type="text" class="form-control" id="inputLinkedIn" name="inputLinkedIn" placeholder="LinkedIn" value="'.$inputLinkedIn.'">
                        </div>
					</div>
                    <div class="form-row">
                        <div class="form-group col-md-6">
                            <input id="mobilenum" name="mobilenum" placeholder="Mobile No." class="form-control" type="text" value="'.$mobilenum.'">
                        </div>
						<div class="form-group col-md-6">
                          <input type="text" class="form-control" id="inputWebsite" placeholder="Website" value="'.$inputWebsite.'">
                        </div>
                    </div>
					<div class="form-row">
                        <div class="form-group col-md-6">
                            <input id="address" name="address" placeholder="Address" class="form-control" required="required" type="text" value="'.$address.'">
                        </div>
						<div class="form-group col-md-6">
                           <input id="city" name="city" placeholder="City" class="form-control" required="required" type="text" value="'.$city.'">
                        </div>
                    </div>
					<div class="form-row">
                        <div class="form-group col-md-6">';
                            $out .= woocommerce_form_field('cst_state', array(
								'type'       => 'state',
								'country'       => 'CA',
								'placeholder'    => __('State'),
								'return' => true
								)
								);
                        $out .= '</div>
						<div class="form-group col-md-6">
                                  <select id="country" class="form-control" name="country">
                                    <option selected value="CA">Canada</option>
                                  </select>
                        </div>
                    </div>
                    <div class="form-row">
						<div class="form-group col-md-12">
                                  <select id="inputState" class="form-control">
                                    <option selected>Where did you hear about us ?</option>
                                    <option>Another Teacher on festivalhuawa.com</option>
                                    <option>Advertisement</option>
                                    <option>Google</option>
                                  </select>
                        </div>
						<div class="form-group col-md-12 hidden ref-teacher">
                                  <select id="inputState" class="form-control">
                                    <option selected>Teacher 1</option>
                                    <option>Teacher 2</option>
                                    <option>Teacher 3</option>
                                    <option>Teacher 4</option>
                                  </select>
                        </div>
					</div>
                    <div class="form-row">
                        <div class="form-group col-md-12">
                                <div class="form-check">
                                  <input class="form-check-input" type="checkbox" value="" id="invalidCheck2" required>
                                  <label class="form-check-label" for="invalidCheck2">
                                    <small>By clicking Submit, you agree to our Terms & Conditions, Visitor Agreement and Privacy Policy.</small>
                                  </label>
                                </div>
                          </div>
                    </div>
                    
                    <div class="form-row">
						<div class="form-group col-md-12">
							<input type="submit" class="btn btn-danger" name="reg_submit" value="Submit">
						</div>
                    </div>
    
            </div>
        </div>
		</form>
    </div>
</section>';
return $out;
    }


/* add_action('wp_head', 'cst_clear_class_session');
function cst_clear_class_session(){
	if(!session_id())
		session_start();
	unset($_SESSION['cst_class_creation']);
} */

add_action('wp_ajax_cst_remove_class', 'cst_remove_class');	
add_action('wp_ajax_nopriv_cst_remove_class', 'cst_remove_class');	
function cst_remove_class(){
	if(!session_id())
		session_start();
	if($_POST['id']==0){
wp_delete_post($_SESSION['cst_class_creation'], true);
foreach($_SESSION['cst_modules_creation'] as $cst_modules_creation){
	wp_delete_post($cst_modules_creation, true);
}
unset($_SESSION['cst_class_creation']);
unset($_SESSION['cst_modules_creation']);
	}
	else{
		wp_delete_post($_POST['id'], true);
		unset($_SESSION['cst_class_creation']);
unset($_SESSION['cst_modules_creation']);
	}
return true;
}	
	
add_action('wp_ajax_cst_create_class', 'cst_create_class');	
add_action('wp_ajax_nopriv_cst_create_class', 'cst_create_class');	
function cst_create_class(){
	if(!session_id())
		session_start();
	if(!isset($_SESSION['cst_class_creation'])){
		$class = array(
		'post_title'    => wp_strip_all_tags( $_POST['class_title'] ),
		'post_content'  => $_POST['class_desc'],
		'post_status'   => 'publish',
		'post_author'   => get_current_user_id(),
		'post_type'		=> 'sfwd-courses'
		);
		
		// Create Class
		 $class_id = wp_insert_post( $class );
		echo 'classid='.$class_id;	
		$_SESSION['cst_class_creation'] = $class_id; 
	}
	else{
		$class_id = $_SESSION['cst_class_creation'];
		$class_title = get_the_title($class_id);
		$class_desc = get_the_content($class_id);
		if($class_title != $_POST['class_title'] || $class_desc != $_POST['class_desc']){
			$class = array(
			'ID'           => $class_id,
			'post_title'   => $_POST['class_title'],
			'post_content' => $_POST['class_desc']
			);

			// Update Class
			wp_update_post( $class );
			echo 'updated='.$class_id;
		}
	}
}


add_action('wp_ajax_cst_class_preview', 'cst_class_preview');	
add_action('wp_ajax_nopriv_cst_class_preview', 'cst_class_preview');	
function cst_class_preview(){
if(!session_id())
		session_start();
$title = get_the_title($_SESSION['cst_class_creation']);
$class = array(
			'ID'           => $_SESSION['cst_class_creation'],
			'post_status'   => 'publish'
			);

			// Update Class
			wp_update_post( $class );
echo get_site_url()."/courses/".strtolower(str_replace(" ", "-", $title));
exit();
}

function cst_get_post_by_title($page_title, $post_type ='post' , $output = OBJECT) {
    global $wpdb;
        $post = $wpdb->get_var( $wpdb->prepare( "SELECT ID FROM $wpdb->posts WHERE post_title = %s AND post_type= %s", $page_title, $post_type));
        if ( $post )
            return $post;

    return false;
}

add_action('wp_ajax_cst_create_lesson', 'cst_create_lesson');	
add_action('wp_ajax_nopriv_cst_create_lesson', 'cst_create_lesson');	
function cst_create_lesson(){
	if(!session_id())
		session_start();
	
	$values = $_POST['values'];
	$cst_modules_creation = array();
	if(isset($_SESSION['cst_class_creation']) && !empty($_SESSION['cst_class_creation']))
	{
	$class_id = $_SESSION['cst_class_creation'];
	
	 foreach($values as $key => $value){
		 if(!empty($values[$key])){
			$mtitle = $value['mtitle'];
			$module_desc = $value['module_desc'];
			$module_desc = htmlentities($module_desc);
			$module_cont =  $value['module_cont'];
			$module_cont = htmlentities($module_cont);
			$quiz_check = $value['quiz_check'][0];
			$assigment_check = $value['assign_check'][0];
			$file_ext = $value['file_ext'];
			$file_size_limit = $value['file_size_limit'];
			

		 if(!cst_get_post_by_title($mtitle, 'sfwd-lessons')){
		$class = array(
		'post_title'    => $mtitle,
		'post_content'  => $module_desc,
		'post_status'   => 'publish',
		'post_author'   => get_current_user_id(),
		'post_type'		=> 'sfwd-lessons'
		);

		// Create Lesson
		$lession_id = wp_insert_post( $class );
		//var_dump($lession_id);
		 }
		 else{
				$lession_id = cst_get_post_by_title($mtitle, 'sfwd-lessons');
				$module = array(
				'ID'           => $lession_id,
				'post_content'  => $module_desc,
				'post_status'   => 'publish',
				'post_author'   => get_current_user_id(),
				'post_type'		=> 'sfwd-lessons'
				);

				// Update the post into the database
				wp_update_post( $module );
			}
		$cst_modules_creation[] = $lession_id;
		$lessionpostdate = array();
		//$lessionpostdate = get_post_meta($lession_id, '_sfwd-lessons', false );
		
		$lessionpostdate[0]='default';
		if(!empty($module_cont))
		{
			$lessionpostdate['sfwd-lessons_lesson_materials_enabled']='on';
			$lessionpostdate['sfwd-lessons_lesson_materials']=$module_cont;
			$lessionpostdate['sfwd-lessons_course']=$class_id;
		}
		if($assigment_check=='Yes')
		{
			$file_ext = explode(",", $file_ext);
			$lessionpostdate['sfwd-lessons_lesson_assignment_upload']='on';
			$lessionpostdate['sfwd-lessons_assignment_upload_limit_extensions']=$file_ext;
			$lessionpostdate['sfwd-lessons_assignment_upload_limit_size']=$file_size_limit;
		}
		update_post_meta( $lession_id, '_sfwd-lessons', $lessionpostdate );
		 update_post_meta( $lession_id, 'course_id', $class_id );
		  
		 update_post_meta( $lession_id, '_wp_page_template', 'default' );
		 
		 if($quiz_check == "Yes"){
	
		 $quiz_title = $value['quiz_title'];
		 $quiz_desc = $value['quiz_desc'];
		 $quiz_desc = strip_tags($quiz_desc);
			if(!cst_get_post_by_title($quiz_title, 'sfwd-quiz')){
				$quizdtl = array(
					'post_title'    => $quiz_title,
					'post_content'  => $quiz_desc,
					'post_status'   => 'publish',
					'post_author'   => get_current_user_id(),
					'post_type'		=> 'sfwd-quiz'
				);


				$quiz_id = wp_insert_post( $quizdtl ); 
			}
			else{
				$quiz_id = cst_get_post_by_title($quiz_title, 'sfwd-quiz');
				$quizdtl = array(
				'ID'           => $quiz_id,
				'post_content'  => $quiz_desc,
				'post_status'   => 'publish',
				'post_author'   => get_current_user_id(),
				'post_type'		=> 'sfwd-quiz'
				);

				// Update the post into the database
				wp_update_post( $quizdtl );
			}

		update_post_meta( $quiz_id, 'course_id', $class_id); 
		update_post_meta( $quiz_id, 'lesson_id', $lession_id );
	
		$sfwd_quiz_data = array();
		$sfwd_quiz_data['sfwd-quiz_course'] = $class_id;
		$sfwd_quiz_data['sfwd-quiz_lesson'] = $lession_id;
		$sfwd_quiz_data['sfwd-quiz_startOnlyRegisteredUser'] = "";
    $sfwd_quiz_data['sfwd-quiz_prerequisiteList'] = "";
    $sfwd_quiz_data['sfwd-quiz_prerequisite'] = "";
    $sfwd_quiz_data['sfwd-quiz_retry_restrictions'] = "";
    $sfwd_quiz_data['sfwd-quiz_repeats'] = "";
    $sfwd_quiz_data['sfwd-quiz_quizRunOnceType'] = "";
    $sfwd_quiz_data['sfwd-quiz_quizRunOnceCookie'] = "";
    $sfwd_quiz_data['sfwd-quiz_passingpercentage'] = 80;
    $sfwd_quiz_data['sfwd-quiz_certificate'] = "";
    $sfwd_quiz_data['sfwd-quiz_threshold'] = "";
    $sfwd_quiz_data['sfwd-quiz_quiz_time_limit_enabled'] = "";
    $sfwd_quiz_data['sfwd-quiz_timeLimit'] = 0;
    $sfwd_quiz_data['sfwd-quiz_forcingQuestionSolve'] = "";
    $sfwd_quiz_data['sfwd-quiz_quiz_materials_enabled'] = "off";
    $sfwd_quiz_data['sfwd-quiz_quiz_materials'] = "";
    $sfwd_quiz_data['sfwd-quiz_custom_sorting'] = "";
    $sfwd_quiz_data['sfwd-quiz_autostart'] = "";
    $sfwd_quiz_data['sfwd-quiz_showReviewQuestion'] = "";
    $sfwd_quiz_data['sfwd-quiz_quizSummaryHide'] = 1;
    $sfwd_quiz_data['sfwd-quiz_skipQuestionDisabled'] = 1;
    $sfwd_quiz_data['sfwd-quiz_sortCategories'] = "";
    $sfwd_quiz_data['sfwd-quiz_questionRandom'] = "";
    $sfwd_quiz_data['sfwd-quiz_showMaxQuestion'] = "";
    $sfwd_quiz_data['sfwd-quiz_showMaxQuestionValue'] = "";
    $sfwd_quiz_data['sfwd-quiz_showPoints'] = "";
    $sfwd_quiz_data['sfwd-quiz_showCategory'] = "";
    $sfwd_quiz_data['sfwd-quiz_hideQuestionPositionOverview'] = 1;
    $sfwd_quiz_data['sfwd-quiz_hideQuestionNumbering'] = 1;
    $sfwd_quiz_data['sfwd-quiz_numberedAnswer'] = "";
    $sfwd_quiz_data['sfwd-quiz_answerRandom'] = "";
    $sfwd_quiz_data['sfwd-quiz_quizModus'] = 0;
    $sfwd_quiz_data['sfwd-quiz_quizModus_multiple_questionsPerPage'] = 0;
    $sfwd_quiz_data['sfwd-quiz_quizModus_single_back_button'] = "";
    $sfwd_quiz_data['sfwd-quiz_quizModus_single_feedback'] = "end";
    $sfwd_quiz_data['sfwd-quiz_titleHidden'] = 1;
    $sfwd_quiz_data['sfwd-quiz_resultGradeEnabled'] = "";
    $sfwd_quiz_data['sfwd-quiz_resultText'] = "";
    $sfwd_quiz_data['sfwd-quiz_btnRestartQuizHidden'] = "";
    $sfwd_quiz_data['sfwd-quiz_showAverageResult'] = "";
    $sfwd_quiz_data['sfwd-quiz_showCategoryScore'] = "";
    $sfwd_quiz_data['sfwd-quiz_hideResultPoints'] = "";
    $sfwd_quiz_data['sfwd-quiz_hideResultCorrectQuestion'] = "";
    $sfwd_quiz_data['sfwd-quiz_hideResultQuizTime'] = "";
    $sfwd_quiz_data['sfwd-quiz_hideAnswerMessageBox'] = "";
    $sfwd_quiz_data['sfwd-quiz_disabledAnswerMark'] = "";
    $sfwd_quiz_data['sfwd-quiz_btnViewQuestionHidden'] = "";
    $sfwd_quiz_data['sfwd-quiz_custom_answer_feedback'] = "";
    $sfwd_quiz_data['sfwd-quiz_custom_result_data_display'] = "";
    $sfwd_quiz_data['sfwd-quiz_associated_settings_enabled'] = "";
    $sfwd_quiz_data['sfwd-quiz_toplistDataShowIn_enabled'] = "";
    $sfwd_quiz_data['sfwd-quiz_statisticsIpLock_enabled'] = "";
    $sfwd_quiz_data['sfwd-quiz_quiz_pro'] = 3;
    $sfwd_quiz_data['sfwd-quiz_formActivated'] = "";
    $sfwd_quiz_data['sfwd-quiz_formShowPosition'] = 0;
    $sfwd_quiz_data['sfwd-quiz_toplistDataAddPermissions'] = 1;
    $sfwd_quiz_data['sfwd-quiz_toplistDataAddMultiple'] = "";
    $sfwd_quiz_data['sfwd-quiz_toplistDataAddBlock'] = 0;
    $sfwd_quiz_data['sfwd-quiz_toplistDataAddAutomatic'] = "";
    $sfwd_quiz_data['sfwd-quiz_toplistDataShowLimit'] = 0;
    $sfwd_quiz_data['sfwd-quiz_toplistDataSort'] = 1;
    $sfwd_quiz_data['sfwd-quiz_toplistActivated'] = "";
    $sfwd_quiz_data['sfwd-quiz_toplistDataShowIn'] = 0;
    $sfwd_quiz_data['sfwd-quiz_toplistDataCaptcha'] = "";
    $sfwd_quiz_data['sfwd-quiz_statisticsOn'] = 1;
    $sfwd_quiz_data['sfwd-quiz_viewProfileStatistics'] = "";
    $sfwd_quiz_data['sfwd-quiz_statisticsIpLock'] = 0;
    $sfwd_quiz_data['sfwd-quiz_email_enabled'] = "";
    $sfwd_quiz_data['sfwd-quiz_email_enabled_admin'] = "";
    $sfwd_quiz_data['sfwd-quiz_emailNotification'] = 0;
    $sfwd_quiz_data['sfwd-quiz_userEmailNotification'] = "";
    $sfwd_quiz_data['sfwd-quiz_timeLimitCookie_enabled'] = "";
    $sfwd_quiz_data['sfwd-quiz_timeLimitCookie'] = "";
    $sfwd_quiz_data['sfwd-quiz_templates_enabled'] = "";
		update_post_meta( $quiz_id, '_sfwd-quiz', $sfwd_quiz_data);
		
		$quiz_pro = new WpProQuiz_Model_Quiz();
				$quiz_pro->setId(0);
				$quiz_pro->setName($quiz_title);
				$quiz_pro->setText("AAZZAAZZ");
				$quizMapper = new WpProQuiz_Model_QuizMapper();
				$quizMapper->save($quiz_pro);
				learndash_update_setting($quiz_id, "quiz_pro", $quiz_pro->getId());
		  foreach($value['quiz-group'] as $topics){
			 
			
			$quest_type = $topics['questiontype'];
			$answerModels = array();
			if($quest_type=='single'){
			   $quest_title = $topics['quest_title'];
			   $quest_content = $topics['quest_title'];
			   $answer1 = $topics['answer1'];
			   $answer2 = $topics['answer2'];
			   $answer3 = $topics['answer3'];
			   $answer4 = $topics['answer4'];
			   $correct_answer = $topics['correct_answer'];
			   $answerModel1 = new WpProQuiz_Model_AnswerTypes();
				$answerModel1->setAnswer($answer1);
				$answerModel1->setPoints(1);
				if($correct_answer=='answer1')
				{
					$answerModel1->setCorrect(true);
				}
				else{
					$answerModel1->setCorrect(0);
				}
				
				$answerModels[0] = $answerModel1;
				$answerModel2 = new WpProQuiz_Model_AnswerTypes();
				$answerModel2->setAnswer($answer2);
				$answerModel2->setPoints(1);
				if($correct_answer=='answer2')
				{
					$answerModel2->setCorrect(true);
				}
				else{
					$answerModel2->setCorrect(0);
				}
				
				$answerModels[1] = $answerModel2;
				$answerModel3 = new WpProQuiz_Model_AnswerTypes();
				$answerModel3->setAnswer($answer3);
				$answerModel3->setPoints(1);
				if($correct_answer=='answer3')
				{
					$answerModel3->setCorrect(true);
				}
				else{
					$answerModel3->setCorrect(0);
				}
				
				$answerModels[2] = $answerModel3;
				$answerModel4 = new WpProQuiz_Model_AnswerTypes();
				$answerModel4->setAnswer($answer4);
				$answerModel4->setPoints(1);
				if($correct_answer=='answer4')
				{
					$answerModel4->setCorrect(true);
				}
				else{
					$answerModel4->setCorrect(0);
				}
				
				$answerModels[3] = $answerModel4;
			}
		   else{
				$quest_title = $topics['quest_titlee'];
				$quest_content = $topics['answer1e'];
				$answerModel11 = new WpProQuiz_Model_AnswerTypes();
				$answerModel11->setPoints( 1 );
				$answerModel11->setGraded( true );
				$answerModel11->setGradedType( $quest_content );
				$answerModel11->setGradingProgression( 'not-graded-none' );

				$answerModels[0] = $answerModel11;
		   }
			if(!cst_get_post_by_title($quest_title, 'sfwd-question')){

				$quesdtl = array(
					'post_title'    => $quest_title,
					'post_content'  => "",
					'post_status'   => 'publish',
					'post_author'   => get_current_user_id(),
					'post_type'		=> 'sfwd-question'
				);

				// Create QUESTION
				$question_id = wp_insert_post( $quesdtl );
			}
			else{
				 $question_id = cst_get_post_by_title($quest_title, 'sfwd-question');
			 $quesdtl = array(
				'ID'           => $question_id,
				'post_content'  => $quest_content,
				'post_status'   => 'publish',
				'post_author'   => get_current_user_id(),
				'post_type'		=> 'sfwd-question'
				);

				// Update the post into the database
				wp_update_post( $quesdtl );
			}
		   
		    
			
			
		 update_post_meta($question_id, 'quiz_id', $quiz_id);
		 update_post_meta($question_id, 'question_type', $quest_type); 
		 update_post_meta($question_id, '_sfwd-question', array('sfwd-question_quiz'=>strval($quiz_id)));
			
				
				$question = new WpProQuiz_Model_Question();
				$question->setId(0);
				$question->setTitle($quest_title);
				$question->setQuestion($quest_title);
				$question->setAnswerData( $answerModels );
				$question->setAnswerType($quest_type);
				$question->setQuizId( $quiz_pro->getId() );
				$quiz_quest_controler = new WpProQuiz_Controller_Question();
			$questionMapper = new WpProQuiz_Model_QuestionMapper();
			$questionpro = $questionMapper->save( $question, true );
			$question = $questionMapper->fetch( $question->getId() );
				$quiz_quest_controler->setAnswerObject($question);
			update_post_meta($question_id,"question_pro_id", $question->getId());
			//var_dump($questionpro); 
		 } 
		 } 
		 }
	 }
	 
	 
}
if(isset($cst_modules_creation))
	{
		
		$output='';
		$modulids = $cst_modules_creation;
		foreach($modulids as $modulid)
		{
			$selected = '';
			$lessionsdetails = get_post_meta($modulid, '_sfwd-lessons', false );
				foreach($lessionsdetails as $lessionsdetail){
					if($lessionsdetail['sfwd-lessons_sample_lesson']=='on')
					{
						$selected='selected';
					}
				}
			$output .= '<option value="'.$modulid.'" class="cstlena" '.$selected.'>'.get_the_title( $modulid ).'</option>';
			
		}
		echo $output;
	}
exit;
}


add_action("wp_ajax_cst_assign_class_price", "cst_assign_class_price");
add_action("wp_ajax_nopriv_cst_assign_class_price", "cst_assign_class_price");
function cst_assign_class_price() {
	if(!session_id())
		session_start();
		$access_mode = $_POST['access_mode'];
		$course_price = $_POST['course_price'];
		$sample_lessonsid = $_POST['sample_lessons'];
		$sample_lessonsid = intval($sample_lessonsid);
		if(!empty($sample_lessonsid))
		{
			$lessionsdetails = get_post_meta($sample_lessonsid, '_sfwd-lessons', false );
		    $lessionsdetails['sfwd-lessons_sample_lesson']='on';
		    update_post_meta($sample_lessonsid, '_sfwd-lessons', $lessionsdetails);
			
		}
		
			$button_url = $_POST['button_url'];
		update_option('course_pricetest', $course_price);
		update_option('abutton_urltest', $button_url);
		update_option('access_modetest', $access_mode);
		$disable_content = "on";
		$class_progress = "on";
		
	
	
	$visible = $_POST['cst_visibility'];
	$class_progress = $_POST['class_progress'];
	
	//echo $_SESSION['cst_class_creation'];
	if($visible == 1)
	{
		$disable_content = "";
	}
	if($class_progress == 1)
	{
		$class_progress = "";
	}
	if($class_progress == 2)
	{
		$class_progress = "on";
	}

	
	//var_dump($class_price);
	$data = array();
	$data['sfwd-courses_wcf_course_template'] = "none";
    $data['sfwd-courses_course_prerequisite_enabled'] = "";
    $data['sfwd-courses_course_prerequisite'] = "";
    $data['sfwd-courses_course_materials_enabled'] = "";
    $data['sfwd-courses_course_lesson_order_enabled'] = "";
    $data['sfwd-courses_course_materials'] = "";
    $data['sfwd-courses_certificate'] = "";
    $data['sfwd-courses_course_disable_content_table'] = $disable_content;
    $data['sfwd-courses_course_price_type'] = $access_mode;
    $data['sfwd-courses_course_lesson_per_page'] = "";
    $data['sfwd-courses_course_lesson_per_page_custom'] = "";
    $data['sfwd-courses_course_topic_per_page_custom'] = "";
    $data['sfwd-courses_course_lesson_orderby'] = "";
    $data['sfwd-courses_course_lesson_order'] = "";
    $data['sfwd-courses_course_access_list_enabled'] = "";
    //$data['sfwd-courses_course_price_type'] = $type_sub;
	if($access_mode == "closed")
	{
			
			$course_price = $_POST['course_price'];
			$button_url = $_POST['button_url'];
			$data['sfwd-courses_course_price'] = $course_price;
			$data['sfwd-courses_custom_button_url'] = $button_url;
	}
    
    $data['sfwd-courses_course_prerequisite_compare'] = "ANY";
    $data['sfwd-courses_course_points_enabled'] = "";
    $data['sfwd-courses_course_points'] = "";
    $data['sfwd-courses_course_points_access'] = "";
    $data['sfwd-courses_expire_access'] = "";
    $data['sfwd-courses_expire_access_days'] = 0;
    $data['sfwd-courses_expire_access_delete_progress'] = "";
    $data['sfwd-courses_course_access_list'] = "";
    
    $data['sfwd-courses_course_price_billing_p3'] = "";
    $data['sfwd-courses_course_price_billing_t3'] = "";
    $data['sfwd-courses_course_disable_lesson_progression'] = $class_progress;
	
	//var_dump($data);
	
	update_post_meta($_SESSION['cst_class_creation'], '_sfwd-courses', $data);
	update_post_meta($_SESSION['cst_class_creation'], 'course_price_billing_p3', $billing_days);
	update_post_meta($_SESSION['cst_class_creation'], 'course_price_billing_t3', $billing_type);
	unset($_SESSION['cst_class_creation']);
	unset($_SESSION['cst_modules_creation']);
}


add_action("wp_ajax_get_img_url", "get_img_url");
add_action("wp_ajax_nopriv_get_img_url", "get_img_url");
function get_img_url() {
echo wp_get_attachment_url($_POST['attachment']);
exit();
}	
add_action("wp_ajax_cstaddEditQuestion", "cstaddEditQuestion");
add_action("wp_ajax_nopriv_cstaddEditQuestion", "cstaddEditQuestion");
function cstaddEditQuestion($post) {
$_POST = $post;
//var_dump($_POST);
	$quiz_quest_controler = new WpProQuiz_Controller_Question();
	$quizId = $_POST['quizId'];
		$questionId = isset( $_GET['questionId'] ) ? (int) $_GET['questionId'] : 0;

		if ( $questionId ) {
			if ( ! current_user_can( 'wpProQuiz_edit_quiz' ) ) {
				wp_die( esc_html__( 'You do not have sufficient permissions to access this page.', 'learndash' ) );
			}
		} else {
			if ( ! current_user_can( 'wpProQuiz_add_quiz' ) ) {
				wp_die( esc_html__( 'You do not have sufficient permissions to access this page.', 'learndash' ) );
			}
		}

		$quizMapper     = new WpProQuiz_Model_QuizMapper();
		$questionMapper = new WpProQuiz_Model_QuestionMapper();
		$cateoryMapper  = new WpProQuiz_Model_CategoryMapper();
		$templateMapper = new WpProQuiz_Model_TemplateMapper();

		if ( $questionId && $questionMapper->existsAndWritable( $questionId ) == 0 ) {
			WpProQuiz_View_View::admin_notices( esc_html__( 'Question not found', 'learndash' ), 'error' );

			return;
		}

		$question = new WpProQuiz_Model_Question();
		
		if ( isset( $_POST['template'] ) || ( isset( $_POST['templateLoad'] ) && isset( $_POST['templateLoadId'] ) ) ) {
			if ( isset( $_POST['template'] ) ) {
				$template = $quiz_quest_controler->saveTemplate();
			} else {
				$template = $templateMapper->fetchById( $_POST['templateLoadId'] );
			}

			$data = $template->getData();
//var_dump($data);
			if ( $data !== null ) {
				$question = $data['question'];
				$question->setId( $questionId );
				$question->setQuizId( $quizId );
			}
		} else if ( isset( $_POST['submit'] ) ) {
			$add_new_question_url = admin_url( "admin.php?page=ldAdvQuiz&module=question&action=addEdit&quiz_id=" . $quizId . "&post_id=" . @$_REQUEST["post_id"] );
			$add_new_question     = "<a href='" . $add_new_question_url . "'>" . esc_html__( "Click here to add another question.", 'learndash' ) . "</a>";
			//if ( $questionId ) {
			//	WpProQuiz_View_View::admin_notices( esc_html__( 'Question edited', 'learndash' ) . ". " . $add_new_question, 'info' );
			//} else {
			//	WpProQuiz_View_View::admin_notices( esc_html__( 'Question added', 'learndash' ) . ". " . $add_new_question, 'info' );
			//}

			$question   = $questionMapper->save( $quiz_quest_controler->getPostQuestionModel( $quizId, $questionId ), true );

			$questionId = $question->getId();
			//var_dump($questionId);
		} else {
			if ( $questionId ) {
				$question = $questionMapper->fetch( $questionId );
			}
		}

		$quiz_quest_controler->view             = new WpProQuiz_View_QuestionEdit();
		$quiz_quest_controler->view->categories = $cateoryMapper->fetchAll();
		$quiz_quest_controler->view->quiz       = $quizMapper->fetch( $quizId );
		$quiz_quest_controler->view->templates  = $templateMapper->fetchAll( WpProQuiz_Model_Template::TEMPLATE_TYPE_QUESTION, false );
		$quiz_quest_controler->view->question   = $question;
		$quiz_quest_controler->view->data       = $quiz_quest_controler->setAnswerObject( $question );

		$quiz_quest_controler->view->header = $questionId ? esc_html__( 'Edit question', 'learndash' ) : esc_html__( 'New question', 'learndash' );

		if ( $quiz_quest_controler->view->question->isAnswerPointsActivated() ) {
			$quiz_quest_controler->view->question->setPoints( 1 );
		}

		$quiz_quest_controler->view->show();
	}
	
add_action('init', 'allow_contributor_uploads');

function allow_contributor_uploads() {
$contributor = get_role('teacher');
$contributor->add_cap('upload_files');
}

add_action('wp_footer', 'cst_footer_loader');
function cst_footer_loader(){
	echo '<div class="cst_overlay">
    <div class="overlay__inner">
        <div class="overlay__content"><span class="spinner"></span></div>
    </div>
</div>';
}

add_filter('learndash_content_tabs', 'cst_filter_cont', 10, 4);
function cst_filter_cont( $content, $context, $course_id, $user_id ){
	foreach($content as $key => $cont){
		if($cont['id']=='content'){
			$cont['content'] = html_entity_decode($cont['content']);
			$cont['content'] = force_balance_tags($cont['content']);
    $cont['content'] = preg_replace('#<p>\s*+(<br\s*/*>)?\s*</p>#i', '', $cont['content']);
			$content[$key] = $cont;
		}
	}
	return $content;
}

add_action('wp_ajax_cst_temp_login', 'cst_temp_login');
add_action('wp_ajax_nopriv_cst_temp_login', 'cst_temp_login');
function cst_temp_login(){
if( ! empty( $_FILES ) ) {
  foreach( $_FILES as $file ) {
    if( is_array( $file ) ) {
      $attachment_id = upload_user_file( $file );
    }
  }
}
}

function upload_user_file( $file = array() ) {
	require_once( ABSPATH . 'wp-admin/includes/admin.php' );
      $file_return = wp_handle_upload( $file, array('test_form' => false ) );
      if( isset( $file_return['error'] ) || isset( $file_return['upload_error_handler'] ) ) {
          echo false;
      } else {
          $filename = $file_return['file'];
          $attachment = array(
              'post_mime_type' => $file_return['type'],
              'post_title' => preg_replace( '/\.[^.]+$/', '', basename( $filename ) ),
              'post_content' => '',
              'post_status' => 'inherit',
              'guid' => $file_return['url']
          );
          $attachment_id = wp_insert_attachment( $attachment, $file_return['url'] );
          require_once(ABSPATH . 'wp-admin/includes/image.php');
          $attachment_data = wp_generate_attachment_metadata( $attachment_id, $filename );
          wp_update_attachment_metadata( $attachment_id, $attachment_data );
          if( 0 < intval( $attachment_id ) ) {
          	echo $attachment_id;
			exit;
          }
      }
      echo false;
}
add_action("wp_ajax_get_lession", "get_lession");
add_action("wp_ajax_nopriv_get_lession", "get_lession");
function get_lession()
{
	global $wpdb;
	$lesssion_id = $_POST['lession_id']; 
	//echo $lesssion_id;
	$posts = $wpdb->get_results("SELECT * FROM $wpdb->postmeta
       WHERE meta_key = 'course_id' AND  meta_value = '$lesssion_id' ");
    $all_id =  array();
	foreach($posts as $post)
	{
		$all_id[] = $post->post_id;
	}
	
	//var_dump($all_id);
	$total_data = '<select name="select_lesson">';
	foreach($all_id as $all_ids)
	{
		
		$total_data .= '<option value='.$all_ids .'>'. get_the_title( $all_ids ).'</option>';
	}
	$total_data .= '</select>';
	
	echo $total_data;
	exit();
}

add_action("wp_ajax_delete_classfunction", "delete_classfunction");
add_action("wp_ajax_nopriv_delete_classfunction", "delete_classfunction");
function delete_classfunction()
{
	global $wpdb;
	$lesssion_id = $_POST['idata']; 
	wp_delete_post( $lesssion_id, true);
	echo "Post Deleted";
	exit();
}

function cst_custom_template(){
$file = plugin_dir_path( __FILE__ ).'template-parts/learndash-sidebar.php';
$newfile = get_stylesheet_directory().'/learndash/ld30/learndash-sidebar.php';
if (!file_exists(get_stylesheet_directory().'/learndash/ld30/')) {
    mkdir(get_stylesheet_directory().'/learndash/ld30/', 0777, true);
}
copy($file, $newfile);
}
function cst_custom_template_unlink(){
$file = get_stylesheet_directory().'/learndash/ld30/learndash-sidebar.php';
unlink($file);
}

register_activation_hook( __FILE__, 'cst_custom_template' );
register_deactivation_hook( __FILE__, 'cst_custom_template_unlink' );

add_action('wp_ajax_cst_get_lessons', 'cst_get_lessons');
function cst_get_lessons(){
	$custom_labels = get_option('learndash_settings_custom_labels');
	$class_id = $_POST['val'];
	$course_lesson_ids = learndash_course_get_steps_by_type( $class_id, 'sfwd-lessons' );
	$out = "";
	if($custom_labels['lesson']!= '') {
		$out = "<option>Select Your ".$custom_labels['lesson']."</option>";
	} else {
		$out = '<option>Select Your Lession</option>';
	}
	foreach($course_lesson_ids as $course_lesson_id){
		$out .= '<option value="'.$course_lesson_id.'">'.get_the_title($course_lesson_id).'</option>';
	}
	echo $out;
	exit;
}

add_action('wp_ajax_cst_get_quizzes', 'cst_get_quizzes');
function cst_get_quizzes(){
	$class_id = $_POST['val'];
	$course_quizzes_ids = learndash_course_get_steps_by_type( $class_id, 'sfwd-quiz' );
	$out = "<option>Select Your Quiz</option>";
	foreach($course_quizzes_ids as $course_quiz_id){
		$out .= '<option value="'.$course_quiz_id.'">'.get_the_title($course_quiz_id).'</option>';
	}
	echo $out;
	exit;
}

add_action('wp_ajax_cst_save_topic', 'cst_save_topic');
function cst_save_topic(){
	parse_str($_REQUEST['val'], $params);
	$my_post = array(
    'post_title' => $params["topictitle"],
	'post_content' => $params['kv_frontend_editor'],
    'post_status' => 'publish',
    'post_type' => 'sfwd-topic',
	);
	$the_post_id = wp_insert_post( $my_post );
	
   update_post_meta( $the_post_id, 'course_id' , $params["select_cource"] );
   update_post_meta( $the_post_id, 'lesson_id' , $params["select_lesson"] );
}

add_action('wp_ajax_cst_save_quiz', 'cst_save_quiz');
function cst_save_quiz(){
	$values = $_POST['val'];
	$cst_modules_creation = array();
	$cst_course_id = $values['cst_course_id'];
	$cst_lesson_id = $values['cst_lesson_id'];
	$questions = $values['questions'];
	$quiz_title = $values['cst_quiz_title'];
	$quiz_desc = $values['cst_quiz_description'];
	$quiz_desc = strip_tags($quiz_desc);
	//var_dump($quiz_title);
	$quizdtl = array(
		'post_title'    => $quiz_title,
		'post_content'  => $quiz_desc,
		'post_status'   => 'publish',
		'post_author'   => get_current_user_id(),
		'post_type'		=> 'sfwd-quiz'
	);
	$quiz_id = wp_insert_post( $quizdtl );
	//var_dump($quiz_id);
	update_post_meta( $quiz_id, 'course_id', $cst_course_id); 
	update_post_meta( $quiz_id, 'lesson_id', $cst_lesson_id );
	$sfwd_quiz_data = array();
	$sfwd_quiz_data['sfwd-quiz_course'] = $cst_course_id;
	$sfwd_quiz_data['sfwd-quiz_lesson'] = $cst_lesson_id;
	$sfwd_quiz_data['sfwd-quiz_startOnlyRegisteredUser'] = "";
    $sfwd_quiz_data['sfwd-quiz_prerequisiteList'] = "";
    $sfwd_quiz_data['sfwd-quiz_prerequisite'] = "";
    $sfwd_quiz_data['sfwd-quiz_retry_restrictions'] = "";
    $sfwd_quiz_data['sfwd-quiz_repeats'] = "";
    $sfwd_quiz_data['sfwd-quiz_quizRunOnceType'] = "";
    $sfwd_quiz_data['sfwd-quiz_quizRunOnceCookie'] = "";
    $sfwd_quiz_data['sfwd-quiz_passingpercentage'] = 80;
    $sfwd_quiz_data['sfwd-quiz_certificate'] = "";
    $sfwd_quiz_data['sfwd-quiz_threshold'] = "";
    $sfwd_quiz_data['sfwd-quiz_quiz_time_limit_enabled'] = "";
    $sfwd_quiz_data['sfwd-quiz_timeLimit'] = 0;
    $sfwd_quiz_data['sfwd-quiz_forcingQuestionSolve'] = "";
    $sfwd_quiz_data['sfwd-quiz_quiz_materials_enabled'] = "off";
    $sfwd_quiz_data['sfwd-quiz_quiz_materials'] = "";
    $sfwd_quiz_data['sfwd-quiz_custom_sorting'] = "";
    $sfwd_quiz_data['sfwd-quiz_autostart'] = "";
    $sfwd_quiz_data['sfwd-quiz_showReviewQuestion'] = "";
    $sfwd_quiz_data['sfwd-quiz_quizSummaryHide'] = 1;
    $sfwd_quiz_data['sfwd-quiz_skipQuestionDisabled'] = 1;
    $sfwd_quiz_data['sfwd-quiz_sortCategories'] = "";
    $sfwd_quiz_data['sfwd-quiz_questionRandom'] = "";
    $sfwd_quiz_data['sfwd-quiz_showMaxQuestion'] = "";
    $sfwd_quiz_data['sfwd-quiz_showMaxQuestionValue'] = "";
    $sfwd_quiz_data['sfwd-quiz_showPoints'] = "";
    $sfwd_quiz_data['sfwd-quiz_showCategory'] = "";
    $sfwd_quiz_data['sfwd-quiz_hideQuestionPositionOverview'] = 1;
    $sfwd_quiz_data['sfwd-quiz_hideQuestionNumbering'] = 1;
    $sfwd_quiz_data['sfwd-quiz_numberedAnswer'] = "";
    $sfwd_quiz_data['sfwd-quiz_answerRandom'] = "";
    $sfwd_quiz_data['sfwd-quiz_quizModus'] = 0;
    $sfwd_quiz_data['sfwd-quiz_quizModus_multiple_questionsPerPage'] = 0;
    $sfwd_quiz_data['sfwd-quiz_quizModus_single_back_button'] = "";
    $sfwd_quiz_data['sfwd-quiz_quizModus_single_feedback'] = "end";
    $sfwd_quiz_data['sfwd-quiz_titleHidden'] = 1;
    $sfwd_quiz_data['sfwd-quiz_resultGradeEnabled'] = "";
    $sfwd_quiz_data['sfwd-quiz_resultText'] = "";
    $sfwd_quiz_data['sfwd-quiz_btnRestartQuizHidden'] = "";
    $sfwd_quiz_data['sfwd-quiz_showAverageResult'] = "";
    $sfwd_quiz_data['sfwd-quiz_showCategoryScore'] = "";
    $sfwd_quiz_data['sfwd-quiz_hideResultPoints'] = "";
    $sfwd_quiz_data['sfwd-quiz_hideResultCorrectQuestion'] = "";
    $sfwd_quiz_data['sfwd-quiz_hideResultQuizTime'] = "";
    $sfwd_quiz_data['sfwd-quiz_hideAnswerMessageBox'] = "";
    $sfwd_quiz_data['sfwd-quiz_disabledAnswerMark'] = "";
    $sfwd_quiz_data['sfwd-quiz_btnViewQuestionHidden'] = "";
    $sfwd_quiz_data['sfwd-quiz_custom_answer_feedback'] = "";
    $sfwd_quiz_data['sfwd-quiz_custom_result_data_display'] = "";
    $sfwd_quiz_data['sfwd-quiz_associated_settings_enabled'] = "";
    $sfwd_quiz_data['sfwd-quiz_toplistDataShowIn_enabled'] = "";
    $sfwd_quiz_data['sfwd-quiz_statisticsIpLock_enabled'] = "";
    $sfwd_quiz_data['sfwd-quiz_quiz_pro'] = 3;
    $sfwd_quiz_data['sfwd-quiz_formActivated'] = "";
    $sfwd_quiz_data['sfwd-quiz_formShowPosition'] = 0;
    $sfwd_quiz_data['sfwd-quiz_toplistDataAddPermissions'] = 1;
    $sfwd_quiz_data['sfwd-quiz_toplistDataAddMultiple'] = "";
    $sfwd_quiz_data['sfwd-quiz_toplistDataAddBlock'] = 0;
    $sfwd_quiz_data['sfwd-quiz_toplistDataAddAutomatic'] = "";
    $sfwd_quiz_data['sfwd-quiz_toplistDataShowLimit'] = 0;
    $sfwd_quiz_data['sfwd-quiz_toplistDataSort'] = 1;
    $sfwd_quiz_data['sfwd-quiz_toplistActivated'] = "";
    $sfwd_quiz_data['sfwd-quiz_toplistDataShowIn'] = 0;
    $sfwd_quiz_data['sfwd-quiz_toplistDataCaptcha'] = "";
    $sfwd_quiz_data['sfwd-quiz_statisticsOn'] = 1;
    $sfwd_quiz_data['sfwd-quiz_viewProfileStatistics'] = "";
    $sfwd_quiz_data['sfwd-quiz_statisticsIpLock'] = 0;
    $sfwd_quiz_data['sfwd-quiz_email_enabled'] = "";
    $sfwd_quiz_data['sfwd-quiz_email_enabled_admin'] = "";
    $sfwd_quiz_data['sfwd-quiz_emailNotification'] = 0;
    $sfwd_quiz_data['sfwd-quiz_userEmailNotification'] = "";
    $sfwd_quiz_data['sfwd-quiz_timeLimitCookie_enabled'] = "";
    $sfwd_quiz_data['sfwd-quiz_timeLimitCookie'] = "";
	$sfwd_quiz_data['sfwd-quiz_templates_enabled'] = "";
	update_post_meta( $quiz_id, '_sfwd-quiz', $sfwd_quiz_data);

	$quiz_pro = new WpProQuiz_Model_Quiz();
	$quiz_pro->setId(0);
	$quiz_pro->setName($quiz_title);
	$quiz_pro->setText("AAZZAAZZ");
    $quizMapper = new WpProQuiz_Model_QuizMapper();
	$quizMapper->save($quiz_pro);
	learndash_update_setting($quiz_id, "quiz_pro", $quiz_pro->getId());
	foreach ($questions as $question) {
			// var_dump($question['questiontype']);
		$quest_type = $question['questiontype'];
		$answerModels = array();
		if($quest_type=='single'){
			   $quest_title = $question['quest_title'];
			   $quest_content = $question['quest_title'];
			   $answer1 = $question['answer1'];
			   $answer2 = $question['answer2'];
			   $answer3 = $question['answer3'];
			   $answer4 = $question['answer4'];
			   $correct_answer = $question['correct_answer'];
			   $answerModel1 = new WpProQuiz_Model_AnswerTypes();
			   $answerModel1->setAnswer($answer1);
			   $answerModel1->setPoints(1);
			   if($correct_answer=='answer1')
			   {
				   $answerModel1->setCorrect(true);
			   }
			   else{
				   $answerModel1->setCorrect(0);
			   }
			   $answerModels[0] = $answerModel1;
			   $answerModel2 = new WpProQuiz_Model_AnswerTypes();
			   $answerModel2->setAnswer($answer2);
			   $answerModel2->setPoints(1);
			   if($correct_answer=='answer2')
			   {
				   $answerModel2->setCorrect(true);
			   }
			   else{
				   $answerModel2->setCorrect(0);
			   }
			   
			   $answerModels[1] = $answerModel2;
			   $answerModel3 = new WpProQuiz_Model_AnswerTypes();
			   $answerModel3->setAnswer($answer3);
			   $answerModel3->setPoints(1);
			   if($correct_answer=='answer3')
			   {
				   $answerModel3->setCorrect(true);
			   }
			   else{
				   $answerModel3->setCorrect(0);
			   }
			   
			   $answerModels[2] = $answerModel3;
			   $answerModel4 = new WpProQuiz_Model_AnswerTypes();
			   $answerModel4->setAnswer($answer4);
			   $answerModel4->setPoints(1);
			   if($correct_answer=='answer4')
			   {
				   $answerModel4->setCorrect(true);
			   }
			   else{
				   $answerModel4->setCorrect(0);
			   }
			   
			   $answerModels[3] = $answerModel4;
		} else {
				$quest_title = $question['essay_quest_title'];
				$quest_content = $question['answer1e'];
				$answerModel11 = new WpProQuiz_Model_AnswerTypes();
				$answerModel11->setPoints( 1 );
				$answerModel11->setGraded( true );
				$answerModel11->setGradedType( $quest_content );
				$answerModel11->setGradingProgression( 'not-graded-none' );

				$answerModels[0] = $answerModel11;
		}
		$quesdtl = array(
			'post_title'    => $quest_title,
			'post_content'  => "",
			'post_status'   => 'publish',
			'post_author'   => get_current_user_id(),
			'post_type'		=> 'sfwd-question'
		);
		$question_id = wp_insert_post( $quesdtl );
		update_post_meta($question_id, 'quiz_id', $quiz_id);
		update_post_meta($question_id, 'question_type', $quest_type); 
		update_post_meta($question_id, '_sfwd-question', array('sfwd-question_quiz'=>strval($quiz_id)));

		$questionModel = new WpProQuiz_Model_Question();
		$questionModel->setId(0);
		$questionModel->setTitle($quest_title);
		$questionModel->setQuestion($quest_title);
		$questionModel->setAnswerData( $answerModels );
		$questionModel->setAnswerType($quest_type);
		$questionModel->setQuizId( $quiz_pro->getId() );
		$quiz_quest_controler = new WpProQuiz_Controller_Question();

		$questionMapper = new WpProQuiz_Model_QuestionMapper();
		$questionpro = $questionMapper->save( $questionModel, true );
		$questionModel = $questionMapper->fetch( $questionModel->getId() );
		$quiz_quest_controler->setAnswerObject($questionModel);
		update_post_meta($question_id,"question_pro_id", $questionModel->getId());
		var_dump($quesdtl);
	}	
}

add_action('wp_ajax_cst_save_question', 'cst_save_question');
function cst_save_question(){
	$values = $_POST['val'];
	$cst_modules_creation = array();
	$cst_course_id = $values['cst_course_id'];
	$cst_lesson_id = $values['cst_lesson_id'];
	$quiz_id = $values['cst_quiz_id'];
	$quiz_pro_id = learndash_get_setting( $quiz_id, 'quiz_pro' );
	$questions = $values['questions'];
	foreach ($questions as $question) {
		$quest_type = $question['questiontype'];
		$answerModels = array();
		if($quest_type=='single'){
			$quest_title = $question['quest_title'];
			$quest_content = $question['quest_title'];
			$answer1 = $question['answer1'];
			$answer2 = $question['answer2'];
			$answer3 = $question['answer3'];
			$answer4 = $question['answer4'];
			$correct_answer = $question['correct_answer'];
			$answerModel1 = new WpProQuiz_Model_AnswerTypes();
			$answerModel1->setAnswer($answer1);
			$answerModel1->setPoints(1);
			if($correct_answer=='answer1')
			{
				$answerModel1->setCorrect(true);
			}
			else{
				$answerModel1->setCorrect(0);
			}
			$answerModels[0] = $answerModel1;
			$answerModel2 = new WpProQuiz_Model_AnswerTypes();
			$answerModel2->setAnswer($answer2);
			$answerModel2->setPoints(1);
			if($correct_answer=='answer2')
			{
				$answerModel2->setCorrect(true);
			}
			else{
				$answerModel2->setCorrect(0);
			}
			
			$answerModels[1] = $answerModel2;
			$answerModel3 = new WpProQuiz_Model_AnswerTypes();
			$answerModel3->setAnswer($answer3);
			$answerModel3->setPoints(1);
			if($correct_answer=='answer3')
			{
				$answerModel3->setCorrect(true);
			}
			else{
				$answerModel3->setCorrect(0);
			}
			
			$answerModels[2] = $answerModel3;
			$answerModel4 = new WpProQuiz_Model_AnswerTypes();
			$answerModel4->setAnswer($answer4);
			$answerModel4->setPoints(1);
			if($correct_answer=='answer4')
			{
				$answerModel4->setCorrect(true);
			}
			else{
				$answerModel4->setCorrect(0);
			}
			
			$answerModels[3] = $answerModel4;
		} else {
				$quest_title = $question['essay_quest_title'];
				$quest_content = $question['answer1e'];
				$answerModel11 = new WpProQuiz_Model_AnswerTypes();
				$answerModel11->setPoints( 1 );
				$answerModel11->setGraded( true );
				$answerModel11->setGradedType( $quest_content );
				$answerModel11->setGradingProgression( 'not-graded-none' );

				$answerModels[0] = $answerModel11;
		}
		$quesdtl = array(
			'post_title'    => $quest_title,
			'post_content'  => "",
			'post_status'   => 'publish',
			'post_author'   => get_current_user_id(),
			'post_type'		=> 'sfwd-question'
		);
		$question_id = wp_insert_post( $quesdtl );
		update_post_meta($question_id, 'quiz_id', $quiz_id);
		update_post_meta($question_id, 'question_type', $quest_type); 
		update_post_meta($question_id, '_sfwd-question', array('sfwd-question_quiz'=>strval($quiz_id)));

		$questionModel = new WpProQuiz_Model_Question();
		$questionModel->setId(0);
		$questionModel->setTitle($quest_title);
		$questionModel->setQuestion($quest_title);
		$questionModel->setAnswerData( $answerModels );
		$questionModel->setAnswerType($quest_type);
		$questionModel->setQuizId($quiz_pro_id);
		$quiz_quest_controler = new WpProQuiz_Controller_Question();

		$questionMapper = new WpProQuiz_Model_QuestionMapper();
		$questionpro = $questionMapper->save( $questionModel, true );
		$questionModel = $questionMapper->fetch( $questionModel->getId() );
		$quiz_quest_controler->setAnswerObject($questionModel);
		update_post_meta($question_id,"question_pro_id", $questionModel->getId());
		var_dump($quesdtl);
	}
}

add_action('wp_ajax_cst_save_class_group', 'cst_save_class_group');
function cst_save_class_group(){
	$values = $_POST['val'];
	foreach ($values as $value) {
		$groupID = $value["groupID"];
		$courseId =$value["courseId"];
		$status = json_decode($value["status"]);
		var_dump($groupID);
		var_dump($courseId);
		var_dump($status);
		ld_update_course_group_access( $courseId, $groupID, $status );
	}
}

add_shortcode('cst_learndash_classes', 'cst_learndash_classes');
function cst_learndash_classes(){
		wp_enqueue_style( 'astra-bootstrap-css' );
		wp_enqueue_style( 'astra-conditional-css' );
		wp_enqueue_script( 'astra-bootstrap-js' );
	require_once( plugin_dir_path( __FILE__ ) . '/template-parts/my-classes.php');
}
add_shortcode('cst_learndash_topic_list', 'cst_learndash_topic_list');
function cst_learndash_topic_list(){
		wp_enqueue_style( 'astra-bootstrap-css' );
		wp_enqueue_style( 'astra-conditional-css' );
		wp_enqueue_script( 'astra-bootstrap-js' );
	require_once( plugin_dir_path( __FILE__ ) . '/template-parts/my-topics.php');
}
add_shortcode('cst_learndash_quiz_list', 'cst_learndash_quiz_list');
function cst_learndash_quiz_list(){
		wp_enqueue_style( 'astra-bootstrap-css' );
		wp_enqueue_style( 'astra-conditional-css' );
		wp_enqueue_script( 'astra-bootstrap-js' );
	require_once( plugin_dir_path( __FILE__ ) . '/template-parts/my-quiz.php');
}

add_shortcode('cst_learndash_question_list', 'cst_learndash_question_list');
function cst_learndash_question_list(){
		wp_enqueue_style( 'astra-bootstrap-css' );
		wp_enqueue_style( 'astra-conditional-css' );
		wp_enqueue_script( 'astra-bootstrap-js' );
	require_once( plugin_dir_path( __FILE__ ) . '/template-parts/my-questions.php');
}

add_shortcode('cst_learndash_topics', 'cst_learndash_topics');
function cst_learndash_topics(){
wp_enqueue_style( 'astra-bootstrap-css' );
		wp_enqueue_style( 'astra-conditional-css' );
		wp_enqueue_script( 'astra-bootstrap-js' );
	require_once( plugin_dir_path( __FILE__ ).'/template-parts/create-topic.php');
}

add_shortcode('cst_learndash_quiz', 'cst_learndash_quiz');
function cst_learndash_quiz(){
	wp_enqueue_style( 'astra-bootstrap-css' );
			wp_enqueue_style( 'astra-conditional-css' );
			wp_enqueue_script( 'astra-bootstrap-js' );
		require_once( plugin_dir_path( __FILE__ ).'/components/quiz/create_quiz.php');
	}

add_shortcode('cst_learndash_question', 'cst_learndash_question');
function cst_learndash_question(){
	wp_enqueue_style( 'astra-bootstrap-css' );
			wp_enqueue_style( 'astra-conditional-css' );
			wp_enqueue_script( 'astra-bootstrap-js' );
		require_once( plugin_dir_path( __FILE__ ).'/components/question/create_question.php');
	}

add_shortcode('cst_learndash_create_classes', 'cst_learndash_create_classes');
function cst_learndash_create_classes(){
wp_enqueue_style( 'astra-bootstrap-css' );
		wp_enqueue_style( 'astra-conditional-css' );
		wp_enqueue_script( 'astra-bootstrap-js' );
		if(!session_id())
		session_start();
		if(isset($_SESSION['cst_class_creation']))
		{
			unset($_SESSION['cst_class_creation']);
		}
		if(isset($_SESSION['cst_modules_creation'])){
		    unset($_SESSION['cst_modules_creation']);
		}
		
		
	require_once( plugin_dir_path( __FILE__ ) . '/template-parts/create-class.php');
}

add_action('admin_init', 'my_general_section');  
function my_general_section() {  
    add_settings_section(  
        'my_settings_section', // Section ID 
        'Learndash Frontned Option', // Section Title
        'my_section_options_callback', // Callback
        'general' // What Page?  This makes the section show up on the General Settings Page
    );

    add_settings_field( // Option 1
        'cst_frnt_page', // Option ID
        'Frontned Class Creation Page', // Label
        'my_textbox_callback', // !important - This is where the args go!
        'general', // Page it will be displayed (General Settings)
        'my_settings_section', // Name of our section
        array( // The $args
            'cst_frnt_page' // Should match Option ID
        )  
    ); 

    register_setting('general','cst_frnt_page', 'esc_attr');
	
	add_settings_field( // Option 1
        'cst_frnt_topic_page', // Option ID
        'Frontned Topic Creation Page', // Label
        'my_textbox_callback', // !important - This is where the args go!
        'general', // Page it will be displayed (General Settings)
        'my_settings_section', // Name of our section
        array( // The $args
            'cst_frnt_topic_page' // Should match Option ID
        )  
    ); 

    register_setting('general','cst_frnt_topic_page', 'esc_attr');
	
	add_settings_field( // Option 1
        'cst_frnt_quest_page', // Option ID
        'Frontned Question Creation Page', // Label
        'my_textbox_callback', // !important - This is where the args go!
        'general', // Page it will be displayed (General Settings)
        'my_settings_section', // Name of our section
        array( // The $args
            'cst_frnt_quest_page' // Should match Option ID
        )  
    ); 

    register_setting('general','cst_frnt_quest_page', 'esc_attr');
	
	add_settings_field( // Option 1
        'cst_frnt_quiz_page', // Option ID
        'Frontned Quiz Creation Page', // Label
        'my_textbox_callback', // !important - This is where the args go!
        'general', // Page it will be displayed (General Settings)
        'my_settings_section', // Name of our section
        array( // The $args
            'cst_frnt_quiz_page' // Should match Option ID
        )  
    ); 

    register_setting('general','cst_frnt_quiz_page', 'esc_attr');
}

function my_section_options_callback() { // Section Callback
    echo '<p>Enter the create class page link</p>';  
}

function my_textbox_callback($args) {  // Textbox Callback
    $option = get_option($args[0]);
    echo '<input type="text" id="'. $args[0] .'" name="'. $args[0] .'" value="' . $option . '" />';
}