<?php
/*
 * Dịch ngôn ngữ
 */

/*function __($key = null, $replace = [], $locale = null){
	if( config('app.debug') ){
		__autoCreateLanguage($key, $replace);
	}
	if( Permission::has('admin') && Option::get('settings__admin_quick_edit_language') ){
		// Lưu dữ liệu chỉnh ngôn ngữ nhanh
		__saveLanguageToQuickEdit($key, trans($key, $replace, $locale) );
	}
	return trans($key, $replace, $locale);
}

function trans_choice($key, $number, array $replace = [], $locale = null){
	if( config('app.debug') ){
		__autoCreateLanguage($key, $replace, $number);
	}
	if( Permission::has('admin') && Option::get('settings__admin_quick_edit_language') ){
		// Lưu dữ liệu chỉnh ngôn ngữ nhanh
		__saveLanguageToQuickEdit($key, app('translator')->choice($key, $number, $replace, $locale) );
	}
	return app('translator')->choice($key, $number, $replace, $locale);
}*/

/*
 * Thêm dòng ngôn ngữ mới nếu chưa tồn tại
 */
function __autoCreateLanguage($key, $replace = [], $number = null, $delete = false){
	foreach( glob( base_path('resources/lang/*') ) as $folder){
		$langFilePath = $folder.'/'.(explode('.', $key)[0]).'.php';
		$langKey = explode('.', $key)[1];
		if( file_exists($langFilePath) ){
			$getLang = include $langFilePath;
		}else{
			$getLang = [];
		}
		if($delete){
			// Xóa dòng ngôn ngữ
			unset($getLang[$langKey]);
			putArrayToFile($langFilePath, $getLang);
		}else if( !isset($getLang[$langKey]) ){
			// Cập nhật dòng ngôn ngữ
			$value = $key;
			if( !is_null($number) ){
				$value = "{0} One|[1,19] Some|[20,*] Many";
			}
			if( !empty($replace) ){
				$value .= ' :'.implode(' :', array_keys($replace) ).' ';
			}
			putArrayToFile($langFilePath, array_merge( $getLang, [$langKey => $value]) );
		} 
	}
}

/*
 * Xóa dòng ngôn ngữ
 */
function __deleteLang($key, $replace = []){
	__autoCreateLanguage($key, $replace, null, true);
	return "Đã xóa: $key";
}

/*
 * Lưu dữ liệu để chỉnh nhanh
 */
function __saveLanguageToQuickEdit($key, $value){
	\App\Helpers\Assets::showOnFooter('
		<template class="admin-language-data">'.json_encode( [
			'key'   => trim($value),
			'value' => $key
		]).'</template>
	');
}