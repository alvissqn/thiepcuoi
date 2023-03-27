@php
	use App\Helpers\Form;	
@endphp
<main class="row">
	<section class="mt-2 col-md-6">
		<div class="card">
			<div class="card-header">
				<h4 class="card-title">
					{{ __('admin/settings/general.option_title') }}
				</h4>
			</div>
			<hr>
			<div class="card-content">
				<div class="card-body">
					<div class="mb-2">
						{!!
							Form::select2([
								'title'         => __('admin/settings/general.language'),
								'placeholder'   => '',
								'name'          => 'settings__general_language',
								'class'         => 'round',
								'attr'          => 'onchange="settingsSave(this, true);"',
								'icon'          => '',
								'icon_position' => 'right',
								'options' => call_user_func(function(){
									foreach(glob( base_path('resources/lang/*') ) as $path){
										$out[basename($path)] = basename($path);
									}
									return $out;
								}),
								'selected' => [ Option::get('settings__general_language', config('app.locale') ) ],
								'multiple' => false,
								'search'   => false
							])
						!!}
					</div>
					<div class="mb-2">
						{!!
							Form::switch([
								'label'      => __('admin/settings/general.search_engine_index'),
								'name'       => 'settings__general_search_engine_index',
								//'value'      => true,
								'class'      => '',
								'attr'       => ''
							])
						!!}
					</div>
					<div class="mb-2">
						{!!
							Form::number([
								'label'      => __('admin/settings/general.pagination_limit'),
								'prefix'     => '',
								'postfix'    => '',
								'name'       => 'settings__general_pagination_limit',
								//'value'    => 5,
								'min'        => 5,
								'max'        => 1000,
								'step'       => 1,
								'class'      => '',
								'attr'       => '',
							])
						!!}
					</div>
					<div class="mb-2">
						{!!
							Form::select2([
								'title'         => __('admin/settings/general.time_format'),
								'placeholder'   => '',
								'name'          => 'settings__general_time_format',
								'class'         => 'round',
								'attr'          => 'onchange="settingsSave(this, true);"',
								'icon'          => '',
								'icon_position' => 'right',
								'options' => [
									'H:i - d/m/Y'  => 'H:i - d/m/Y',
									'H:i d/m/Y'    => 'H:i d/m/Y',
									'Y-m-d - H:i'  => 'Y-m-d - H:i',
									'H:i - j F, Y' => 'H:i - j F, Y',
								],
								'selected' => [ Option::get('settings__general_time_format', 'H:i - d/m/Y' ) ],
								'multiple' => false,
								'search'   => false
							])
						!!}
					</div>
					<div class="mb-2">
						{!!
							Form::select2([
								'title'         => __('admin/settings/general.date_format'),
								'placeholder'   => '',
								'name'          => 'settings__general_date_format',
								'class'         => 'round',
								'attr'          => 'onchange="settingsSave(this, true);"',
								'icon'          => '',
								'icon_position' => 'right',
								'options' => [
									'd/m/Y'  => 'd/m/Y',
									'Y-m-d'  => 'Y-m-d',
									'j F, Y' => 'j F, Y',
								],
								'selected' => [ Option::get('settings__general_date_format', 'd/m/Y' ) ],
								'multiple' => false,
								'search'   => false
							])
						!!}
					</div>
				</div><!--/.card-body-->
			</div><!--/.card-content-->
		</div><!--/.card-->
	</section>

	<section class="mt-2 col-md-6">
		<div class="card">
			<div class="card-header">
				<h4 class="card-title">
					Thông tin đầu trang
				</h4>
			</div>
			<hr>
			<div class="card-content">
				<div class="card-body">

				</div><!--/.card-body-->
			</div><!--/.card-content-->
		</div>
	</section>
</main>
<div class="form-footer-sticky" onclick="settingsSave(this, false)">
	<button type="button" class="btn btn-primary form-save-btn">
		<i class="bx bx-save"></i>
		{{ __('admin/settings.save_button_label') }}
	</button>
</div>