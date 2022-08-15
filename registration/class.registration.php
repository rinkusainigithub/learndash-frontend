<?php
class Teacher_registration_form
{

    private $username;
    private $email;
    private $password;
    private $website;
    private $first_name;
    private $last_name;
    private $nickname;
    private $bio;

    public function registration_form()
    {
	echo '<section class="teacher-registration py-5" id="registration">
    <div class="container">
	<form action="" method="POST">
        <div class="row ">
            <div class="col-md-4 py-5 bg-primary text-white text-center ">
                <div class=" ">
                    <div class="card-body">
                        <img src="'.get_stylesheet_directory_uri().'/assets/images/profile.png" style="width:61%">
                        <h2 class="py-3">Upload Profile Photo</h2>
                        <p><input type="text" placeholder="Enter Tag line" required="required" ></p>
                    </div>
                </div>
            </div>
            <div class="col-md-8 py-5 border">
                <h2 class="pb-4">Please fill with your details</h2>
         
                    <div class="form-row">
                        <div class="form-group col-md-6">
                          <input id="fullname" name="fullname" placeholder="Full Name" class="form-control" type="text">
                        </div>
						<div class="form-group col-md-6">
                          <input type="email" class="form-control" id="inputEmail4" name="inputemail" placeholder="Email">
                        </div>
                    </div>
                    <div class="form-row">
						<div class="form-group col-md-6">
                          <input type="text" class="form-control" id="inputFacebook" placeholder="Facebook">
                        </div>
						<div class="form-group col-md-6">
                          <input type="text" class="form-control" id="inputLinkedIn" placeholder="LinkedIn">
                        </div>
					</div>
                    <div class="form-row">
                        <div class="form-group col-md-6">
                            <input id="mobilenum" name="mobilenum" placeholder="Mobile No." class="form-control" required="required" type="text">
                        </div>
						<div class="form-group col-md-6">
                          <input type="text" class="form-control" id="inputWebsite" placeholder="Website">
                        </div>
                    </div>
					<div class="form-row">
                        <div class="form-group col-md-6">
                            <input id="address" name="address" placeholder="Address" class="form-control" required="required" type="text">
                        </div>
						<div class="form-group col-md-6">
                           <input id="city" name="city" placeholder="City" class="form-control" required="required" type="text">
                        </div>
                    </div>
					<div class="form-row">
                        <div class="form-group col-md-6">
                            <input id="state" name="state" placeholder="State" class="form-control" required="required" type="text">
                        </div>
						<div class="form-group col-md-6">
                           <input id="country" name="country" placeholder="Country" class="form-control" required="required" type="text">
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
							<input type="submit" class="btn btn-danger" name="submit" value="Submit">
						</div>
                    </div>
    
            </div>
        </div>
		</form>
    </div>
</section>';
    }

    function validation()
    {

        if (empty($this->fullname) || empty($this->inputemail) || empty($this->mobilenum)) {
            return new WP_Error('field', 'Required form field is missing');
        }

        if (!is_email($this->inputemail)) {
            return new WP_Error('email_invalid', 'Email is not valid');
        }

        if (email_exists($this->inputemail)) {
            return new WP_Error('email', 'Email Already in use');
        }

    }

    function registration()
    {
		$username = explode("@", $this->inputemail);
		$fname = explode(" ", $this->fullname);
        $userdata = array(
            'user_login' => esc_attr($username[0]),
            'user_email' => esc_attr($this->inputemail),
            'user_pass' => esc_attr(randomPassword()),
            'user_url' => esc_attr($this->website),
            'first_name' => esc_attr($fname[0]),
            'last_name' => esc_attr($fname[1]),
        );

        if (is_wp_error($this->validation())) {
            echo '<div style="margin-bottom: 6px" class="btn btn-block btn-lg btn-danger">';
            echo '<strong>' . $this->validation()->get_error_message() . '</strong>';
            echo '</div>';
        } else {
            $register_user = wp_insert_user($userdata);
            if (!is_wp_error($register_user)) {

                echo '<div style="margin-bottom: 6px" class="btn btn-block btn-lg btn-danger">';
                echo '<strong>Registration complete. Goto <a href="' . wp_login_url() . '">login page</a></strong>';
                echo '</div>';
            } else {
                echo '<div style="margin-bottom: 6px" class="btn btn-block btn-lg btn-danger">';
                echo '<strong>' . $register_user->get_error_message() . '</strong>';
                echo '</div>';
            }
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
	
    function flat_ui_kit()
    {
        wp_enqueue_style('bootstrap-css', plugins_url('bootstrap/css/bootstrap.css', __FILE__));
        wp_enqueue_style('flat-ui-kit', plugins_url('css/flat-ui.css', __FILE__));

    }

    function shortcode()
    {

        ob_start();
		if(is_user_logged_in()){
			wp_redirect(get_site_url().'/my-account/');
			exit();
		}
        if ($_POST['reg_submit']) {
            $this->username = $_POST['reg_name'];
            $this->email = $_POST['reg_email'];
            $this->password = $_POST['reg_password'];
            $this->website = $_POST['reg_website'];
            $this->first_name = $_POST['reg_fname'];
            $this->last_name = $_POST['reg_lname'];
            $this->nickname = $_POST['reg_nickname'];
            $this->bio = $_POST['reg_bio'];

            $this->validation();
            $this->registration();
        }

        $this->registration_form();
        return ob_get_clean();
    }

}