<?php
/*
 * Function to grab last 11 instagram posts into an array
 * @args string $username : your username  on instagram (instagram.com/username)
 * @args float $cache_duration : Cache duration in hours, you can use integer or float eg: 0.5 (30 minutes)
 * @require allow_url_fopen=1
 */
function zkif_get_instagram_feed($username, $cache_duration=1){
	$wp_upload=wp_upload_dir();
	$upload_path=$wp_upload['basedir'].'/zkif-instagram-cache';
	
	if (!file_exists($upload_path)) {
    	mkdir($upload_path, 0755, true);
	}
	
	$img_regex='/thumbnail_resources":\[([^\]]*)/';
	$display_regex='/display_url":"([^"]*)/';
	$text_regex='/text":"([^"]*)/';
	$id_regex='/shortcode":"([^"]*)/';

	$profil_url='https://www.instagram.com/'.$username.'/';
	$cached_file=$upload_path.'/'.$username.'.json';
	
	// FIRST CHECK IF FILE IS NEVER CACHED
	if(!file_exists($cached_file)){
		$need_recache=1;
	}else{
		// IF FILE IS ALREADY CACHED GET IS AGE
		$file_timestamp=filemtime($cached_file);		
		$current_timestamp=time();
		
		$difference_in_seconde=$current_timestamp - $file_timestamp;
		$difference_in_hour=$difference_in_seconde / 60 / 60;
		
		// IF FILE IS OLDER THAN GIVEN CACHE DURATION we need recache else not
		if($difference_in_hour > $cache_duration){
			// More than 2 hours
			$need_recache=1;
		}else{
			// Less than 2 hours
			$need_recache=0;
		}
		
	}
	
	// IF WE DON'T NEED RECACHE WE CAN ONLY RETURN CACHED FILE CONTENT
	if(!$need_recache){
		$instagram_post=file_get_contents($cached_file);
		return json_decode($instagram_post, true);
		
	}else{
		$content=file_get_contents($profil_url);
	
		
		preg_match_all($img_regex, $content, $match);
		
		$instagram_post=array();
		foreach($match[0] as $key=>$post){
			$match[0][$key]='{"'.$match[0][$key].']}';
			$match[0][$key]=json_decode($match[0][$key]);
			$match[0][$key]=$match[0][$key]->thumbnail_resources;
			$instagram_post[$key]['150']=$match[0][$key][0]->src;
			$instagram_post[$key]['240']=$match[0][$key][1]->src;
			$instagram_post[$key]['320']=$match[0][$key][2]->src;
			$instagram_post[$key]['480']=$match[0][$key][3]->src;
			$instagram_post[$key]['640']=$match[0][$key][4]->src;
		}
		
		preg_match_all($display_regex, $content, $match);
		foreach($match[1] as $key=>$url){
			$instagram_post[$key]['full']=$url;	
		}
		
		preg_match_all($text_regex, $content, $match);
		foreach($match[1] as $key=>$text){
			$instagram_post[$key]['text']=$text;	
		}
		preg_match_all($id_regex, $content, $match);
		foreach($match[1] as $key=>$insta_id){
			$instagram_post[$key]['link']='https://www.instagram.com/p/'.$insta_id.'/';	
		}
		
		file_put_contents($cached_file, json_encode($instagram_post));
		
		return $instagram_post;
	}
}