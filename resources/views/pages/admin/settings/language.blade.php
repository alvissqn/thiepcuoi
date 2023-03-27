@php
	use App\Helpers\Form;
@endphp
@extends('layouts.admin')
@section('header')
	@parent
@endsection

@section('content')
	<main class="row">
		<section class="mt-2 col-md-12">
			<div class="card">
				<div class="card-header row align-items-center">
					<div class="col-md-6">
						<h4 class="card-title">
							{{ __('admin/language.language_editor_heading_title') }}
						</h4>
					</div>
					<div class="col-md-6">
						<form method="GET" class="row justify-content-end">
							<div class="col-md-6 mt-1">
								{!!
									Form::select2([
										'title'         => __('admin/language.select_language'),
										'name'          => 'lang',
										'class'         => '',
										'attr'          => 'onchange="$(this).parents(\'form\').submit()"',
										'icon'          => '',
										'icon_position' => 'right',
										'options' => call_user_func(function(){
											$out = [];
											foreach(glob( base_path('resources/lang/*') ) as $path){
												$out[basename($path)] = basename($path);
											}
											return $out;
										}),
										'selected' => [request('lang', config('app.locale') )],
										'multiple' => false,
										'search' => false
									])
								!!}
							</div>
							@if( request('lang', config('app.locale') ) )
								<div class="col-md-6 mt-1">
									{!!
										Form::select2([
											'title'         => __('admin/language.select_language_path'),
											'name'          => 'path',
											'class'         => 'mb-0',
											'attr'          => 'onchange="$(this).parents(\'form\').submit()"',
											'icon'          => '',
											'icon_position' => 'right',
											'options' => call_user_func(function(){
												$out = ['' => '--'];
												foreach( glob_recursive( base_path('resources/lang/'.request('lang', config('app.locale') ).'/*.php') ) as $path){
													$path = str_replace(
														[
															base_path('resources/lang/'.request('lang', config('app.locale') ).'/'),
															'.php'
														],
														[''],
														$path
													);
													$out[$path] = ($path);
												}
												return $out;
											}),
											'selected' => [request()->get('path')],
											'multiple' => false,
											'search'   => true
										])
									!!}
								</div>
							@endif
						</form>
					</div>
				</div>
				<div class="card-content">
					<form class="card-body pt-0" method="POST">
						@if( !is_dir( base_path('resources/lang/'.request('lang', config('app.locale') ) ) ) )
							<div class="text-center alert alert-danger">
								{{ __('admin/language.language_package_not_found') }}
							</div>
						@elseif( empty( request()->get('path') ) )
							<div class="text-center">
								{{ __('admin/language.please_select_language_path') }}
							</div>
						@else
							@php
								$langPath = base_path('resources/lang/'.request('lang', config('app.locale') ).'/'.request()->get('path').'.php' );
								if( file_exists($langPath) ){
									$data = include base_path('resources/lang/'.request('lang', config('app.locale') ).'/'.request()->get('path').'.php' );
								}else{
									echo '<div class="alert alert-danger">File ngôn ngữ không tồn tại!</div>';
								}
							@endphp
							@foreach( ($data ?? []) as $key => $value)
								@php
									$langParams = '';
									preg_match_all('/\:([a-zA-Z0-9_]+)(\s|$)/i', $value, $matches, PREG_PATTERN_ORDER);
								@endphp
								@foreach( ($matches[1] ?? []) as $p)
									@php
										$langParams .= '<code class="highlighter-rouge ml-1">:'.$p.'</code>';
									@endphp
								@endforeach
								<div class="mb-2 position-relative">
									{!!
										Form::textarea([
											'title'       => $key.$langParams,
											'placeholder' => '',
											'group_name'  => 'data',
											'name'        => $key,
											'value'       => $value,
											'rows'        => 6,
											'class'       => '',
											'attr'        => 'id="field-'.$key.'"',
										])
									!!}
									<button onclick="$(this).parent().find('textarea').prop('disabled', function(i, v) { return !v; });" title="Xóa bỏ nếu dòng này không còn dùng (sẽ tự update lại khi dòng này vẫn dùng)" class="btn btn-sm btn-default" type="button" style="position: absolute; top: 30px; right: 5px; padding: 5px">
										<i class="bx bx-x"></i>
									</button>
								</div>
							@endforeach
							<div class="form-footer-sticky">
								<button type="submit" class="btn btn-primary">
									<i class="bx bx-save"></i>
									{{ __('admin/language.save_button_label') }}
								</button>
							</div>
						@endif
					</form><!--/.card-body-->
				</div><!--/.card-content-->
			</div><!--/.card-->
		</section>
	</main>
@endsection

@section('footer')
	@parent
@endsection

@section('footer-assets')
	@parent
	<!-- Page JS-->
	<script src="/assets/pages/admin/language/scripts.js"></script>
	<!-- /Page JS-->
@endsection