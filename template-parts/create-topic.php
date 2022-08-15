<div id="validations"></div>
<?php 
$custom_labels = get_option('learndash_settings_custom_labels');
if($custom_labels['course']!= '') 
{
	$ld_custom_course = $custom_labels['course'];
} else {
	$ld_custom_course = 'Course';
}

if($custom_labels['courses']!= '') {
	$ld_custom_courses = $custom_labels['courses']; 
} else {
	$ld_custom_courses = 'Courses';
}

if($custom_labels['lesson']!= '') {
	$ld_custom_lesson = $custom_labels['lesson'];
} else {
	$ld_custom_lesson = 'Lesson';
}

if($custom_labels['lessons']!= '') { 
	$ld_custom_lessons = $custom_labels['lessons']; 
} else {
	$ld_custom_lessons = 'Lessons';
}

if($custom_labels['topic']!= '') { 
	$ld_custom_topic = $custom_labels['topic']; 
} else {
	$ld_custom_topic = 'Topic';
}

if($custom_labels['topics']!= '') { 
	$ld_custom_topics = $custom_labels['topics']; 
} else {
	$ld_custom_topics = 'Topics';
}

if($custom_labels['quiz']!= '') { 
	$ld_custom_quiz = $custom_labels['quiz']; 
} else {
	$ld_custom_quiz = 'Quiz';
}

if($custom_labels['quizzes']!= '') {
	$ld_custom_quizzes = $custom_labels['quizzes']; 
} else {
	$ld_custom_quizzes = 'Quizzes';
}

if($custom_labels['question']!= '') { 
	$ld_custom_question = $custom_labels['question'];
} else {
	$ld_custom_question = 'Question';
}

if($custom_labels['questions']!= ''){
	$ld_custom_questions = $custom_labels['questions'];
} else {
	$ld_custom_questions = 'Questions';
}
	$topic_id = 0;
	$topic_title = "";
	$topic_cont = "";
	$topic_course_id = "";
	$topic_lesson_id = "";
	$topic_lesson = "";
if(isset($_GET['topic_id'])){
	$topic_id = $_GET['topic_id'];
	$topic = get_post($topic_id);
	$topic_title = $topic->post_title;
	$topic_cont = $topic->post_content;
	$topic_course_id = get_post_meta( $topic_id, 'course_id', true );
	$topic_lesson_id = get_post_meta( $topic_id, 'lesson_id', true );
	$topic_lesson = get_post( $topic_lesson_id );
}
wp_enqueue_script('slidesjs_core');
wp_enqueue_media();
?>
<h1>Add Your New <?php echo $ld_custom_topic;     ?></h1>
<form  name="cst_topic" method="post" class="cst_topic">
<div class="formcontrol">
<label>Title</label>
<input type="text" name="topictitle" id="topictitle" value="<?php echo $topic_title; ?>">
</div><br/>
<div class="fomcontrol med_con_group">
<label>Add Your Content</label>
 <textarea id="kv_frontend_editor" name="kv_frontend_editor" class="module_field med_cont"><?php echo $topic_cont;?></textarea>
</div><br/>
<div class="form-group">
	<div class="cst_upload_box">
	<input type="hidden" name="file-5[]" id="file-7" class="inputfile inputfile-6" data-multiple-caption="{count} files selected" multiple />
	<label for="file-7" class="inputfile-6-lable"><figure> <div class="upload_cst_text"><span>Upload Audio / Video / Image</span><div class="upload_cst_new"><span>select your file</span></div></div></figure> </label> 
	</div>
	</div>
<div class="formcontrol">
<?php 

$args = array(
  'numberposts' => -1,
  'post_type'   => 'sfwd-courses',
  'post_status'   => 'pending, publish',
  'author'   => get_current_user_id()
);
 
$courses = get_posts( $args );

/* echo "<pre>";
print_r($latest_books);
echo "</pre>";  */
?>
<label>Associated <?php echo $ld_custom_course;    ?></label>
<select name="select_cource" id="cst_course_id">
<option>Select Your <?php echo $ld_custom_course;    ?></option>
<?php
foreach($courses as $course)
{
?> 
<option value="<?php echo $course->ID ?>" <?php if($topic_course_id == $course->ID) echo "selected"; ?>><?php echo $course->post_title  ?></option>	
<?php
}
?>

</select>
</div><br/>
<div class="formcontrol">
<label>Associated <?php echo $ld_custom_lesson;    ?></label>  
<select name="select_lesson" id="lesson_data">
<option>Select Your <?php echo $ld_custom_lesson;    ?></option>
<?php if(!empty($topic_lesson)){ ?>
<option value="<?php echo $topic_lesson->ID; ?>" selected> <?php echo $topic_lesson->post_title;    ?></option>
<?php } ?>
</select>
</div>
<?php if(isset($_GET['topic_id'])){ ?>
<input type="hidden" name="topic_update" value="<?php echo $_GET['topic_id']; ?>">
<?php } ?>
<input type="submit" name="topicsubmit" class="topicsubmit">
</form>