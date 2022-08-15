<?php wp_enqueue_script('slidesjs_core'); ?>
<div id="validations">
</div>
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
	
	$quiz_id = 0;
	$quiz_title = "";
	$quiz_cont = "";
	$quiz_course_id = "";
	$quiz_lesson_id = "";
	$quiz_lesson = "";
	if(isset($_GET['quiz_id'])){
		$quiz_id = $_GET['quiz_id'];
		$quiz = get_post($quiz_id);
		$quiz_title = $quiz->post_title;
		$quiz_cont = $quiz->post_content;
		$quiz_course_id = get_post_meta( $quiz_id, 'course_id', true );
		$quiz_lesson_id = get_post_meta( $quiz_id, 'lesson_id', true );
		$course_lesson_ids = learndash_course_get_steps_by_type( $quiz_course_id, 'sfwd-lessons' );
	$out = "";
	foreach($course_lesson_ids as $course_lesson_id){
		if($quiz_lesson_id == $course_lesson_id){
			$selected = "Selected";
		}
		else{
			$selected = "";
		}
		$out .= '<option value="'.$course_lesson_id.'" '.$selected.'>'.get_the_title($course_lesson_id).'</option>';
	}
		$quiz_lesson = get_post( $quiz_lesson_id );
	}

?>
<h1>Add Your New <?php echo $ld_custom_quiz;      ?></h1>

<form id="cst_quiz_form" novalidate action="action.php" name="cst_quiz_form" class="class_children_data1" method="post">
    <div class="formcontrol">
        <?php 
            $args = array(
                'numberposts' => -1,
                'post_type'   => 'sfwd-courses',
                'post_status'   => 'pending, publish',
                'author'   => get_current_user_id()
              );
              
            $courses = get_posts( $args );
        ?>
        <label>Associated <?php echo $ld_custom_course;    ?></label>
        <select name="select_cource" id="cst_course_id">
            <option>Select Your <?php echo $ld_custom_course;    ?></option>
            <?php
                foreach($courses as $course)
                {
            ?>
                <option value="<?php echo $course->ID ?>" <?php if($quiz_course_id == $course->ID) echo "selected"; ?>><?php echo $course->post_title  ?></option>
            <?php
                }
            ?>
        </select>
    </div><br/>
    <div class="formcontrol">
        <label>Associated <?php echo $ld_custom_lesson; ?></label>
        <select name="select_lesson" id="lesson_data">
            <option>Select Your <?php echo $ld_custom_lesson; ?></option>
			<?php if($quiz_lesson_id){ 
				echo $out;
			} ?>
        </select>        
    </div><br />
    <div>
	<div class="cst_quiz formcontrol">
                <label><?php echo $ld_custom_quiz; ?> Title</label>
                <input type="text" name="quiztitle" id="quiztitle"  placeholder="<?php echo $ld_custom_quiz; ?> title" value="<?php echo $quiz_title; ?>">
            </div>
            <div class="fomcontrol">
                <label><?php echo $ld_custom_quiz; ?> Description</label>
                <textarea id="kv_frontend_editor" name="kv_frontend_editor" id="kv_frontend_editor" class="module_field"><?php echo $quiz_cont; ?></textarea>
            </div><br/>
        <div class="repeater"> 
            
            <div data-repeater-list="category-group" id="question_div">
			<?php
		$qquets = new LDLMS_Quiz_Questions($_GET['quiz_id']);
		$quests = $qquets->get_questions();
		if($quests && isset($_GET['quiz_id'])){
		global $wpdb;
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
			<div data-repeater-item>
                    <div class="question_type_sec">
                        <span class="question_type"><?php echo $ld_custom_question; ?> Type</span><br>
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
                    <div class="cst_quiz Single_choice_div" 
                        <?php if($data['_answerType'] == "single") echo 'style="display:block;"'; ?>>
                        <div class="form-group">
                            <label for="lName"><?php echo $ld_custom_question; ?></label>
                            <input type="text" class="form-control dyna_remo" id="quest_title" name="quest_title" placeholder="<?php echo $ld_custom_question; ?> title" value="<?php echo $data['_title']; ?>">
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

                     <div class="cst_quiz ssay_choice_div" <?php if($data['_answerType'] == "essay") echo 'style="display:block;"'; echo $data['_answerType']?>>
	  <div class="form-group">
    <label for="lName"><?php echo $ld_custom_question; ?></label>
	<input type="text" class="form-control dyna_remo" id="essay_quest_title" name="essay_quest_title" placeholder="<?php echo $ld_custom_question; ?> title" value="<?php echo $data['_title']; ?>">
		</div> 
	<div class="answer-group">
                            <div class="form-group">
                                <label for="lName">Answer</label>
      <select name="answer1e" id="answer1e">
					<option value="text" <?php if($data['_answerData'][0]['_gradedType'] == "test") echo 'selected'; ?>>Text Box</option>
					<option value="upload" <?php if($data['_answerData'][0]['_gradedType'] == "upload") echo 'selected'; ?>>Upload</option>
				</select>
			
                        
                        </div>
    </div>
    </div>
                    <input data-repeater-delete type="button" value="Delete <?php echo $ld_custom_question; ?>" class="del_ques" not_include="true"/><br /><br />
                </div>
		<?php } 
		} else{ ?>
                <div data-repeater-item>
                    <div class="question_type_sec">
                        <span class="question_type"><?php echo $ld_custom_question; ?> Type</span><br>
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
                    <div class="cst_quiz Single_choice_div" 
                        <?php if($data['_answerType'] == "single") echo 'style="display:block;"'; ?>>
                        <div class="form-group">
                            <label for="lName"><?php echo $ld_custom_question; ?></label>
                            <input type="text" class="form-control dyna_remo" id="quest_title" name="quest_title" placeholder="<?php echo $ld_custom_question; ?> title" value="<?php echo $data['_title']; ?>">
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

                    <div class="cst_quiz ssay_choice_div">
                        <div class="form-group">
                                <label for="lName"><?php echo $ld_custom_question; ?></label>
                                <input type="text" class="form-control dyna_remo" id="essay_quest_title" name="essay_quest_title" placeholder="<?php echo $ld_custom_question; ?> title">
                        </div>
                        <div class="answer-group">
                            <div class="form-group">
                                <label for="lName">Answer</label>
                                <select name="answer1e" id="answer1e">
                                                <option value=""> Select</option>
                                                <option value="text">Text Box</option>
                                                <option value="upload">Upload</option>
                                </select>
                            </div> 
                        
                        </div><br /><br />
                    </div>
                    <input data-repeater-delete type="button" value="Delete <?php echo $ld_custom_question; ?>" class="del_ques" not_include="true"/><br /><br />
                </div>
		<?php } ?>
            </div>
            <input data-repeater-create type="button" value="Add <?php echo $ld_custom_question; ?>" class="add_ques" not_include="true"/><br /><br />
        </div>
    </div>
	<input type="hidden" id="cst_quiz_id" name="cst_quiz_id" value="<?php if(isset($_GET['quiz_id'])){  echo $_GET['quiz_id']; } ?>">
    <input type="submit" name="submit" class="submit btn btn-success" value="Submit" not_include="true"/>
</form>