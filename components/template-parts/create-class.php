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
	<?php if(isset($course_lesson_ids))
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
    </div>
    <div class="form-group">
    <label for="lName"><?php if($custom_labels['lesson']!= '') echo $custom_labels['lesson']; else "Lesson"; ?> Description</label>
    <textarea id='module_desc' name='module_desc' class="module_field" style='width: 99%; height: 200px;'><?php echo (isset($moduledata->post_content) ? $moduledata->post_content : '') ; ?></textarea>
    </div>
	<div class="form-group">
    <label for="lName"><?php if($custom_labels['lesson']!= '') echo $custom_labels['lesson']; else "Lesson"; ?> Content</label>
     <textarea id='module_cont' name='module_cont' class="module_field" style='width: 99%; height: 200px;'><?php echo $modelcontent; ?></textarea>
    </div>
	
	
	<div class="form-group">
    <label for="lName">Assignment Uploads</label>
     <div>
	 
	 
	 <label class="checkbox-container">Yes
		<input type="checkbox" name="assign_check" value="Yes" class="assign_check" <?php if($assignment_upload == "on") echo "checked"; ?>>
		<span class="checkbox-checkmark"></span>
	 </label>
	 </div>
    </div>
	
	<div class="assign_check_div <?php if($assignment_upload == "on") echo 'assign_check_div1';?>">
		<div class="form-group">
		<label for="lName">File Extensions</label>
		 <div><input type="text" name="file_ext" value="<?php echo $assignment_exts; ?>" class="file_ext dyna_remo"></div>
		</div>
		<div class="form-group">
		<label for="lName">File Size Limit</label>
		 <div><input type="text" name="file_size_limit" value="<?php echo $assignment_limit; ?>" class="file_size_limit dyna_remo"></div>
		</div>
	</div>
	<?php 
	$cst_quiz_ids = array();
	foreach($course_quiz_ids as $quizid){
			$quiz_lesson_id = get_post_meta($quizid, 'lesson_id', true);
			if($quiz_lesson_id==$moduledata->ID){
				$cst_quiz_ids[] = $quizid;
			}
			}
			foreach($cst_quiz_ids as $cstquizid){
				$quiz = get_post($cstquizid);
				if($quiz){
					$quiztitle = $quiz->post_title;
					$quizdesc = $quiz->post_content;
				}
	?>
	
	<div class="form-group cst-form-group">
	<label for="lName">Would like to add <?php if($custom_labels['quiz']!= '') echo $custom_labels['quiz']; else "Quiz"; ?></label>
    <div>
	<label class="checkbox-container">Yes
		<input type="checkbox" <?php if(!empty($cstquizid)){ echo 'checked';}?> value="" class="quiz_check">
		<span class="checkbox-checkmark">
		</span>
	 </label>
	
	
	</div>
    </div>
	<div class="quiz_repeater cst_repeater" <?php if(!empty($cstquizid)){ echo 'style="display:block;"'; } ?>>
	<h2> <?php if($custom_labels['quiz']!= '') echo $custom_labels['quiz']; else "Quiz"; ?></h2>
	<div class="form-group">
    <label for="class_title"><?php if($custom_labels['quiz']!= '') echo $custom_labels['quiz']; else "Quiz"; ?> Title</label>
    <input type="text" class="form-control  dyna_remo" id="quiz_title" name="quiz_title" value="<?php if(!empty($quiztitle)){ echo $quiztitle; }?>" placeholder="<?php if($custom_labels['quiz']!= '') echo $custom_labels['quiz']; else "Quiz"; ?> title" not_include="true">
    </div>
	<div class="form-group">
    <label for="lName"><?php if($custom_labels['quiz']!= '') echo $custom_labels['quiz']; else "Quiz"; ?> Description</label>
    <textarea id='quiz_desc' name='quiz_desc' class="module_field dyna_remo" style='width: 99%; height: 200px;' not_include="true"><?php if(!empty($quizdesc)){ echo $quizdesc; }?></textarea>
    </div>
	<h2><?php if($custom_labels['questions']!= '') echo $custom_labels['questions']; else "Questions"; ?></h2>
	  
	<div class="inner-repeater">
	
	<?php
		$qquets = new LDLMS_Quiz_Questions($cstquizid);
		$quests = $qquets->get_questions();
		global $wpdb;
			?>
	<div data-repeater-list="quiz-group">
	<?php
		foreach($quests as $qk=>$qv){
		// Get Answers from Question.
			$question_pro_id     = (int) get_post_meta( $qk, 'question_pro_id', true );
			$question_mapper     = new \WpProQuiz_Model_QuestionMapper();

			if ( ! empty( $question_pro_id ) ) {
				$question_model = $question_mapper->fetch( $question_pro_id );
			} else {
				$question_model = $question_mapper->fetch( null );
			}

			// Get data as array.
			$question_data = $question_model->get_object_as_array();

			$answer_data = [];

			// Get answer data.
			foreach ( $question_data['_answerData'] as $answer ) {
				$answer_data[] = $answer->get_object_as_array();
			}

			unset( $question_data['_answerData'] );

			$question_data['_answerData'] = $answer_data;

			// Generate output object.
			$data = array_merge( $question_data, [
				'question_id'            => $question_id,
				'question_post_title'    => get_the_title( $question_id ),
			] );
		
	?>
	<div data-repeater-list="quiz-group">
      <div data-repeater-item class="cst_repetative_section">
	  <div class="question_type_sec">
	   <span class="question_type"><?php if($custom_labels['question']!= '') echo $custom_labels['question']; else "Question"; ?> Type</span><br>
	    <div class="form-group cst-radio">
		
	    <label class="label-container">
		<span class="span-hdng">Single choice</span>
	     <input type="radio" id="singletype" name="questiontype" value="single" class="selctqustintype" <?php if($data['_answerType'] == "single") { echo "checkedcst"; } ?>>
		 <span class="label-checkmark"></span>
		</label>
		
		 <label class="label-container">
		<span class="span-hdng">Essay / Open Answer</span>
		<input type="radio" id="essaytype" name="questiontype" value="essay" class="selctqustintype" <?php if($data['_answerType'] == "essay") { echo "checkedcst"; } ?>>
		 <span class="label-checkmark"></span>
		</label>
 
		
	   </div>
	  </div>
	  <div class="Single_choice_div" <?php if($data['_answerType'] == "single") echo 'style="display:block;"'; ?>>
	  <div class="form-group">
    <label for="lName"><?php if($custom_labels['question']!= '') echo $custom_labels['question']; else "Question"; ?></label>
      <input type="text" class="form-control dyna_remo" id="quest_title" name="quest_title" placeholder="<?php if($custom_labels['question']!= '') echo $custom_labels['question']; else "Question"; ?> title" value="<?php echo $data['_title']; ?>">
    </div>
	<div class="answer-group">
	<?php 
	if($data['_answerType'] == "single"){
	$answer_1 = $answer_2 = $answer_3 = $answer_4 = $count = 0;
	$answer_num = $_correct_num = array();
		foreach($data['_answerData'] as $_answerData){
				$answer_num[$count] = $_answerData['_answer'];
				$_correct_num[$count] = $_answerData['_correct'];
				$count = $count + 1;
			}
	}
	?>
	<div class="form-group">
	
    <label for="lName">Answer 1</label>
    <textarea id="answer1" name="answer1"><?php echo $answer_num[0]; ?></textarea>
	<div class="correct_ans">
		<label class="label-container">
			<span class="span-hdng">Correct Answer</span>
			<input type="radio"  name="correct_answer" class="correct_answer dyna_remo" value="answer1" <?php if($_correct_num[0]==1) { echo "checkedcst"; } ?>>
			<span class="label-checkmark"></span>
		  </label>
	</div>
	
    </div>
	<div class="form-group">
    <label for="lName">Answer 2</label>
    <textarea id="answer2" name="answer2"><?php echo $answer_num[1]; ?></textarea>
	
	<div class="correct_ans">
	   <label class="label-container">
			<span class="span-hdng">Correct Answer</span>
			<input type="radio"  name="correct_answer" class="correct_answer dyna_remo" value="answer2" <?php if($_correct_num[1]==1) { echo "checkedcst"; } ?>>
			<span class="label-checkmark"></span>
		  </label>
	</div>
	
    </div>
	<div class="form-group">
    <label for="lName">Answer 3</label>
    <textarea id="answer3" name="answer3"><?php echo $answer_num[2]; ?></textarea>
	<div class="correct_ans">
	
	    <label class="label-container">
			<span class="span-hdng">Correct Answer</span>
			<input type="radio"  name="correct_answer" class="correct_answer dyna_remo" value="answer3" <?php if($_correct_num[2]==1) { echo "checkedcst"; } ?>>
			<span class="label-checkmark"></span>
		 </label>
		  
	</div>
    </div>
	<div class="form-group">
    <label for="lName">Answer 4</label>
    <textarea id="answer4" name="answer4"><?php echo $answer_num[3]; ?></textarea>
	<div class="correct_ans">
	
		<label class="label-container">
			<span class="span-hdng">Correct Answer</span>
			<input type="radio"  name="correct_answer" class="correct_answer dyna_remo" value="answer4" <?php if($_correct_num[3]==1) { echo "checkedcst"; } ?>>
			<span class="label-checkmark"></span>
		 </label>
	</div>
    </div>
    </div>
    </div>
	
	
	 <div class="ssay_choice_div" <?php if($data['_answerType'] == "essay") echo 'style="display:block;"'; echo $data['_answerType']?>>
	  <div class="form-group">
    <label for="lName"><?php if($custom_labels['question']!= '') echo $custom_labels['question']; else "Question"; ?></label>
	<input type="text" class="form-control dyna_remo" id="quest_title" name="quest_titlee" placeholder="<?php if($custom_labels['question']!= '') echo $custom_labels['question']; else "Question"; ?> title" value="<?php echo $data['_title']; ?>">
      <select name="answer1e" id="answer1e">
					<option value="text" <?php if($data['_answerData'][0]['_gradedType'] == "test") echo 'selected'; ?>>Text Box</option>
					<option value="upload" <?php if($data['_answerData'][0]['_gradedType'] == "upload") echo 'selected'; ?>>Upload</option>
				</select>
    </div>
    </div>
	
	
	  <input data-repeater-delete type="button" value="Delete <?php if($custom_labels['question']!= '') echo $custom_labels['question']; else "Question"; ?>" class="del_ques" not_include="true"/>
    </div>
    
		</div>
		<?php } ?>
	<input data-repeater-create type="button" value="Add <?php if($custom_labels['question']!= '') echo $custom_labels['question']; else "Question"; ?>" class="add_ques" not_include="true"/>
    </div>
	</div>
	<input data-repeater-delete type="button" value="Delete <?php if($custom_labels['lesson']!= '') echo $custom_labels['lesson']; else "Lesson"; ?> " class="del_mod" not_include="true"/>
    </div>
	<?php  }?>
  
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
    <div class="form-group">
    <label for="lName"><?php if($custom_labels['lesson']!= '') echo $custom_labels['lesson']; else "Lesson"; ?>  Description</label>
    <textarea id='module_desc' name='module_desc' class="module_field" style='width: 99%; height: 200px;'></textarea>
    </div>
	<div class="form-group">
	<!--<div class="cst_upload_box">
	<input type="hidden" name="file-5[]" id="file-5" class="inputfile inputfile-4" data-multiple-caption="{count} files selected" multiple />
	<label for="file-5" class="inputfile-4-lable"><figure><svg xmlns="http://www.w3.org/2000/svg" width="20" height="17" viewBox="0 0 20 17"><path d="M10 0l-5.2 4.9h3.3v5.1h3.8v-5.1h3.3l-5.2-4.9zm9.3 11.5l-3.2-2.1h-2l3.4 2.6h-3.5c-.1 0-.2.1-.2.1l-.8 2.3h-6l-.8-2.2c-.1-.1-.1-.2-.2-.2h-3.6l3.4-2.6h-2l-3.2 2.1c-.4.3-.7 1-.6 1.5l.6 3.1c.1.5.7.9 1.2.9h16.3c.6 0 1.1-.4 1.3-.9l.6-3.1c.1-.5-.2-1.2-.7-1.5z"/></svg></figure> <span>Choose a video file&hellip;</span></label>
	<span class="cst_or">OR</span>
	</div>
	<div class="cst_upload_box">
	<input type="hidden" name="file-5[]" id="file-6" class="inputfile inputfile-5" data-multiple-caption="{count} files selected" multiple />
	<label for="file-6" class="inputfile-5-lable"><figure><svg xmlns="http://www.w3.org/2000/svg" width="20" height="17" viewBox="0 0 20 17"><path d="M10 0l-5.2 4.9h3.3v5.1h3.8v-5.1h3.3l-5.2-4.9zm9.3 11.5l-3.2-2.1h-2l3.4 2.6h-3.5c-.1 0-.2.1-.2.1l-.8 2.3h-6l-.8-2.2c-.1-.1-.1-.2-.2-.2h-3.6l3.4-2.6h-2l-3.2 2.1c-.4.3-.7 1-.6 1.5l.6 3.1c.1.5.7.9 1.2.9h16.3c.6 0 1.1-.4 1.3-.9l.6-3.1c.1-.5-.2-1.2-.7-1.5z"/></svg></figure> <span>Choose an audio file&hellip;</span></label>
	<span class="cst_or">OR</span>
	</div>-->
	<div class="cst_upload_box">
	<input type="hidden" name="file-5[]" id="file-7" class="inputfile inputfile-6" data-multiple-caption="{count} files selected" multiple />
	<label for="file-7" class="inputfile-6-lable"><figure> <div class="upload_cst_text"><span>Upload Audio / Video / Image</span><div class="upload_cst_new"><span>select your file</span></div></div></figure> </label> 
	</div>
    </div>
	<div class="form-group med_con_group">
    <label for="lName"><?php if($custom_labels['lesson']!= '') echo $custom_labels['lesson']; else "Lesson"; ?>  Content</label>
     <textarea id='module_cont' name='module_cont' class="module_field med_cont" style='width: 99%; height: 200px;'></textarea>
    </div>
	<div class="form-group cst-form-group">
    <label for="lName">Assignment Uploads</label>
     <div>
	  <label class="checkbox-container">Yes
		<input type="checkbox" name="assign_check" value="Yes" class="assign_check">
		<span class="checkbox-checkmark">
		</span>
	 </label>
	</div>
    </div>
	<div class="assign_check_div">
		<div class="form-group">
		<label for="lName">File Extensions</label>
		 <div><input type="text" name="file_ext"  class="file_ext dyna_remo"></div>
		</div>
		<div class="form-group">
		<label for="lName">File Size Limit</label>
		 <div><input type="text" name="file_size_limit" class="file_size_limit dyna_remo"></div>
		</div>
	</div>
	<div class="form-group cst-form-group">
	<label for="lName">Would like to add <?php if($custom_labels['quiz']!= '') echo $custom_labels['quiz']; else "Quiz"; ?></label>
    <div>
	<label class="checkbox-container">Yes
		<input type="checkbox" name='quiz_check' value="no" class="quiz_check"> 
		<span class="checkbox-checkmark">
		</span>
	 </label>

	</div>
	
    </div>
	<div class="quiz_repeater cst_repeater">
	<h2> <?php if($custom_labels['quiz']!= '') echo $custom_labels['quiz']; else "Quiz"; ?></h2>
	<div class="form-group">
    <label for="class_title"><?php if($custom_labels['quiz']!= '') echo $custom_labels['quiz']; else "Quiz"; ?> Title</label>
    <input type="text" class="form-control  dyna_remo" id="quiz_title" name="quiz_title" placeholder="<?php if($custom_labels['quiz']!= '') echo $custom_labels['quiz']; else "Quiz"; ?> title">
    </div>
	<div class="form-group">
    <label for="lName"><?php if($custom_labels['quiz']!= '') echo $custom_labels['quiz']; else "Quiz"; ?> Description</label>
    <textarea id='quiz_desc' name='quiz_desc' class="module_field dyna_remo" style='width: 99%; height: 200px;'></textarea>
    </div>
	<h2><?php if($custom_labels['questions']!= '') echo $custom_labels['questions']; else "Questions"; ?></h2>
	<div class="inner-repeater">
	<div data-repeater-list="quiz-group">
      <div data-repeater-item class="cst_repetative_section">
	  <div class="question_type_sec">
	   <span class="question_type"><?php if($custom_labels['question']!= '') echo $custom_labels['question']; else "Question"; ?> Type</span><br>
	   
	  
		
		<label class="label-container">
		<span class="span-hdng">Single choice</span>
	     <input type="radio" id="singletype" name="questiontype" value="single" class="selctqustintype">
		<span class="label-checkmark"></span>
		</label>
		
		<label class="label-container">
		<span class="span-hdng">Essay / Open Answer</span>
		<input type="radio" id="essaytype" name="questiontype" value="essay" class="selctqustintype">
		<span class="label-checkmark"></span>
		</label>
		
	  </div>
	  <div class="Single_choice_div">
	  <div class="form-group">
    <label for="lName"><?php if($custom_labels['question']!= '') echo $custom_labels['question']; else "Question"; ?></label>
      <input type="text" class="form-control dyna_remo" id="quest_title" name="quest_title" placeholder="<?php if($custom_labels['question']!= '') echo $custom_labels['question']; else "Question"; ?> title">
    </div>
	<div class="answer-group">
	<div class="form-group">
	
    <label for="lName">Answer 1</label>
    <textarea id="answer1" name="answer1"></textarea>
	<div class="correct_ans">
	
	   <label class="label-container">
		<span class="span-hdng">Correct Answer</span>
		<input type="radio"  name="correct_answer" class="correct_answer dyna_remo" value="answer1">
		<span class="label-checkmark"></span>
	  </label>
	
	
	</div>
    </div>
	<div class="form-group">
    <label for="lName">Answer 2</label>
    <textarea id="answer2" name="answer2"></textarea>
	<div class="correct_ans">
		<label class="label-container">
			<span class="span-hdng">Correct Answer</span>
			<input type="radio"  name="correct_answer" class="correct_answer dyna_remo" value="answer2">
			<span class="label-checkmark"></span>
	   </label>
	  
	</div>
    </div>
	<div class="form-group">
    <label for="lName">Answer 3</label>
    <textarea id="answer3" name="answer3"></textarea>
	<div class="correct_ans">
	  <label class="label-container">
		<span class="span-hdng">Correct Answer</span>
		<input type="radio"  name="correct_answer" class="correct_answer dyna_remo" value="answer3">
		<span class="label-checkmark"></span>
	  </label>
	</div>
	
    </div>
	<div class="form-group">
    <label for="lName">Answer 4</label>
    <textarea id="answer4" name="answer4"></textarea>
	<div class="correct_ans">
	 <label class="label-container">
		<span class="span-hdng">Correct Answer</span>
		<input type="radio"  name="correct_answer" class="correct_answer dyna_remo"  value="answer4">
		<span class="label-checkmark"></span>
	  </label>
	
	
	</div>
    </div>
    </div>
    </div>
	
	
	 <div class="ssay_choice_div">
	  <div class="form-group">
    <label for="lName"><?php if($custom_labels['question']!= '') echo $custom_labels['question']; else "Question"; ?></label>
      <input type="text" class="form-control dyna_remo" id="quest_titlee" name="quest_titlee" placeholder="<?php if($custom_labels['question']!= '') echo $custom_labels['question']; else "Question"; ?> title">
    </div>
	<div class="answer-group">
	<div class="form-group">
	
    <label for="lName">Answer</label>
   <select name="answer1e" id="answer1e">
					<option value="text">Text Box</option>
					<option value="upload">Upload</option>
				</select>
    </div> 
	
	 
	
    </div>
    </div>
	  <input data-repeater-delete type="button" value="Delete <?php if($custom_labels['question']!= '') echo $custom_labels['question']; else "Question"; ?>" class="del_ques" not_include="true"/>
    </div>
    </div>
	<input data-repeater-create type="button" value="Add <?php if($custom_labels['question']!= '') echo $custom_labels['question']; else "Question"; ?>" class="add_ques" not_include="true"/>
    </div>
	</div>
	
	<input data-repeater-delete type="button" value="Delete <?php if($custom_labels['lesson']!= '') echo $custom_labels['lesson']; else "Lesson"; ?>" class="del_mod" not_include="true"/>
    </div>
    </div> 
	<?php } ?>
	<input data-repeater-create type="button" value="Add <?php if($custom_labels['lesson']!= '') echo $custom_labels['lesson']; else "Lesson"; ?>" class="add_mod" not_include="true"/>
    <input type="button" name="previous" class="previous btn btn-default" value="Previous" not_include="true"/>
    <input type="button" name="next" class="next btn btn-info" value="Next" not_include="true"/>
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
	 <input type="radio" class="cst_visibility" <?php if($onlyvisible=='checked'){ echo 'checked';}?> name="cst_visibility"   value="2">
	 <span class="label-checkmark"></span>
	</label>
	
    </div>
	<h2 class="csth2"><?php if($custom_labels['course']!= '') echo $custom_labels['course']; else "Course"; ?> Navigation Settings</h2>
	
	<div class="form-group cst-radio">
    
	
		<label class="label-container">
		  <span class="span-hdng">Linear</span>
	       <input type="radio" class="class_progress" <?php if($linear=='checked'){ echo 'checked';}?>  name="class_progress"  value="1"> 
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
	
    <div class="form-group cst-radio">
		<label class="label-container">
			<span class="span-hdng">Open</span>
			</br>
			<span>The <?php if($custom_labels['course']!= '') echo $custom_labels['course']; else "Course"; ?> is not protected. Any user can access its content without the need to be logged-in or enrolled.
			</span>
			<input type="radio" class="access_mode" <?php if($open=='checked'){ echo 'checked';}?> name="access_mode"  value="open" >
			<span class="label-checkmark"></span>
		</label>
	</div>
	
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
	     <input type="radio" class="access_mode" <?php if($closed=='checked'){ echo 'checked';}?> name="access_mode"   value="closed"> 
	     <span class="label-checkmark"></span>
	   </label>
	   </div>
		<div class="form-group cst-radio <?php if($closed=='checked'){ echo 'cst_hidden1';} else{ echo '';}?> cst_hidden button_urlss">
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