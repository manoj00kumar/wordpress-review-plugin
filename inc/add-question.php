
<?php
function my_plugin_settings_page() {
	
?>

<div class="panel panel-default" style="margin-top:30px;border-radius: 10px;">
<div class="panel-heading">
	<legend style="color:#ff156; text-align:center; ">Add Question to Servey</legend>
	</div>
<div class="panel-body">
		<form class="form"  id="cli_form">
		<input type="hidden" name="action" value="servey">
		<input type="hidden" name="cli_nonce" value="<?php echo wp_create_nonce('cli-nonce'); ?>"/>   
		<div class="form-group">
		<label>Select Survey from List</label>
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

	<option value="<?= get_the_ID(); ?>"><?= the_title(); ?></option>			
			
  <?php


}
}

?>
</select>
</div>
<?php 


 ?>
			<div class="form-group">
			<label>Question desctiption</label>
				<input type="text" name="question" id="qu" class="form-control" placeholder="Question">
			</div>
			<div class="form-group">
					<label>Select Input type(Radio choice or Textbox)</label>
             <select name="input-type" id="input-type" class="form-control" onchange="show_view();">

            
            <option value="r" selected>Radio choice</option>
            <option value="t">Textbox</option>
             </select>
			</div>
			<div class="form-group" id="opt_group">
            
			</div>

			<div class="form-group" id="add-opt-btn">

				<button type="button" class="btn btn-primary" id="add_opt"><span class="dashicons dashicons-plus"></span> Add option</button>
			</div>
			<input type="hidden" name="noch" value="0" id="noch">
<div class="form-group">
				<button type="submit" class="btn btn-primary" >Save Question</button>
			</div>
		</form>	
	
</div>
<?php
}