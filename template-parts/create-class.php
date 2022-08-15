<?php
	if(!session_id())
		session_start();
if(isset($_SESSION['cst_class_creation'])){
	$class_obj = get_post($_SESSION['cst_class_creation']);
	$class_title = get_the_title($_SESSION['cst_class_creation']);
	$class_desc = $class_obj->post_content;
}
$class_id = get_query_var('class_id', false);
if($class_id){
		$class_obj = get_post($class_id);
	$class_title = get_the_title($class_id);
	$class_desc = $class_obj->post_content;
$course_lesson_ids = learndash_course_get_steps_by_type( $class_id, 'sfwd-lessons' );
$course_quiz_ids = learndash_course_get_steps_by_type( $class_id, 'sfwd-quiz' );
$course_quest_ids = learndash_course_get_steps_by_type( $class_id, 'sfwd-question' );
$_SESSION['cst_class_creation']=$class_id;

/* echo"<pre>";
print_r($_SESSION['cst_modules_creation']);
echo"</pre><br/>";
echo"<pre>";
print_r($_SESSION['cst_quiz_creation']);
echo"</pre><br/>";
foreach($_SESSION['cst_quiz_creation'] as $qzid){
	$qquets = new LDLMS_Quiz_Questions($qzid);
	echo"$qzid : <pre>";
print_r($qquets->get_questions());
echo"</pre><br/>";
} */

}
wp_enqueue_script('slidesjs_core');
wp_enqueue_media();
?>

<h1 class="class_creation_head"><?php if($custom_labels['course']!= '') echo $custom_labels['course']; else "Course"; ?> Creation <?php echo isset($class_title) ? '<input type="button" class="btn btn-info abrt_class del_mod" value="Abort Class Creation" />' : '' ;?></h1>
<!--<div class="progress">
    <div class="progress-bar progress-bar-striped active" role="progressbar" aria-valuemin="0" aria-valuemax="100"></div>
  </div>-->
  <?php $custom_labels = get_option('learndash_settings_custom_labels'); ?>
  <form id="regiration_form" novalidate action="action.php"  method="post">
  <fieldset>
    <h2>Step 1: Create your <?php if($custom_labels['course']!= '') echo $custom_labels['course']; else "Course"; ?></h2>
	<div class="form-group">
    <label for="class_title"><?php if($custom_labels['course']!= '') echo $custom_labels['course']; else "Course"; ?> Title</label>
    <input type="text" class="form-control cst_req" id="class_title" name="class_title" placeholder="<?php if($custom_labels['course']!= '') echo $custom_labels['course']; else "Course"; ?> title" value="<?php echo (isset($class_title) ? $class_title : '') ; ?>">
    </div>
    <div class="form-group">
    <label for="class_desc"><?php if($custom_labels['course']!= '') echo $custom_labels['course']; else "Course"; ?> Description</label>
    <textarea class="form-control module_field" id="class_desc" name="class_desc" placeholder="<?php if($custom_labels['course']!= '') echo $custom_labels['course']; else "Course"; ?> Description"><?php echo (isset($class_desc) ? $class_desc : '') ; ?></textarea>
	</div>
	<!--<div  class="formcontrol">
        <label>Status</label>
        <select class="cst_req" name="select_class_status" id="select_class_status">
			<option value="">Select Status</option>
			<option value="publish">Publish</option>
			<option value="pending">Pending</option>
			<option value="draft">Draft</option>
        </select>    
    </div><br />--> 
		<input type="hidden" name="select_class_status" value="publish">
    <input type="button" class="next btn btn-info" value="Next" />
  </fieldset>
  <fieldset class="repeater class_children_data1" id="class_children_data">
    <h2> Step 2: Add <?php if($custom_labels['lessons']!= '') echo $custom_labels['lessons']; else "Lessons"; ?></h2>	
	<?php if(!empty($course_lesson_ids))
	{
		?>
			<div data-repeater-list="category-group">
		<?php
	  foreach($course_lesson_ids as $moduledata){
		$moduledata = get_post($moduledata);
		$modelcontent ='';
		
		$modeldetails = get_post_meta($moduledata->ID, '_sfwd-lessons', false );
	    if(!empty($modeldetails))
		{
		 foreach($modeldetails as $modeldetail)
		 {
			$modelcontent = $modeldetail['sfwd-lessons_lesson_materials'];
			if(isset($modeldetail['sfwd-lessons_lesson_assignment_upload'])){
				$assignment_upload = $modeldetail['sfwd-lessons_lesson_assignment_upload'];
				$assignment_exts = $modeldetail['sfwd-lessons_assignment_upload_limit_extensions'];
				$assignment_exts = implode(',', $assignment_exts);
				$assignment_limit = $modeldetail['sfwd-lessons_assignment_upload_limit_size'];
			}
		 }
		}
		?>

      <div data-repeater-item class="cst_repetative_section">
    <div class="form-group">
    <label for="fName"><?php if($custom_labels['lesson']!= '') echo $custom_labels['lesson']; else "Lesson"; ?> Title</label>
    <input type="text" class="form-control cst_req" name="mtitle" placeholder="<?php if($custom_labels['lesson']!= '') echo $custom_labels['lesson']; else "Lesson"; ?> Title" value="<?php echo (isset($moduledata->post_title) ? $moduledata->post_title : '') ; ?>">
	<input type="hidden" class="form-control" name="hmtitle" value="<?php echo (isset($moduledata->post_title) ? $moduledata->post_title : '') ; ?>">
    </div>
    <div class="form-group med_con_group">
    <label for="lName"><?php if($custom_labels['lesson']!= '') echo $custom_labels['lesson']; else "Lesson"; ?> Content</label>
    <textarea name='module_desc' class="module_field med_cont" style='width: 99%; height: 200px;'><?php echo (isset($moduledata->post_content) ? $moduledata->post_content : '') ; ?></textarea>
    </div>
	<div class="form-group">
	<div class="cst_upload_box">
	<input type="hidden" name="file-5[]" id="file-7" class="inputfile inputfile-6" data-multiple-caption="{count} files selected" multiple />
	<label for="file-7" class="inputfile-6-lable"><figure> <div class="upload_cst_text"><span>Upload Audio / Video / Image</span><div class="upload_cst_new"><span>select your file</span></div></div></figure> </label> 
	</div>
	</div>

	<div class="form-group">
    <label for="lName">Assignment Uploads</label>
     <div>
	 
	 
	 <label class="checkbox-container">Yes
		<input type="checkbox" name="assign_check" value="<?php if($assignment_upload == "on") { echo "Yes"; } else { echo "no"; } ?>" class="assign_check" <?php if($assignment_upload == "on") echo "checked"; ?>>
		<span class="checkbox-checkmark"></span>
	 </label>
	 </div>
    </div>
	
	<!--<div class="assign_check_div">
		<div class="form-group">
		<label for="lName">File Extensions</label>
		 <div><input type="text" name="file_ext" value="<?php echo $assignment_exts; ?>" class="file_ext dyna_remo"></div>
		</div>
		<div class="form-group">
		<label for="lName">File Size Limit</label>
		 <div><input type="text" name="file_size_limit" value="<?php echo $assignment_limit; ?>" class="file_size_limit dyna_remo"></div>
		</div>
	</div>-->
	<input data-repeater-delete type="button" value="Delete <?php if($custom_labels['lesson']!= '') echo $custom_labels['lesson']; else "Lesson"; ?>" class="del_mod cst_del" not_include="true"/>
	  </div>
	<?php } 
	?>
	  </div>
	  <?php
	} 
	else{ ?>
	<div data-repeater-list="category-group">
      <div data-repeater-item class="cst_repetative_section">
    <div class="form-group">
    <label for="fName"><?php if($custom_labels['lesson']!= '') echo $custom_labels['lesson']; else "Lesson"; ?>  Title</label>
    <input type="text" class="form-control cst_req" name="mtitle" placeholder="<?php if($custom_labels['lesson']!= '') echo $custom_labels['lesson']; else "Lesson"; ?>  Title">
    </div>
    <div class="form-group med_con_group">
    <label for="lName"><?php if($custom_labels['lesson']!= '') echo $custom_labels['lesson']; else "Lesson"; ?>  Description</label>
    <textarea name='module_desc' class="module_field med_cont" style='width: 99%; height: 200px;'></textarea>
    </div>
	<div class="form-group">
	<div class="cst_upload_box">
	<input type="hidden" name="file-5[]" id="file-7" class="inputfile inputfile-6" data-multiple-caption="{count} files selected" multiple />
	<label for="file-7" class="inputfile-6-lable"><figure> <div class="upload_cst_text"><span>Upload Audio / Video / Image</span><div class="upload_cst_new"><span>select your file</span></div></div></figure> </label> 
	</div>
    </div>
	<div class="form-group cst-form-group">
    <label for="lName">Assignment Uploads</label>
     <div>
	  <label class="checkbox-container">Yes
		<input type="checkbox" name="assign_check" value="No" class="assign_check">
		<span class="checkbox-checkmark">
		</span>
	 </label>
	</div>
    </div>
	<!--<div class="assign_check_div cst_temphide">
		<div class="form-group">
		<label for="lName">File Extensions</label>
		 <div><input type="text" name="file_ext"  class="file_ext dyna_remo" value="pdf"></div>
		</div>
		<div class="form-group">
		<label for="lName">File Size Limit</label>
		 <div><input type="text" name="file_size_limit" class="file_size_limit dyna_remo" value="600"></div>
		</div>
	</div>-->
	
	<input data-repeater-delete type="button" value="Delete <?php if($custom_labels['lesson']!= '') echo $custom_labels['lesson']; else "Lesson"; ?>" class="del_mod cst_del" not_include="true"/>
    </div>
    </div> 
	<?php } ?>
	<input data-repeater-create type="button" value="Add <?php if($custom_labels['lesson']!= '') echo $custom_labels['lesson']; else "Lesson"; ?>" class="add_mod cst_addmd" not_include="true"/>
    <input type="button" name="next" class="next btn btn-info cst_flt_right" value="Next" not_include="true"/>
    <input type="button" name="previous" class="previous btn btn-default cst_flt_right" value="Previous" not_include="true"/>
  </fieldset>
  <fieldset>
    <h2>Step 3: Enrolment Settings</h2>
	
	<h2 class="csth2">Sample <?php if($custom_labels['lesson']!= '') echo $custom_labels['lesson']; else "Lesson"; ?></h2>
	<select id="sample_lessonspt3" name="sample_lesson">
	  <option value="">select <?php if($custom_labels['lesson']!= '') echo $custom_labels['lesson']; else "Lesson"; ?></option>
	  
	</select>
    <h2 class="csth2"><?php if($custom_labels['course']!= '') echo $custom_labels['course']; else "Course"; ?> Content</h2>
    <div class="form-group cst-radio">
	<?php 
	$alwaysvisible='';
	$onlyvisible='';
	$freefrom='';
	$linear='';
	$open='';
	$free='';
	$closed='';
	$course_price='';
	$button_url='';
	if(isset($_SESSION['cst_class_creation']))
	{
		$clsdetails = get_post_meta($_SESSION['cst_class_creation'], '_sfwd-courses', false );
		foreach($clsdetails as $clsdetail)
		{
			if($clsdetail['sfwd-courses_course_disable_content_table']=='on')
			{
				$onlyvisible='checked';
			}
			elseif($clsdetail['sfwd-courses_course_disable_content_table']=='')
			{
				$alwaysvisible='checked';
			}
			if($clsdetail['sfwd-courses_course_disable_lesson_progression']=='on')
			{
				$freefrom='checked';
			}
			elseif($clsdetail['sfwd-courses_course_disable_lesson_progression']=='')
			{
				$linear='checked';
			}
			if($clsdetail['sfwd-courses_course_price_type']=='open')
			{
				$open='checked';
			}
			if($clsdetail['sfwd-courses_course_price_type']=='free')
			{
				$free='checked';
			}
			if($clsdetail['sfwd-courses_course_price_type']=='closed')
			{
				$closed='checked';
			}
			if($closed=='checked')
			{
				if($clsdetail['sfwd-courses_course_price_type']=='closed')
				{
					$course_price = $clsdetail['sfwd-courses_course_price'];
					$button_url = $clsdetail['sfwd-courses_custom_button_url'];
				}
				
			}
			
			
		}
	}
		
		
	?>
    <label class="label-container">
	   <span class="span-hdng">Always visible</span>
	   <input type="radio" class="cst_visibility" <?php if($alwaysvisible=='checked'){ echo 'checked';}?> name="cst_visibility"  value="1" > 
	  <span class="label-checkmark"></span>
	 </label>
	
    <label class="label-container">
	 <span class="span-hdng">Only visible to enrollees</span>
	 <input type="radio" class="cst_visibility" <?php if($onlyvisible=='checked'){ echo 'checked';} if($onlyvisible=='' &&$alwaysvisible==''){ echo 'checked';} ?> name="cst_visibility"   value="2">
	 <span class="label-checkmark"></span>
	</label>
	
    </div>
	<h2 class="csth2"><?php if($custom_labels['course']!= '') echo $custom_labels['course']; else "Course"; ?> Navigation Settings</h2>
	
	<div class="form-group cst-radio">
    
	
		<label class="label-container">
		  <span class="span-hdng">Linear</span>
	       <input type="radio" class="class_progress" <?php if($linear=='checked'){ echo 'checked';} if($linear=='' && $freefrom =='' ){ echo 'checked';}?>  name="class_progress"  value="1"> 
		   <span class="label-checkmark"></span>
	    </label>
	
	
     
		<label class="label-container">
		<span class="span-hdng">Free form</span>
	      <input type="radio" class="class_progress" <?php if($freefrom=='checked'){ echo 'checked';}?> name="class_progress"   value="2"> 
		  <span class="label-checkmark"></span>
	    </label>
	
	
    </div>
	
	<h2 class="csth2"><?php if($custom_labels['course']!= '') echo $custom_labels['course']; else "Course"; ?> Access Settings</h2>
	<h3 class="csth2">Access Mode</h3>
	<div class="form-group">
	
    <!--<div class="form-group cst-radio">
		<label class="label-container">
			<span class="span-hdng">Open</span>
			</br>
			<span>The <?php //if($custom_labels['course']!= '') echo $custom_labels['course']; else "Course"; ?> is not protected. Any user can access its content without the need to be logged-in or enrolled.
			</span>
			<input type="radio" class="access_mode" <?php //if($open=='checked'){ echo 'checked';}?> name="access_mode"  value="open" >
			<span class="label-checkmark"></span>
		</label>
	</div>-->
	
    <div class="form-group cst-radio">
		<label class="label-container">
			<span class="span-hdng">Free</span>
			</br>
			<span>The <?php if($custom_labels['course']!= '') echo $custom_labels['course']; else "Course"; ?> is protected. Registration and enrollment are required in order to access the content.</span>
			<input type="radio" class="access_mode" <?php if($free=='checked'){ echo 'checked';}?> name="access_mode"   value="free"> 
			<span class="label-checkmark"></span>
	</label>
	</div>
	
    <div class="form-group">
	   <div class="form-group cst-radio">
		<label class="label-container">
		
		<span class="span-hdng">Closed</span>
	     </br>
	     <span>The <?php if($custom_labels['course']!= '') echo $custom_labels['course']; else "Course"; ?> can only be accessed through admin enrollment (manual), group enrollment, or integration (shopping cart or membership) enrollment. No enrollment button will be displayed, unless a URL is set (optional).</span>
	     <input type="radio" class="access_mode" <?php if($closed=='checked'){ echo 'checked';} if($closed=='' && $free==''){ echo 'checked';}?> name="access_mode"   value="closed"> 
	     <span class="label-checkmark"></span>
	   </label>
	   </div>
		<div class="form-group cst-radio <?php if($closed=='checked'){ echo 'cst_hidden1';} if($closed=='' && $free==''){ echo 'cst_hidden1';}?> cst_hidden button_urlss">
		<label for="fName"><?php if($custom_labels['course']!= '') echo $custom_labels['course']; else "Course"; ?> Price</label>
		<input type="text" class="form-control cst_req" id="price_date3" name="class_price" <?php if(!empty($course_price)){ ?> value="<?php echo $course_price;?>"<?php }?> placeholder="<?php if($custom_labels['course']!= '') echo $custom_labels['course']; else "Course"; ?> Price">
		
		<label for="fName">Button URL</label>
		<input type="text" class="form-control cst_req" id="button_url" name="button_url" <?php if(!empty($button_url)){ ?> value="<?php echo $button_url;?>"<?php }?> placeholder="">
		
		</div>
	</div>
	
	
    </div>
    <a href="<?php echo get_permalink($_SESSION['cst_class_creation']); ?>" name="preview" class="btn btn-default add_mod cst_preview" value="Preview" not_include="true" target="_blank"/>Preview</a>
    <input type="button" id="previousStep3" name="previous" class="previous btn btn-default" value="Previous" not_include="true"/>
    <input type="submit" name="submit" class="submit btn btn-success" value="Submit" not_include="true"/>
  </fieldset>
  </form>