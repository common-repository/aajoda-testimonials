<?php

/*

Plugin Name: Aajoda Testimonials

Plugin URI: https://www.aajoda.com

Description: Integrate Aajoda Testimonials on your website.

Version: 2.1.1

Author: Aajoda

Author URI: https://www.aajoda.com

License: GPL2

Usage: [aajoda id="xxxxxxx"]

*/

/* Enable internationalisation */

global $ata;

global $scriptAdded;
$scriptAdded=false;

load_plugin_textdomain( 'aajodatestimonials', false, dirname( plugin_basename( __FILE__ ) ) . '/lang/');
add_action('admin_menu', 'aajodatestimonials_admin_actions');
	
function aajodatestimonials_admin_actions() {
	add_menu_page('Aajoda', 'Aajoda', 'manage_options', 'aajoda-testimonials', 'aajodatestimonials_admin', WP_PLUGIN_URL . "/". plugin_basename( dirname( __FILE__ ) ).'/img/aajodatestimonials-icon.png');
}

function aajodatestimonials_admin() {
    if ( !current_user_can('manage_options') )

    	wp_die( __('You do not have sufficient permissions to access this page.','aajodatestimonials') );

	include('aajoda-testimonials-admin.php');
}

add_action('wp_head', 'aajodatestimonials_load_script' );

function aajodatestimonials_load_script () {	
	$code = get_option( 'aajodatestimonials_code');

	if( !empty( $code ) ) {
		echo "\n" . html_entity_decode( $code );
	}
}

/* used from plugin-admin */
function aajodasanitize_data( $code="" ) {

	if ( !function_exists( 'wp_kses' ) ) {
		require_once( ABSPATH . 'wp-includes/kses.php' );
	}

	global $allowedposttags;
	global $allowedprotocols;

	if ( is_string( $code ) ) {

		$code = htmlentities( stripslashes( $code ), ENT_QUOTES, 'UTF-8' );

	}
	$code = wp_kses( $code, $allowedposttags, $allowedprotocols );

	return $code ;
}

function add_aajoda_scripts() {
	$version = get_option( 'aajoda_version' );
	
	if($version==="2.0"){
		wp_register_script('aajoda_refsv20','https://az666548.vo.msecnd.net/misc/Aajoda_refsv2_0.min.js',null,null,false);
	}else{
		wp_register_script('aajoda_refsv20','https://az666548.vo.msecnd.net/misc/Aajoda_refsv2_1.min.js',null,null,false);
	}
	wp_enqueue_script( 'aajoda_refsv20' );
}	
add_action('wp_enqueue_scripts', 'add_aajoda_scripts');

function getUrl($atts, $map=false){
	$output=wp_kses_post($atts[ 'version' ]);
	$output.= $map?'/coords/':'/items/';
	$output.=wp_kses_post($atts['company']).'/';
	$output.=wp_kses_post($atts['type']).'/';
	$output.=$map?"1000":wp_kses_post($atts['count']);
	$output.='?ei='.wp_kses_post($atts['includes']);
	
	if($atts['hashtags']!=''){
		$output.='\u0026fiha='.wp_kses_post( $atts[ 'hashtags' ] );
	}

	if($atts['aajodas']!=''){
		$output.='\u0026fiwi='.wp_kses_post( $atts[ 'aajodas' ] );
	}
	
	if($atts['campaigncodes']!=''){
		$output.='\u0026fica='.wp_kses_post( $atts[ 'campaigncodes' ] );
	}
	
	if($map)
		return $output;
	/* return map */

	if($atts[ 'size' ]){
		$output.='\u0026sz='.wp_kses_post( $atts[ 'size' ] )  ;
	}
	
	if($atts[ 'backgroundcolor' ]){
		$output.='\u0026bgc='.wp_kses_post( $atts[ 'backgroundcolor' ] )  ;
	}
	
	if($atts[ 'fontcolor' ]){
		$output.='\u0026fc='.wp_kses_post( $atts[ 'fontcolor' ] )  ;
	}
	
	if($atts[ 'linkcolor' ]){
		$output.='\u0026lc='.wp_kses_post( $atts[ 'linkcolor' ] )  ;
	}
	
	if($atts[ 'shadowcolor' ]){
		$output.='\u0026shc='.wp_kses_post( $atts[ 'shadowcolor' ] )  ;
	}
	
	if($atts[ 'shadowwidth' ]){
		$output.='\u0026shw='.wp_kses_post( $atts[ 'shadowwidth' ] )  ;
	}
	
	if($atts[ 'bordercolor' ]){
		$output.='\u0026bc='.wp_kses_post( $atts[ 'bordercolor' ] )  ;
	}
	
	if($atts[ 'borderwidth' ]){
		$output.='\u0026bw='.wp_kses_post( $atts[ 'borderwidth' ] )  ;
	}
	
	if($atts[ 'borderradius' ]){
		$output.='\u0026br='.wp_kses_post( $atts[ 'borderradius' ] )  ;
	}
	
	if($atts[ 'hovercolor' ]){
		$output.='\u0026hc='.wp_kses_post( $atts[ 'hovercolor' ] )  ;
	}
	if($atts[ 'baseurl' ]){
		$output.='\u0026bu='.wp_kses_post( $atts[ 'baseurl' ] )  ;
	}
	return $output;
}

/* aajoda tab shortcode */
function aajoda_tab_shortcode($atts){

	global $ata ;
	/*print_r("Tab");  */

	$aajoda_tab_atts = shortcode_atts(array(

		/* changes to parameters will lead to breaking changes */

        'id' => $ata['id'],
		'version' =>$ata['version'],
        'company' => $ata['company'],
        'type' => $ata['type'],
		'count' =>$ata['count'],
		'hashtags' =>$ata['hashtags'],
		'aajodas' =>$ata['aajodas'],
		'campaigncodes'=>$ata['campaigncodes'],
		'includes' =>$ata['includes'],
		'size' => $ata['size'],
		'backgroundcolor' => $ata['backgroundcolor'],
		'fontcolor' => $ata['fontcolor'],
		'linkcolor' => $ata['linkcolor'],
		'shadowcolor' => $ata['shadowcolor'],
		'shadowwidth' => $ata['shadowwidth'],
		'bordercolor'=>$ata['bordercolor'],
		'borderwidth'=>$ata['borderwidth'],
		'borderradius'=>$ata['borderradius'],
		'hovercolor'=>$ata['hovercolor'],
		'baseurl'=>$ata['baseurl'],
		'tabreload'=>$ata['tabreload'],
		'map'=>$ata['map'],
		'tabtext'=>'',
		'tabfilter'=>'', 
		'tabactive'=>'0'		
    ), $atts);

	/*	print_r($aajoda_tab_atts["tabtext"]); */

	if($aajoda_tab_atts["tabtext"]==""){
		return "";
	}
   
	$output="<button ";
	if($aajoda_tab_atts["tabactive"]=="1"){
		$output.=" class='active'";
	}

	$output.=" onclick=\"";
	$output.="aajodaFilter('".remove_accents($aajoda_tab_atts["hashtags"])."','".$aajoda_tab_atts["id"]."'";
	if($aajoda_tab_atts["tabreload"]=="1"){
		$output.=",'".getUrl($aajoda_tab_atts)."'";
	}
	$output.=");";

	if($aajoda_tab_atts["map"]=="1"){
		if($aajoda_tab_atts["tabreload"]!="1"){
				$output.=",null,";
		}
		$output.="aajoda_mapCoords('".getUrl($aajoda_tab_atts,true)."');";
	}

	/*
		$url=getUrl($aajoda_tab_atts);
		$output.="aajoda_refs_get('aajoda_refs_div".$aajoda_tab_atts["id"]."','".$url."');";
	*/

	$output.="return false;";
	$output.="\">".$aajoda_tab_atts["tabtext"]."</button>";
   	
   	return $output;
}

/* aajoda tabs shortcode */
function aajoda_tabs_shortcode( $atts ,$content = ''){
	global $ata;
	$aajoda_tabs_atts = shortcode_atts(array(
		/* changes to parameters will lead to breaking changes */
        'id' => '',
		'version' =>'1.0',
        'company' => '',
        'type' => '63',
		'count' =>'99',   
		'hashtags' =>'',
		'aajodas' =>'',
		'campaigncodes'=>'',
		'includes' =>'11',
		'size' => '',
		'backgroundcolor' => '',
		'fontcolor' => '',
		'linkcolor' => '',
		'shadowcolor' => '',
		'shadowwidth' => '',
		'bordercolor'=>'',
		'borderwidth'=>'',
		'borderradius'=>'',
		'hovercolor'=>'',
        'baseurl'=>'',
		'tabreload'=>'1',
		'map'=>''
    ), $atts);

	$ata=$aajoda_tabs_atts;

	$output= "<div id='aajoda_filter_div".$aajoda_tabs_atts["id"]."' class='aajodasfilter aajoda-tabs-default'>";

	/* nested here */
	$output.=do_shortcode($content);
	$output.="</div>";

	/* print_r(htmlspecialchars($output));   */
	return $output; 
}

/* aajoda map shortcode */
function aajoda_map_shortcode($atts) {
	$aajoda_map_atts = shortcode_atts(array(
		/* changes to parameters will lead to breaking changes */
        'id' => '',
		'version' =>'1.0',
        'company' => '',
        'type' => '63',
		'count' =>'1000',   
		'hashtags' =>'',
		'aajodas' =>'',
		'campaigncodes'=>'',
			/*		'includes' =>'11', */
		'style'=>'height:300px; width: 100%;',
		'mapkey'=>''
    ), $atts);

	/*
		AIzaSyCXoVFC6cqK2tYyLQtCTauK7H1PlKZYX3M&

		print_r("id: ".$aajoda_tabs_atts["id"]);
		print_r("bla: ".$bla);
	*/

	if($aajoda_map_atts["company"]==""){
		return "missing company parameter.";
	}
	/*
		if($aajoda_map_atts["mapkey"]==""){
			return "missing mapkey parameter.";
		}
	*/
 	wp_register_script('ajje-map-script','https://maps.googleapis.com/maps/api/js?callback=initAajodaMap&key='.$aajoda_map_atts["mapkey"],null,null,true);

	//$output= "<script>";

	$url=getUrl($aajoda_map_atts,true);
	
	/*
	print_r("url1: ".$url);
	$url=str_replace("/items","/coords",$url);
	print_r("url2: ".$url);
	*/

	//   $script= "var aajodaMap;";
	//   $script.= "var markers = [];";
	$script.= "function initAajodaMap(){";
	$script.= "aajoda_mapCoords('".$url."');}";
    //$output+= "</script>";
	/* .$aajoda_tabs_atts["id"]."' class='aajodasfilter aajoda-tabs-default'>"; */

	//wp_add_inline_script("ajje-map-script",$script,"before");
	wp_enqueue_script( 'ajje-map-script' );
	/* print_r(htmlspecialchars($output));   */

	$script="<script>".$script."</script>";
	
	return $script.'<div id="aajodaMap" style="'.$aajoda_map_atts["style"].'"></div>'; 
}

/* aajoda feed shortcode */
function aajoda_feed_shortcode( $atts ) {

	$output="";
   	$aajoda_feed_atts = shortcode_atts( array(

		/* changes to parameters will lead to breaking changes */

        'id' => '',
		'version' =>'1.0',
        'company' => '',
        'type' => '63',
		'number' =>'',  /*legacy support*/
		'count' =>'99',  
		'hashtags' =>'',
		'tag'=>'',	    /*legacy support*/
		'aajodas' =>'',
		'campaigncodes'=>'',
		'includes' =>'11',
		'size' => '',
		'backgroundcolor' => '',
		'fontcolor' => '',
		'linkcolor' => '',
		'shadowcolor' => '',
		'shadowwidth' => '',
		'bordercolor'=>'',
		'borderwidth'=>'',
		'borderradius'=>'',
		'hovercolor'=>'',		
        'baseurl'=>''	
    ), $atts );
	
	if($aajoda_feed_atts['company']==""){
		return "Parameter company is required!";
	}

	/*legacy support*/
	if($aajoda_feed_atts['tag']!=""){
		$aajoda_feed_atts['hashtags']=$aajoda_feed_atts['tag'];
	}
	if($aajoda_feed_atts['number']!=""){
		$aajoda_feed_atts['count']=$aajoda_feed_atts['number'];
	}
	/*end legacy support*/

	$divId=$aajoda_feed_atts['id'];
	if($divId==""){
		$divId=(string)rand(100000,1000000);
	}
	
	//print_r(htmlspecialchars("script aajdoaRefs"));
	$output.='<div id="aajoda_refs_div'.$divId.'"></div>';
	$output.='<script>aajoda_refs_get("aajoda_refs_div'.$divId.'","';
	$output.=getUrl( $aajoda_feed_atts);
	$output.='");</script>';

	/* print_r(htmlspecialchars($output)); */	
		
    return $output;
}

/* aajoda con shortcode */
function aajoda_shortcode( $atts ) {

	 //wp_deregister_script('aajoda_refsv10'); 
	 //wp_enqueue_script( 'aajoda_refsv20' );

	$output="";
	
    $con_atts = shortcode_atts(array('id' => '' ), $atts );
	
	if($con_atts['id']==""){
		return "Parameter id is required!";
	}

	$output .= '<div id="aajoda_div'.$con_atts['id'].'" class="aajoda_con"></div>';
	$output.="<script>aajodaInit()</script>";
	
	 /*print_r(htmlspecialchars("fetching "));*/ 	
		
    return $output;
}

/*
	<div id="aajoda_divca74737e0e9542d4bed8" name="aajoda_con"></div>
    <script src="http://localhost:23483/Scripts/Public/AajodaDEV_refsv2_0.js"></script>
*/

function aajoda_detail_shortcode($atts) {

	$output="";

   	$q=get_query_var("testimonial");

   	if($q){
         /*print_r(htmlspecialchars("enter the aajframe"));*/
		$output="<style>iframe{width:100%}</style>";
		$output.="<iframe id='aajframe' src='"."https://aajoda-staging.azurewebsites.net/mdl/wi/".$q."/' scrolling='no'></iframe>";
		$output.="<script>var initAajframe=function(){console.log('init1');iFrameResize({log:true,heightCalculationMethod : 'max',widthCalculationMethod : 'max'});}; initAajframe();</script>";
   	}
   	return $output;
}

function add_query_vars_filter( $vars ){
  $vars[] = "testimonial";
  return $vars;
}
add_filter( 'query_vars', 'add_query_vars_filter' );


add_shortcode('aajoda-detail', 'aajoda_detail_shortcode');
add_shortcode('aajoda-feed', 'aajoda_feed_shortcode');
add_shortcode('aajoda-tab', 'aajoda_tab_shortcode');
add_shortcode('aajoda-tabs', 'aajoda_tabs_shortcode');
add_shortcode('aajoda-map', 'aajoda_map_shortcode');
add_shortcode('aajoda', 'aajoda_shortcode');

?>