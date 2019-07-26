
	jQuery(function($){
  

      
   $("#cli_form").submit(function(e) {


		e.preventDefault();
		var rdata = $('#cli_form').serialize();
		
		$.ajax({
			
				url : auth_ajax.ajax_url,
				type : 'post',
				data : rdata,
				success : function(response) {
					
					console.log(response);
					var res = JSON.parse(response);
					
					if(res.status==1){
						alert(res.message);
						//$('#cli_form').hide();
						location.reload();
					}
					else
					{
					alert(res.message);	
							
					} 
				
				}
			});	
	 });

   // save question



   $("#cli_edit_form").submit(function(e) {


		e.preventDefault();
		var rdata = $('#cli_edit_form').serialize();
		
		$.ajax({
			
				url : auth_ajax.ajax_url,
				type : 'post',
				data : rdata,
				success : function(response) {
					
					console.log(response);
					var res = JSON.parse(response);
					
					if(res.status==1){
						alert(res.message);
						//location.reload();
					}
					else
					{
					alert(res.message);	
							
					} 
				
				}
			});	
	 });
   // add options
   $("#add_opt").click(function(){
if($("#input-type").val()=="t")
{
	alert("Please select radio input");
	return false;
}

var tf="<div class='form-group'><input type='text' class='form-control'";
var tid=$("#opt_group").children().length;
 
tf=tf+" name='option[]' id='opt"+tid+"' placeholder=Enter option value>"
tf+="<i class='dashicons dashicons-trash' id=btn"+tid+" onclick='remove_option("+tid+");'></i><div>";
$("#opt_group").append(tf);
$("#noch").val(tid);
   });

  //go back to profile
  jQuery("#back_view").click(function(){

jQuery("#views").show();
					jQuery("#edit_views").hide();

  });

  
  

});

 //delete
 function del_question(id)
 {
 	var rdata ={'id':id,'action':'delete_question'};
 	var conf=confirm("Do you want to delete it");
 
 	if(conf)
 	{
	jQuery.ajax({
			
				url : auth_ajax.ajax_url,
				type : 'post',
				data : rdata,
				success : function(response) {
					
					console.log(response);
					var res = JSON.parse(response);
					if(res.status==1)
					{
						alert("Question deleted");
						location.reload();
					}
					
				
				}
			});
}
 }
 //edit questions

//  function edit_question(id)
//  {
//  	var rdata =
 	
 	
// 	jQuery.ajax({
			
// 				url : auth_ajax.ajax_url,
// 				type : 'post',
// 				data : rdata,
// 				success : function(response) {
					
// 					console.log(response);
// 					var res = JSON.parse(response);
// 					if(res.status==1)
// 					{
						


		

// 	var t2="<label>Options</label>";
// jQuery.each(JSON.parse(res.message.options),function(key,value)

// {
// t2+="<input type='text' name='question' id='qu' class='form-control' value="+value+">";
	
	
// 		}	);	

// 						jQuery("#ques1").val(res.message.title);
// 						jQuery("#opt_group").html(t2);
// 						jQuery("#views").hide();
// 					jQuery("#edit_views").show();
// 					}
					
					
				
//                 }           

//                   });

//  }

//delete response record

function delete_record(sid)
{
        var rdata={action:'delete_survey_response',id:sid};
        var yes=confirm("Do you want to delete this record");
        if(yes==false)
        {
        	return false;
        }

		jQuery.ajax({
			
				url : auth_ajax.ajax_url,
				type : 'post',
				data : rdata,
				success : function(response) {
					
					console.log(response);
					var res = JSON.parse(response);
					if(res.status==1)
					{
			alert(res.message);
			location.reload();
			          }
					
					
				
                }           

                  });

}

//select option or text

function show_view()
{
	var itype=jQuery("#input-type").val();
 if(itype=="r")
 {
 	jQuery("#add-opt-btn").show()
 }
 else
 {
 	jQuery("#add-opt-btn").hide();
 }
 
}
 
 //remove option
 function remove_option(tid)
 {
 	jQuery("#opt"+tid).remove();
 	jQuery("#btn"+tid).remove();
 }
 //delete multiple

 function delete_these()
 {

var rdata=$("#survey_responses").serialize();
        var yes=confirm("Do you want to delete these records");
        if(yes==false)
        {
        	return false;
        }

		jQuery.ajax({
			
				url : auth_ajax.ajax_url,
				type : 'post',
				data : rdata,
				success : function(response) {
					
					console.log(response);
					var res = JSON.parse(response);
					if(res.status==1)
					{
			alert(res.message);
			location.reload();
			          }
					
					
				
                }           

                  });


 }
 //check all

 jQuery("#check-all").change(function()
 { 
 		if(jQuery("#check-all").prop("checked"))
 	{
 jQuery("input[type='checkbox']").prop("checked", true);  
  }

  else
  {
  	jQuery('input[type="checkbox"]').prop("checked", false);  
  }
 
 }
 );