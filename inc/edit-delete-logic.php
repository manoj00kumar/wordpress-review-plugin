
<?php

add_action('wp_ajax_edit_question','edit_question');

function edit_question()
{
	
		 if(check_ajax_referer( 'cli-nonce', 'cli_nonce' )){

		 	
		 $q = trim($_POST['question']);
		 $type = trim($_POST['input-type']);
		 $id = trim($_POST['id']);
	$survey=trim($_POST['survey']);
		
$error="";
	$arr=array();
	if($type=="r")
	{
		$i=0;
	foreach($_POST['option'] as $opt)
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
$s=$wpdb->update("{$wpdb->prefix}collective_survey",array("title"=>$q,"survey_id"=>$survey,"options"=>$sdata),array("id"=>$id));
if($s)
{
	echo json_encode(array('status'=>1,'message'=>'Updated!'));
		}
}
	}
	else
	{

		echo json_encode(array('status'=>0,'message'=>'Not a valid request!'));
	}
	die;
}

//find survey name(post title) by post id
function find_Servay_name($id)
{
global $wpdb;
$name=$wpdb->get_var("select post_title from {$wpdb->prefix}posts where ID=$id");
return $name; 
}

//delete question

add_action('wp_ajax_delete_question','delete_question');
function delete_question()
{
	
		 $q = trim($_POST['id']);
		global $wpdb;
		$stat = $wpdb->query("delete from {$wpdb->prefix}collective_survey where id=$q");
		if($stat)
		{
			echo json_encode(array('status'=>1,'message'=>'Deleted!'));

		}
	die;
}

//delete response record

add_action('wp_ajax_delete_survey_response','delete_responses');
function delete_responses()
{
	 
		 $sid = trim($_POST['id']);
		
		global $wpdb;
		$stat = $wpdb->query("delete from {$wpdb->prefix}collective_survey_response where id=$sid ");
		if($stat)
		{
			echo json_encode(array('status'=>1,'message'=>'Deleted!'));

		}
	die;
}

//delete response  multi record

add_action('wp_ajax_delete_multiple','delete_multi_response');
function delete_multi_response()
{
	 
	 
		global $wpdb;
		foreach($_POST['check'] as $check)
		{
		$stat = $wpdb->query("delete from {$wpdb->prefix}collective_survey_response where id=$check ");
	}
		if($stat)
		{
			echo json_encode(array('status'=>1,'message'=>'Deleted!'));

		}
	die;
}