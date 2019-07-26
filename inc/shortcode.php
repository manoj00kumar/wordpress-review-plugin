<?php
add_shortcode("collective_survey", "collective_surveycode");

function collective_surveycode() {
    
    global $wpdb;
    //if not logged in
     if (!is_user_logged_in()) {
        echo "<p class='alert alert-warning'>You are not allowed to visit this page, please login first</p>";

        echo "<button type='button' class='btn btn-primary'>Goto Login Page</button>";
       
                              }
   $userid=get_current_user_id();
   
     // print_r($post_data);
    $survey_for_user = $wpdb->get_row("select P.ID as id,P.post_title as title ,P.post_content  from 

        {$wpdb->prefix}collective_survey S 
        inner join {$wpdb->prefix}posts P 
        on S.survey_id=P.ID   
        where
        S.survey_id not in (select survey_id from 
        {$wpdb->prefix}collective_survey_response R where R.userid=$userid 
        group by R.survey_id ) 
        and P.post_type='cli_survey'
         limit 1

         ");
          if (!$survey_for_user) 
      {
        echo "<p class='alert alert-warning'>You have completed all surveys. Thanks for your valuable time</p>";
       
      }
      else
{
$data=$wpdb->get_results("select * from 
        {$wpdb->prefix}collective_survey where survey_id=$survey_for_user->id");
    

    }
    
    ?>
   
    <div>
		
	 <style>
        fieldset {
            display: none;
        }
    </style>
   
<div class="main-title" id="main-title">
                        <h1>
                            <?php if(isset($survey_for_user->title)) {echo $survey_for_user->title; } ?></h1>
                    </div>
                    <h3 class="subtitle" id="survey_end_text">
                        <?php if(isset($survey_for_user->post_content)) {echo $survey_for_user->post_content; } ?></h3>

    <form class="survey-form" id="survey_form">
            <input type="hidden" name="action" value="save_response">
           <input type="hidden" name="survey_nonce" value="<?php echo wp_create_nonce('survey-nonce'); ?>"/> 
            <input type="hidden" name="noq" value="<?php echo count($data); ?>">  
         <input type="hidden" name="survey_id" value="<?= $data[0]->survey_id ?>"> 
         <span style="color:red;display:none;" class="survey-error"></span>
                     <?php
                            
                            if (isset($data) && ($data)) {
                                $i = 1;
                            $total_no_of_questions = count($data);
                                foreach ($data as $row) {
                                    ?>
                                    <fieldset>
                                        <p><span class="badge"><?php echo $i."/".$total_no_of_questions; ?></span>&nbsp; <input type="hidden" name="question_<?= $i ?>" value="<?= $row->id ?>">

                                        <p><?php echo $row->title; ?></p><br clear="all" />

                                        <?php
                                        //
//                                        print_r(json_decode($row->options));
//                                        echo $row->options;
                                        $options = json_decode($row->options);
//                                        print_r($options);
                                        if (!empty($options)) {

                                            foreach ($options as $val) {
                                                ?>
                                                <div class="checkbox-new">
                                                    <input type="radio" value="<?php echo $val; ?>" name="res_<?php echo $i; ?>"  alt="" />
                                                    <label>  <?php echo $val; ?></label>
                                                </div>
                                                <?php
                                            }
                                        } else {
                                            ?>
                                            <div class="checkbox-new">
                                                <input type="text" name="res_<?php echo $i; ?>" value="" class="form-control" placeholder="Please enter your feedback">
                                            </div>
                                            <?php
                                        }
                                        ?>
                                        <div class="col-md-12 text-center button-grp">
                                            <?php if ($i != 1) { ?>
												<?php if ($i == $total_no_of_questions) { ?>
												<div class="row" style="margin-bottom:20px;">
													<label> Any suggestions, ideas or recommendations you would like to share with us</label>
<!--
													<textarea name="survey_extra" rows="2" cols="30"></textarea>
-->
													<input type="textarea" name="survey_extra" id="survey_extra"/>
												</div>
                                                
												
												<?php } ?>
												
                                                <button type="button" class="btn btn-theme-blue btn-previous"><i class="fa fa-angle-left"></i> BACKWARD</button>   
                                            <?php } ?>
                                            <?php if ($i == $total_no_of_questions) { ?>
												
                                                <button type="button" class="btn btn-theme-blue btn-submit">SUBMIT <i class="fa fa-angle-right"></i></button>
                                            <?php } else { ?>
												
                                                <button type="button" class="btn btn-theme-blue btn-next">FORWARD <i class="fa fa-angle-right"></i></button>
                                            <?php } ?>
												
                                        </div>
                                    </fieldset>   
                                    <?php
                                    $i++;
                                }
                            }
                        
                            ?>



                        </form>

                        </div>
    <script type="text/javascript">
        $(document).ready(function () {
            $("fieldset").first().fadeIn('slow');

            //            $('.survey-form fieldset:nth-child(1)').fadeIn('slow');
            //            console.log($('.survey-form fieldset:nth-child(1)').html())    
            $('.survey-form input[type="textarea"]').on('focus', function () {
                $(this).removeClass('input-error');
            });

            // next step
            $('.survey-form .btn-next').on('click', function () {
                var parent_fieldset = $(this).parents('fieldset');
                                console.log(parent_fieldset)
                var next_step = false;

                parent_fieldset.find('input[type="radio"]').each(function () {

                    if ($(this).is(':checked')) {
                        next_step = true;
                    }
                });
                parent_fieldset.find('input[type="text"]').each(function () {

                    if ($(this).val() == "") {
                        $(this).addClass('input-error');
                        next_step = false;
                    } else {
                        next_step = true;
                        $(this).removeClass('input-error');
                    }
                });

                if (next_step) {
                    $('.survey-error').html('').hide();
                    parent_fieldset.fadeOut(400, function () {
                        $(this).next().fadeIn();
                    });
                } else {
                    $('.survey-error').html('Please provide input').show();
                }

            });

            // previous step
            $('.survey-form .btn-previous').on('click', function () {
                $('.survey-error').html('').hide();
                $(this).parents('fieldset').fadeOut(400, function () {
                    $(this).prev().fadeIn();
                });
            });

            // submit
            $('.survey-form .btn-submit').on('click', function (e) {
                e.preventDefault();
               parent_fieldset = $(this).parents('fieldset');
                 console.log(parent_fieldset)
                var next_step = false;
                parent_fieldset.find('input[type="radio"]').each(function () {
                    console.log('rad')    
                    if ($(this).is(':checked')) {
                        next_step = true;
                    }
                });
                parent_fieldset.find('input[type="text"]').each(function () {
                    console.log('enter')    
                    if ($(this).val() == "") {
                        console.log('out')
                        $(this).addClass('input-error');
                        next_step = false;
                    } else {
                        console.log('in')
                        next_step = true;
                        $(this).removeClass('input-error');
                    }
                });
                console.log(next_step)
                if (next_step) {
                    $('.survey-error').html('').hide();
                    var app_data = $('#survey_form').serialize();
                    console.log(app_data)
                    $.ajax({
                        url: auth_ajax.ajax_url,
                        type: 'post',
                        data: app_data,
                        success: function (response) {
                            console.log(response);
                            var res = JSON.parse(response);

                            if (res.status == 1)
                            {
                                //hide form and 
                                $("fieldset").hide();
                                swal('Thank you for completing our survey!',res.message);
                                $('#survey_form').hide();
                                $(".main-title").html("");
                                 $("#main-title").hide();
                                $("#survey_end_text").html("<p>If you want to send us a more detailed feedback, please go to FEEDBACK </p>");
//                                $("#feedback-status").addClass("alert alert-success");
//                                $("#feedback-status").text(res.message);
                                      //location.reload();
                            } else
                            {
                                swal(res.message);
//                                $("#feedback").addClass("alert alert-warning");
//                                $("#feedback-status").text(res.message);

                            }
                        }
                    });
                } else {
                    $('.survey-error').html('Please provide input').show();
                }

            });


        });

        



    </script>
    <?php
    return ob_get_clean();
}
?>
 
