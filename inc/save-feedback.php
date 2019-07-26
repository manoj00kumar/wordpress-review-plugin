<?php

add_action('wp_ajax_collective_save_feedback', 'collective_save_feedback');
function collective_save_feedback()
{

if(check_ajax_referer( 'feedback-nonce', 'feedback_nonce' )){
	 $subject = sanitize_text_field($_POST['about']);
	  $msg = sanitize_text_field($_POST['message']);
	   
		if(empty($subject)|| empty($msg)){

			
			echo json_encode(array('status'=>0,'message'=>'Please fill all fields'));
			die;
		}
		else
		{



global $wpdb;
$user=get_current_user_id();
$fdate=date("Y-m-d H:i:s");
$question=sanitize_text_field($_POST['user_question']);
// $suc=$wpdb->insert("{$wpdb->prefix}posts",array('post_author'=>$user,'post_date'=>$fdate,'post_content'=>$msg,'post_title'=>$subject,'post_status'=>'published','post_type'=>'hrf_faq'));
$suc=wp_insert_post(array("post_author"=>$user,"post_content"=>$msg,"post_title"=>$subject,"post_status"=>"draft","post_type"=>"hrf_faq"));
wp_set_object_terms( $suc, "user-feedback", "faq_cat" );
add_post_meta($suc,"user_question",$question);
if($suc)
{
    $feedback_email = get_option( 'feedback_email' );
	//sendFeedbackmail('compasstool2017@gmail.com','Feedback Online Compass Tool',$subject,$question,$msg);
    sendFeedbackmail($feedback_email,'Feedback Online Compass Tool',$subject,$question,$msg);	
	echo json_encode(array('status'=>1,'message'=>'We will get back to you in a short time!'));
}
		}

}
else
{
	echo json_encode(array('status'=>0,'message'=>'Bad request'));
}
die;
}


function sendFeedbackmail($email,$subject,$feedback_subject, $question,$comment)
   { 
    global $current_user;
	$to=sanitize_email($email);
    //$cc = "prateek@graycelltech.com";
    $dir=get_template_directory_uri();
    $subject=$subject;
	$body= <<<HTML
   <!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="x-apple-disable-message-reformatting">
<title>Collective Leadership Compass</title>
 
</head>
<body width="100%" bgcolor="#333333" style="margin:0; padding:0; mso-line-height-rule: exactly;">
<table bgcolor="#333333" width="100%" cellspacing="0" cellpadding="0" border="0" align="center" style="table-layout:fixed;">
  <tr>
    <td width="100%" align="center"><!--[if mso>
<table width="600" border="0" cellspacing="0" cellpadding="0" align="center">
  <tr>
   <td>
<![endif]-->
      
      <table cellspacing="0" cellpadding="0" border="0" align="center"  bgcolor="#ffffff" width="100%" style="max-width:600px" >
        <tr>
          <td align="center" style="padding-top: 5px; padding-bottom: 5px;"><img alt="" src="{$dir}/assets/images/email-logo.png" /></td>
        </tr>
      </table>
      <table cellspacing="0" cellpadding="0" border="0" align="center"  bgcolor="#ffffff" width="100%" style="max-width:600px" >
        <tr>
          <td align="center"><img alt="" src="{$dir}/assets/images/email-compass-img.jpg" /></td>
        </tr>
      </table>
      <table cellspacing="0" cellpadding="0" border="0" align="center"  bgcolor="#ffffff" width="100%" style="max-width:600px" >
        <tr>
          <td style="font-family:sans-serif;  font-size: 14px; color: #404041; padding-left:27px; padding-right:27px;" colspan="2">
            
            <h1 style="font-family:sans-serif; font-size: 22px; font-weight: 700; text-align: center; margin-top:35px; margin-bottom: 30px;">Feedback</h1>
            


              

            
          </td>
        </tr>
		<tr><td style="padding-left:30px;"><b>User:</b> </td><td>{$current_user->user_nicename} ({$current_user->user_email}) </td></tr>
		<tr><td style="padding-left:30px;"><b>Subject:</b> </td><td>{$feedback_subject} </td></tr>
		<tr><td style="padding-left:30px;"><b>Question:</b> </td><td>{$question} </td></tr>
		<tr><td style="padding-left:30px;"><b>Comment:</b> </td><td>{$comment} </td></tr>
		</tr>
		<tr><td style="font-family:sans-serif;  font-size: 14px; color: #404041; padding-left:27px; padding-right:27px;padding-top:20px;" colspan="2"><p style="font-family:sans-serif; margin-bottom:30px; line-height: 24px;  font-size: 14px; color: #404041; "><strong>People who lead collectively take care of the future and turn crises into opportunities.</strong></p></td></tr>
      </table>
      
      <!--[if mso>
   </td>
  </tr>
</table>
<![endif]--></td>
  </tr>
</table>
</body>
</html>
HTML;
    $headers = array('Content-Type: text/html; charset=UTF-8');
       $res=wp_mail( $to, $subject, $body, $headers );
       return $res;
   }
 
