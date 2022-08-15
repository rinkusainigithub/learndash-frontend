jQuery(document).ready(function ($) {
	var classtable = jQuery('#class-table');
	if (classtable.length) {
		//classtable.DataTable();
		classtable.find("tr>td>select").each(function() {
			var options = this.options;
			var id = this.id;
			var intializeMultiSelect = document.multiselect(`#${this.id}`);
			for(var i=0; i< options.length; i++) {
				var optionsValue = options[i].value;
				intializeMultiSelect.setCheckBoxClick(optionsValue, function(target, args){
					optionChange(target, args);
				});
			}
			intializeMultiSelect.setCheckBoxClick("checkboxAll", function(target, args){
				selectAllChange(target, args)
			});
			
	  });
	}

	setTimeout(function () {
		jQuery('input[type="radio"]').each(function () {
			var attr = jQuery(this).attr('checkedcst');
			console.log(jQuery(this).attr('name'));
			if (typeof attr !== typeof undefined && attr !== false) {
				jQuery(this).attr('checked', true);
			}
		});
	}, 300);

	var url = window.location.search;
	if(url.includes("step3")) {
		var controls = ["#previousStep3"];
		showStep(3, url, controls);
	}

	//jQuery('input:radio[name="questiontype"]').change(function(){
	jQuery(document).on('click', 'input:radio[class="selctqustintype"]', function () {


		if (jQuery(this).val() == 'single') {
			jQuery(this).parents('.question_type_sec').siblings('.Single_choice_div').show();
			jQuery(this).parents('.question_type_sec').siblings('.ssay_choice_div').hide();
		}
		if (jQuery(this).val() == 'essay') {
			jQuery(this).parents('.question_type_sec').siblings('.ssay_choice_div').show();
			jQuery(this).parents('.question_type_sec').siblings('.Single_choice_div').hide();
		}
	});

	jQuery('.cptocb').on('click', function () {
		copyToClipboard(jQuery(this).siblings('.clsurl').text(), jQuery(this));
	});
	jQuery('#propic').on('change', function () {
		submitForm();
	});
	jQuery('.clsdelete').on('click', function () {
		var id = jQuery(this).data('id');
		cst_remove_class(id);
	});
	jQuery('.access_mode').on("click", function () {
		var data_value = jQuery(this).val();

		if (data_value == "price") {

			jQuery(this).siblings('.cst_hidden').show();
			jQuery('.pricewithdays').hide();
			jQuery('.button_urlss').hide();
		}
		else if (data_value == 4) {

			jQuery(this).siblings('.cst_hidden').show();
			jQuery('.only_price').hide();
			jQuery('.button_urlss').hide();
		}
		else if (data_value == 5) {

			jQuery(this).siblings('.cst_hidden').show();
			jQuery('.only_price').hide();
			jQuery('.pricewithdays').hide();
		}
		else if (data_value == 'closed') {
			jQuery('.cst_hidden').show();

		}
		else {

			jQuery('.cst_hidden').hide();
		}

	});
	$(document).on('click', '.media_check', function () {
		if ($(this).prop('checked') == true) {
			jQuery(this).parents('.form-group').siblings('.cst_hidden').show();
			jQuery(this).parents('.form-group').siblings('.cst_opt').hide();
		}
		else {
			jQuery(this).parents('.form-group').siblings('.cst_opt').show();
			jQuery(this).parents('.form-group').siblings('.cst_hidden').hide();
		}
	});
	jQuery(document).on('click', '.inputfile-4-lable', function () {
		open_media_uploader_video(jQuery(this));
	});
	jQuery(document).on('click', '.inputfile-5-lable', function () {
		open_media_uploader_audio_playlist(jQuery(this));
	});
	jQuery(document).on('click', '.inputfile-6-lable', function () {
		open_media_uploader_image(jQuery(this));
	});
	jQuery('#regiration_form').on('submit', function (e) {
		e.preventDefault();
		var cst_visibility = jQuery('input:radio[name=cst_visibility]:checked').val();
		var class_progress = jQuery('input:radio[name=class_progress]:checked').val();
		var access_mode = jQuery('input:radio[name=access_mode]:checked').val();
		var course_price = jQuery('#price_date3').val();
		var sample_lessons = jQuery('#sample_lessonspt3').val();

		var button_url = jQuery('#button_url').val();
		jQuery.ajax({
			url: ajax_vars.ajaxurl,
			method: 'POST',
			data: {
				action: 'cst_assign_class_price',
				access_mode: access_mode, button_url: button_url,
				course_price: course_price, cst_visibility: cst_visibility, class_progress: class_progress, sample_lessons: sample_lessons
			},
			success: function (responce) {
				Swal.fire({
					title: 'Done!',
					text: "Class has been succesfully created!",
					icon: 'success',
				}).then((result) => {
					window.location.replace(ajax_vars.all_classes);
					//window.location.reload();
				})
			}
		});
	});
	// Add TinyMCE
	addTinyMCE();
	var current = 1, current_step, next_step, steps;
	steps = $("fieldset").length;
	$(".next").click(function () {
		current_step = $(this).parent();
		var validated = cst_validate(current_step);
		if (validated) {
			next_step_function(current);
			next_step = $(this).parent().next();
			next_step.show();
			current_step.hide();
			++current
			//setProgressBar(++current);
		}
	});
	$(".previous").click(function () {
		current_step = $(this).parent();
		next_step = $(this).parent().prev();
		next_step.show();
		current_step.hide();
		--current;
		//setProgressBar(--current);
	});
	//setProgressBar(current);
	// Change progress bar action
	function setProgressBar(curStep) {
		var percent = parseFloat(100 / steps) * curStep;
		percent = percent.toFixed();
		$(".progress-bar")
			.css("width", percent + "%")
			.html(percent + "%");
	}
	/*   
	  jQuery('#regiration_form').on('submit', function(e){
		  e.preventDefault();
		  Swal.fire({
	  title: 'Good Job!',
	  text: "Your Class has been created succesfully",
	  icon: 'success',
	  confirmButtonColor: '#3085d6',
	  confirmButtonText: 'Okay'
	}).then((result) => {
	  if (result.value) {
		window.location.reload();
	  }
	})
	  }); */
	$(document).on('click', '.quiz_check', function () {
		if ($(this).prop('checked') == true) {
			$(this).parents('.form-group').siblings('.quiz_repeater').show();
			$(this).parents('.form-group').siblings('.quiz_repeater').find("#quiz_title").addClass('cst_req');
			jQuery('.dyna_remo').each(function () {
				jQuery(this).removeAttr('not_include');
			});
			jQuery(this).val("Yes");
		}
		else {
			$(this).parents('.form-group').siblings('.quiz_repeater').hide();
			$(this).parents('.form-group').siblings('.quiz_repeater').find("#quiz_title").removeClass('cst_req');
			jQuery('.dyna_remo').each(function () {
				jQuery(this).attr('not_include', '');
			});
			jQuery(this).val("no");
		}
	});
	$(document).on('click', '.assign_check', function () {
		if ($(this).prop('checked') == true) {
			$(this).parents('.form-group').siblings('.assign_check_div').show();
			jQuery('.assign_check_div').find('.dyna_remo').each(function () {
				jQuery(this).removeAttr('not_include');
				jQuery(this).addClass('cst_req');
			});
			jQuery(this).val("Yes");
		}
		else {
			$(this).parents('.form-group').siblings('.assign_check_div').hide();
			jQuery('.assign_check_div').find('.dyna_remo').each(function () {
				jQuery(this).attr('not_include', '');
				jQuery(this).removeClass('cst_req');
			});
			jQuery(this).val("no");
		}
	});

	$(document).on('click', '.quest_type', function () {
		if ($(this).val() == "multi_choice") {
			$.each($(this).parents('.form-group').siblings('.answer-group').find('.correct_answer'), function () {
				$(this).attr("type", "checkbox");
				chkname = $(this).attr("name");
				$(this).attr("name", chkname + "[]");

			});
		}
		else {
			$.each($(this).parents('.form-group').siblings('.answer-group').find('.correct_answer'), function () {
				$(this).attr("type", "radio");

			});
		}
	});

	$(document).on('keyup, click', '.cst_req', function () {
		$(this).siblings('.cst_error').remove();
	});

	$(document).on('click', '.abrt_class', function () {
		cst_remove_class();
	});

	$(document).on('change', '#cst_course_id', function () {
		var val = jQuery(this).val();
		if (!!val) {
			getLessonQuizDropDownData('cst_get_lessons', '#lesson_data', val);
			getLessonQuizDropDownData('cst_get_quizzes', '#quizzes_data', val);
		}
	});
	

	$(document).on('submit', 'form[name="cst_topic"]', function (e) {
		e.preventDefault();
		var val = jQuery(this).serialize();
		jQuery.ajax({
			url: ajax_vars.ajaxurl,
			method: 'post',
			data: {
				action: 'cst_save_topic',
				val: val,
			},
			success: function (resp) {
				Swal.fire({
					title: 'Done!',
					text: "Topic has been created.",
					icon: 'success',
				}).then((result) => {
					window.location.reload();
					//window.location.reload();
				})
			}
		});
	});

	$(document).on('submit', 'form[name="cst_quiz_form"]', function (e) {
		e.preventDefault();
		var dataToSend = getJsonData();
		var isFormValid = checkValidations(dataToSend, "quiz");
		if (isFormValid) {
			postRequest(dataToSend, 'cst_save_quiz', 'Quiz has been created.');
		}
	});

	$(document).on('submit', 'form[name="cst_question_form"]', function (e) {
		e.preventDefault();
		var dataToSend = getJsonData();
		var isFormValid = checkValidations(dataToSend, "question");
		if (isFormValid) {
			postRequest(dataToSend, 'cst_save_question', 'Question has been created.');
		}
	});

	function getJsonData() {
		var dataToSend = {
			cst_course_id: jQuery("#cst_course_id").val(),
			cst_lesson_id: jQuery("#lesson_data").val(),
			cst_quiz_title: jQuery("#quiztitle").val(),
			cst_quiz_description: jQuery("#kv_frontend_editor").val(),
			questions: []
		};
		var cst_quiz_id = jQuery("#quizzes_data").val();
		if (!!cst_quiz_id) {
			dataToSend.cst_quiz_id = cst_quiz_id;
		}
		jQuery("#question_div > div").each(function () {
			var questionData = {};
			jQuery(this).find(':input').each(function () {
				var attr = jQuery(this).attr('not_include');
				var arr = this.name.split('[');
				var name = arr[arr.length - 1].replace(/[[\]]/g, '');
				if (typeof attr === typeof undefined && attr !== true) {

					if (this.type == 'radio') {
						if (jQuery(this).is(':checked')) {
							questionData[name] = jQuery(this).val();
						}
					}
					else {
						questionData[name] = jQuery(this).val();
					}
				}
			});
			dataToSend.questions.push(questionData)
		});
		return dataToSend;
	}

	

	function checkValidations(dataToSend, validationFor = "") {
		var validations = [];
		if (!dataToSend.cst_course_id || dataToSend.cst_course_id == "Select Your Course") {
			validations.push("Associated course is required.")
		}
		if (!dataToSend.cst_lesson_id || dataToSend.cst_lesson_id == "Select Your Lession") {
			validations.push("Associated lesson is required.")
		}

		if (validationFor == "quiz") {
			if (!dataToSend.cst_quiz_title) {
				validations.push("Quiz title is required.")
			}
			if (!dataToSend.cst_quiz_description) {
				validations.push("Quiz description is required.")
			}
		} else if (validationFor == "question") {
			if (!dataToSend.cst_quiz_id || dataToSend.cst_quiz_id == "Select Your Quiz") {
				validations.push("Associated quiz is required.")
			}
		}

		if (dataToSend && dataToSend.questions && dataToSend.questions.length > 0) {
			dataToSend.questions.forEach((x, i) => {
				if (x.questiontype && x.questiontype === "essay") {
					if (!x.essay_quest_title) {
						// const isExists = checkIndex(validations, "Essay question title is required");
						validations.push("Essay question title is required");
					}
					if (!x.answer1e) {
						validations.push("Essay answer is required");
					}
				} else if (x.questiontype && x.questiontype === "single") {
					if (!x.quest_title) {
						validations.push("Single question title is required");
					}
					if (!x.answer1) {
						validations.push(`Answer 1 is required of question ${i}`)
					}
					if (!x.answer2) {
						validations.push(`Answer 2 is required of question${i}`)
					}
					if (!x.answer3) {
						validations.push(`Answer 3 is required of question${i}`)
					}
					if (!x.answer4) {
						validations.push(`Answer 4 is required of question${i}`)
					}
					if (!x.correct_answer) {
						validations.push(`Correct Answer is required of question${i}`)
					}
				} else {
					validations.push("Question type is required");
				}
			})
		} else {
			if (validationFor == "question") {
				validations.push("Atleast 1 question is required.")
			}
		}
		const uniqueValidations = [];
		if (validations.length > 0) {
			validations.forEach(v => {
				const index = uniqueValidations.findIndex(n => n === v);
				if (!(index > -1)) {
					uniqueValidations.push(v);
				}
			})
		}
		var validationMessages = uniqueValidations.join("<br />");
		jQuery("#validations").text("");
		jQuery("#validations").append(validationMessages);
		window.scroll(0, 0);
		return uniqueValidations && uniqueValidations.length > 0 ? false : true;
	}

});

function postRequest(dataToSend, action, message) {
	jQuery.ajax({
		url: ajax_vars.ajaxurl,
		method: 'post',
		data: {
			action: action,
			val: dataToSend,
		},
		success: function (resp) {
			Swal.fire({
				title: 'Done!',
				text: message,
				icon: 'success',
			}).then((result) => {
				window.location.reload();
				//window.location.reload();
			})
		}
	});
}

function generateOptions (optionsElements, args) {
	var courseGroupS = [];
	for(var i=0; i< optionsElements.length; i++) {
		var optionsValue = optionsElements[i].value;
		var courseGroup = getCourseGroupObject(optionsValue, args.checked);
		courseGroupS.push(courseGroup);
	}
	updateCourseGroup(courseGroupS)
}

function optionChange(target, args) {
	var courseGroupS = [];
	var courseGroup = getCourseGroupObject(args.id, args.checked);
	courseGroupS.push(courseGroup);
	updateCourseGroup(courseGroupS);
}

function selectAllChange(target, args) {
	var multiSelectID = target.parentElement.parentElement.parentElement.id;
	var parentDropDownID = multiSelectID.split("_itemList");
	if(parentDropDownID && parentDropDownID.length > 0 && parentDropDownID[0]) {
		var control = document.getElementById(parentDropDownID[0]);
		if(args.checked) {
			generateOptions(control, args)
		} else {
			generateOptions(control, args)
		}
	}
}

function getCourseGroupObject(value, status) {
	const couseGroupData = value.split("&");
	if(couseGroupData && couseGroupData.length > 1) {
		return { groupID: couseGroupData[0], courseId: couseGroupData[1], status: !status}
	}
	return null;
}

function updateCourseGroup(courseGroups) {
	if(courseGroups && courseGroups.length > 0) {
		postRequest(courseGroups, 'cst_save_class_group', 'Updated successfully');
	}
}
function getLessonQuizDropDownData(action, controlId, val) {
	jQuery.ajax({
		url: ajax_vars.ajaxurl,
		method: 'post',
		data: {
			action: action,
			val: val,
		},
		success: function (resp) {
			jQuery(controlId).html(resp);
		}
	});
}

function showStep(stepNumber, url, hideControls) {
	var urlQueryData = url.split("&");
	var class_id = "";
	urlQueryData.forEach(x => {
		var queryData = x.split("=");
		if(queryData[0].includes("class_id") && queryData[1]) {
			class_id = queryData[1];
		}
		if (queryData[0] === "step" && queryData[1] === `step${stepNumber}` && class_id) {
			var showFieldSet = stepNumber > 0 ? stepNumber -1: null;
			if(showFieldSet) {
				getLessonQuizDropDownData('cst_get_lessons', '#sample_lessonspt3', class_id);
				jQuery("#regiration_form > fieldset").hide();
				jQuery(hideControls.join(",")).hide();
				jQuery(`#regiration_form > fieldset:eq(${showFieldSet})`).show();
			}
		}
	})
}

// Add TinyMCE
function addTinyMCE() {
	// Initialize
	tinymce.init({
		selector: '.module_field',
		themes: 'modern',
		height: 200,
		plugins: "media",
		menu: {
			file: { title: 'File', items: 'newdocument restoredraft | preview | print ' },
			edit: { title: 'Edit', items: 'undo redo | cut copy paste | selectall | searchreplace' },
			view: { title: 'View', items: 'code | visualaid visualchars visualblocks | spellchecker | preview fullscreen' },
			insert: { title: 'Insert', items: 'image link media template codesample inserttable | charmap emoticons hr | pagebreak nonbreaking anchor toc | insertdatetime' },
			format: { title: 'Format', items: 'bold italic underline strikethrough superscript subscript codeformat | formats blockformats fontformats fontsizes align | forecolor backcolor | removeformat' },
			tools: { title: 'Tools', items: 'spellchecker spellcheckerlanguage | code wordcount' },
			table: { title: 'Table', items: 'inserttable tableprops deletetable row column cell' },
			help: { title: 'Help', items: 'help' }
		}
	});
}

function AddRemoveTinyMce(editorId) {
	//tinyMCE.EditorManager.execCommand('mceFocus', false, editorId);                    
	tinyMCE.EditorManager.execCommand('mceRemoveEditor', true, editorId);
	tinymce.EditorManager.execCommand('mceAddEditor', false, editorId);
}

function next_step_function(step) {
	if (step == 1) {
		cst_create_class();
	}
	else {
		create_other_data();
	}
}

function cst_validate(current_step) {
	var make_false = false;
	jQuery.each(current_step.find('.cst_req'), function () {
		if (jQuery(this).val() == "") {
			if (jQuery(this).parents('.cst_repeater').length || jQuery(this).parents('.assign_check_div').length) {
				if (jQuery(this).parents('.cst_repeater').css('display') !== 'none' || jQuery(this).parents('.assign_check_div').css('display') !== 'none') {
					jQuery(this).siblings('.cst_error').remove();
					jQuery('<p class="cst_error">Please fill this field</p>').insertAfter(jQuery(this));
					make_false = true;
				}
			}
			else {
				jQuery(this).siblings('.cst_error').remove();
				jQuery('<p class="cst_error">Please fill this field</p>').insertAfter(jQuery(this));
				make_false = true;
			}
		}
	});
	if (make_false) {
		return false;
	}
	else {
		return true;
	}
}

function create_other_data() {
	tinyMCE.triggerSave();
	$inputs = jQuery('.class_children_data1 :input');
	var values = {};
	var i = 1;
	$inputs.each(function () {
		var attr = jQuery(this).attr('not_include');
		if (typeof attr === typeof undefined && attr !== true) {

			if (this.type == 'radio') {
				if (jQuery(this).is(':checked')) {
					values[this.name] = jQuery(this).val();
					//console.log(values);
					// alert('if');
				}
			}
			else {
				values[this.name] = jQuery(this).val();
				//console.log(values);
				//alert('else');
			}

		}
	});

	console.log(values);
	jQuery.ajax({
		url: ajax_vars.ajaxurl,
		method: 'post',
		data: {
			action: 'cst_create_lesson',
			values: values
		},
		success: function (responce) {
			jQuery('.cstlena').remove();
			jQuery('#sample_lessonspt3').append(responce);
			console.log(responce);
		}
	});
}

function cst_create_class() {
	var class_title = jQuery('#class_title').val();
	var class_desc = tinymce.get('class_desc').getContent();
	var class_status = jQuery('#select_class_status').val();
	jQuery.ajax({
		url: ajax_vars.ajaxurl,
		method: 'post',
		data: {
			action: 'cst_create_class',
			class_title: class_title,
			class_desc: class_desc,
			class_status: class_status
		},
		success: function (data) {
			if (jQuery('.class_creation_head .abrt_class').length <= 0) {
				cst_show_abort_btn();
			}
			jQuery.ajax({
				url: ajax_vars.ajaxurl,
				method: 'post',
				data: {
					action: 'cst_class_preview',
				},
				success: function (data) {
					jQuery('.cst_preview').attr("href", data);
				}
			});
		}
	});
}

function cst_show_abort_btn() {
	jQuery('.class_creation_head').append('<input type="button" class="btn btn-info abrt_class del_mod" value="Abort Class Creation" />');
}

function cst_remove_class(id = 0) {
	Swal.fire({
		title: 'Are you sure?',
		text: "You won't be able to revert this!",
		icon: 'warning',
		showCancelButton: true,
		confirmButtonColor: '#3085d6',
		cancelButtonColor: '#d33',
		confirmButtonText: 'Yes, abort it!'
	}).then((result) => {
		if (result.value) {
			jQuery.ajax({
				url: ajax_vars.ajaxurl,
				method: 'post',
				data: {
					action: 'cst_remove_class',
					id: id,
				},
				success: function (data) {
					Swal.fire({
						title: 'Deleted!',
						text: "Class has been deleted.",
						icon: 'success',
					}).then((result) => {
						window.location.replace(ajax_vars.all_classes);
						//window.location.reload();
					})
				}
			});

		}
	})

}

var media_uploader = null;

function open_media_uploader_video(ths) {
	media_uploader = wp.media({
		frame: "video",
		state: "video-details",
	});

	media_uploader.on("update", function () {

		var extension = media_uploader.state().media.extension;
		var video_url = media_uploader.state().media.attachment.changed.url;
		var video_icon = media_uploader.state().media.attachment.changed.icon;
		var video_title = media_uploader.state().media.attachment.changed.title;
		var video_desc = media_uploader.state().media.attachment.changed.description;
		tinymce.get("module_cont").setContent('<iframe src="' + video_url + '" width="560px" height="314px"></iframe>');
		ths.siblings('.inputfile').attr('src', video_url);
	});

	media_uploader.open();
}
var audio_uploader = null;
function open_media_uploader_audio_playlist(ths) {
	audio_uploader = wp.media({
		frame: "audio", //template
		state: "audio-details", //URL will be returned.
	});

	audio_uploader.on("update", function () {
		console.log(audio_uploader.state().media.changed.mp3);
		var audio_url = audio_uploader.state().media.changed.mp3;
		tinymce.get("module_cont").setContent('<iframe src="' + audio_url + '" width="560px" height="314px"></iframe>');
		ths.siblings('.inputfile').attr('src', audio_url);
	});

	audio_uploader.open();
}
var image_uploader = null;
function open_media_uploader_image(ths) {
	image_uploader = wp.media({
		frame: "post", //template
		state: "insert", //URL will be returned.
	});

	image_uploader.on("insert", function () {
		var length = image_uploader.state().attributes.selection.length;
		console.log(image_uploader.state());
		for (var iii = 0; iii < length; iii++) {
			var audio_url = image_uploader.state().attributes.selection.models[iii].attributes.url;
			var mtype = getextension(audio_url);
			var vhtml = "";
			if (mtype == "image") {
				vhtml = '<img src="' + audio_url + '">';
			}
			else if (mtype == "audio" || mtype == "video") {
				vhtml = '<iframe src="' + audio_url + '" width="560px" height="314px"></iframe>';
			}
			var thsid = ths.parents('.form-group').siblings('.med_con_group').find('.med_cont').attr('id');
			tinymce.get(thsid).execCommand('mceInsertContent', false, vhtml);
			ths.siblings('.inputfile').attr('src', audio_url);
		}
	});

	image_uploader.open();
}

/* var image_uploader1 = null;
function open_media_uploader_profile_image()
{
	jQuery.ajax({
		url: ajax_vars.ajaxurl,
		method: 'post',
		data: {
			action: 'cst_temp_login',
			id:id,
		},
		success: function (data) {
			 image_uploader1 = wp.media({
        frame:    "post", //template
        state:    "insert", //URL will be returned.
		multiple : false
    });

    image_uploader1.on("insert", function(){
        console.log(image_uploader1.state());
    });

    image_uploader1.open();
		}
	});
   
} */

function getextension(url) {
	var extension = url.substr((url.lastIndexOf('.') + 1));
	switch (extension) {
		case 'jpg':
		case 'png':
		case 'gif':
			return 'image';  // There's was a typo in the example where
			break;                         // the alert ended with pdf instead of gif.
		case 'mp3':
			return 'audio';
			break;
		case 'mp4':
			return 'video';
			break;
		default:
			return 'who knows';
	}
};

function copyToClipboard(e, ths) {
	var tempItem = document.createElement('input');

	tempItem.setAttribute('type', 'text');
	tempItem.setAttribute('display', 'none');

	let content = e;
	if (e instanceof HTMLElement) {
		content = e.innerHTML;
	}

	tempItem.setAttribute('value', content);
	document.body.appendChild(tempItem);

	tempItem.select();
	document.execCommand('Copy');

	tempItem.parentElement.removeChild(tempItem);
	jQuery('.cptocb').text("Copy");
	ths.text('Copied');
}

function submitForm() {
	var imgclean = $('#propic');
	data = new FormData();
	data.append('file', $('#propic')[0].files[0]);
	data.append('action', 'cst_temp_login');

	$.ajax({
		url: ajax_vars.ajaxurl,
		method: 'post',
		data: data,
		enctype: 'multipart/form-data',
		processData: false,  // tell jQuery not to process the data
		contentType: false,  // tell jQuery not to set contentType
		success: function (resp) {

		}
	})
}