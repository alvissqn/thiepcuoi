<?php
/*
 * Xử lý dữ liệu OptionModel
 */
use App\Option as OptionModel;
class Option
{
	private static $items = [];

	/*
	 * Cập nhật option
	 */
	public static function update($data = [], $groupName = null){
		foreach($data as $name => $value){
			if( isset( self::$items[$name] ) ){
				self::$items[$name] = $value;
			}
			$isArray = is_object($value) || is_array($value) ? true : false;
			OptionModel::updateOrCreate(
				['name' => $name],
				[
					'value'      => $isArray ? serialize($value) : $value,
					'is_array'   => $isArray ? 1 : 0,
					'group_name' => $groupName
				]
			);
		}
	}

	/*
	 * Lấy option
	 */
	public static function get($name, $default = null){
		if( count( self::$items ) > 5 ){
			// Xóa các bản ghi cache cũ
			array_splice(self::$items, 0, -5);
		}
		if( isset( self::$items[$name] ) ){
			// Lấy data đã lưu trước đó
			return self::$items[$name];
		}else{
			// Lấy từ database
			$data = OptionModel::select('value', 'is_array')->where('name', $name)->first();
		}
		if( !$data ){
			return $default;
		}
		$storeCache = strlen($data->value) > 5000 ? false : true; // Không lưu cache khi chuỗi lớn hơn 5k ký tự
		$data = $data->is_array ? unserialize($data->value) : $data->value;
		if( $storeCache ){
			self::$items[$name] = $data; // Lưu cache để truy vấn nhanh hơn
		}
		return $data;
	}

	/*
	 * Xóa option
	 */
	public static function delete($name){
		unset( self::$items[$name] );
		return OptionModel::destroy($name);
	}

	/*
	 * Lấy option theo nhóm
	 */
	public static function getOptionByGroup($groupName, $default = []){
		$data = [];
		foreach( OptionModel::select('value', 'is_array')->where('group_name', $groupName)->get() as $item){
			$item->value = $item->is_array ? unserialize($item->value) : $item->value;
			$data[] = $item;
		}
		return $data;
	}

	/*
	 * Xóa option theo nhóm
	 */
	public static function deleteOptionByGroup($groupName){
		return OptionModel::where('group_name', $groupName)->delete();
	}

}