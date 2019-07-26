 <?php
 function view_survey_response()
 {

 	global $wpdb;
  $pagenum = isset( $_GET['pagenum'] ) ? absint( $_GET['pagenum'] ) : 1;

$limit = 12; // number of rows in page
$offset = ( $pagenum - 1 ) * $limit;
$total = $wpdb->get_var( "select  COUNT(*) FROM {$wpdb->prefix}collective_survey_response");

$results=$wpdb->get_results("select *  from {$wpdb->prefix}collective_survey_response LIMIT $offset, $limit ",ARRAY_A);
// echo "<pre>";
// print_r($results);
// echo "</pre>";
$num_of_pages = ceil( $total / $limit );
if($total==0)
{
echo "<div class='panel panel-primary' style='margin-top:30px;'><div class='panel-heading'><h1>No data found !</div></h1></div>";
die;
}
?>

  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
 
    <div class="panel panel-default" style="position:absolute; z-index:1000;width:90%;margin-top:40px;">

        <div class="panel-heading bg-info">
     <h1 style="font-weight: bold;text-align:center;">Survey response submitted by users</h1>
          </div>
     <div class="panel-body">
      <form id="survey_responses">
        <input type="hidden" name="action" value="delete_multiple">
        <table class="table table-striped">
	<thead>
	<tr>
    <th> <!-- <input type="checkbox" id="check-all" value="y"> -->&nbsp;&nbsp;<i onclick="delete_these();"  class="dashicons dashicons-trash"></i></th>
  <th> User</th>
   <th>Survey</th>
        <th>Response</th>
         <th>Date</th> 
          <th>Action</th>   
	</tr>
      </thead>
          <tbody>
          	<?php
         $i=1;
          foreach($results as $row)
          {
          	?>
              <tr>
                <td><input type="checkbox" id="check_<?php echo $i; ?>" name="check[]" value="<?php echo $row['id'] ?>"> </td>
             <td>
             	<?php echo userbyId($row['userid']);

             	 ?>
             </td>

               <td>
             <?php echo surveybyId($row['survey_id']); ?>
                  </td>
               <td>
               	<!-- <button class="btn btn-primary" id="btns<?php echo $i; ?>" onclick="collapsedDiv('<?php echo $i; ?>');"> --><span id="btns<?php echo $i; ?>" class="dashicons dashicons-plus"  onclick="collapsedDiv('<?php echo $i; ?>');"></span><!-- </button> -->
               <div id="div<?php echo $i; ?>" style="display:none;">
 
   <div class="panel panel-default">
   <table class="table">
    <?php
    $arr=json_decode($row['response'],true); 

     ?>
    <tr>
      <th>Question</th>
      <th>Response</th>
      </tr>
      <?php
foreach($arr as $ar)
    {
      ?>
    
    
     <tr>
      <td> <?php echo question_titleby_id($ar['question']); ?> </td>
     <td><?php echo $ar['res']; ?> </td>
    </tr>
<?php
}
?>
     

</table>
</div>



  
  </div>
 
              <!--<span class="dashicons dashicons-edit"></span>-->
   </td>
     <td>
       <?php echo $row['created']; ?> 
    </td>

    <td><i class="dashicons dashicons-trash" onclick="delete_record(<?php echo $row['id']; ?>);"></i></td>
               </tr>
<?php
$i++;
}
?>
                  

                   
	  </tbody>
       </table>
     </form>
         </div>
         <div class="panel-footer">
<?php
//pagination pages
$page_links = paginate_links( array(
    'base' => add_query_arg( 'pagenum', '%#%' ),
    'format' => '',
    'prev_text' => __( '&laquo;', 'text-domain' ),
    'next_text' => __( '&raquo;', 'text-domain' ),
    'total' => $num_of_pages,
    'current' => $pagenum
) );

if ( $page_links ) {
    echo '<div class="tablenav"><div class="tablenav-pages" style="margin: 1em 0">' . $page_links . '</div></div>';
}

?>

<p id="alert-msg">
</p>


         </div>
        
        
	</div>

<script type="text/javascript">
function collapsedDiv(id)
{

$("#div"+id).toggle();
$("#btns"+id).toggleClass("dashicons-plus dashicons-minus");
return false;
}

</script>
  
<?php
 }

//returns name by userid
function userbyId($uid)
{

$obj=get_userdata($uid);
return $obj->display_name;
}
//returns name by userid
function surveybyId($sid)
{

$obj=get_post($sid,ARRAY_A);
return $obj['post_title'];
}
function question_titleby_id($qid)
{

  global $wpdb;
  return $wpdb->get_var("select title from {$wpdb->prefix}collective_survey where id=$qid");
}