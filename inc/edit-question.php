<?php

function edit_question_data()
{
	global $wpdb;
	 if(isset($_GET['id']))
	 {
 
		$id=$_GET['id'];
	 }
    $record= $wpdb->get_row("select  * from {$wpdb->prefix}collective_survey where id=$id",ARRAY_A);
    
if(!$record)
{
	echo "<p class='alert alert-warning'>No question with id:$id</p>";
	die;
}

	?>
<div class=' panel panel-default' id='edit_views' ">
<div class='panel-heading' style='color:#428bca;font-size:20px;'>
Edit Questions <a href="<?php echo admin_url('edit.php?post_type=cli_survey&page=view'); ?>"><button class="btn btn-primary" id="back_view">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<span class="dashicons dashicons-undo"></span></button></a>
</div>
<div class='panel-body'>
<form class="form"  id="cli_edit_form">
		<input type="hidden" name="action" value="edit_question">
		<input type="hidden" name="id" value="<?php echo $record['id']; ?>">
		<input type="hidden" name="cli_nonce" value="<?php echo wp_create_nonce('cli-nonce'); ?>"/>     
		<div class="form-group">
		<label>Select Survey</label>
<select class="form-control" name="survey">
<?php

$args = array(
    'post_type'=> 'cli_survey',
   
    'order'    => 'ASC'
    );              

$the_query = new WP_Query( $args );
if($the_query->have_posts() ) {
 while ( $the_query->have_posts() ) 
 {
  $the_query->the_post(); 
  ?>

	<option value="<?php $ID=get_the_ID();  echo $ID; ?>" <?php if($ID==$record['survey_id'])
{echo "selected";} ?> ><?= the_title(); ?></option>			
			
  <?php


}
}

?>

</select>

</div>
	<div class="form-group" id="question">
		<label>Question desctiption</label>
<input type="text" name="question" id="ques1" class="form-control" placeholder="Question" value="<?php echo $record['title']; ?>">
	</div>
	<div class="form-group">
					<label>Select Input type(Radio choice or Textbox)</label>
             <select name="input-type" id="input-type" class="form-control" onchange="show_view();">

            
            <option value="r" selected>Radio choice</option>
            <option value="t">Textbox</option>
             </select>
			</div>	
<div class="form-group" id="opt_group">

		<?php
  $options=json_decode($record['options'],true);
  
  $i=0;

foreach($options as $option)
{
?>
<div class='form-group'>
<input type='text' class='form-control' name='option[]' id='opt<?php echo $i; ?>' placeholder='Enter option value' value="<?php echo $option;  ?>">
<i class='dashicons dashicons-trash'  id="btn<?php echo $i; ?>" onclick='remove_option("<?php echo $i; ?>");'></i>
</div>
<?php
$i++;
}
		?>

	</div>
<div class="form-group">
				<button type="button" class="btn btn-primary" id="add_opt"><span class="dashicons dashicons-plus"></span> </button>
			</div>
			
<div class="form-group">
				<button type="submit" class="btn btn-primary" >Update Question</button>
			</div>

		</form>	

</div>
</div>
<?php
}
