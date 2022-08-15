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
?>
<h1>Add Your New <?php echo $ld_custom_question;  ?></h1>
<?php $custom_labels = get_option('learndash_settings_custom_labels'); ?>
<form id="cst_question_form" novalidate action="action.php" name="cst_question_form" class="class_children_data1" method="post">
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
                <option value="<?php echo $course->ID ?>"><?php echo $course->post_title  ?></option>
            <?php
                }
            ?>
        </select>
    </div>
    <div  class="formcontrol">
        <label>Associated <?php echo $ld_custom_lesson;    ?></label>
        <select name="select_lesson" id="lesson_data" class="lesson_data_question">
            <option>Select Your <?php echo $ld_custom_lesson;    ?></option>
        </select>        
    </div><br />
    <div  class="formcontrol">
        <label>Associated <?php echo $ld_custom_quiz;      ?></label>
        <select name="select_quizzes" id="quizzes_data" class="quizzes_data_question">
            <option>Select Your <?php echo $ld_custom_quiz;      ?></option>
        </select>        
    </div><br />
    <div>
        <div class="repeater"> 
            <div data-repeater-list="category-group" id="question_div">
                <div data-repeater-item>
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
                    <div class="cst_quiz Single_choice_div" 
                        <?php if($data['_answerType'] == "single") echo 'style="display:block;"'; ?>>
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

                    <div class="cst_quiz ssay_choice_div">
                        <div class="form-group">
                                <label for="lName"><?php if($custom_labels['question']!= '') echo $custom_labels['question']; else "Question"; ?></label>
                                <input type="text" class="form-control dyna_remo" id="essay_quest_title" name="essay_quest_title" placeholder="<?php if($custom_labels['question']!= '') echo $custom_labels['question']; else "Question"; ?> title">
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
                    <input data-repeater-delete type="button" value="Delete <?php if($custom_labels['question']!= '') echo $custom_labels['question']; else "Question"; ?>" class="del_ques" not_include="true"/><br /><br />
                </div>
            </div>
            <input data-repeater-create type="button" value="Add <?php if($custom_labels['question']!= '') echo $custom_labels['question']; else "Question"; ?>" class="add_ques" not_include="true"/><br /><br />
        </div>
    </div>
    <input type="submit" name="submit" class="submit btn btn-success" value="Submit" not_include="true"/>

</form>