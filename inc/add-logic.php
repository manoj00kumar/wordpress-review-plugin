<?php

add_action('wp_ajax_servey','create_question');
function create_question()
{
	if(check_ajax_referer( 'cli-nonce', 'cli_nonce' )){
		 $q = trim($_POST['question']);
		 $type = trim($_POST['input-type']);
		$options= $_POST['option'];
	$survey=trim($_POST['survey']);
		
$error="";
	$arr=array();
	if($type=="r")
	{
		$i=0;
	foreach($options as $opt)
	{ 
		if(isset($opt))
     {
       if($opt=="")
       {
       $error.="Empty choice value";
        break;
       }
       else
       {
		$arr[$i]=trim($opt);
       
           }
	}
	$i++;
   }
}
else
{
	$arr[0]="";
}

	$sdata=json_encode($arr);

	
		if(empty($q)){
		$error.="Please provide question text";	
		}
		if($error!="")
		{
			echo json_encode(array('status'=>0,'message'=>$error));
			die;
		}
		else
		{
global $wpdb;
$s=$wpdb->insert("{$wpdb->prefix}collective_survey",array("title"=>$q,"survey_id"=>$survey,"options"=>$sdata));
if($s)
{
	echo json_encode(array('status'=>1,'message'=>'Added!'));
		}
}
	}
	else
	{

		echo json_encode(array('status'=>0,'message'=>'Not a valid request!'));
	}
	die;
}
