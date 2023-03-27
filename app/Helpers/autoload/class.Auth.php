<?php
use App\User as UserModel;

/**
 * Class lấy thông tin tài khoản đăng nhập
 */
class Auth
{
	/*
	 * Lấy thông tin tài khoản đang đăng nhập
	 */
	public static function user()
	{
		return request()->logged_user_data ?? null;
	}

	/*
	 * Kiểm tra xem đã đăng nhập hay chưa
	 */
	public static function check()
	{
		return self::user()->id ?? false;
	}

	/*
	 * Lấy id tài khoản đăng nhập
	 */
	public static function id()
	{
		return self::user()->id ?? null;
	}

}