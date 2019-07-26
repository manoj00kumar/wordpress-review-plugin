<?php

function view_questions()
{

global $wpdb;
//pagination code

$pagenum = isset( $_GET['pagenum'] ) ? absint( $_GET['pagenum'] ) : 1;

$limit = 12; // number of rows in page
$offset = ( $pagenum - 1 ) * $limit;
$total = $wpdb->get_var("select  COUNT(*) FROM {$wpdb->prefix}collective_survey S inner join {$wpdb->prefix}posts P on S.survey_id=P.ID where P.post_status='publish'");
if($total==0)
{
echo "<div class='panel panel-primary' style='margin-top:30px;'><div class='panel-heading'><h1>No data found</div></h1></div>";
die;
}
$num_of_pages = ceil( $total / $limit );
//*******
global $wpdb;
$data=$wpdb->get_results("select * from {$wpdb->prefix}collective_survey S inner join {$wpdb->prefix}posts P on S.survey_id=P.ID where P.post_status='publish' LIMIT $offset, $limit");
echo "<div class='panel-default'><div class='panel-heading' style='color:#428bca;font-size:20px;'>Questions for survey</div><div class='panel-body'>";
echo "<table class='table table-striped' id='views'><tr style='background:#428bca;'><th>Question</th><th>Survey</th><th>Shortcode</th><th>options</th><th>Actions</th></tr>";
foreach($data as $row)
{
	$id=$row->id;
	$x=(array)json_decode($row->options);

	$opt="<ol>";
	foreach($x as $y)
	{
		$opt.="<li>".$y."</li>";
	}
	$opt.="</ol>"; 
	echo "<tr><td>".ucfirst($row->title)."<td>". ucfirst(find_Servay_name($row->survey_id))."</td><td>[collective_survey]</td><td>".$opt."</td><td>";
	?>
	<a 
	href="<?php echo admin_url('options.php?post_type=cli_survey&page=edit&id='.$id); ?>" title="edit it">
	<span class='dashicons dashicons-edit'>
	</span></a>
	
	<?php
	echo "<button class='btn btn-primary' onclick='del_question($row->id);'>
	<span class='dashicons dashicons-trash'>
	</span></button>
	</td>
	</tr>";

}


//pagination pages
echo "<tr><td colspan=5>";
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
echo "</td></tr>";
echo '</div></div></div>';
}
?>



