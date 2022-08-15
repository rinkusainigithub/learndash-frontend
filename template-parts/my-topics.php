<?php

if(!empty(get_option('cst_frnt_page')) && !empty(get_option('cst_frnt_topic_page'))){
	$cst_frnt_page = get_option('cst_frnt_page');
$cst_frnt_topic_page = get_option('cst_frnt_topic_page');
$cst_frnt_quiz_page = get_option('cst_frnt_quiz_page');
$cst_frnt_quest_page = get_option('cst_frnt_quest_page');
}
else{
	$cst_frnt_page = get_site_url()."/my-account/create-class/";
	$cst_frnt_topic_page = get_site_url()."/my-account/create-topic/";
	$cst_frnt_quiz_page = "#";
	$cst_frnt_quest_page = "#";
}

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
$custom_page_links = get_option('learndash_frontend_page_links');
if(!empty($custom_page_links['frontned_class_creation_page']) && !empty($custom_page_links['frontned_topic_creation_page']) && !empty($custom_page_links['frontned_quiz_creation_page']) && !empty($custom_page_links['frontned_question_creation_page']) ){
	$cst_frnt_page = get_site_url().$custom_page_links['frontned_class_creation_page'];
	$cst_frnt_topic_page = get_site_url().$custom_page_links['frontned_topic_creation_page'];
	$cst_frnt_quiz_page = get_site_url().$custom_page_links['frontned_quiz_creation_page'];
	$cst_frnt_quest_page = get_site_url().$custom_page_links['frontned_question_creation_page'];
	$cst_frnt_tag_page = get_site_url().$custom_page_links['frontned_course_tags_create'];
	$cst_frnt_category_page = get_site_url().$custom_page_links['frontned_course_categories_create'];
}
else{
	$cst_frnt_page = get_site_url()."/my-account/create-class/";
	$cst_frnt_topic_page = get_site_url()."/my-account/create-topic/";
	$cst_frnt_quiz_page = "#";
	$cst_frnt_quest_page = "#";
	$cst_frnt_tag_page = "#";
	$cst_frnt_category_page = "#";
}
?>
<div class="container">
        <div class="table-wrapper">
            <div class="table-title">
                <div class="row create_class">
                    <div class="col-sm-6 crearet_class">
						<h2> <b><?php echo $ld_custom_topics;?></b></h2>
					</div>
					<div class="col-sm-6 crearet_class_btn">
						
						<a href="<?php echo $cst_frnt_topic_page; ?>" class="btn btn-success add_classman">
						<button type="button"><i class="material-icons">&#xE147;</i> <span>Add New <?php echo $ld_custom_topic;?></span></button>
						</a>	
					</div>
                </div>
            </div>
            <table id="class-table" class="table table-striped table-hover">
                <thead>
                    <tr>
                        <th><?php echo $ld_custom_topic; ?> Title</th>
                        <th><?php echo $ld_custom_question; ?> Assigned</th>
                        <th><?php echo $ld_custom_lesson;?> Assigned</th>
                        <th>Modified By</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
				<?php
				$groups = learndash_get_groups(false, get_current_user_id() );
				$args = array(
					'post_type' => 'sfwd-topic',
					'author' => get_current_user_id(),
					'post_status' => array('publish', 'draft','pending'),
					'nopaging'  => true,
				);
				
				$topics = get_posts($args);
				if($topics){
					foreach($topics as $key=> $topic){ 
					if(empty(get_post_meta($topic->ID, 'course_id', true))){
						$course = "-";
					}else{
						$course = get_the_title(get_post_meta($topic->ID, 'course_id', true));
					}
					if(empty(get_post_meta($topic->ID, 'lesson_id', true))){
						$lesson = "-";
					}
					else{
						$lesson = get_the_title(get_post_meta($topic->ID, 'lesson_id', true));
					}


					?>
						<tr>
                        <td><?php  echo $topic->post_title;?></td>
                        <td><?php echo $course; ?></td>
						<td><?php echo $lesson; ?></td>
						<td><?php echo $topic->post_modified; ?></td>
						<td><a href="<?php echo $cst_frnt_topic_page; ?>?topic_id=<?php echo $topic->ID; ?>" class="edit" data-toggle="modal"><i class="material-icons" data-toggle="tooltip" title="Edit"></i></a></td>
                    </tr>
					<?php }
				}
			?>
                </tbody>
            </table>
			<!-- <div class="clearfix">
                 <div class="hint-text">Showing <b> 
				/* 
				$counts; 
				foreach($courses as $cours)
				
				{
					$counts++;
					if($counts >5){
						echo 5;
					} 
				
					
				} echo $counts; ?></b> out of <b>
				$count; 
				foreach($courses as $cours)
				{
					$count++;
				}
				echo $count; */
				 </b> entries</div> 
				
                    
            </div>-->
        </div>
	</div>