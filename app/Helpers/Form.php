<?php
/*
 * Hiển thị các input, select, texarea... nhanh hơn
 */
namespace App\Helpers;
use Option;

class Form
{
	/*
	 * Input text
	 */
	public static function text($params = [], $type = 'text'){
		extract($params);
		if( empty($group_name) ){
			$value = isset($value) ? $value : Option::get($name, $default ?? null);
		}else{
			$value = isset($value) ? $value : Option::get($group_name)[$name] ?? $default ?? null;
			$name = "{$group_name}[{$name}]";
		}
		if( empty($title) ){
			return '
				<div class="form-group position-relative input-label '.($icon && $icon_position == 'left' ? 'has-icon-left' : '').'">
					'.($placeholder ? '<label>'.$placeholder.''.(isset($required) ? ' (*)' : '').'</label>' : '').'
					<input class="form-control '.$class.'" type="'.$type.'" name="'.$name.'" onfocusin="inputLabel.onFocus(this)" onfocusout="inputLabel.outFocus(this)" value="'.e($value).'" '.$attr.'>
					'.(empty($icon) ? '' : '
						<div class="form-control-position">
							<i class="bx '.$icon.'"></i>
						</div>
					').'
				</div>
			';
		}else{
			return '
				<div class="form-group position-relative">
					<label>'.$title.''.(isset($required) ? ' (*)' : '').'</label>
					<div class="position-relative '.($icon && $icon_position == 'left' ? 'has-icon-left' : '').'">
						<input type="'.$type.'" name="'.$name.'" class="form-control '.$class.'" placeholder="'.$placeholder.'" value="'.e($value).'" '.$attr.'>
						'.(empty($icon) ? '' : '
							<div class="form-control-position">
								<i class="bx '.$icon.'"></i>
							</div>
						').'
					</div>
				</div>
			';
		}
	}

	/*
	 * Textarea
	 */
	public static function textarea($params = []){
		extract($params);
		if( empty($group_name) ){
			$value = isset($value) ? $value : Option::get($name, $default ?? null);
		}else{
			$value = isset($value) ? $value : Option::get($group_name)[$name] ?? $default ?? null;
			$name = "{$group_name}[{$name}]";
		}
		if( empty($title) ){
			return '
				<div class="form-group position-relative input-label">
					<label class="input-label-has-content">'.$placeholder.''.(isset($required) ? ' (*)' : '').'</label>
					<textarea class="form-control '.$class.'" name="'.$name.'" rows="'.$rows.'" '.$attr.'>'.e($value).'</textarea>
				</div>
			';
		}else{
			return '
				<div class="form-group position-relative">
					<label>'.$title.''.(isset($required) ? ' (*)' : '').'</label>
					<textarea class="form-control '.$class.'" name="'.$name.'" placeholder="'.$placeholder.'" rows="'.$rows.'" '.$attr.'>'.e($value).'</textarea>
				</div>
			';
		}
	}

	/*
	 * Input file
	 */
	public static function file($params = []){
		extract($params);
		if( empty($group_name) ){
			$value = isset($value) ? $value : Option::get($name, $default ?? null);
		}else{
			$value = isset($value) ? $value : Option::get($group_name)[$name] ?? $default ?? null;
			$name = "{$group_name}[{$name}]";
		}
		Assets::showOnFooter(
			'/assets/plugins/forms/file-selecter/scripts.js',
		);
		$elId = 'file-'.vnStrFilter($name).rand(1, 9e9);
		return '
			<div class="form-group">
				<label>'.( empty($title) ? '' : $title ).'</label>
				<div class="custom-file">
					<input type="file" name="'.$name.'" class="custom-file-input" id="'.$elId.'" onchange="fileSelecter.change(this); '.$onchange.'" accept="'.$accept.'">
					<label class="custom-file-label" for="'.$elId.'">'.$placeholder.'</label>
				</div>
			</div>
		';
	}

	/*
	 * Input password
	 */
	public static function password($params = []){
		return self::text($params, 'password');
	}
	/*
	 * Textarea editor
	 */
	public static function editor($params = []){
		extract($params);
		if( empty($group_name) ){
			$value = isset($value) ? $value : Option::get($name, $default ?? null);
		}else{
			$value = isset($value) ? $value : Option::get($group_name)[$name] ?? $default ?? null;
			$name = "{$group_name}[{$name}]";
		}
		Assets::showOnFooter(
			'/assets/plugins/html-editor/style.css',
			'/assets/plugins/html-editor/scripts.js',
		);
		return '
			<div class="form-group position-relative">
				<label>'.$title.'</label>
				<textarea class="editor-textarea hide '.$class.'" name="'.$name.'" placeholder="'.$placeholder.'" rows="'.$rows.'" '.$attr.'>'.e($value).'</textarea>
			</div>
		';
	}

	/*
	 * Input number
	 */
	public static function number($params = []){
		extract($params);
		if( empty($group_name) ){
			$value = isset($value) ? $value : Option::get($name, $default ?? null);
		}else{
			$value = isset($value) ? $value : Option::get($group_name)[$name] ?? $default ?? null;
			$name = "{$group_name}[{$name}]";
		}
		Assets::showOnFooter(
			'/assets/plugins/forms/number-input/jquery.bootstrap-touchspin.js',
			'/assets/plugins/forms/number-input/number-input.js'
		);
		return '
			<div class="row align-items-center m-0">
				<div style="width: 180px">
					<div class="input-group">
						<input type="number" name="'.$name.'" class="form-control touchspin-min-max '.$class.'" min="'.$min.'" max="'.$max.'" step="'.$step.'" data-decimals="'.($decimals ?? 0).'" value="'.e($value).'" data-bts-prefix="'.$prefix.'" data-bts-postfix="'.$postfix.'" '.$attr.'>
					</div>
				</div>
				<div style="width: calc(100% - 180px)">
					<label class="pl-2">
						'.$label.'
					</label>
				</div>
			</div>
		';
	}

	/*
	 * Input currency
	 */
	public static function currency($params = []){
		extract($params);
		if( empty($group_name) ){
			$value = isset($value) ? $value : Option::get($name, $default ?? null);
		}else{
			$value = isset($value) ? $value : Option::get($group_name)[$name] ?? $default ?? null;
			$name = "{$group_name}[{$name}]";
		}
		if( empty($title) ){
			return '
				<div class="form-group position-relative input-label '.($icon && $icon_position == 'left' ? 'has-icon-left' : '').'">
					<label>'.$placeholder.'</label>
					<input class="form-control '.$class.'" type="text" name="'.$name.'" onfocusin="inputLabel.onFocus(this)" onfocusout="inputLabel.outFocus(this)" onkeyup="inputCurrency(this)" onchange="inputCurrency(this)" value="'.e($value).'" '.$attr.'>
					'.(empty($icon) ? '' : '
						<div class="form-control-position">
							<i class="bx '.$icon.'"></i>
						</div>
					').'
				</div>
			';
		}else{
			return '
				<div class="form-group position-relative">
					<label>'.$title.'</label>
					<div class="position-relative '.($icon && $icon_position == 'left' ? 'has-icon-left' : '').'">
						<input type="text" onkeyup="inputCurrency(this)" onchange="inputCurrency(this)" name="'.$name.'" class="form-control '.$class.'" placeholder="'.$placeholder.'" value="'.e($value).'" '.$attr.'>
						'.(empty($icon) ? '' : '
							<div class="form-control-position">
								<i class="bx '.$icon.'"></i>
							</div>
						').'
					</div>
				</div>
			';
		}
	}

	/*
	 * Select
	 */
	public static function select($params = []){
		extract($params);
		if( empty($group_name) ){
			$selected = isset($selected) ? $selected : Option::get($name, []);
		}else{
			$selected = isset($selected) ? $selected : Option::get($group_name, [])[$name] ?? [];
			$name = "{$group_name}[{$name}]";
		}
		if( is_string($selected) ){
			$selected = [$selected];
		}
		if( $multiple ){
			$name = $name.'[]';
		}
		$opHTML = '';
		foreach($options as $value => $item){
			if( is_array($item) ){
				$opHTML .= '<optgroup label="'.$item['label'].'">';
				foreach($item['options'] as $svalue => $label){
					$opHTML .= '
						<option value="'.$svalue.'" '.(in_array($svalue, $selected) ? 'selected' : '').'>'.$label.'</option>
					';
				}
				$opHTML .= '</optgroup>';
			}else{
				$opHTML .= '
					<option value="'.e($value).'" '.(in_array($value, $selected) ? 'selected' : '').'>'.$item.'</option>
				';
			}
		}
		return '
			<div class="form-group position-relative input-label '.($icon && $icon_position == 'left' ? 'has-icon-left' : '').'">
				<label>'.$placeholder.''.(isset($required) ? ' (*)' : '').'</label>
				<select class="form-control '.$class.'" name="'.$name.'" onfocusin="inputLabel.onFocus(this)" onfocusout="inputLabel.outFocus(this)" '.($multiple ? 'multiple="multiple"' : 'data-minimum-results-for-search="Infinity"').' '.$attr.'>
					'.$opHTML.'
				</select>
				'.(empty($icon) ? '' : '
					<div class="form-control-position">
						<i class="bx '.$icon.'"></i>
					</div>
				').'
			</div>
		';
	}

	/*
	 * Select 2
	 */
	public static function select2($params = []){
		Assets::showOnFooter(
			'/assets/plugins/forms/select2/select2.min.css',
			'/assets/plugins/forms/select2/select2.full.min.js',
			'/assets/plugins/forms/select2/form-select2.min.js'
		);
		extract($params);
		if( empty($group_name) ){
			$selected = isset($selected) ? $selected : Option::get($name, []);
		}else{
			$selected = isset($selected) ? $selected : Option::get($group_name, [])[$name] ?? [];
			$name = "{$group_name}[{$name}]";
		}
		if( !is_array($selected) ){
			$selected = [$selected];
		}
		if( $multiple ){
			$name = $name.'[]';
		}
		$opHTML = '';
		foreach($options as $value => $item){
			if( is_array($item) ){
				$opHTML .= '<optgroup label="'.$item['label'].'">';
				foreach($item['options'] as $svalue => $label){
					$opHTML .= '
						<option value="'.$svalue.'" '.(in_array($svalue, $selected) ? 'selected' : '').'>'.$label.'</option>
					';
				}
				$opHTML .= '</optgroup>';
			}else{
				$opHTML .= '
					<option value="'.e($value).'" '.(in_array($value, $selected) ? 'selected' : '').'>'.$item.'</option>
				';
			}
		}
		return '
			<div class="select2-outer position-relative '.($icon && $icon_position == 'left' ? 'has-icon-left' : '').'">
				'.($title ? '<label>'.$title.''.(isset($required) ? ' (*)' : '').'</label>' : '').'
				<select class="select2 form-control '.$class.'" name="'.$name.'" '.($multiple ? 'multiple="multiple"' : '').' '.($search ? '' : 'data-minimum-results-for-search="Infinity"').' '.$attr.'>
					'.$opHTML.'
				</select>
				'.(empty($icon) ? '' : '
					<div class="form-control-position">
						<i class="bx '.$icon.'"></i>
					</div>
				').'
			</div>
		';
	}

	/*
	 * Input radio
	 */
	public static function radio($params = []){
		extract($params);
		if( empty($group_name) ){
			$checked = isset($checked) ? $checked : Option::get($name, $default ?? null);
		}else{
			$checked = isset($checked) ? $checked : Option::get($group_name)[$name] ?? $default ?? null;
			$name = "{$group_name}[{$name}]";
		}
		$out = '<ul class="list-unstyled mb-0">';
		foreach($data as $value => $label){
			$elId = 'radio-'.vnStrFilter($name).$value.rand(1,9e9);
			$out .= '
				<li class="d-inline-block mr-2 mb-1">
					<div class="radio">
						<input class="'.$class.'" type="radio" name="'.$name.'" value="'.e($value).'" id="'.$elId.'" '.($checked == $value ? 'checked' : '').' '.(in_array($value, $disabled) ? 'disabled' : '').' '.$attr.'>
						<label class="link" for="'.$elId.'">'.$label.'</label>
					</div>
				</li>
			';
		}
		$out .= '</ul>';
		return $out;
	}

	/*
	 * Input radio
	 */
	public static function checkbox($params = []){
		extract($params);
		if( empty($group_name) ){
			$checked = isset($checked) ? $checked : Option::get($name, []);
		}else{
			$checked = isset($checked) ? $checked : Option::get($group_name, [])[$name] ?? [];
			$name = "{$group_name}[{$name}]";
		}
		if( is_string($checked) ){
			$checked = [$checked];
		}
		$name = $name.'[]';
		$out = '<ul class="list-unstyled mb-0">';
		foreach($data as $value => $label){
			$elId = 'checkbox-'.vnStrFilter($name).$value.rand(1,9e9);
			$out .= '
				<li class="d-inline-block mr-2 mb-1">
					<div class="checkbox">
						<input class="'.$class.'" type="checkbox" name="'.$name.'" value="'.e($value).'" id="'.$elId.'" '.(in_array($value, $checked) ? 'checked' : '').' '.(in_array($value, $disabled) ? 'disabled' : '').' '.$attr.'>
						<label class="link" for="'.$elId.'">'.$label.'</label>
					</div>
				</li>
			';
		}
		$out .= '</ul>';
		return $out;
	}

	/*
	 * Switch
	 */
	public static function switch($params = []){
		extract($params);
		if( empty($group_name) ){
			$value = isset($value) ? $value : Option::get($name, $default ?? null);
		}else{
			$value = isset($value) ? $value : Option::get($group_name)[$name] ?? $default ?? null;
			$name = "{$group_name}[{$name}]";
		}
		$elId = 'switch-'.vnStrFilter($name).$value.rand(1,9e9);
		$checked = $checked ?? $value;
		return '
			<div class="custom-control custom-switch custom-control-inline">
				<input class="custom-control-input '.$class.'" type="checkbox" name="'.$name.'" value="'.e($value ?? 1).'" id="'.$elId.'" '.($checked ? 'checked' : '').' '.$attr.'>
				<label class="custom-control-label mr-1" for="'.$elId.'"></label>
				<span class="label link" onclick="$(this).prev().click()">'.$label.'</span>
			</div>
		';
	}

	/*
	 * Color picker
	 */
	public static function colorPicker($params = []){
		extract($params);
		if( empty($group_name) ){
			$value = isset($value) ? $value : Option::get($name, $default ?? null);
		}else{
			$value = isset($value) ? $value : Option::get($group_name)[$name] ?? $default ?? null;
			$name = "{$group_name}[{$name}]";
		}
		Assets::showOnFooter(
			'/assets/plugins/color-picker/scripts.js',
		);
		return '
			<div class="d-flex align-items-center">
				<div style="width: 150px">
					<div class="input-group">
						<input class="jscolor form-control d-inline-block"  size="5" value="'.e($value).'" data-jscolor="{hash:true'.($required ? '' : ', required: 0').'}" name="'.$name.'" '.$attr.' />
						<div class="input-group-append">
							<button type="button" class="btn form-color-default btn-primary" onclick="jscolorResetDefault(this)" data-default="'.$default.'" style="padding: 0 0.9rem;">
								<i class="bx bx-'.(empty($default) ? 'x': 'undo').'"></i>
							</button>
			            </div>
					</div>
				</div>
				<div style="width: calc(100% - 150px)">
					<label class="ml-2">
						'.$label.'
					</label>
				</div>
			</div>
		';
	}

	/*
	 * Color picker
	 */
	public static function datePicker($params = []){
		extract($params);
		if( empty($group_name) ){
			$value = isset($value) ? $value : Option::get($name, $default ?? null);
		}else{
			$value = isset($value) ? $value : Option::get($group_name)[$name] ?? $default ?? null;
			$name = "{$group_name}[{$name}]";
		}
		Assets::showOnFooter(
			'/assets/plugins/date-picker/scripts.js',
			'/assets/plugins/date-picker/style.css',
		);
		return '
			<div class="form-date-wrap" data-format="'.$format.'">
				<div class="form-date-picker form-date-picker-'.$position.' hide"></div>
				<div class="input-icon input-label" onclick="datePickerInit(this)">
					<label>'.$placeholder.'</label>
					<i class="fa fa-calendar"></i>
					<input class="form-control '.$class.'" onfocusin="inputLabel.onFocus(this)" onchange="inputLabel.outFocus(this); '.$onchange.'" type="text" name="'.$name.'" value="'.e($value).'" '.$attr.'>
				</div>
				<code class="hide">'.json_encode($config).'</code>
			</div>
		';
	}

}