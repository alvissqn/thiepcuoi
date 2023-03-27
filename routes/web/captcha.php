<?php
Route::get('/captcha.png', function(){
	\Debugbar::disable();
	$text = rand(1000,9999);
	if( empty( request()->get('refresh') ) && !empty( session('captcha_code') ) ){
		$text = session('captcha_code');
	}
	session(['captcha_code' => $text]);
	$width    = 60;
	$height   = 24;
	$fontsize = 20;

	$img = imagecreate($width, $height);

    // Nền trong suốt
	$bg = imagecolorallocatealpha($img, 255, 255, 255, 127);
	imagefill($img, 0, 0, $bg);
	imagecolortransparent ($img, $bg);
	$font = public_path('/assets/fonts/ttf/corsiva.ttf');
    // Chữ
	$color = imagecolorallocate($img, 00, 00, 00);
	// Tạo chấm
	$pixel_color = imagecolorallocate($img, 0,0,0);
	for($i=0;$i<600;$i++) {
		imagesetpixel($img,rand()%200,rand()%50,$pixel_color);
	}
	//header('Content-type: image/png');
	imagettftext($img, $fontsize, 0, 5, 20, $color, $font, $text);
	ob_start();
	imagepng($img);
	$buffer = ob_get_contents();
	ob_end_clean();
	imagedestroy($img);
	return response($buffer, 200)->header('Content-type','image/png');
})->name('captcha');