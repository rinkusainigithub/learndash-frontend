
<?php

	$metaquery = "";
	$enterdate = "";
	$timezone = "";
	$yrofexp = "";
	$expertlbl = "";
	$Location = "";
	$classtype = "";
	$lessontype = "";
	$calender_ids = array();
	$metaqueryarrc = array();
	$metaqueryarr = array();
	$yrofexparr = array();
	$expertlblarr = array();
	if(isset($_POST['searchbtn']))
	{
		$enterdate = $_POST['enterdate'];
		$timezone = $_POST['timezone'];
		$yrofexp = $_POST['yrofexp'];
		$expertlbl = $_POST['expertlbl'];
		$Location = $_POST['Location'];
		$classtype = $_POST['classtype'];
		$lessontype = $_POST['lessontype'];
		$calender_ids = get_calenders_by_date_time($enterdate, $timezone);
		/* var_dump($enterdate);
		var_dump($timezone);
		var_dump($calender_ids); */
		if(!empty($calender_ids)){
			$metaqueryarrc['relation']  = 'OR';
		foreach($calender_ids as $calender_id){
			$metaqueryarrc[] = array(
					'key' => 'calendar',
					'value' => $calender_id,
					'compare' => 'LIKE',
				);
		}	
			$metaqueryarr = $metaqueryarrc;
		if($yrofexp){
			
			$yrofexparr = array(
					'key' => 'year_of_ex',
					'value' => $yrofexp,
					'compare' => '=',
				);
		}
	
		
		if($expertlbl){
		$expertlblarr = array(
					'key' => 'expertiselbl',
					'value' => $expertlbl,
					'compare' => '=',
				);
		
		}
		
				
		$metaquery = array(
			   'relation' => 'AND',
				$metaqueryarr,
				$yrofexparr,
				$expertlblarr,
				
			);
		
		
		
		}
	}
	if(!empty($metaquery)){
	$args = array(
            'post_type' => 'teacher_post',
            'posts_per_page'   => -1,
			'meta_query' => $metaquery,                         
        );
	}
	else{
		$args = array(
            'post_type' => 'teacher_post',
            'posts_per_page'   => -1,
        ); 
	}
	/* 	echo "<pre>";
		print_r($args);
		echo "</pre>"; */
		 
 $the_posts = get_posts($args);
 ?>
 
 


<div class="container-fluid custom_bootstrp_cls">
<form action="#" method="post">

 <div class="row">
 <p>
<?php 
		$args = array(
			'post_type'		=>	'groups'
		);
	
		$groups_query = get_posts( $args );
?>
    <div class="col-sm-12 col-md-6 col-lg-6">
	
	      <label>Location</label>
		  <select name="Location" class="Location" id="Location">
		  <option value="0">Select Location</option>
		  <?php foreach($groups_query as $location){
			  if($Location==$location->ID){
			  echo  '<option value="'.$location->ID.'" selected>'.$location->post_title.'</option>';
			  }
			  else{
				echo  '<option value="'.$location->ID.'">'.$location->post_title.'</option>';  
			  }
		  }
			  ?>
		</select>
	</div>
	<div class="col-sm-12 col-md-6 col-lg-6">
	    <label class="changehtml">Class type</label>
		<select name="classtype" id="classtype">
		  <option value="">Select Class Type</option>
		</select>
	<input type="hidden" name="hdclasstype" id="hdclasstype" value="">
	
	</div>
		
 </p>
 </div>
  <div class="row">
 <p>
 <div class="col-sm-12 col-md-6 col-lg-6">
	      <label>Lesson type</label>
		  <select name="lessontype" class="lessontype" id="lessontype">
		    <option value="">Select Lesson Type</option>
		</select>
	</div>
    <div class="col-sm-12 col-md-6 col-lg-6">
	    <label>Expert level</label>
		<select name="expertlbl">
			  <option value="expert" <?php if($expertlbl=="expert") echo "selected"; ?>>Expert</option>
			  <option value="beginners" <?php if($expertlbl=="beginners") echo "selected"; ?>>Beginners</option>
			 
		</select>
	
	 
	</div>
	 </p>
	</div>
  <div class="row">
 <p> 
 <div class="col-sm-12 col-md-6 col-lg-6">
 <label>Enter date</label>
 <input type="text" id = "datepicker-13" name="enterdate" placeholder="Select date" autocomplete="Off" value="<?php echo $enterdate; ?>">
 </div>
 <div class="col-sm-12 col-md-6 col-lg-6">
	
	      <label>Time slots</label>
		  <select name="timezone" class="timezonecls">
			  <option value="">select time slot</option>
		</select>
	</div>
 </p>
 
 </div>
 
 <div class="row">
 <p>
<div class="col-sm-12 col-md-6 col-lg-6">
	    <label>Year of experience</label>
		<?php 
		$args123 = array(
            'post_type' => 'teacher_post',
            'meta_key' => 'year_of_ex',
            'posts_per_page' => -1,
         );
		$the_posts123s = get_posts($args123);
		$idsexp = array();
		foreach($the_posts123s as $the_posts123)
		{
		$idsexp[] = get_post_meta( $the_posts123->ID, 'year_of_ex', true);
		}
		$expunVal = array_unique($idsexp);
		$expunVal = array_filter($expunVal);
		sort($expunVal);
		
		?>
		<select name="yrofexp">
		<option value="">Year of experience</option>
		<?php foreach($expunVal as $expunVa){
			if($expunVa == $yrofexp){
				$selected = "selected";
			}
			else{
				$selected = "";
			}
			?>
			  <option value="<?php echo $expunVa; ?>" <?php echo $selected; ?>><?php echo $expunVa; ?></option>
		<?php } ?>
		</select>
	
	
	</div>
 </p>	

 </div>
   <div class="row">
   <p>
		<div class="col-sm-12 col-md-3 col-lg-3">
		</div>
		<div class="col-sm-12 col-md-3 col-lg-3"><input type="submit" name="searchbtn" value="search"><input type="submit" name="resetquery" value="Reset">
		</div>
		<div class="col-sm-12 col-md-3 col-lg-3">
		</div>
	</p>	
	</div>
 </form>
 
</div> 
 
 
 
 
 
 
 
 
 
 <?php
echo '<div class="container-fluid custom_bootstrp_cls">
 <div class="row">';
foreach($the_posts as $the_post)
{ 

$a_id=$the_post->post_author;

$fname = get_the_author_meta('first_name', $a_id);
$lname = get_the_author_meta('last_name', $a_id);
$full_name = '';

if( empty($fname)){
    $full_name = $lname;
} elseif( empty( $lname )){
    $full_name = $fname;
} else {
    //both first name and last name are present
    $full_name = "{$fname} {$lname}";
}
   echo '<div class="col-sm-12 col-md-4 col-lg-4"><a href="'.get_the_permalink($the_post->ID).'">'.$full_name.'</a></div>';
}


   
 echo'</div></div>'; 
?>

   
      
      <link href = "https://code.jquery.com/ui/1.10.4/themes/ui-lightness/jquery-ui.css"
         rel = "stylesheet">
      <script src = "https://code.jquery.com/jquery-1.10.2.js"></script>
      <script src = "https://code.jquery.com/ui/1.10.4/jquery-ui.js"></script>
      
      <script>
         $(function() {
			 $('#datepicker-13').datepicker({ dateFormat: 'yy-mm-dd' }).val();
            //$( "#datepicker-13" ).datepicker();
            //$( "#datepicker-13" ).datepicker("show");
         });
      </script>
  
  
   
