<?php
	include("person-info.tpl.php");
	
	$mtitle = "";
	if ( $headtitle != 'none' ){
		$mtitle = $headtitle;
	}	
?>
<div class="<?php echo $bootstrap_col_span_class; ?>"><div class="featured-bio"><?php if ($show_photo == 'Y'){ ?><div class="featured-img-placeholder"><img src="https://drupalish.ahc.umn.edu/bios/show-image.php?i=<?php echo $p->Photo; ?>"></div><?php } ?><div class="bio-content"><h3><?php echo $mtitle; ?></h3><h5><?php echo $name; ?></h5><span class="stafflist-title" style="color:#333;"><?php  if ($show_title == 'Y'){ echo $plaintitle; } ?></span><span class="primary-organization"><?php if ($show_org == 'Y'){ echo $porg; } ?></span><p><?php  if ($show_phone == 'Y'){ echo $phone; } ?> 
<?php  if ($show_email == 'Y'){ echo $email; } ?><?php if ($show_office == 'Y'){ echo "<br>" .$office_address; } ?></p><p><?php  if ($show_description == 'Y'){ echo $description; } ?></p><p><?php echo $readmorelink; ?></p></div></div></div>

            		 
