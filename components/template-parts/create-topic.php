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
?>
<h1>Add Your New <?php echo $ld_custom_topic;     ?></h1>
<form  name="cst_topic" method="post" class="cst_topic">
<div class="formcontrol">
<label>Title</label>
<input type="text" name="topictitle" id="topictitle">
</div>
<div class="fomcontrol">
<label>Add Your Content</label>
 <textarea id="kv_frontend_editor" name="kv_frontend_editor" id="kv_frontend_editor" class="module_field"></textarea>
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
<option value="<?php echo $course->ID ?>"><?php echo $course->post_title  ?></option>	
<?php
}
?>

</select>
</div>
<div class="formcontrol">
<label>Associated <?php echo $ld_custom_lesson;    ?></label>  
<select name="select_lesson" id="lesson_data">
<option>Select Your <?php echo $ld_custom_lesson;    ?></option>

</select>
</div>

<input type="submit" name="topicsubmit" class="topicsubmit">
</form>