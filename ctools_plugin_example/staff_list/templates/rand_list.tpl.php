<?php

$response = file_get_contents('https://drupalish.ahc.umn.edu/orgweb/get-staff.php?l=' . $listcode );
$response = json_decode($response);
$totalNumberOfObjects = count($response);
$unit = $response[0];
        
 

?>
<?php drupal_set_title($unit->{'ListName'} ); ?>


<!-- START FACULTY STAFF LIST -->
<div id="rand-list">
<?php 

$currentSection = 0;

shuffle($response);

$selectlist = array();
$addcount = 0;
$count = 0;
while( $addcount < 3 ) {
	$unit = $response[$count];
	//DOES THIS HAVE A PHOTO?
	if (isset($unit->{"Photo"}) && strlen(trim($unit->{"Photo"})) > 0  && trim($unit->{"SectionNum"}) == 1 ){

			array_push($selectlist, $unit);
			$addcount = $addcount + 1;
	}
	else{
	
	}
	
	$count = $count + 1;
	
	//incase of weird endless loop if data stream gets bad input
	if ($count > 20){
		break;
	}
	
}


for ($i = 0; $i < 3; $i++) {


  $unit = $selectlist[$i];

 
  $dtitle = ""; 
  if( isset($unit->{"AcademicTitle"}) && strlen( trim($unit->{"AcademicTitle"} ) ) > 0 ) {
		$dtitle = $unit->{"AcademicTitle"} ;
  }
  	
  if( isset($unit->{"Title"}) && strlen( trim($unit->{"Title"} ) ) > 0 ) {
	$dtitle = $unit->{"Title"}  ;
  }
  	
  	
  	
  	
 
  	
	$description = "";
 
  	if( isset($unit->{"Description"}) && strlen( trim($unit->{"Description"} ) ) > 0 ) {
		$description = "<p>" . $unit->{"Description"} . "</p>" ;
  	}
?>  
<li>	
<div class="">  	
<?php  	
  	 echo "<img alt=\"\" class=\"sm-image\" width=\"128\" typeof=\"foaf:Image\" src=\"https://drupalish.ahc.umn.edu/bios/show-image.php?i=" . $unit->{"Photo"} . "\">";

	$name = "";
  	if($unit->{"DisplayName"} === NULL) {
    	$name = $unit->{"FirstName"} . " " . $unit->{"LastName"} ;
  	}else{
    	$name = $unit->{"DisplayName"} ;
  	}
  
  	$cred = "";
  	if($unit->{"DisplayCred"} === NULL) {
  	
  		if (strlen(trim($unit->{"Credentials"})) > 0){
    		$cred = ", " . $unit->{"Credentials"};
    	}
  	}else{
  		if (strlen(trim($unit->{"DisplayCred"})) > 0){
    		$cred =  ", " .$unit->{"DisplayCred"};
    	}
  	}


	if ( isset($unit->{"BioURL"}) &&  strlen(trim($unit->{"BioURL"})) > 0 ){
		$linkedname = "<a href=\"" . $unit->{"BioURL"}  . "\">" . $name . $cred . "</a>";
	}else{
   		$linkedname = "<a href=\"/bio/" . $listcode . "/" . $unit->{"UrlName"}  . "\">" . $name . $cred . "</a>";
	}
		
	$unlinkedname = $name . $cred;

   if ( isset($unit->{"LinkBio"} ) && $unit->{"LinkBio"} > 0 ){
  		print "<h5>" . $linkedname  . "</h5>";
   }else{
   	 	print "<h5>" . $unlinkedname  . "</h5>";
   }
   
 ?>  	
<h4><?php echo $dtitle; ?></h4>
 
</div>		
<?php
  echo "</li>";

  //close off the last section
  if ($i == $totalNumberOfObjects-1) { echo "</ul> "; }

}
?>
</div>
<!-- END STAFF LIST -->