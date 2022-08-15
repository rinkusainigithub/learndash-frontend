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
?>
<div class="container">
        <div class="table-wrapper">
            <div class="table-title">
                <div class="row create_class">
                    <div class="col-sm-6 crearet_class">
						<h2>Manage <b><?php echo $ld_custom_courses; ?></b></h2>
					</div>
					<div class="col-sm-6 crearet_class_btn">
						<a href="<?php echo $cst_frnt_page; ?>" class="btn btn-success add_classman">
						<button type="button"><i class="material-icons">&#xE147;</i> <span><?php echo $ld_custom_course;    ?></span></button>
						</a>	
						
						<a href="<?php echo $cst_frnt_topic_page; ?>" class="btn btn-success add_classman">
						<button type="button"><i class="material-icons">&#xE147;</i> <span><?php echo $ld_custom_topic;     ?></span></button>
						</a>	

						<a href="<?php echo $cst_frnt_quiz_page; ?>" class="btn btn-success add_classman">
						<button type="button"><i class="material-icons">&#xE147;</i> <span><?php echo $ld_custom_quiz;      ?></span></button>
						</a>
						
						<a href="<?php echo $cst_frnt_quest_page; ?>" class="btn btn-success add_classman">
						<button type="button"><i class="material-icons">&#xE147;</i> <span><?php echo $ld_custom_question;  ?></span></button>
						</a>	


						
					</div>
                </div>
            </div>
            <table id="class-table" class="table table-striped table-hover">
                <thead>
                    <tr>
                        <th><?php echo $ld_custom_course;    ?> Title</th>
                        <th>Categories</th>
						<th>Tags</th>
						<th>Status</th>
                        <th>Assigned Group</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
				<?php
				$groups = learndash_get_groups(false, get_current_user_id() );
				$args = array(
					'post_type' => 'sfwd-courses',
					'author' => get_current_user_id(),
					'post_status' => array('publish', 'draft','pending'),
					'nopaging'  => true,
				);
				
				$courses = get_posts($args);
				if($courses){
					foreach($courses as $key=> $course){ 
						$categories = get_the_terms($course->ID, 'ld_course_category');
						if(!empty($categories)){
							$terms_string = join(', ', wp_list_pluck($categories, 'name'));
						}
						else{
							$terms_string = 'N/A';
						}
						$tags = get_the_terms($course->ID, 'ld_course_tag');
						if(!empty($tags)){
							$tags_string = join(', ', wp_list_pluck($tags, 'name'));
						}
						else{
							$tags_string = 'N/A';
						}
					?>
						<tr>
                        <td><?php  echo strtolower($course->post_title);?></td>
                        <td><?php echo $terms_string; ?></td>
						<td><?php echo $tags_string; ?></td>
						<td><?php echo ucfirst($course->post_status) ?></td>
						<!-- <td>  <?php /* echo $course->post_modified; */ ?> </td> -->
						<td>
						<select id='testSelect1<?php echo $key ?>' multiple>
						<?php 
						$courseID = $course->ID;
						$groupsIDs = learndash_get_course_groups($courseID, true );
							foreach($groups as $group) {
								$value = $group->ID;
								$text = $group->post_title;
								?><option value="<?php echo $value?>&<?php echo $courseID ?>"
								<?php if(in_array($value, $groupsIDs)): ?>
									selected
									<?php endif; ?>
								><?php echo $text ?></option>
								<?php 
							}
						?>
						</select>
							</td>
                        <td>
							<a class="edit"><i class="material-icons icon-image-preview" data-toggle="tooltip" title="Report">report</i></a>
							<a href="<?php echo get_site_url();?>/my-account/create-class?class_id=<?php echo $course->ID; ?>&step=step3" class="edit"><i class="material-icons icon-image-preview" data-toggle="tooltip" title="Settings">settings</i></a>
                            <a href="<?php echo get_site_url();?>/courses/<?php  echo str_replace(' ', '-', strtolower($course->post_title));?>" class="edit" data-toggle="modal"><i class="material-icons" data-toggle="tooltip" title="View">&#xE320;</i></a>
                            <a href="<?php echo get_site_url();?>/my-account/create-class?class_id=<?php echo $course->ID; ?>" class="edit" data-toggle="modal"><i class="material-icons" data-toggle="tooltip" title="Edit">&#xE254;</i></a>
                            <a href="#deleteEmployeeModal" class="delete_class" data-toggle="modal" data-value="<?php echo $course->ID; ?>"><i class="material-icons" data-toggle="tooltip" title="Delete">&#xE872;</i></a>
                        </td>
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