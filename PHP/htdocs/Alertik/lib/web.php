<?php
/*
	v0.12
	theme($templates,$vars,$theme_folder_array)

		This function keep all variable active in all templates processing for current call.
		if any precedent theme set any variable it also could used by following theme files.
		In such cases its also possible to set correct order of theme output merge using integer index for theme array.
		Do not use underscore at the begining of variables which will be used in theme files.

		$templates
			Template files name in variable or array
			For arrays its possible to set merge order using integer index for each element
			Any name bigger than 30 char is function
		$vars
			Variable names to be replaced in templates
		$theme_folder_array
			Set current theme default is theme folder
		example
			$vars['title']='SMS Panel';
			echo theme(array(1=>'index',0=>'head',2=>'foot'),$vars);
			echo theme(array('head','index','foot'),$vars,$theme_folder);
	theme_exist()
		return theme file path or null
	is_ajax()
		return true if request is ajax

	html_write($filename,$content,$compress=true)

	compress_html()
	compress_css()
	jsonify(array)
	json_write($filename,$array)
	file_write($filename,$content)
	no_cache()
		HTTP no-cache header

	folder_create($filename)
		Create folder for the given file

	redirect($url,$response=null)
		on ajax return redirect to js

	set_header(404)
		Set page header

	set_locale(locale,locale_folder)

*/
function theme($_themes,$_theme_vars=null,$_theme_folder=['theme'],$_default_theme_folder=''){
	/*	Support old version 	*/
	if (!is_array($_theme_folder)) {
		$tmp[]=$_theme_folder;
		$_theme_folder=$tmp;
		if ($_default_theme_folder) $_theme_folder[]=$_default_theme_folder;
	}
	if (defined(THEME_FOLDER)) $theme_folder[]=THEME_FOLDER;
	if (!is_array($_themes)) {
		$tmp[]=$_themes;
		$_themes=$tmp;
	}
	if (is_array($_theme_vars)) extract($_theme_vars);
	foreach ($_themes as $_theme_index => $_theme_file){
		/*		This is a HTML code		*/
		if (strlen($_theme_file)>100){
			$_theme_output[$_theme_index]=$_theme_file;
			continue;
		}
		if ($_theme_path=theme_exist($_theme_file,$_theme_folder)){
			ob_start();
			require $_theme_path;
			$_theme_output[$_theme_index]=ob_get_contents();
			ob_end_clean();
		} else {
			/*		Theme file not found		*/
			$_theme_output[$_theme_index]='<!-- Theme "'.print_r($_theme_folder,true).'/'.$_theme_file.'" not found! -->';
		}
	}
	if (is_array($_theme_output)) {
		ksort($_theme_output);
		return implode($_theme_output);
	}
}
function theme_exist($file,$folders = [THEME_FOLDER]){
	foreach($folders as $folder){
		$theme='../'.$folder.'/'.$file.'.tpl.php';
		if (file_exists($theme)) return $theme;
	}
	return;
}
function is_ajax(){
	if(!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {
		return true;
	}
}
function html_write($filename,$content,$compress=true){
	if ($compress==true) $content=compress_html($content);
	file_write($filename,$content);
}
function compress_html($content){
	/* Extra spaces */
	$content=preg_replace('~>\s+<~', '><', $content);
	/* Extra " remove */
	$content=preg_replace('/([a-zA-Z0-9_]+=)"([a-zA-Z0-9_]+)"/', '$1$2', $content);
	$content=preg_replace("/([a-zA-Z0-9_]+=)'([a-zA-Z0-9_]+)'/", '$1$2', $content);
	/* Optional properties and closing tags*/
	return str_replace([
			'type="text"','method="get"','="disabled"','="selected"','http:','type="text/css"','type="text/javascript"',
			'</td>','</tr>','</li>','</option>','</html>','</head>','</body>'
		], '', $content);
	// Single atributes
}
function compress_css($css){
	$css = preg_replace('#/\*.*?\*/#s', '', $css);
	$css = preg_replace('/\s*([{}|:;,])\s+/', '$1', $css);
	$css = preg_replace('/\s\s+(.*)/', '$1', $css);
	return str_replace(';}', '}', $css);
}
/*
	size-diff: 	only if size is different
*/
function file_write($filename,$content,$options=[]){
	global $root;
	if ($root&&!strstr($filename,$root)) $filename=$root.$filename;
	folder_create($filename);
	if ($options['size-diff']&&filesize($filename)==strlen($content)) return;
	file_put_contents($filename.'.tmp',$content);
	rename($filename.'.tmp',$filename); // Atomic Update
}
function json_write($filename,$array,$options=[]){
	file_write($filename,jsonify($array),$options);
}
function jsonify($array){
	return json_encode($array,JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
}
function no_cache(){
	header("Cache-Control: no-store, no-cache, must-revalidate");
	header("Cache-Control: post-check=0, pre-check=0", false);
	header("Pragma: no-cache");
}
function folder_create($file) {
	$parts=explode('/',$file);
	$file=array_pop($parts);
	if (is_dir(implode('/',$parts))) return;
	foreach($parts as $part){
		if (!is_dir($dir.=$part)) {
			if ($dir) if (!mkdir($dir)) echo 'Creating folder "'.$dir.'" of "'.$file.'" failed.';
		}
		$dir.='/';
	}
}
function redirect($url,$method=''){
	if (is_ajax()) echo json_encoder(array('redirect'=>$url));
	else location_redirect($url,$method);
	exit;
}
function location_redirect($location=null,$method=''){
	if ($method==301) header('HTTP/1.1 301 Moved Permanently');
	header("Location: ".$location);
}
function set_header($status=404){
	if ($status==404) header("Status: 404 Not Found");
}
function set_locale($locale='en_US',$locale_dir='../translations/'){
	if (!$locale) $locale='en_US';
	putenv("LANGUAGE=$locale");
	putenv("LC_ALL=$locale");
	setlocale(LC_ALL, $locale);
	setlocale(LC_NUMERIC, 'en_US');
	bindtextdomain('messages', $locale_dir);
	textdomain('messages');
}
