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
					<div class="col-md-12">
						<h4 class="card-title">
							{{ __('admin/page-info.heading_title') }}
						</h4>
					</div>
				</div>
				<div class="card-content">
					<form class="card-body pt-0" method="POST" action="{{ route('admin.settings.page-info.update') }}" id="settings-form">
						<div class="accordion collapse-icon accordion-icon-rotate" id="link-item-list">
							@foreach( glob_recursive( config_path('pages/*.php') ) as $file)
								@php
									$getPageConfig = include $file;
									$pageName = str_replace( [config_path('pages/'), '.php', '/'], ['', '', '___'], $file);
								@endphp
								<div class="card collapse-header">
									<div class="card-header collapsed" data-toggle="collapse" data-target="#item-list-{{ vnStrFilter($pageName) }}">
										<span class="collapse-title">
											<span class="align-middle">
												{{ $getPageConfig['title'] ?? $pageName }}
											</span>
										</span>
									</div>
									<div id="item-list-{{ vnStrFilter($pageName) }}" data-parent="#link-item-list"  class="collapse">
										<div class="card-content">
											<div class="card-body">
												<div class="mb-2 position-relative">
													{!!
														Form::text([
															'title'         => '',
															'placeholder'   => '',
															'group_name'    => '',
															'name'          => '',
															'value'         => $pageName,
															'class'         => '',
															'attr'          => 'disabled',
															'icon'          => '',
															'icon_position' => ''
														])
													!!}
												</div>
												<div class="mb-2 position-relative">
													{!!
														Form::text([
															'title'         => '',
															'placeholder'   => __('admin/page-info.page_title'),
															'group_name'    => '',
															'name'          => 'data['.$pageName.'][title]',
															'value'         => $getPageConfig['title'],
															'class'         => '',
															'attr'          => '',
															'icon'          => '',
															'icon_position' => ''
														])
													!!}
												</div>
												<div class="mb-2 position-relative">
													{!!
														Form::textarea([
															'placeholder'       => '',
															'title' => __('admin/page-info.page_description'),
															'group_name'  => '',
															'name'        => 'data['.$pageName.'][description]',
															'value'       => $getPageConfig['description'],
															'rows'        => 6,
															'class'       => '',
															'attr'        => '',
														])
													!!}
												</div>
												<div class="mb-2 position-relative">
													{!!
														Form::text([
															'title'         => '',
															'placeholder'   => __('admin/page-info.page_redirect'),
															'group_name'    => '',
															'name'          => 'data['.$pageName.'][redirect]',
															'value'         => $getPageConfig['redirect'] ?? null,
															'class'         => '',
															'attr'          => '',
															'icon'          => '',
															'icon_position' => ''
														])
													!!}
												</div>
											</div><!--/.card-body-->
										</div><!--/.card-content-->
									</div><!--/.collapse-->
								</div><!--/.card-->
							@endforeach
						</div>
						<div class="form-footer-sticky" onclick="settingsSave(this)">
							<button type="button" class="btn btn-primary form-save-btn">
								<i class="bx bx-save"></i>
								{{ __('admin/language.save_button_label') }}
							</button>
						</div>
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
	<script src="/assets/pages/admin/settings/scripts.js"></script>
	<!-- /Page JS-->
@endsection