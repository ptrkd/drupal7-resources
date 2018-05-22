 <?php
/*  STAFFLIST VERSION 3.2 -- Filtering by department and search box
	The Featured Person and choosing of template styles need to be implemented

*/

if (function_exists('prettifyOrgName')) {
	//Do NOTHING
}else{
	function prettifyOrgName($name){
	
	$editName = $name;
	$acronym ="";
	
	if (strpos($name, ",") !== false && ( strpos($name, "of") !== false ||  strpos($name, "for") !== false ||  strpos($name, "in") !== false ) ) {
		//lop off acronym
		if (strpos($name, "(") !== false ){
			$acronymArray = explode ( "(", trim($name) );
			$name = $acronymArray[0];
			$acronym = " (" . $acronymArray[1];
		}
	
	    //Otolaryngology, Head and Neck Surgery , Department of
		$nameArray = explode ( ",", trim($name) );
		
		if (count($nameArray) < 3 ){
			$reverseArray = array_reverse( $nameArray );
			$editName = implode(" ", $reverseArray);
		}
		else{
			//Department of
			if ( substr($name, 0, 10) == 'Department'){
				$editName = $name;
			}else{
				$editName = array_pop($nameArray);
				$editName = $editName . " " .implode(", ", $nameArray);
			}
		}	
	}
	
	return $editName . $acronym;
	}
}



$url = 'https://drupalish.ahc.umn.edu/orgweb/get-staff-v2.php?l=' . $listcode . "&s=" . $section;
$response = file_get_contents($url);
$response = json_decode($response);
$totalNumberOfObjects = count($response->stafflist);  

$zebra_list = "N";
$head_person = NULL;

$field_arr = str_split(strtoupper($showfields));
$show_title = $field_arr[0];
$show_org = $field_arr[1];
$show_phone = $field_arr[2];
$show_email = $field_arr[3];
$show_description = $field_arr[4];
$show_office = $field_arr[5];
$show_photo = $field_arr[6];

$heads = array();

if ($head == 'none' ){
	//do nothing
}else{
	//find head person in JSON
	foreach ($response->stafflist as $p){
	
		if ($p->x500 == $head ){
			$head_person = $p;
			break;
		}	
	}
	array_push($heads, $head);
}

?><div class="row white-bg"><?php 
$bootstrap_col_span_class = "col-sm-12";

if( $head2 != 'none' ){
	array_push($heads, $head2);
	$bootstrap_col_span_class = "col-sm-6";
}

if( isset($head_person) ){
	include("head-person.tpl.php");
}


if ($head2 == 'none' ){
	//do nothing
}else{
	//find head person in JSON
	foreach ($response->stafflist as $p){
	
		if ($p->x500 == $head2 ){
			$head_person2 = $p;
			break;
		}	
	}
	array_push($heads, $head2);
	
  $head = $head2;
  $headtitle = $headtitle2;
  
  if( isset($head_person2) ){
    include("head-person.tpl.php");
  }	
}

?></div><?php
//sections

//section count    
$sectioncount = $response->sectioncount;

$stafflist = $response->stafflist;

$alphabet = range('A', 'Z');

//FILTER ALPHABETICALLY
usort($stafflist, function($a, $b)
{
    return strcmp($a->LastName, $b->LastName);
});

$firstletter = "";
$previousletter = "";

//listing count
$lcount = 1;

$firstletters = array();

foreach ($stafflist as $p){
	$firstletter = $p->LastName[0];
	 
	if ($firstletter != $previousletter ){
		array_push ($firstletters, $firstletter );
		$previousletter = $firstletter;
	}
}

?>
 
    <div class="container-no">
    	<div class="row">
            <div class="col-sm-12">
<?php       
			$sectioncount = 1;     
        	foreach($response->sections as $section){
        	
        		if ($response->sectioncount > 1 && $sectioncount < 1){ //hobble this function for the A-Z aggregate
?>
				
  				 <a data-id="<?php echo $sectioncount; ?>" href="javascript:void(0);" class="txt-btn-jump"><?php echo $section->name; ?></a>&nbsp;&nbsp;
<?php           
				}
				$sectioncount = $sectioncount + 1;
            }
            
            if ($response->sectioncount > 1 ){
?>
				 <a id="show-all" data-id="show-all" href="javascript:void(0);" class="txt-btn-jump txt-btn-jump-all">Show All</a>
<?php            
            
            }
?>                              
             </div>
        </div>
        
<?php if ($usesearch == 'Y' ){ ?>        
        <div class="row" id="search-row">     
             <div class="col-sm-12">
                <div class="input-group">
                  <input id="search_field" type="text" class="form-control" placeholder="Filter staff by name...">
                 
                  <span class="input-group-btn">
                    <!--<button class="btn btn-default" type="button"><span class="glyphicon glyphicon-search"></span></button>-->
                  </span>
                  &nbsp;<!-- <span id="search_result_count"></span> --> 
                </div><!-- /input-group -->
                 
            </div>
        </div>
<?php } ?> 

<?php 
	$azfilter = 'Y';
	if ($azfilter == 'Y' ){ ?>    
        <!-- A-Z List --> 
         
         <div class="row" id="az-section">
        	<div class="col-sm-12 ">
<?php        	
 
	$displayazinitial = "block";
 
	for ($i=0; $i <= 1; $i++){ //hobbled for single list
			
		
			if ($i > 0){
				$displayazinitial = "none";
				
				$sectionsort = array_filter($stafflist, function($obj) use($i){
    				if (isset($obj->SectionNum)) {
        				if ($obj->SectionNum != $i) return false; 
    				}
    				return true;
					});
 
					//FILTER ALPHABETICALLY
				usort($stafflist, function($a, $b)
					{
    					return strcmp($a->LastName, $b->LastName);
					});
					
				$firstletter = "";
				$previousletter = "";

					//listing count
				$lcount = 1;

				$firstletters = array();

				foreach ($sectionsort as $p){
					$firstletter = $p->LastName[0];
	 
					if ($firstletter != $previousletter ){
						array_push ($firstletters, $firstletter );
						$previousletter = $firstletter;
					}
				}
					
			} 
	
	
	    		
?>        	
        <ul class="alphabetical-sort-list" style="display: <?php echo $displayazinitial; ?>" id="alphabet-list-<?php echo $i; ?>">
<?php                
 		foreach($alphabet as $letter){
 			if ( in_array($letter, $firstletters )){
 ?>
 			<li><a href="#<?php echo $letter; ?>"><?php echo $letter; ?></a></li>
 <?php			
 			}else{
 ?>
			<li><?php echo $letter; ?></li>
<?php		
			}
 		}
?>       
			<li><a href="#ALL-<?php echo $i; ?>">ALL</a></li>         
                </ul> 
<?php
	 
	}
?>               
                
            </div>
        </div>
<?php } ?> 
       
<?php 
		$count = 0;
foreach($response->sections as $section){ 
	

	if ($count > 0){ 
		break; // hack to only show first aggregated section
	}
	
		 
	$zebra_list = "N";
			
	if (isset($section->type) &&  strlen($section->type) > 0 ){
		$zebra_list = $section->type;
	}	
	$NUM = $count; 
 	
	$section_slice = $stafflist;
	 
 
	//FILTER ALPHABETICALLY  !!!!!!!!!!!!!!!!!
	if ($azfilter == 'Y' ){  
		usort($section_slice, function($a, $b)
		{
    			return strcmp(explode("-",$a->LastName)[0] . $a->FirstName  , explode("-",$b->LastName)[0] . $b->FirstName);
		});
	}
?>
       <!--  SECTION --> 
       
<?php
	if ($response->sectioncount > 1){
?>       
        <a id="anchor-<?php echo $count; ?>"name="<?php echo $section->name; ?>"></a>
        <div class="row section-parent"  id="section-parent-<?php echo $count; ?>">
        	<!-- <div class="col-sm-12"><h2 id="faculty"><?php echo $section->name; ?></h2></div> -->
        </div>
 <?php
 	}
 ?>	 
          
<div id="az-list-<?php echo $count;  ?>">    
	<dl  id="section-<?php echo $count; ?>" class="section">  
	    <div class="row" id="row-1">  
<?php        
   //Staff List Output A-Z -->
$lcount = 4;
	if ($count == 0) {
	foreach ($section_slice as $p){
		$firstletter =$p->LastName[0];
 

		include("person-info.tpl.php");

		if ($zebra_list != 'Z') {
?>	
	        	<div class="col-sm-12 stafflist-box lastname-<?php echo $p->LastName[0] ?> <?php echo $departmenthead_class; ?>" id="<?php echo $namedivid; ?>">
            		<?php if ($show_photo == 'Y'){ ?><div class="img-placeholder"><img src="https://drupalish.ahc.umn.edu/bios/show-image.php?i=<?php echo $p->Photo; ?>"></div><?php } ?>
            		<div class="bio-content">
                		<span class='stafflist-name'><?php echo $name; ?></span>
                		<?php if ($show_title == 'Y'){ echo $dtitle; } ?>
                		<?php if ($show_org == 'Y'){ echo $porg; } ?>
                		<p class="contact-block"><?php if ($show_phone == 'Y'){ echo $phone; }?><?php if ($show_email == 'Y'){ echo $email; } ?><?php if ($show_office == 'Y'){ echo "<br>" .$office_address; } ?></p>
                		<p><?php if ($show_description == 'Y'){ echo $description; } ?></p>
                      	<p><?php echo $readmorelink; ?></p>
            		</div>
            	</div><!-- close col -->
<?php
		}else{
?>

		<div class="row zebra staff-list stafflist-box lastname-<?php echo $p->LastName[0] ?> <?php echo $departmenthead_class; ?>" id="<?php echo $namedivid; ?>">
        	<div class="col-xs-5"><strong><?php echo $name ?></strong> <?php echo $dtitle ?></div>
            <div class="col-xs-2"><?php echo $phone ?></div>
            <div class="col-xs-5"><?php echo $email_without_br ?></div>
        </div>
<?php
		}	
	 	$lcount = $lcount + 1;	
	}
//Fill out last row
	for ($i = $lcount; $i < 2; $i++){
		echo "<div class='col-sm-12'> </div>";	 
	}
	}
	
echo "</div></dl>";
//END Staff List Output A-Z

?>     
</div>        
<?php 	
	$count = $count + 1;
	
} 
?>
  
          <div id="no_results" class="row">     
             <div id="no_results_col" class="col-sm-12">
                	<h3>No Results</h3>  
                	<button id='clear-button' type="button" class="btn btn-danger">Clear</button>
            </div>
        </div>
        <!--  /div /.row -->
    </div><!-- /.container -->
