<?php

$plugin = array(
  'single' => TRUE,
  'title' => t('Staff list'),
  'description' => t('Shows a Staff List from the Bios system with configuration options.'),
  'category' => t('Custom'),
  'edit form' => 'staff_list_edit_form',
  'render callback' => 'staff_list_render',
  'admin info' => 'staff_list_admin_info',
  'defaults' => array(
    'list_to_show' => 5,
    'number_of_columns' => 1,
    'section' => 0,
    'searchbox' => 0,
    'a_to_z' => 0,
    'show_photo' => 1,
    'show_title' => 1,
    'show_org' => 0,
    'show_email' => 1,
    'show_phone' => 1,
    'show_office_address' => 0,
    'show_description' => 1,
    'head_x500_1' => '',
    'head_title_1' => '',
    'hide_head_1' => 0,
    'head_x500_2' => '',
    'head_title_2' => '',
    'single_az' => 0,
    'node_id' => 0,
    'node_alias' => 0,
    'site_url' =>0,
  )

);

/**
 * 'admin info' callback for panel pane.
 */
function staff_list_admin_info($subtype, $conf, $contexts) {
  if (!empty($conf)) {
    $block = new stdClass;
    $block->title = $conf['override_title'] ? $conf['override_title_text'] : '';
    $block->content = t('Showing @list_to_show tweets.', array(
      '@list_to_show' => $conf['list_to_show'],
    ));
    return $block;
  }

}

/**
 * 'Retrieve stafflists from web service and return in the form of associative array.
 *
 * @see staff_list_edit_form
 */
function get_staff_lists($units){
	$response = file_get_contents('https://drupalish.ahc.umn.edu/orgweb/get-stafflists.php');
	$response = json_decode($response);

	$lists = array();
	foreach($response as $row){
		if (in_array($row->parent_id,$units ) || is_null($units) || count($units)== 0 ) {
			$lists[$row->code] = t($row->unitname . ": ". $row->name);
		}
	}

	return $lists;
}

/**
 * 'Edit form' callback for the content type.
 */
function staff_list_edit_form($form, &$form_state) {
  	$conf = $form_state['conf'];

	$form['list_options'] = array(
  		'#type' => 'value',
  		'#value' => get_staff_lists(variable_get('units'))
	);

	$form['list_to_show'] = array(
    	'#title' => t('List to show'),
    	'#description' => t('Select which list you want to appear.'),
    	'#type' => 'select',
    	'#options' => $form['list_options']['#value'],
    	'#default_value' => $conf['list_to_show'],
    	'#required' => TRUE,
  	);

  	$form['number_of_columns'] = array(
    	'#title' => t('Number of Columns'),
    	'#description' => t('Select the max number of columns'),
    	'#type' => 'select',
    	'#options' => drupal_map_assoc(range(1, 3)),
    	'#default_value' => $conf['number_of_columns'],
    	'#required' => TRUE,
  	);

  	$form['section'] = array(
    	'#title' => t('Section'),
    	'#description' => t('Chose a section to show, zero for all.'),
    	'#type' => 'select',
    	'#options' => drupal_map_assoc(range(0, 8)),
    	'#default_value' => $conf['section'],
    	'#required' => TRUE,
  	);

	$form['searchbox'] = array(
    	'#title' => t('Use Search'),
    	'#description' => t('Provides a search box for the list.'),
    	'#type' => 'checkbox',
    	'#default_value' => $conf['searchbox'],
    	'#required' => FALSE,
  	);

	$form['a_to_z'] = array(
    	'#title' => t('A-Z Filter'),
    	'#description' => t('Provides an A to Z filter for the list.'),
    	'#type' => 'checkbox',
    	'#default_value' => $conf['a_to_z'],
    	'#required' => FALSE,
  	);

  	//Photo, Title, Organization, Phone, Email, Description
  	$form['show_photo'] = array(
    	'#title' => t('Show photos'),
    	'#type' => 'checkbox',
    	'#default_value' => $conf['show_photo'],
    	'#required' => FALSE,
  	);

  	$form['show_title'] = array(
    	'#title' => t('Show titles'),
    	'#type' => 'checkbox',
    	'#default_value' => $conf['show_title'],
    	'#required' => FALSE,
  	);

  	$form['show_org'] = array(
    	'#title' => t('Show organization'),
    	'#type' => 'checkbox',
    	'#default_value' => $conf['show_org'],
    	'#required' => FALSE,
  	);

  	$form['show_email'] = array(
    	'#title' => t('Show email'),
    	'#type' => 'checkbox',
    	'#default_value' => $conf['show_email'],
    	'#required' => FALSE,
  	);

  	$form['show_phone'] = array(
    	'#title' => t('Show phone'),
    	'#type' => 'checkbox',
    	'#default_value' => $conf['show_phone'],
    	'#required' => FALSE,
  	);

  	$form['show_office_address'] = array(
    	'#title' => t('Show office address'),
    	'#type' => 'checkbox',
    	'#default_value' => $conf['show_office_address'],
    	'#required' => FALSE,
  	);

  	$form['show_description'] = array(
    	'#title' => t('Show description'),
    	'#type' => 'checkbox',
    	'#default_value' => $conf['show_description'],
    	'#required' => FALSE,
  	);

  	$form['head_x500_1'] = array(
    	'#title' => t('Head x500 #1'),
    	'#description' => t('The x500 of the callout head.'),
    	'#type' => 'textfield',
    	'#default_value' => $conf['head_x500_1'],
    	'#required' => FALSE,
  	);

  	$form['head_title_1'] = array(
    	'#title' => t('Head Title #1'),
    	'#description' => t('The title for the callout head.'),
    	'#type' => 'textfield',
    	'#default_value' => $conf['head_title_1'],
    	'#required' => FALSE,
  	);

  	$form['hide_head_1'] = array(
    	'#title' => t('Hide Head(s)'),
    	'#description' => t('Hide head(s) on list. This is to prevent when a list has a head near the top making the callout information duplicated.'),
    	'#type' => 'checkbox',
    	'#default_value' => $conf['hide_head_1'],
    	'#required' => FALSE,
  	);


  	$form['head_x500_2'] = array(
    	'#title' => t('Head x500 #2'),
    	'#description' => t('The x500 of the second callout head -- if there is shared leadership.'),
    	'#type' => 'textfield',
    	'#default_value' => $conf['head_x500_2'],
    	'#required' => FALSE,
  	);

  	$form['head_title_2'] = array(
    	'#title' => t('Head Title #2'),
    	'#description' => t('The title for the callout head.'),
    	'#type' => 'textfield',
    	'#default_value' => $conf['head_title_2'],
    	'#required' => FALSE,
  	);

  	$form['single_az'] = array(
    	'#title' => t('Compress groups to single A-Z list'),
    	'#description' => t('This allows a list with groups to be displayed as a single ungrouped A-Z list'),
    	'#type' => 'checkbox',
    	'#default_value' => $conf['single_az'],
    	'#required' => FALSE,
  	);


  	//Get node id from ajax url
  	//Example url: panels/ajax/editor/edit-pane/panelizer:node:168:page_manager:1984/4003
  	$path_args = explode(':', current_path());
	$path_args[2];

  	$form['node_id'] = array('#type' => 'value', '#value' => $path_args[2]);

  	$form['node_alias'] = array('#type' => 'value', '#value' =>  drupal_get_path_alias('node/' . $path_args[2] ) );
    $form['site_url'] = array('#type' => 'value', '#value' =>  $_SERVER['SERVER_NAME']);
  return $form;
}

/**
 * Validation Handler
 */
function staff_list_edit_form_validation($form, &$form_state) {
	//Do validation
	//validate x500s

}

/**
 * The submit form stores the data in $conf.
 */
function staff_list_edit_form_submit($form, &$form_state) {
	//drupal_set_message(t('<pre>'.print_r($form_state['values'],1) . '</pre>'));

  	foreach (array_keys($form_state['plugin']['defaults']) as $key) {
    	if (isset($form_state['values'][$key])) {

      		$form_state['conf'][$key] = $form_state['values'][$key];

   		}
  	}


  	$url = "https://drupalish.ahc.umn.edu/orgweb/save-list-placement.php";
	$content = json_encode($form_state['conf']);

	$curl = curl_init($url);
	curl_setopt($curl, CURLOPT_HEADER, false);
	curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
	curl_setopt($curl, CURLOPT_HTTPHEADER,
        array("Content-type: application/json"));
	curl_setopt($curl, CURLOPT_POST, true);
	curl_setopt($curl, CURLOPT_POSTFIELDS, $content);

	$json_response = curl_exec($curl);

	$status = curl_getinfo($curl, CURLINFO_HTTP_CODE);

	if ( $status != 201 ) {
	    drupal_set_message(t("Error: call to URL $url failed with status $status, response $json_response, curl_error " . curl_error($curl) . ", curl_errno " . curl_errno($curl) ));
	}


	curl_close($curl);

	$response = json_decode($json_response, true);

}

/**
 * Run-time rendering of the body of the block (content type)
 * See ctools_plugin_examples for more advanced info
 */
function staff_list_render($subtype, $conf, $panel_args, $context = NULL) {
  $block = new stdClass();

  // initial content is blank
  $block->title = '';
  $block->content = '';

	//initialize show 'switches' to negative -- they will be turned on if requested
	$showfields = array('N','N','N','N','N','N','N');
  	$headtitle = '';
  	$headtitle2 = '';
  	$hidehead = '';
  	$single_az = 'N';

  	if ( isset($conf['section']) ){
		$section = $conf['section'];
	}else{
		$section = 0;
	}

  	if ( strlen( trim( $conf['head_x500_1'] ) ) > 1 ){
		$head = $conf['head_x500_1'];
	}else{
		$head = 'none';
	}

	if ( strlen( trim( $conf['head_title_1'] ) ) > 1 ){
		$headtitle = $conf['head_title_1'];
	}

	if (isset($conf['hide_head_1']) && $conf['hide_head_1'] == 1 ){
		$hidehead = 'Y';
	}else{
		$hidehead = 'N';
	}

	if ( strlen( trim( $conf['head_x500_2'] ) ) > 1 ){
		$head2 = $conf['head_x500_2'];
	}else{
		$head2 = 'none';
	}

	if ( strlen( trim( $conf['head_title_2'] ) ) > 1 ){
		$headtitle2 = $conf['head_title_2'];
	}

	if (isset($conf['searchbox']) && $conf['searchbox'] == 1){
		$usesearch = 'Y';
	}else{
		$usesearch = 'N';
	}

	if (isset($conf['a_to_z']) && $conf['a_to_z'] == 1){
		$azfilter = 'Y';
	}else{
		$azfilter = 'N';
	}

	if (isset($conf['show_photo']) && $conf['show_photo'] == 1){
		$showfields[6] = 'Y';
	}

	if (isset($conf['show_title']) && $conf['show_title'] == 1){
		$showfields[0] = 'Y';
	}

	if (isset($conf['show_org']) && $conf['show_org'] == 1){
		$showfields[1] = 'Y';
	}

	if (isset($conf['show_email']) && $conf['show_phone'] == 1){
		$showfields[2] = 'Y';
	}

	if (isset($conf['show_phone']) && $conf['show_phone'] == 1){
		$showfields[3] = 'Y';
	}

	if (isset($conf['show_description']) && $conf['show_description'] == 1){
		$showfields[4] = 'Y';
	}

	if (isset($conf['show_office_address']) && $conf['show_office_address'] == 1){
		$showfields[5] = 'Y';
	}

	if (isset($conf['show_photo']) && $conf['show_photo'] == 1){
		$showfields[6] = 'Y';
	}

	if (isset($conf['single_az']) && $conf['single_az'] == 1){
		$single_az = 'Y';
	}



	if (isset($conf['number_of_columns']) && is_numeric( $conf['number_of_columns'] ) ){
		$numberofcolumns = $conf['number_of_columns'];
	}else{
		$numberofcolumns = 1;
	}
	$showfieldcode = implode($showfields);

	if (isset($conf['list_to_show']) && strlen($conf['list_to_show'] ) > 0 ){
		$listcode = $conf['list_to_show'];
		$title = "Nothing";

		$block->content .= staff_list_template_v3($conf['list_to_show'], $section, "None", $head, $headtitle, $hidehead, $head2, $headtitle2, $usesearch, $azfilter, $showfieldcode, $numberofcolumns, $single_az );
	}
	else{
		$block->content .= "Mistake! " . $conf['list_to_show'];
	}



  return $block;
}

function staff_list_template_v3(
	$listcode = 0,
	$section = 0,
	$title = "Pathology Faculty",
	$head = 'none',
	$headtitle = 'none',
	$hidehead = 'N',
	$head2 = 'none',
	$headtitle2 = 'none',
	$usesearch = 'Y',
	$azfilter = 'N',
	$showfields = 'YYYYYN',
	$numberofcolumns = 1,
	$single_az = 'N' ) {

 	//Show Fields is five character string either Y or N for Title, Organization, Phone, Email, Description


 	if (strpos( $_SERVER["REQUEST_URI"], 'panelizer') ) {
 		//DO NOT INCLUDE BOOTSTRAP and other CSS ON ADMIN PAGES
 	}else{

 		drupal_add_css(drupal_get_path('module', 'staff_list') . '/css/bootstrap.min.css');
 		drupal_add_css(drupal_get_path('module', 'staff_list') . '/css/stafflist.css');
 		drupal_add_css('https://fonts.googleapis.com/css?family=Titillium+Web:400,700', array('type' => 'external'));
 	}

	if ($numberofcolumns > 1){
		//includes javascript to resize listings to the size of the largest, which allows consistancy for div grid system
		drupal_add_js(drupal_get_path('module', 'staff_list') . '/js/stafflist-v3-with-listing-resize.js');
	}else{
		drupal_add_js(drupal_get_path('module', 'staff_list') . '/js/stafflist-v3.js');
	}

  $theme_name = 'stafflist-v3';

  if ($single_az == 'Y') {
  	$theme_name = 'stafflist-az';
  }


   	$output = theme( $theme_name, array('title' => $title, 'listcode' => $listcode, 'section' => $section, 'head' => $head, 'headtitle' => $headtitle, 'hidehead' => $hidehead,  'head2' => $head2, 'headtitle2' => $headtitle2, 'usesearch' => $usesearch, 'azfilter' =>  $azfilter, 'showfields' => $showfields, 'numberofcolumns' => $numberofcolumns ) );

  return $output;
}
