
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
						$('#cli_form').hide();
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
						location.reload();
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
var tf="<input type='text' class='form-control'";
var tid=$("#opt_group").children().length+1;
tf=tf+" name='opt"+tid+"' placeholder=Enter option>"
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

 function edit_question(id)
 {
 	var rdata ={'id':id,'action':'edit_question'};
 	
 	
	jQuery.ajax({
			
				url : auth_ajax.ajax_url,
				type : 'post',
				data : rdata,
				success : function(response) {
					
					console.log(response);
					var res = JSON.parse(response);
					if(res.status==1)
					{
						


		

	var t2="<label>Options</label>";
jQuery.each(JSON.parse(res.message.options),function(key,value)

{
t2+="<input type='text' name='question' id='qu' class='form-control' value="+value+">";
	
	
		}	);	

						jQuery("#ques1").val(res.message.title);
						jQuery("#opt_group").html(t2);
						jQuery("#views").hide();
					jQuery("#edit_views").show();
					}
					
					
				
                }           

                  });

 }


 