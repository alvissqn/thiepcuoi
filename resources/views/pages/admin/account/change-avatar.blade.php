@php
	use App\Helpers\Form;
@endphp
@extends('layouts.admin')
@section('header')
	@parent
	<script src="/assets/plugins/form-ajax-save/scripts.js"></script>
@endsection

@section('content')
	<main class="row">
		<section class="col-lg-4 mt-2">
			@include('pages.admin.account.includes.navbar-menu', ['active' => 'change-avatar'])
		</section>
		<section class="col-lg-8 mt-2">
			<div class="card">
				<h4 class="card-header">
					{{ __('user/profile.account_change_avatar_heading_title') }}
				</h4>
				<hr>
				<form class="card-body" method="POST" id="change-avatar-form">
					<section>
						<div class="text-center link" onclick="$(this).parent().find('.custom-file>input').click()">
							<span class="avatar">
								<img src="{{ \App\Services\UserServices::avatar() }}?t={{ time() }}" style="height: 180px; width: 180px">
							</span>
						</div>
						<div class="text-center mt-25">
							<button type="button" class="btn btn-sm btn-primary" onclick="changeAvatar.rotate(this)">
								<i class="bx bx-rotate-left"></i>
								{{ __('user/profile.rotate_avatar') }}
							</button>
						</div>
						<div>
							<div class="mb-2">
								{!!
									Form::file([
										'title'         => '',
										'placeholder'   => __('user/profile.account_edit_profile_field_select_avatar'),
										'name'          => 'avatar',
										'value'         => '',
										'class'         => '',
										'attr'          => '',
										'icon'          => '',
										'onchange'      => '$(this).parents(\'form\').find(\'.form-save\').click();',
										'icon_position' => 'right',
										'accept'        => 'image/*',
										'required'      => true
									])
								!!}
							</div>
						</div>
					</section>
					<div class="form-notify alert alert-danger hide"></div>
					<div class="text-center hide">
						<button type="button" class="btn btn-primary form-save">
							{{ __('user/profile.update_profile_btn_label') }}
						</button>
					</div>
				</form>
				<script type="text/javascript">
					formAjaxSave.init({
						element: '#change-avatar-form',
						success: function(){
							location.reload();
						}
					});
				</script>
			</div>
		</section>
	</main>
@endsection

@section('footer')
	@parent
@endsection

@section('footer-assets')
	@parent
	<script type="text/javascript">
		changeAvatar = {
			// Xoay ảnh avatar
			rotate: function(self){
				var form = $(self).parents('form');
				form.find('.avatar').css({opacity: '0.2'});
				$(self).prop('disabled', true);
				$.ajax({
					url: '{{ route('admin.account.change-avatar.rotate') }}',
					method: 'POST',
					dataType: 'JSON',
					success: function(){
						form.find('.avatar').css({opacity: ''});
						$(self).prop('disabled', false);
						var imgEl = form.find('.avatar>img');
						if( typeof imgEl.attr('data-src') == 'undefined' ){
							imgEl.attr('data-src', imgEl.attr('src') );
						}
						imgEl.attr('src', imgEl.attr('data-src')+'&t2='+(new Date).getTime() );
					},
					error: function(){
						setTimeout(function(){
							changeAvatar.rotate(self);
						}, 2e3);
					}
				});
			}
		};
	</script>
@endsection