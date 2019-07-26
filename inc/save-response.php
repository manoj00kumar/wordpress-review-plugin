<?php


add_action('wp_ajax_save_response', 'collective_save_response');
  
function collective_save_response(){

 
    	$noq=$_POST['noq'];
    	$survey_id=$_POST['survey_id'];
    	$survey_extra=$_POST['survey_extra'];
    	$res=array();
    	for($i=1;$i<=$noq;$i++)
    	{
			$res[$i]["question"]=$_POST['question_'.$i];
			$res[$i]["res"]=$_POST['res_'.$i];
    	}
    	
	if(check_ajax_referer('survey-nonce', 'survey_nonce' )){

	$uid=get_current_user_id();	
	$res1=json_encode($res);
	global $wpdb;
$stat=$wpdb->insert("{$wpdb->prefix}collective_survey_response",array("userid"=>$uid,"survey_id"=>$survey_id,"response"=>$res1,"feedback"=>$survey_extra,"created"=>date("d-M-Y")));
	if($stat)
	{
		echo json_encode(array('status'=>1,'message'=>'Data saved!'));	
	}	
		
	}
	else
	{
		echo json_encode(array('status'=>0,'message'=>'Not a valid request!'));
		}
//do something
die();
}


