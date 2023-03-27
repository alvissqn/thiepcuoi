<?php
/*
 * Các function hỗ trợ
 */

/*
 * Loại bỏ tất cả dấu, ký tự đặc biệt
 */
function vnStrFilter($text, $space="-", $lower=true){
	$text = html_entity_decode(trim($text),ENT_QUOTES,'UTF-8');
	$replace = array(
		'a'=>'á|à|ả|ã|ạ|ă|ắ|ặ|ằ|ẳ|ẵ|â|ấ|ầ|ẩ|ẫ|ậ',
		'd'=>'đ',
		'e'=>'é|è|ẻ|ẽ|ẹ|ê|ế|ề|ể|ễ|ệ',
		'i'=>'í|ì|ỉ|ĩ|ị',
		'o'=>'ó|ò|ỏ|õ|ọ|ô|ố|ồ|ổ|ỗ|ộ|ơ|ớ|ờ|ở|ỡ|ợ',
		'u'=>'ú|ù|ủ|ũ|ụ|ư|ứ|ừ|ử|ữ|ự',
		'y'=>'ý|ỳ|ỷ|ỹ|ỵ',
		'A'=>'Á|À|Ả|Ã|Ạ|Ă|Ắ|Ặ|Ằ|Ẳ|Ẵ|Â|Ấ|Ầ|Ẩ|Ẫ|Ậ',
		'D'=>'Đ',
		'E'=>'É|È|Ẻ|Ẽ|Ẹ|Ê|Ế|Ề|Ể|Ễ|Ệ',
		'I'=>'Í|Ì|Ỉ|Ĩ|Ị',
		'O'=>'Ó|Ò|Ỏ|Õ|Ọ|Ô|Ố|Ồ|Ổ|Ỗ|Ộ|Ơ|Ớ|Ờ|Ở|Ỡ|Ợ',
		'U'=>'Ú|Ù|Ủ|Ũ|Ụ|Ư|Ứ|Ừ|Ử|Ữ|Ự',
		'Y'=>'Ý|Ỳ|Ỷ|Ỹ|Ỵ',
		' ' => '[^a-z0-9]'
	);
	foreach($replace as $to=>$from){
		$text = preg_replace("/($from)/i", $to, $text);
	}
	$text=trim($text);
	$text=str_replace(" ", $space, $text);
	while( strpos($text, "--")!==false ){
		$text = str_replace("--", "-", $text);
	}
	if($lower){ $text=strtolower($text); }
	return $text;
}

/*
 * Chuyển sang dạng số nguyên
 */
function toNumber($str){
	return (int)vnStrFilter($str,"");
}

/*
 * Cắt văn bản
 */
function cutWords($str, $leng, $more="", $filter=true){
	$str=preg_replace('#<[^>]+>#', ' ', html_entity_decode($str, ENT_QUOTES, 'UTF-8'));
	if($filter){
		$replace=['"', "'", "/", "<", ">", "\\"];
		$str=preg_replace('/\s+/', ' ',$str);
		$str=str_replace($replace, "", $str);
		while( stristr($str, '  ') ){
			$str=str_replace('  ', ' ', $str);
		}
	}
	$str=trim($str);
	if(substr_count($str, " ")>$leng){
		$str=implode(" ", array_slice(explode(" ", $str), 0, $leng));
		$str=$str."".$more;
	}
	return $str; 
}

/*
 * Tạo ký tự ngẫu nhiên
 */
function randomString($length=10, $number=true) {
	$characters = ''.($number ? '0123456789' : '').'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
	$charactersLength = strlen($characters);
	$str= '';
	for($i=0; $i < $length; $i++){
		$str.= $characters[rand(0, $charactersLength - 1)];
	}
	return $str;
}

/*
 * CURL get content url
 */
function file_get_contents_curl($url) {
	$ch = curl_init();
	curl_setopt($ch, CURLOPT_AUTOREFERER, TRUE);
	curl_setopt($ch, CURLOPT_HEADER, 0);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	curl_setopt($ch, CURLOPT_URL, $url);
	curl_setopt($ch, CURLOPT_FOLLOWLOCATION, TRUE);  
	curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 3);     
	curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
	$data = curl_exec($ch);
	curl_close($ch);
	return $data;
}

/*
 * Ngày tháng dạng chữ
 */
function dateText($time, $dateFormat = null){
	if(!is_numeric($time)){
		return;
	}
	$dateFormat = $dateFormat ?? Option::get('settings__general_date_format');
	$timeElapsed = time()-$time;
	$minutes     = round($timeElapsed/60);
	$hours       = round($timeElapsed/3600);
	$days        = round($timeElapsed/86400);


	if($minutes <=60){
		if($minutes==0){
			$minutes=1;
		}
		$date="$minutes ".__('date.minutes_ago');
	}else if($hours<=24){
		$date="$hours ".__('date.hours_ago');
	}else if($days<=7){
		if($days==1){
			$date = __('date.yesterday');
		}else{
			$date="$days ".__('date.days_ago');
		}
	}else{
		$date=date($dateFormat, $time);
	}
	return $date;
}

/*
 * Check dung lượng thư mục
 */
function folderSize($dir){
	$size = 0;
	foreach (glob(rtrim($dir, '/').'/*', GLOB_NOSORT) as $each) {
		$size += is_file($each) ? filesize($each) : folderSize($each);
	}

	return $size;
}


/*
 * Chuyển Mb sang Byte
 */
function mb2Bytes($size){
	return $size*1024*1024;
}

/*
 * Chuyển byte sang Kb,Mb,Gb
 */
function bytesConvert($size, $type="auto"){
	if($size<1024){
		$out["auto"]=$size." Bytes";
		$out["Bytes"]=$out["auto"];
	}else if(($size<1048576)&&($size>1023)){
		$out["auto"]=round($size/1024, 0)." KB";
		$out["KB"]=$out["auto"];
	}elseif(($size<1073741824)&&($size>1048575)){
		$out["auto"]=round($size/1048576, 0)." MB";
		$out["MB"]=$out["auto"];
	}else{
		$out["auto"]=round($size/1073741824, 1)." GB";
		$out["GB"]=$out["auto"];
	}
	return $out[$type]??$size;
}

/*
 * Replace duy nhất 1 lần
 */
function str_replace_first($from, $to, $content){
	return preg_replace('/'.preg_quote($from, '/').'/', $to, $content, 1);
}

/*
 * Chuyển màu từ HEX sang RGB
 */
function hexToRGB($hex){
	list($r, $g, $b) = sscanf($hex, "#%02x%02x%02x");
	return "$r,$g,$b";
}

/*
 * Load ngôn ngữ JS
 */
function loadJSLanguage($packages){
	if( empty($packages) ){
		return;
	}
	$out = '';
	foreach($packages as $p){
		$out .= '<script src="/assets/lang/'.$p.'.js"></script>';
	}
	return $out;
}

/*
 * Tìm tất cả các files trong cả thư mục con
 */
function glob_recursive($pattern, $flags = 0){
	$files = glob($pattern, $flags);
	foreach (glob(dirname($pattern).'/*', GLOB_ONLYDIR) as $dir)
	{
		$files = array_merge($files, glob_recursive($dir.'/'.basename($pattern), $flags));
	}
	return $files;
}

/*
 * Cập nhật nội dung array vào file
 */
function putArrayToFile($path, $data = []){
	if( !is_dir( dirname($path) ) ){
		mkdir( dirname($path), 0755, true );
	}
	file_put_contents($path, "<?php\nreturn ".var_export($data, true).";");
}

/*
 * Chuyển định dạng timestamp
 */
function timestamp($date = null){
	return ($date ? strtotime($date) : date("Y-m-d H:i:s") );
}

/*
 * Chuyển định dạng ngày
 */
function dateFormat($date, $from, $to){
	$date = \DateTime::createFromFormat($from, $date);
	return $date->format($to);
}

/*
 * Lấy số bản ghi tối đa / trang
 */
function paginationLimit(){
	$limit = request()->pagination_limit ?? \App\Services\UserDataServices::get('pagination_limit') ?? Option::get('settings__general_pagination_limit', 10);
	if( isset($_GET['page']) ){
		// Lưu số trang cho từng tài khoản
		\App\Services\UserDataServices::update(['pagination_limit' => $limit]);
	}
	return $limit; 
}