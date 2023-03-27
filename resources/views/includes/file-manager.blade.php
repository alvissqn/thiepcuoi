{{-- Trình quản lý tệp tin --}}

@php
	use App\Helpers\Form;
@endphp

<link href="/assets/pages/admin/file-manager/style.css" rel="stylesheet" type="text/css">
<section class="row" id="file-manager">

	<div class="col-lg-3 file-manager-left">

		<div class="file-manager-filter file-manager-filter-type mb-2" data-name="type">
			<div class="file-manager-type">
				<div class="row align-items-center no-gutters">
					<div class="file-manager-type-icon">
						<i class="bx bx-image"></i>
					</div>
					<div  class="file-manager-type-title">
						Hình ảnh
					</div>
				</div>
			</div>
			<div class="file-manager-type">
				<div class="row align-items-center no-gutters">
					<div class="file-manager-type-icon">
						<i class="bx bx-video"></i>
					</div>
					<div  class="file-manager-type-title">
						Video
					</div>
				</div>
			</div>
			<div class="file-manager-type">
				<div class="row align-items-center no-gutters">
					<div class="file-manager-type-icon">
						<i class="bx bx-volume"></i>
					</div>
					<div class="file-manager-type-title">
						Audio
					</div>
				</div>
			</div>
		</div><!-- /.file-manager-filter -->
		<div class="file-manager-filter mb-2" data-name="folder">
			<div class="file-manager-folder">
				<div class="row align-items-center no-gutters">
					<div class="file-manager-folder-icon">
						<i class="bx bx-folder"></i>
					</div>
					<div>
						<div class="file-manager-folder-title">
							Bài viết
						</div>
						<div class="file-manager-folder-description">
							100 files
						</div>
					</div>
				</div>
			</div>
			<div class="file-manager-folder">
				<div class="row align-items-center no-gutters">
					<div class="file-manager-folder-icon">
						<i class="bx bx-folder"></i>
					</div>
					<div>
						<div class="file-manager-folder-title">
							Bài viết 2
						</div>
						<div class="file-manager-folder-description">
							100 files
						</div>
					</div>
				</div>
			</div>
			<div class="file-manager-folder">
				<div class="row align-items-center no-gutters">
					<div class="file-manager-folder-icon">
						<i class="bx bx-folder"></i>
					</div>
					<div>
						<div class="file-manager-folder-title">
							Bài viết 3
						</div>
						<div class="file-manager-folder-description">
							100 files
						</div>
					</div>
				</div>
			</div>
		</div><!-- /.file-manager-filter -->

	</div><!-- /.file-manager-left -->

	<div class="col-lg-9 file-manager-right">
		<div class="file-manager-search">
			<div class="m-auto" style="max-width: 450px">
				{!!
					Form::text([
						'title'         => '',
						'placeholder'   => __('admin/file_manager.search_label'),
						'name'          => 'keyword',
						'value'         => '',
						'class'         => 'round',
						'attr'          => '',
						'icon'          => 'bx-search',
						'icon_position' => 'right'
					])
				!!}
			</div>
		</div><!-- /.file-manager-search -->
	</div><!-- /.file-manager-right -->

</section><!-- /#file-manager -->

<script src="/assets/pages/admin/file-manager/scripts.js" type="text/javascript"></script>