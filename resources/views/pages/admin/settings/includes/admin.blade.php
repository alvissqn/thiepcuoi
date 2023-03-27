@php
	use App\Helpers\Form;	
@endphp
<main class="row">
	<section class="mt-2 col-md-6">
		<div class="card">
			<div class="card-header">
				<h4 class="card-title">
					{{ __('admin/settings/admin.setting_admin_theme_heading_title') }}
				</h4>
			</div>
			<hr>
			<div class="card-content">
				<div class="card-body">
					<div class="mb-2">
						{!!
							Form::select2([
								'title'         => __('admin/settings/admin.setting_theme'),
								'placeholder'   => '',
								'name'          => 'settings__admin_theme',
								'class'         => '',
								'attr'          => 'onchange="settingsSave(this, true);"',
								'icon'          => '',
								'icon_position' => 'right',
								'options' => [
									'light' => 'Light',
									'dark'  => 'Dark'
								],
								//'selected' => [],
								'multiple' => false,
								'search'   => false
							])
						!!}
					</div>
					<div class="mb-2">
						{!!
							Form::colorPicker([
								'label'         => __('admin/settings/admin.setting_admin_background_color'),
								'name'          => 'settings__style_admin_background_color',
								'default'         => '#F2F4F4',
								'required'      => true,
								'class'         => '',
								'attr'          => '',
								'icon'          => 'bx-user',
								'icon_position' => 'left'
							])
						!!}
					</div>
					<div class="mb-2">
						{!!
							Form::number([
								'label'    => __('admin/settings/admin.setting_admin_theme_menu_padding'),
								'prefix'   => '',
								'postfix'  => '',
								'name'     => 'settings__style_admin_menu_padding',
								//'value'  => 5,
								'default'  => 0.7,
								'min'      => 0,
								'max'      => 20,
								'step'     => 0.1,
								'decimals' => 1,
								'class'    => '',
								'attr'     => '',
							])
						!!}
					</div>
					<div class="mb-2">
						{!!
							Form::number([
								'label'    => __('admin/settings/admin.setting_admin_theme_menu_font_size'),
								'prefix'   => '',
								'postfix'  => '',
								'name'     => 'settings__style_admin_menu_font_size',
								//'value'  => 5,
								'default'  => 0.9,
								'min'      => 0,
								'max'      => 20,
								'step'     => 0.1,
								'decimals' => 1,
								'class'    => '',
								'attr'     => '',
							])
						!!}
					</div>
					<div class="mb-2">
						{!!
							Form::colorPicker([
								'label'         => __('admin/settings/admin.setting_admin_menu_actived_background'),
								'name'          => 'settings__style_admin_actived_background',
								'default'         => Option::get('settings__style_primary_color'),
								'required'      => true,
								'class'         => '',
								'attr'          => '',
								'icon'          => 'bx-user',
								'icon_position' => 'left'
							])
						!!}
					</div>
				</div><!--/.card-body-->
			</div><!--/.card-content-->
		</div>
	</section>

	<section class="mt-2 col-md-6">
		<div class="card">
			<div class="card-header">
				<h4 class="card-title">
					{{ __('admin/settings/admin.setting_general_heading_title') }}
				</h4>
			</div>
			<hr>
			<div class="card-content">
				<div class="card-body">
					<div class="mb-2">
						{!!
							Form::switch([
								'label'      => __('admin/settings/admin.quick_edit_language'),
								'name'       => 'settings__admin_quick_edit_language',
								//'value'      => true,
								'class'      => '',
								'attr'       => ''
							])
						!!}
					</div>
				</div><!--/.card-body-->
			</div><!--/.card-content-->
		</div><!--/.card-->
	</section>
</main>
<div class="form-footer-sticky" onclick="settingsSave(this, true)">
	<button type="button" class="btn btn-primary form-save-btn">
		<i class="bx bx-save"></i>
		{{ __('admin/settings.save_button_label') }}
	</button>
</div>