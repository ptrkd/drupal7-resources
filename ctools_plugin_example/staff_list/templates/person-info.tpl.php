<?php
		//Find correct source for person information
	$phone = ""; 
  	$email = "";
  	$office_address = "";
  	$email_without_br = "";
  	
  	if( $p->DisplayEmail === NULL || strlen($p->DisplayEmail) == 0 ) {
  		if ( strlen($p->Email) > 0 )
    		$email = "<a class='stafflist-email' href=\"mailto:" . $p->Email . "\">" . $p->Email . "</a>";  
  	}else{
  		if ( strlen($p->DisplayEmail) > 0 )
    		$email = "<a class='stafflist-email' href=\"mailto:" . $p->DisplayEmail . "\">" . $p->DisplayEmail . "</a>"; 
  	}

  	if( $p->DisplayPhone === NULL || strlen( $p->DisplayPhone ) == 0 ) { 
    	if( isset($p->OfficePhone) && strlen(trim($p->OfficePhone)) > 0 ) {
			$phone = $p->OfficePhone;
  		}
  	}else{
    	if( isset($p->DisplayPhone) && strlen(trim( $p->DisplayPhone )) > 0 ) {
		$phone =  $p->DisplayPhone;
  		}
  	}
  	
  	$email_without_br = $email;
  	
  	if( isset($phone) && strlen( trim( $phone ) ) > 0 ) {
  		$email = "<br>" . $email;	
  	}
  	
  	if( isset($p->OfficeAddress) && strlen( trim($p->OfficeAddress ) ) > 0 ) {
		$office_address = $p->OfficeAddress;
  	}
  	
  	
  	$dtitle = ""; 
  	$plaintitle = "";
  	if( isset($p->Title) && strlen( trim($p->Title ) ) > 0  ) {
		$dtitle = "<br><span class='stafflist-title'>" . $p->Title . "</span>" ;
		$plaintitle = $p->Title;
  	}else if( isset($p->AcademicTitle) && strlen( trim($p->AcademicTitle ) ) > 0  ) {
		$dtitle = "<br><span class='stafflist-title'>" . $p->AcademicTitle . "</span>" ;
		$plaintitle = $p->AcademicTitle;
  	}
  	
  	$description = "";
 
  	if( isset($p->Description) && strlen( trim($p->Description ) ) > 0 ) {
		$description = "<p>" . $p->Description . "</p>" ;
  	}
    
    $showorg = 0;
    
  	$porg = ""; 
  	if( isset($p->PrimaryOrganization) && strlen( trim($p->PrimaryOrganization ) ) > 0 ) {
		$porg = "<p class=\"primary-organization\">" . prettifyOrgName($p->PrimaryOrganization) . "</p>" ;
	
  	}
 
 	$name = "";
  	if($p->DisplayName === NULL  || strlen($p->DisplayName) == 0 ) {
    	$name = $p->FirstName . " " . $p->LastName ;
  	}else{
    	$name = $p->DisplayName ;
  	}
    
    $namedivid = str_replace(" ","-",$name);
    
  	$cred = "";
  	if($p->DisplayCred === NULL) {
  	
  		if (strlen(trim($p->Credentials)) > 0){
    		$cred = ", " . $p->Credentials;
    	}
  	}else{
  		if (strlen(trim($p->DisplayCred)) > 0){
    		$cred =  ", " .$p->DisplayCred;
    	}
  	}

    $readmorelink = "";
	if ( isset($p->BioURL) &&  strlen(trim($p->BioURL)) > 0 ){
		$linkedname = "<a href=\"" . $p->BioURL  . "\">" . $name . $cred . "</a>";
		$readmorelink = "<a href=\"" . $p->BioURL  . "\" class=\"read-more-link\">Full bio</a>";
	}else{  
   		$linkedname = "<a href=\"/bio/" . $listcode . "/" . $p->UrlName  . "\">" . $name . $cred . "</a>";
   		$readmorelink = "<a href=\"/bio/" . $listcode . "/" . $p->UrlName  . "\" class=\"read-more-link\">Full bio</a>";
	}
		
	$unlinkedname = $name . $cred;

   if ( isset($p->LinkBio ) && $p->LinkBio > 0 ){
  		$name = $linkedname;
  		 
   }else{
   	 	$name =  $unlinkedname;
   	 	$readmorelink = "";
   }

	$departmenthead_class = "";
	
	if (  in_array($p->x500, $heads) && $hidehead == "Y"){
		$departmenthead_class = "department-head";		
	}

?>	
