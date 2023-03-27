@php
	use App\Helpers\Form;
@endphp
@extends('layouts.admin')
@section('header')
	@parent
@endsection

@section('content')

<section class="card mt-2">
	<div class="card-header">
		<h4 class="card-title">Form demo</h4>
	</div>
	<div class="card-content">
		<div class="card-body">
			<div class="row">
				<div class="col-md-6">
					<div class="mb-2">
						{!!
							Form::text([
								'title'         => 'Tiêu đề',
								'placeholder'   => 'Test okkk',
								'name'          => 'test',
								'value'         => 'Giá trị',
								'class'         => 'round',
								'attr'          => '',
								'icon'          => 'bx-user',
								'icon_position' => 'right'
							])
						!!}
					</div>

					<div class="mb-2">
						{!!
							Form::text([
								'title'         => '',
								'placeholder'   => 'Test okkk',
								'name'          => 'test',
								'value'         => 'Giá trị',
								'class'         => 'round',
								'attr'          => '',
								'icon'          => 'bx-user',
								'icon_position' => 'left'
							])
						!!}
					</div>

					<div class="mb-2">
						{!!
							Form::textarea([
								'title'       => 'Tiêu đề',
								'placeholder' => 'Test okkk',
								'name'        => 'test',
								'value'       => 'Giá trị',
								'rows'        => 4,
								'class'       => '',
								'attr'        => '',
							])
						!!}
					</div>

					<div class="mb-2">
						{!!
							Form::textarea([
								'title'       => '',
								'placeholder' => 'Test okkk',
								'name'        => 'test',
								'value'       => 'Giá trị',
								'rows'        => 4,
								'class'       => '',
								'attr'        => '',
							])
						!!}
					</div>

					<div class="mb-2">
						{!!
							Form::number([
								'label'    => __('admin/settings/admin.'),
								'prefix'   => '',
								'postfix'  => '',
								'name'     => 'test',
								'value'    => 5,
								'min'      => 5,
								'max'      => 10,
								'step'     => 2,
								'decimals' => 0,
								'class'    => '',
								'attr'     => '',
							])
						!!}
					</div>

					<div class="mb-2">
						{!!
							Form::currency([
								'title'         => '',
								'placeholder'   => 'Input currency',
								'name'          => 'test',
								'value'         => number_format('10000'),
								'class'         => 'round',
								'attr'          => '',
								'icon'          => 'bx-user',
								'icon_position' => 'left'
							])
						!!}
					</div>

					<div class="mb-2">
						{!!
							Form::currency([
								'title'         => 'Input currency',
								'placeholder'   => 'Input currency',
								'name'          => 'test',
								'value'         => number_format('10000'),
								'class'         => 'round',
								'attr'          => '',
								'icon'          => 'bx-user',
								'icon_position' => 'left'
							])
						!!}
					</div>

					<div class="mb-2">
						{!!
							Form::select([
								'title'         => '',
								'placeholder'   => 'Select',
								'name'          => 'test',
								'class'         => 'round',
								'attr'          => '',
								'icon'          => '',
								'icon_position' => 'right',
								'options' => [
									''    => 'Chọn',
									'op1' => 'Chọn 1',
									'op2' => 'Chọn 2',
									'op3' => 'Chọn 3'
								],
								'selected' => ['op3'],
								'multiple' => false
							])
						!!}
					</div>

					<div class="mb-2">
						{!!
							Form::select([
								'title'         => '',
								'placeholder'   => 'Select group',
								'name'          => 'test',
								'class'         => '',
								'attr'          => '',
								'icon'          => '',
								'icon_position' => 'right',
								'options' => [
									''    => 'Chọn',
									[
										'label' => 'Nhóm 1',
										'options' => [
											'op1g' => 'Chọn 1',
											'op2g' => 'Chọn 2',
											'op3g' => 'Chọn 3'
										]
									],
									[
										'label' => 'Nhóm 2',
										'options' => [
											'op1g2' => 'Chọn 2 1',
											'op2g2' => 'Chọn 2 2',
											'op3g2' => 'Chọn 3 3'
										]
									],
									'op1' => 'Chọn 1 parent',
									'op2' => 'Chọn 2 parent',
									'op3' => 'Chọn 3 parent'
								],
								'selected' => ['op2g2'],
								'multiple' => false
							])
						!!}
					</div>

					<div class="mb-2">
						{!!
							Form::select2([
								'title'   => 'Select group',
								'name'          => 'test',
								'class'         => 'round',
								'attr'          => '',
								'icon'          => '',
								'icon_position' => 'right',
								'options' => [
									''    => 'Chọn',
									[
										'label' => 'Nhóm 1',
										'options' => [
											'op1g' => 'Chọn 1',
											'op2g' => 'Chọn 2',
											'op3g' => 'Chọn 3'
										]
									],
									[
										'label' => 'Nhóm 2',
										'options' => [
											'op1g2' => 'Chọn 2 1',
											'op2g2' => 'Chọn 2 2',
											'op3g2' => 'Chọn 3 3'
										]
									],
									'op1' => 'Chọn 1 parent',
									'op2' => 'Chọn 2 parent',
									'op3' => 'Chọn 3 parent'
								],
								'selected' => ['op2g2'],
								'multiple' => false,
								'search'   => false
							])
						!!}
					</div>

					<div class="mb-2">
						{!!
							Form::select2([
								'title'   => 'Select group',
								'name'          => 'test',
								'class'         => 'round',
								'attr'          => '',
								'icon'          => '',
								'icon_position' => 'right',
								'options' => [
									[
										'label' => 'Nhóm 1',
										'options' => [
											'op1g' => 'Chọn 1',
											'op2g' => 'Chọn 2',
											'op3g' => 'Chọn 3'
										]
									],
									[
										'label' => 'Nhóm 2',
										'options' => [
											'op1g2' => 'Chọn 2 1',
											'op2g2' => 'Chọn 2 2',
											'op3g2' => 'Chọn 3 3'
										]
									],
									'op1' => 'Chọn 1 parent',
									'op2' => 'Chọn 2 parent',
									'op3' => 'Chọn 3 parent'
								],
								'selected' => ['op2g2'],
								'multiple' => true,
								'search'   => true
							])
						!!}
					</div>
				</div>
				<div class="col-md-6">
					<div class="mb-2">
						Radio
						{!!
							Form::radio([
								'name'    => 'test',
								'class'   => '',
								'attr'    => '',
								'data' => [
									'key1' => 'Value 1',
									'key2' => 'Value 2',
									'key3' => 'Value 3',
									'key4' => 'Value 4',
								],
								'checked' => 'key2',
								'disabled' => ['key1', 'key4']
							])
						!!}
					</div>

					<div class="mb-2">
						Checkbox
						{!!
							Form::checkbox([
								'name'    => 'test',
								'class'   => '',
								'attr'    => '',
								'data' => [
									'key1' => 'Value 1',
									'key2' => 'Value 2',
									'key3' => 'Value 3',
									'key4' => 'Value 4',
								],
								'checked' => ['key2', 'key3'],
								'disabled' => ['key1', 'key4']
							])
						!!}
					</div>

					<div class="mb-2">
						{!!
							Form::switch([
								'label' => 'Switch',
								'name'    => 'test',
								'value'   => true,
								'class'   => '',
								'attr'    => ''
							])
						!!}
					</div>

					<div class="mb-2">
						{!!
							Form::colorPicker([
								'label'         => 'Color picker',
								'name'          => 'test',
								'value'         => '#FF00FF',
								'default'       => '',
								'required'      => true,
								'class'         => 'round',
								'attr'          => '',
								'icon'          => 'bx-user',
								'icon_position' => 'left'
							])
						!!}
					</div>

					<div class="mb-2">
						{!!
							Form::datePicker([
								'placeholder' => 'Chọn',
								'name'        => 'test',
								'value'       => '',
								'position'    => 'top',
								'format'      => 'hour day/month/year',
								'config'      => [
									'allow'=>[
										'hours' => [ [6,11], [13,17], [20,23] ],
										'minutes' => true,
										'days' => '1-31',
										'months' => '1-12',
										'weekDay' => [],
										'min' => ['y' => 1997, 'm' => 2, 'd' => 14],
										'max' => ['y' => 2020, 'm' => 3, 'd' => 15]
									],
									'value' => ['day' => date('d'), 'month' => date('m'), 'year' => date('Y') ]
								],
								'class'         => 'round',
								'attr'          => '',
								'icon'          => 'bx-user',
								'icon_position' => 'left',
								'onchange'      => ''
							])
						!!}
					</div>

					<div class="mb-2">
						{!!
							Form::editor([
								'title'       => 'Tiêu đề',
								'placeholder' => 'Test okkk',
								'name'        => 'test',
								'value'       => 'Giá trị',
								'rows'        => 4,
								'class'       => '',
								'attr'        => '',
							])
						!!}
					</div>
				</div>
			</div>
		</div>
	</div>
</section>

<section id="basic-buttons">
	<div class="row">
		<div class="col-12">
			<div class="card">
				<div class="card-header">
					<h4 class="card-title">Basic Buttons</h4>
				</div>
				<div class="card-content">
					<div class="card-body">
						<div class="row">
							<div class="col-12">
								<h6>Default</h6>
								<p>Bootstrap includes six predefined button styles, each serving its own semantic purpose.</p>
								<!-- basic buttons -->
								<button type="button" class="btn btn-primary mr-1 mb-1">Primary</button>
								<button type="button" class="btn btn-secondary mr-1 mb-1">Secondary</button>
								<button type="button" class="btn btn-success mr-1 mb-1">Success</button>
								<button type="button" class="btn btn-info mr-1 mb-1">Info</button>
								<button type="button" class="btn btn-warning mr-1 mb-1">Warning</button>
								<button type="button" class="btn btn-danger mr-1 mb-1">Danger</button>
								<button type="button" class="btn btn-light mr-1 mb-1">Light</button>
							</div>
						</div>
						<hr>
						<div class="row">
							<div class="col-12">
								<h6>Rounded</h6>
								<p>Use a class <code>.round</code> with <code>.btn</code> class to create round button.</p>
								<!-- basic buttons -->
								<button type="button" class="btn btn-primary round mr-1 mb-1">Primary</button>
								<button type="button" class="btn btn-secondary round mr-1 mb-1">Secondary</button>
								<button type="button" class="btn btn-success round mr-1 mb-1">Success</button>
								<button type="button" class="btn btn-info round mr-1 mb-1">Info</button>
								<button type="button" class="btn btn-warning round mr-1 mb-1">Warning</button>
								<button type="button" class="btn btn-danger round mr-1 mb-1">Danger</button>
								<button type="button" class="btn btn-light round mr-1 mb-1">Light</button>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</section>

<section id="outline-button">
	<div class="row">
		<div class="col-12">
			<div class="card">
				<div class="card-header">
					<h4 class="card-title">Outline Buttons</h4>
				</div>
				<div class="card-content">
					<div class="card-body">
						<div class="row">
							<div class="col-12">
								<h6>Default</h6>
								<p>Use a class <code>.btn-outline-{color}</code> to quickly create a outline button.</p>
								<!-- Outline buttons -->
								<button type="button" class="btn btn-outline-primary mr-1 mb-1">Primary</button>
								<button type="button" class="btn btn-outline-secondary mr-1 mb-1">Secondary</button>
								<button type="button" class="btn btn-outline-success mr-1 mb-1">Success</button>
								<button type="button" class="btn btn-outline-info mr-1 mb-1">Info</button>
								<button type="button" class="btn btn-outline-warning mr-1 mb-1">Warning</button>
								<button type="button" class="btn btn-outline-danger mr-1 mb-1">Danger</button>
								<button type="button" class="btn btn-outline-light mr-1 mb-1">Light</button>
							</div>
						</div>
						<hr>
						<div class="row">
							<div class="col-12">
								<h6>Rounded</h6>
								<p>Use a class <code>.round</code> with outline button class to create round outline button.</p>
								<!-- Round buttons -->
								<button type="button" class="btn btn-outline-primary round mr-1 mb-1">Primary</button>
								<button type="button" class="btn btn-outline-secondary round mr-1 mb-1">Secondary</button>
								<button type="button" class="btn btn-outline-success round mr-1 mb-1">Success</button>
								<button type="button" class="btn btn-outline-info round mr-1 mb-1">Info</button>
								<button type="button" class="btn btn-outline-warning round mr-1 mb-1">Warning</button>
								<button type="button" class="btn btn-outline-danger round mr-1 mb-1">Danger</button>
								<button type="button" class="btn btn-outline-light round mr-1 mb-1">Light</button>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</section>

<section id="basic-dropdown">
	<div class="row">
		<div class="col-12">
			<div class="card">
				<div class="card-header">
					<h4 class="card-title">Basic</h4>
				</div>
				<div class="card-content">
					<div class="card-body">
						<div class="btn-group mb-1">
							<div class="dropdown">
								<button class="btn btn-primary dropdown-toggle mr-1" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
									Primary
								</button>
								<div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
									<a class="dropdown-item" href="#">Option 1</a>
									<a class="dropdown-item" href="#">Option 2</a>
									<a class="dropdown-item" href="#">Option 3</a>
								</div>
							</div>
						</div>
						<div class="btn-group mb-1">
							<div class="dropdown">
								<button class="btn btn-secondary dropdown-toggle mr-1" type="button" id="dropdownMenuButtonSec" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
									Secondary
								</button>
								<div class="dropdown-menu" aria-labelledby="dropdownMenuButtonSec">
									<a class="dropdown-item" href="#">Option 1</a>
									<a class="dropdown-item" href="#">Option 2</a>
									<a class="dropdown-item" href="#">Option 3</a>
								</div>
							</div>
						</div>
						<div class="btn-group mb-1">
							<div class="dropdown">
								<button class="btn btn-success dropdown-toggle mr-1" type="button" id="dropdownMenuButton2" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
									Success
								</button>
								<div class="dropdown-menu" aria-labelledby="dropdownMenuButton2">
									<a class="dropdown-item" href="#">Option 1</a>
									<a class="dropdown-item" href="#">Option 2</a>
									<a class="dropdown-item" href="#">Option 3</a>
								</div>
							</div>
						</div>
						<div class="btn-group mb-1">
							<div class="dropdown">
								<button class="btn btn-info dropdown-toggle mr-1" type="button" id="dropdownMenuButton3" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
									Info
								</button>
								<div class="dropdown-menu" aria-labelledby="dropdownMenuButton3">
									<a class="dropdown-item" href="#">Option 1</a>
									<a class="dropdown-item" href="#">Option 2</a>
									<a class="dropdown-item" href="#">Option 3</a>
								</div>
							</div>
						</div>
						<div class="btn-group mb-1">
							<div class="dropdown">
								<button class="btn btn-danger dropdown-toggle mr-1" type="button" id="dropdownMenuButton4" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
									Danger
								</button>
								<div class="dropdown-menu" aria-labelledby="dropdownMenuButton4">
									<a class="dropdown-item" href="#">Option 1</a>
									<a class="dropdown-item" href="#">Option 2</a>
									<a class="dropdown-item" href="#">Option 3</a>
								</div>
							</div>
						</div>
						<div class="btn-group mb-1">
							<div class="dropdown">
								<button class="btn btn-warning dropdown-toggle mr-1" type="button" id="dropdownMenuButton5" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
									Warning
								</button>
								<div class="dropdown-menu" aria-labelledby="dropdownMenuButton5">
									<a class="dropdown-item" href="#">Option 1</a>
									<a class="dropdown-item" href="#">Option 2</a>
									<a class="dropdown-item" href="#">Option 3</a>
								</div>
							</div>
						</div>
						<div class="btn-group mb-1">
							<div class="dropdown">
								<div class="dropdown-menu" aria-labelledby="dropdownMenuButton6">
									<a class="dropdown-item" href="#">Option 1</a>
									<a class="dropdown-item" href="#">Option 2</a>
									<a class="dropdown-item" href="#">Option 3</a>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</section>

<section id="dropdown-with-split-btn">
	<div class="row">
		<div class="col-12">
			<div class="card">
				<div class="card-header">
					<h4 class="card-title">Split Dropdowns</h4>
				</div>
				<div class="card-content">
					<div class="card-body">
						<p>To create a split button add class <code>.dropdown-toggle-split</code> with your dropdown toggle class
							And to add divider between dropdown item use class <code>.doprdown-divider</code>
						</p>
						<div class="btn-group dropdown mr-1 mb-1">
							<button type="button" class="btn btn-primary">Primary</button>
							<button type="button" class="btn btn-primary dropdown-toggle dropdown-toggle-split" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" data-reference="parent">
								<span class="sr-only">Toggle Dropdown</span>
							</button>
							<div class="dropdown-menu">
								<a class="dropdown-item" href="#">Option 1</a>
								<a class="dropdown-item active" href="#">Option 2</a>
								<a class="dropdown-item" href="#">Option 3</a>
								<div class="dropdown-divider"></div>
								<a class="dropdown-item" href="#">Separated link</a>
							</div>
						</div>
						<div class="btn-group dropdown mr-1 mb-1">
							<button type="button" class="btn btn-secondary">Secondary</button>
							<button type="button" class="btn btn-secondary dropdown-toggle dropdown-toggle-split" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" data-reference="parent">
								<span class="sr-only">Toggle Dropdown</span>
							</button>
							<div class="dropdown-menu" x-placement="bottom-start" style="position: absolute; will-change: transform; top: 0px; left: 0px; transform: translate3d(0px, 38px, 0px);">
								<a class="dropdown-item" href="#">Option 1</a>
								<a class="dropdown-item active" href="#">Option 2</a>
								<a class="dropdown-item" href="#">Option 3</a>
								<div class="dropdown-divider"></div>
								<a class="dropdown-item" href="#">Separated link</a>
							</div>
						</div>
						<div class="btn-group dropdown mr-1 mb-1">
							<button type="button" class="btn btn-success">Success</button>
							<button type="button" class="btn btn-success dropdown-toggle dropdown-toggle-split" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" data-reference="parent">
								<span class="sr-only">Toggle Dropdown</span>
							</button>
							<div class="dropdown-menu">
								<h6 class="dropdown-header">Header</h6>
								<a class="dropdown-item" href="#">Option 2</a>
								<a class="dropdown-item" href="#">Option 3</a>
								<div class="dropdown-divider"></div>
								<a class="dropdown-item" href="#">Separated link</a>
							</div>
						</div>
						<div class="btn-group dropdown mr-1 mb-1">
							<button type="button" class="btn btn-info">Info</button>
							<button type="button" class="btn btn-info dropdown-toggle dropdown-toggle-split" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" data-reference="parent">
								<span class="sr-only">Toggle Dropdown</span>
							</button>
							<div class="dropdown-menu">
								<a class="dropdown-item" href="#">Option 1</a>
								<a class="dropdown-item disabled" href="#">Option 2</a>
								<a class="dropdown-item" href="#">Option 3</a>
								<div class="dropdown-divider"></div>
								<a class="dropdown-item" href="#">Separated link</a>
							</div>
						</div>
						<div class="btn-group dropdown mr-1 mb-1">
							<button type="button" class="btn btn-danger">Danger</button>
							<button type="button" class="btn btn-danger dropdown-toggle dropdown-toggle-split" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" data-reference="parent">
								<span class="sr-only">Toggle Dropdown</span>
							</button>
							<div class="dropdown-menu">
								<a class="dropdown-item" href="#">Option 1</a>
								<a class="dropdown-item" href="#">Option 2</a>
								<a class="dropdown-item" href="#">Option 3</a>
								<div class="dropdown-divider"></div>
								<a class="dropdown-item" href="#">Separated link</a>
							</div>
						</div>
						<div class="btn-group dropdown mr-1 mb-1">
							<button type="button" class="btn btn-warning">Warning</button>
							<button type="button" class="btn btn-warning dropdown-toggle dropdown-toggle-split" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" data-reference="parent">
								<span class="sr-only">Toggle Dropdown</span>
							</button>
							<div class="dropdown-menu">
								<a class="dropdown-item" href="#">Option 1</a>
								<a class="dropdown-item" href="#">Option 2</a>
								<a class="dropdown-item" href="#">Option 3</a>
								<div class="dropdown-divider"></div>
								<a class="dropdown-item" href="#">Separated link</a>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</section>

<section>
	<div>
		<div class="card">
			<div class="card-header">
				<h4 class="card-title">Alerts with Icons</h4>
			</div>
			<div class="card-content">
				<div class="card-body">
					<p>
						Add a dismiss button, use <code>.alert-dismissible</code> class, which adds extra padding to the right of the alert and positions the <code>.close</code> button.
					</p>
					<div class="alert alert-primary alert-dismissible mb-2" role="alert">
						<button type="button" class="close" data-dismiss="alert" aria-label="Close">
							<span aria-hidden="true">×</span>
						</button>
						<div class="d-flex align-items-center">
							<i class="bx bx-star"></i>
							<span>
								Good Morning! Start your day with some alerts.
							</span>
						</div>
					</div>
					<div class="alert alert-danger alert-dismissible mb-2" role="alert">
						<button type="button" class="close" data-dismiss="alert" aria-label="Close">
							<span aria-hidden="true">×</span>
						</button>
						<div class="d-flex align-items-center">
							<i class="bx bx-error"></i>
							<span>
								Good Morning! Start your day with some alerts.
							</span>
						</div>
					</div>
					<div class="alert alert-warning alert-dismissible mb-2" role="alert">
						<button type="button" class="close" data-dismiss="alert" aria-label="Close">
							<span aria-hidden="true">×</span>
						</button>
						<div class="d-flex align-items-center">
							<i class="bx bx-error-circle"></i>
							<span>
								Good Morning! Start your day with some alerts.
							</span>
						</div>
					</div>
					<div class="alert alert-success alert-dismissible mb-2" role="alert">
						<button type="button" class="close" data-dismiss="alert" aria-label="Close">
							<span aria-hidden="true">×</span>
						</button>
						<div class="d-flex align-items-center">
							<i class="bx bx-like"></i>
							<span>
								Good Morning! Start your day with some alerts.
							</span>
						</div>
					</div>
					<div class="alert alert-info alert-dismissible mb-2" role="alert">
						<button type="button" class="close" data-dismiss="alert" aria-label="Close">
							<span aria-hidden="true">×</span>
						</button>
						<div class="d-flex align-items-center">
							<i class="bx bx-heart"></i>
							<span>
								Good Morning! Start your day with some alerts.
							</span>
						</div>
					</div>
					<div class="alert alert-secondary alert-dismissible mb-2" role="alert">
						<button type="button" class="close" data-dismiss="alert" aria-label="Close">
							<span aria-hidden="true">×</span>
						</button>
						<div class="d-flex align-items-center">
							<i class="bx bx-edit"></i>
							<span>
								Good Morning! Start your day with some alerts.
							</span>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</section>

<section>
	<div>
		<div class="card">
			<div class="card-header">
				<h4 class="card-title">
					Modal default
				</h4>
			</div>
			<div class="card-content">
				<div class="card-body">
					<!-- button trigger for  Vertically Centered modal -->
					<button type="button" class="btn btn-outline-primary block" data-toggle="modal" data-target="#exampleModalCenter">
						Show
					</button>
					<!-- Vertically Centered modal Modal -->
					<div class="modal fade" id="exampleModalCenter">
						<div class="modal-dialog modal-dialog-centered modal-dialog-centered modal-dialog-scrollable">
							<div class="modal-content">
								<div class="modal-header">
									<h5 class="modal-title">
										Modal title
									</h5>
									<button type="button" class="close" data-dismiss="modal">
										<i class="bx bx-x"></i>
									</button>
								</div>
								<div class="modal-body">
									<p>
										Modal body
									</p>
								</div>
								<div class="modal-footer">
									<button type="button" class="btn btn-secondary" data-dismiss="modal">
										<i class="bx bx-x d-block d-sm-none"></i>
										<span class="d-none d-sm-block">Close</span>
									</button>
									<button type="button" class="btn btn-primary ml-1" data-dismiss="modal">
										<i class="bx bx-check d-block d-sm-none"></i>
										<span class="d-none d-sm-block">Accept</span>
									</button>
								</div>
							</div>
						</div>
					</div>

				</div>
			</div>
		</div>
	</div>
</section>

<section>
	<div>
		<div class="card">
			<div class="card-header">
				<h4 class="card-title">
					Modal color
				</h4>
			</div>
			<div class="card-content">
				<div class="card-body">
					<!-- button trigger for  Vertically Centered modal -->
					<button type="button" class="btn btn-outline-primary block" data-toggle="modal" data-target="#exampleModal2">
						Show
					</button>
					<!-- Vertically Centered modal Modal -->
					<div class="modal fade" id="exampleModal2">
						<div class="modal-dialog modal-dialog-centered modal-dialog-centered modal-dialog-scrollable">
							<div class="modal-content">
								<div class="modal-header bg-primary">
									<h5 class="modal-title text-white">
										Modal title
									</h5>
									<button type="button" class="close" data-dismiss="modal">
										<i class="bx bx-x"></i>
									</button>
								</div>
								<div class="modal-body">
									<p>
										Modal body
									</p>
								</div>
								<div class="modal-footer">
									<button type="button" class="btn btn-secondary" data-dismiss="modal">
										<i class="bx bx-x d-block d-sm-none"></i>
										<span class="d-none d-sm-block">Close</span>
									</button>
									<button type="button" class="btn btn-primary ml-1" data-dismiss="modal">
										<i class="bx bx-check d-block d-sm-none"></i>
										<span class="d-none d-sm-block">Accept</span>
									</button>
								</div>
							</div>
						</div>
					</div>

				</div>
			</div>
		</div>
	</div>
</section>


<section id="basic-tabs-components">
	<div class="card">
		<div class="card-header">
			<div class="card-title">
				<h4>Basic Tab</h4>
			</div>
		</div>
		<div class="card-content">
			<div class="card-body">
				<p>Takes the basic nav from above and adds the <code>.nav-tabs</code> class to generate a tabbed interface.</p>
				<ul class="nav nav-tabs" role="tablist">
					<li class="nav-item current">
						<a class="nav-link active" id="home-tab" data-toggle="tab" href="#home" aria-controls="home" role="tab" aria-selected="true">
							<i class="bx bx-home align-middle"></i>
							<span class="align-middle">Home</span>
						</a>
					</li>
					<li class="nav-item">
						<a class="nav-link" id="profile-tab" data-toggle="tab" href="#profile" aria-controls="profile" role="tab" aria-selected="false">
							<i class="bx bx-user align-middle"></i>
							<span class="align-middle">Service</span>
						</a>
					</li>
					<li class="nav-item">
						<a class="nav-link" id="about-tab" data-toggle="tab" href="#about" aria-controls="about" role="tab" aria-selected="false">
							<i class="bx bx-message-square align-middle"></i>
							<span class="align-middle">Messages</span>
						</a>
					</li>
				</ul>
				<div class="tab-content">
					<div class="tab-pane active" id="home" aria-labelledby="home-tab" role="tabpanel">
						<p>
							Gummi bears liquorice brownie donut pastry bonbon biscuit. Jelly-o pudding fruitcake toffee apple pie
							sugar
							plum biscuit. Sweet roll brownie marshmallow dragée. Carrot cake carrot cake muffin donut gingerbread
							sweet.
							pudding chocolate. Halvah powder sugar plum marshmallow powder biscuit. Biscuit pudding fruitcake. Donut
							gummies
							dessert lollipop pie carrot cake bear claw lollipop danish.
						</p>
					</div>
					<div class="tab-pane" id="profile" aria-labelledby="profile-tab" role="tabpanel">
						<p>
							Pudding candy canes sugar plum cookie chocolate cake powder croissant. Carrot cake tiramisu danish
							candy cake muffin croissant tart dessert. Tiramisu caramels candy canes chocolate cake sweet roll
							liquorice icing cupcake.
						</p>
					</div>
					<div class="tab-pane" id="about" aria-labelledby="about-tab" role="tabpanel">
						<p>
							Carrot cake dragée chocolate. Lemon drops ice cream wafer gummies dragée. Chocolate bar liquorice
							cheesecake cookie chupa chups marshmallow oat cake biscuit. Dessert toffee fruitcake ice cream
							powder
							tootsie roll cake.
						</p>
					</div>
				</div>
			</div>
		</div>
	</div>
</section>

<section id="nav-justified">
	<div class="row">
		<div class="col-sm-12">
			<div class="card">
				<div class="card-header">
					<h4 class="card-title">Justified</h4>
				</div>
				<div class="card-content">
					<div class="card-body">
						<p>
							For equal-width elements, use <code>.nav-justified</code>. All horizontal space will be occupied by nav
							links, but unlike the <code>.nav-fill</code> above, every nav item will be the same width.
						</p>
						<ul class="nav nav-tabs nav-justified" id="myTab2" role="tablist">
							<li class="nav-item current">
								<a class="nav-link active" id="home-tab-justified" data-toggle="tab" href="#home-just" role="tab" aria-controls="home-just" aria-selected="true">
									Home
								</a>
							</li>
							<li class="nav-item">
								<a class="nav-link" id="profile-tab-justified" data-toggle="tab" href="#profile-just" role="tab" aria-controls="profile-just" aria-selected="true">
									Profile
								</a>
							</li>
							<li class="nav-item">
								<a class="nav-link" id="messages-tab-justified" data-toggle="tab" href="#messages-just" role="tab" aria-controls="messages-just" aria-selected="false">
									Messages
								</a>
							</li>
							<li class="nav-item">
								<a class="nav-link" id="settings-tab-justified" data-toggle="tab" href="#settings-just" role="tab" aria-controls="settings-just" aria-selected="false">
									Settings
								</a>
							</li>
						</ul>
						<!-- Tab panes -->
						<div class="tab-content pt-1">
							<div class="tab-pane active" id="home-just" role="tabpanel" aria-labelledby="home-tab-justified">
								<p>
									Biscuit powder jelly beans. Lollipop candy canes croissant icing chocolate cake. Cake fruitcake powder
									pudding pastry.Danish fruitcake bonbon bear claw gummi bears apple pie. Chocolate sweet topping
									fruitcake cake.
								</p>
							</div>
							<div class="tab-pane" id="profile-just" role="tabpanel" aria-labelledby="profile-tab-justified">
								<p>
									Chocolate cake icing tiramisu liquorice toffee donut sweet roll cake. Cupcake dessert icing dragée
									dessert. Liquorice jujubes cake tart pie donut. Cotton candy candy canes lollipop liquorice chocolate
									marzipan muffin pie liquorice.
								</p>
							</div>
							<div class="tab-pane" id="messages-just" role="tabpanel" aria-labelledby="messages-tab-justified">
								<p>
									Tootsie roll oat cake I love bear claw I love caramels caramels halvah chocolate bar. Cotton candy
									gummi bears pudding pie apple pie cookie. Cheesecake jujubes lemon drops danish dessert I love
									caramels powder.
								</p>
							</div>
							<div class="tab-pane" id="settings-just" role="tabpanel" aria-labelledby="settings-tab-justified">
								<p>
									Biscuit powder jelly beans. Lollipop candy canes croissant icing chocolate cake. Cake fruitcake powder
									pudding pastry.I love caramels caramels halvah chocolate bar. Cotton candy
									gummi bears pudding pie apple pie cookie.
								</p>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</section>
<script type="text/javascript">
	!function(n,t,a){"use strict";a(".nav-tabs .nav-item").click(function(){a(this).addClass("current").siblings().removeClass("current")}),a(".nav-tabs .nav-item").find(".active").parent().addClass("current")}(window,document,jQuery);
</script>

<section id="basic-badges">
	<div class="row">
		<div class="col-12">
			<div class="card">
				<div class="card-header">
					<h4 class="card-title">Basic Badges</h4>
				</div>
				<div class="card-content">
					<div class="card-body">
						<div class="row">
							<div class="col-12">
								<p>Use the <code>.badge</code> class, followed by<code>.badge-{colorName}</code>class within element to create
								badge with different color options.</p>
								<!-- basic Badges -->
								<div class="badge badge-primary mr-1 mb-1">Primary</div>
								<div class="badge badge-secondary mr-1 mb-1">Secondary</div>
								<div class="badge badge-success mr-1 mb-1">Success</div>
								<div class="badge badge-info mr-1 mb-1">Info</div>
								<div class="badge badge-warning mr-1 mb-1">Warning</div>
								<div class="badge badge-danger mb-1">Danger</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</section>

<section id="basic-pill-badges">
	<div class="row">
		<div class="col-12">
			<div class="card">
				<div class="card-header">
					<h4 class="card-title">Basic Pill Badges</h4>
				</div>
				<div class="card-content">
					<div class="card-body">
						<div class="row">
							<div class="col-12">
								<p>Use the <code>.badge-pill</code> modifier class to make badges more rounded (with a larger
								border-radius and additional horizontal padding). </p>
								<!-- basic Badges Pill -->
								<div class="badge badge-pill badge-primary mr-1 mb-1">Primary</div>
								<div class="badge badge-pill badge-secondary mr-1 mb-1">Secondary</div>
								<div class="badge badge-pill badge-success mr-1 mb-1">Success</div>
								<div class="badge badge-pill badge-info mr-1 mb-1">Info</div>
								<div class="badge badge-pill badge-warning mr-1 mb-1">Warning</div>
								<div class="badge badge-pill badge-danger mb-1">Danger</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</section>

<section id="pill-badge-notification">
	<div class="row">
		<div class="col-12">
			<div class="card">
				<div class="card-header">
					<h4 class="card-title">Pill Badges as Notification</h4>
				</div>
				<div class="card-content">
					<div class="card-body">
						<p>Use class <code>.badge-up</code> within <code>.position-relative</code> class to add badges as
						notification.</p>
						<div class="position-relative d-inline-block mr-2">
							<i class="bx bxl-facebook font-medium-5 text-primary"></i>
							<span class="badge badge-pill badge-primary badge-up badge-round">4</span>
						</div>
						<div class="position-relative d-inline-block mr-2">
							<i class="bx bxl-twitter font-medium-5 text-info"></i>
							<span class="badge badge-pill badge-info badge-up badge-round">5</span>
						</div>
						<div class="position-relative d-inline-block">
							<i class="bx bxl-instagram font-medium-5 text-danger"></i>
							<span class="badge badge-pill badge-danger badge-up badge-round badge-glow">6</span>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</section>

<section>
	<div><!-- default Progress start -->
		<section id="default-progress">
			<div class="row">
				<div class="col-12">
					<div class="card">
						<div class="card-header">
							<h4 class="card-title">Progress Default</h4>
						</div>
						<div class="card-content">
							<div class="card-body">
								<p>Use class <code>.progress-bar-{color-name}</code> to add different colors to progressbar.</p>
								<div class="progress progress-bar-primary mb-2">
									<div class="progress-bar" role="progressbar" aria-valuenow="20" aria-valuemin="20" aria-valuemax="100" style="width:20%"></div>
								</div>
								<div class="progress progress-bar-secondary mb-2">
									<div class="progress-bar" role="progressbar" aria-valuenow="55" aria-valuemin="20" aria-valuemax="100" style="width:55%"></div>
								</div>
								<div class="progress progress-bar-success mb-2">
									<div class="progress-bar" role="progressbar" aria-valuenow="40" aria-valuemin="40" aria-valuemax="100" style="width:40%"></div>
								</div>
								<div class="progress progress-bar-danger mb-2">
									<div class="progress-bar" role="progressbar" aria-valuenow="60" aria-valuemin="60" aria-valuemax="100" style="width:60%"></div>
								</div>
								<div class="progress progress-bar-warning mb-2">
									<div class="progress-bar" role="progressbar" aria-valuenow="75" aria-valuemin="75" aria-valuemax="100" style="width:75%"></div>
								</div>
								<div class="progress progress-bar-info mb-2">
									<div class="progress-bar" role="progressbar" aria-valuenow="80" aria-valuemin="80" aria-valuemax="100" style="width:80%"></div>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</section>
		<!-- default Progress end -->
		<!-- Labeled Progress start -->
		<section id="labeled-progress">
			<div class="row">
				<div class="col-12">
					<div class="card">
						<div class="card-header">
							<h4 class="card-title">Labeled Progress</h4>
						</div>
						<div class="card-content">
							<div class="card-body">
								<p class="mb-3">Use class <code>progress-label</code>. to add label.</p>
								<div class="progress progress-bar-primary mb-2 ">
									<div class="progress-bar progress-label" role="progressbar" aria-valuenow="20" aria-valuemin="20" aria-valuemax="100" style="width:20%"></div>
								</div>
								<div class="progress progress-bar-success mb-2 ">
									<div class="progress-bar progress-label" role="progressbar" aria-valuenow="40" aria-valuemin="40" aria-valuemax="100" style="width:40%"></div>
								</div>
								<div class="progress progress-bar-danger mb-2 ">
									<div class="progress-bar progress-label" role="progressbar" aria-valuenow="60" aria-valuemin="60" aria-valuemax="100" style="width:60%"></div>
								</div>
								<div class="progress progress-bar-info mb-2 ">
									<div class="progress-bar progress-label" role="progressbar" aria-valuenow="80" aria-valuemin="80" aria-valuemax="100" style="width:80%"></div>
								</div>
								<div class="progress progress-bar-warning mb-2 ">
									<div class="progress-bar progress-label" role="progressbar" aria-valuenow="100" aria-valuemin="100" aria-valuemax="100" style="width:100%"></div>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</section>
		<!-- Labeled Progress end -->

		<!-- Striped Progress start -->
		<section id="striped-progress">
			<div class="row">
				<div class="col-12">
					<div class="card">
						<div class="card-header">
							<h4 class="card-title">Striped Progress</h4>
						</div>
						<div class="card-content">
							<div class="card-body">
								<p>Add <code class="highlighter-rouge">.progress-bar-striped</code> to any <code class="highlighter-rouge">.progress-bar</code> to apply a stripe via CSS gradient over the progress
								bar’s background color.</p>
								<div class="progress progress-bar-primary mb-2">
									<div class="progress-bar progress-bar-striped" role="progressbar" aria-valuenow="20" aria-valuemin="20" aria-valuemax="100" style="width:20%"></div>
								</div>
								<div class="progress progress-bar-success mb-2">
									<div class="progress-bar progress-bar-striped" role="progressbar" aria-valuenow="40" aria-valuemin="40" aria-valuemax="100" style="width:40%"></div>
								</div>
								<div class="progress progress-bar-danger mb-2">
									<div class="progress-bar progress-bar-striped" role="progressbar" aria-valuenow="60" aria-valuemin="60" aria-valuemax="100" style="width:60%"></div>
								</div>
								<div class="progress progress-bar-info mb-2">
									<div class="progress-bar progress-bar-striped" role="progressbar" aria-valuenow="80" aria-valuemin="80" aria-valuemax="100" style="width:80%"></div>
								</div>
								<div class="progress progress-bar-warning mb-2">
									<div class="progress-bar progress-bar-striped" role="progressbar" aria-valuenow="100" aria-valuemin="100" aria-valuemax="100" style="width:100%"></div>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</section>
		<!-- Striped Progress end -->

		<!-- Animated Progress start -->
		<section id="animated-progress">
			<div class="row">
				<div class="col-12">
					<div class="card">
						<div class="card-header">
							<h4 class="card-title">Animated Progress</h4>
						</div>
						<div class="card-content">
							<div class="card-body">
								<p>To get progressbar with animated effect, add <code>.progress-bar-animated</code> with
									<code>.progress-bar</code> class.</p>
									<div class="progress progress-bar-primary mb-2">
										<div class="progress-bar progress-bar-striped progress-bar-animated" role="progressbar" aria-valuenow="20" aria-valuemin="20" aria-valuemax="100" style="width:20%"></div>
									</div>
									<div class="progress progress-bar-success mb-2">
										<div class="progress-bar progress-bar-striped progress-bar-animated" role="progressbar" aria-valuenow="40" aria-valuemin="40" aria-valuemax="100" style="width:40%"></div>
									</div>
									<div class="progress progress-bar-danger mb-2">
										<div class="progress-bar progress-bar-striped progress-bar-animated" role="progressbar" aria-valuenow="60" aria-valuemin="60" aria-valuemax="100" style="width:60%"></div>
									</div>
									<div class="progress progress-bar-info mb-2">
										<div class="progress-bar progress-bar-striped progress-bar-animated" role="progressbar" aria-valuenow="80" aria-valuemin="80" aria-valuemax="100" style="width:80%"></div>
									</div>
									<div class="progress progress-bar-warning mb-2">
										<div class="progress-bar progress-bar-striped progress-bar-animated" role="progressbar" aria-valuenow="100" aria-valuemin="100" aria-valuemax="100" style="width:100%"></div>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
			</section>
			<!-- Animated Progress end -->

			<!-- Progress Sizes start -->
			<section id="progress-sizes">
				<div class="row">
					<div class="col-12">
						<div class="card">
							<div class="card-header">
								<h4 class="card-title">Progress Sizes</h4>
							</div>
							<div class="card-content">
								<div class="card-body">
									<p>For Default progress, No size class needed. you can use class <code>.progress-sm</code>
										with <code>.progress</code> to change size of your progress bar.
									</p>
									<div class="progress progress-bar-primary mb-2">
										<div class="progress-bar" role="progressbar" aria-valuenow="20" aria-valuemin="20" aria-valuemax="100" style="width:20%;"></div>
									</div>
									<div class="progress progress-bar-info mb-1 progress-sm">
										<div class="progress-bar" role="progressbar" aria-valuenow="50" aria-valuemin="80" aria-valuemax="100" style="width:50%;"></div>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
			</section>
			<!-- Progress Sizes end -->
		</div>
	</section>


<section id="types">
	<div class="card">
		<div class="card-header">
			<h4 class="card-title">Types</h4>
		</div>
		<div class="card-content">
			<div class="card-body">
				<button type="button" class="btn btn-outline-success mr-1 mb-1" id="type-success">Success</button>
				<button type="button" class="btn btn-outline-info mr-1 mb-1" id="type-info">Info</button>
				<button type="button" class="btn btn-outline-warning mr-1 mb-1" id="type-warning">Warning</button>
				<button type="button" class="btn btn-outline-danger mr-1 mb-1" id="type-error">Error</button>
				<button type="button" class="btn btn-outline-danger mr-1 mb-1" id="remove-toast">Remove
              Toast</button>
			</div>
		</div>
	</div>
</section>
<script type="text/javascript">
	$(document).ready(function() {
    $("#type-success").on("click", function() {
        toastr.success("Have fun storming the castle!", "Miracle Max Says")
    }), $("#type-info").on("click", function() {
        toastr.info("We do have the Kapua suite available.", "Turtle Bay Resort")
    }), $("#type-warning").on("click", function() {
        toastr.warning("My name is Inigo Montoya. You killed my father, prepare to die!")
    }), $("#type-error").on("click", function() {
        toastr.error("I do not think that word means what you think it means.", "Inconceivable!")
    }), $("#position-top-left").on("click", function() {
        toastr.info("I do not think that word means what you think it means.", "Top Left!", {
            positionClass: "toast-top-left",
            containerId: "toast-top-left"
        })
    }), $("#position-top-center").on("click", function() {
        toastr.info("I do not think that word means what you think it means.", "Top Center!", {
            positionClass: "toast-top-center",
            containerId: "toast-top-center"
        })
    }), $("#position-top-right").on("click", function() {
        toastr.info("I do not think that word means what you think it means.", "Top Right!", {
            positionClass: "toast-top-right",
            containerId: "toast-top-right"
        })
    }), $("#position-top-full").on("click", function() {
        toastr.info("I do not think that word means what you think it means.", "Top Full Width!", {
            positionClass: "toast-top-full-width"
        })
    }), $("#position-bottom-left").on("click", function() {
        toastr.info("I do not think that word means what you think it means.", "Bottom Left!", {
            positionClass: "toast-bottom-left",
            containerId: "toast-bottom-left"
        })
    }), $("#position-bottom-center").on("click", function() {
        toastr.info("I do not think that word means what you think it means.", "Bottom Center!", {
            positionClass: "toast-bottom-center",
            containerId: "toast-bottom-center"
        })
    }), $("#position-bottom-right").on("click", function() {
        toastr.info("I do not think that word means what you think it means.", "Bottom Right!", {
            positionClass: "toast-bottom-right",
            containerId: "toast-bottom-right"
        })
    }), $("#position-bottom-full").on("click", function() {
        toastr.info("I do not think that word means what you think it means.", "Bottom Full Width!", {
            positionClass: "toast-bottom-full-width"
        })
    }), $("#text-notification").on("click", function() {
        toastr.info("Have fun storming the castle!", "Miracle Max Says")
    }), $("#close-button").on("click", function() {
        toastr.success("Have fun storming the castle!", "With Close Button", {
            closeButton: !0
        })
    }), $("#progress-bar").on("click", function() {
        toastr.warning("Have fun storming the castle!", "Progress Bar", {
            progressBar: !0
        })
    }), $("#clear-toast-btn").on("click", function() {
        toastr.error('Clear itself?<br /><br /><button type="button" class="btn btn-primary clear">Yes</button>', "Clear Toast Button")
    }), $("#show-remove-toast").on("click", function() {
        toastr.info("Have fun storming the castle!", "Miracle Max Says")
    }), $("#remove-toast").on("click", function() {
        toastr.remove()
    }), $("#show-clear-toast").on("click", function() {
        toastr.info("Have fun storming the castle!", "Miracle Max Says")
    }), $("#clear-toast").on("click", function() {
        toastr.clear()
    }), $("#fast-duration").on("click", function() {
        toastr.success("Have fun storming the castle!", "Fast Duration", {
            showDuration: 500
        })
    }), $("#slow-duration").on("click", function() {
        toastr.warning("Have fun storming the castle!", "Slow Duration", {
            hideDuration: 3e3
        })
    }), $("#timeout").on("click", function() {
        toastr.error("I do not think that word means what you think it means.", "Timeout!", {
            timeOut: 5e3
        })
    }), $("#sticky").on("click", function() {
        toastr.info("I do not think that word means what you think it means.", "Sticky!", {
            timeOut: 0
        })
    }), $("#slide-toast").on("click", function() {
        toastr.success("I do not think that word means what you think it means.", "Slide Down / Slide Up!", {
            showMethod: "slideDown",
            hideMethod: "slideUp",
            timeOut: 2e3
        })
    }), $("#fade-toast").on("click", function() {
        toastr.success("I do not think that word means what you think it means.", "Slide Down / Slide Up!", {
            showMethod: "fadeIn",
            hideMethod: "fadeOut",
            timeOut: 2e3
        })
    })
});
</script>


<section id="basic-examples">
	<div class="card">
		<div class="card-header">
			<h4 class="card-title">Sweet Alert</h4>
		</div>
		<div class="card-content">
			<div class="card-body">
				<p>SweetAlert automatically centers itself on the page and looks great no matter if you're using a desktop
				computer, mobile or tablet. It's even highly customizable, as you can see below!</p>
				<button type="button" class="btn btn-outline-primary mr-1 mb-1" id="basic-alert">Basic</button>
				<button type="button" class="btn btn-outline-primary mr-1 mb-1" id="with-title">With Title</button>
				<button type="button" class="btn btn-outline-primary mr-1 mb-1" id="footer-alert">With Footer</button>
				<button type="button" class="btn btn-outline-primary mr-1 mb-1" id="html-alert">HTML</button>
			</div>
		</div>
	</div>
</section>

<script src="/assets/plugins/sweet-alert/sweetalert2.all.min.js"></script>
<script type="text/javascript">
	$(document).ready(function () {
    $("#basic-alert").on("click", function () {
        Swal.fire( {
            title: "Any fool can use a computer", confirmButtonClass: "btn btn-primary", buttonsStyling: !1
        }
        )
    }
    ), $("#with-title").on("click", function () {
        Swal.fire( {
            title: "The Internet?,", text: "That thing is still around?", confirmButtonClass: "btn btn-primary", buttonsStyling: !1
        }
        )
    }
    ), $("#footer-alert").on("click", function () {
        Swal.fire( {
            type: "error", title: "Oops...", text: "Something went wrong!", footer: "<a href>Why do I have this issue?</a>", confirmButtonClass: "btn btn-primary", buttonsStyling: !1
        }
        )
    }
    ), $("#html-alert").on("click", function () {
        Swal.fire( {
            title: "<strong>HTML <u>example</u></strong>", type: "info", html: 'You can use <b>bold text</b>, <a href="https://pixinvent.com/" target="_blank">links</a> and other HTML tags', showCloseButton: !0, showCancelButton: !0, focusConfirm: !1, confirmButtonText: '<i class="fa fa-thumbs-up"></i> Great!', confirmButtonAriaLabel: "Thumbs up, great!", cancelButtonText: '<i class="fa fa-thumbs-down"></i>', cancelButtonAriaLabel: "Thumbs down", confirmButtonClass: "btn btn-primary", buttonsStyling: !1, cancelButtonClass: "btn btn-danger ml-1"
        }
        )
    }
    ), $("#position-top-start").on("click", function () {
        Swal.fire( {
            position: "top-start", type: "success", title: "Your work has been saved", showConfirmButton: !1, timer: 1500, confirmButtonClass: "btn btn-primary", buttonsStyling: !1
        }
        )
    }
    ), $("#position-top-end").on("click", function () {
        Swal.fire( {
            position: "top-end", type: "success", title: "Your work has been saved", showConfirmButton: !1, timer: 1500, confirmButtonClass: "btn btn-primary", buttonsStyling: !1
        }
        )
    }
    ), $("#position-bottom-start").on("click", function () {
        Swal.fire( {
            position: "bottom-start", type: "success", title: "Your work has been saved", showConfirmButton: !1, timer: 1500, confirmButtonClass: "btn btn-primary", buttonsStyling: !1
        }
        )
    }
    ), $("#position-bottom-end").on("click", function () {
        Swal.fire( {
            position: "bottom-end", type: "success", title: "Your work has been saved", showConfirmButton: !1, timer: 1500, confirmButtonClass: "btn btn-primary", buttonsStyling: !1
        }
        )
    }
    ), $("#bounce-in-animation").on("click", function () {
        Swal.fire( {
            title: "Bounce In Animation", animation: !1, customClass: "animated bounceIn", confirmButtonClass: "btn btn-primary", buttonsStyling: !1
        }
        )
    }
    ), $("#fade-in-animation").on("click", function () {
        Swal.fire( {
            title: "Fade In Animation", animation: !1, customClass: "animated fadeIn", confirmButtonClass: "btn btn-primary", buttonsStyling: !1
        }
        )
    }
    ), $("#flip-x-animation").on("click", function () {
        Swal.fire( {
            title: "Flip In Animation", animation: !1, customClass: "animated flipInX", confirmButtonClass: "btn btn-primary", buttonsStyling: !1
        }
        )
    }
    ), $("#tada-animation").on("click", function () {
        Swal.fire( {
            title: "Tada Animation", animation: !1, customClass: "animated tada", confirmButtonClass: "btn btn-primary", buttonsStyling: !1
        }
        )
    }
    ), $("#shake-animation").on("click", function () {
        Swal.fire( {
            title: "Shake Animation", animation: !1, customClass: "animated shake", confirmButtonClass: "btn btn-primary", buttonsStyling: !1
        }
        )
    }
    ), $("#type-success").on("click", function () {
        Swal.fire( {
            title: "Good job!", text: "You clicked the button!", type: "success", confirmButtonClass: "btn btn-primary", buttonsStyling: !1
        }
        )
    }
    ), $("#type-info").on("click", function () {
        Swal.fire( {
            title: "Info!", text: "You clicked the button!", type: "info", confirmButtonClass: "btn btn-primary", buttonsStyling: !1
        }
        )
    }
    ), $("#type-warning").on("click", function () {
        Swal.fire( {
            title: "Warning!", text: " You clicked the button!", type: "warning", confirmButtonClass: "btn btn-primary", buttonsStyling: !1
        }
        )
    }
    ), $("#type-error").on("click", function () {
        Swal.fire( {
            title: "Error!", text: " You clicked the button!", type: "error", confirmButtonClass: "btn btn-primary", buttonsStyling: !1
        }
        )
    }
    ), $("#custom-icon").on("click", function () {
        Swal.fire( {
            title: "Sweet!", text: "Modal with a custom image.", imageUrl: "../../../app-assets/images/slider/04.jpg", imageWidth: 400, imageHeight: 200, imageAlt: "Custom image", animation: !1, confirmButtonClass: "btn btn-primary", buttonsStyling: !1
        }
        )
    }
    ), $("#auto-close").on("click", function () {
        var t;
        Swal.fire( {
            title: "Auto close alert!", html: "I will close in <strong></strong> seconds.", timer: 2e3, confirmButtonClass: "btn btn-primary", buttonsStyling: !1, onBeforeOpen: function () {
                Swal.showLoading(), t=setInterval(function () {
                    Swal.getContent().querySelector("strong").textContent=Swal.getTimerLeft()
                }
                , 100)
            }
            , onClose: function () {
                clearInterval(t)
            }
        }
        ).then(function (t) {
            t.dismiss===Swal.DismissReason.timer && console.log("I was closed by the timer")
        }
        )
    }
    ), $("#outside-click").on("click", function () {
        Swal.fire( {
            title: "Click outside to close!", text: "This is a cool message!", allowOutsideClick: !0, confirmButtonClass: "btn btn-primary", buttonsStyling: !1
        }
        )
    }
    ), $("#prompt-function").on("click", function () {
        Swal.mixin( {
            input: "text", confirmButtonText: "Next &rarr;", showCancelButton: !0, progressSteps: ["1", "2", "3"], confirmButtonClass: "btn btn-primary", buttonsStyling: !1, cancelButtonClass: "btn btn-danger ml-1"
        }
        ).queue([ {
            title: "Question 1", text: "Chaining swal2 modals is easy"
        }
        , "Question 2", "Question 3"]).then(function (t) {
            t.value && Swal.fire( {
                title: "All done!", html: "Your answers: <pre><code>" + JSON.stringify(t.value) + "</code></pre>", confirmButtonText: "Lovely!"
            }
            )
        }
        )
    }
    ), $("#ajax-request").on("click", function () {
        Swal.fire( {
            title: "Search for a user", input: "text", confirmButtonClass: "btn btn-primary", buttonsStyling: !1, inputAttributes: {
                autocapitalize: "off"
            }
            , showCancelButton: !0, confirmButtonText: "Look up", showLoaderOnConfirm: !0, cancelButtonClass: "btn btn-danger ml-1", preConfirm: function (t) {
                return fetch("//api.github.com/users/" + t).then(function (t) {
                    if (!t.ok) throw console.log(t), new Error(t.statusText);
                    return t.json()
                }
                ).catch(function (t) {
                    Swal.showValidationMessage("Request failed:  " + t)
                }
                )
            }
            , allowOutsideClick: function () {
                Swal.isLoading()
            }
        }
        ).then(function (t) {
            t.value && Swal.fire( {
                title: t.value.login + "'s avatar", imageUrl: t.value.avatar_url
            }
            )
        }
        )
    }
    ), $("#confirm-text").on("click", function () {
        Swal.fire( {
            title: "Are you sure?", text: "You won't be able to revert this!", type: "warning", showCancelButton: !0, confirmButtonColor: "#3085d6", cancelButtonColor: "#d33", confirmButtonText: "Yes, delete it!", confirmButtonClass: "btn btn-primary", cancelButtonClass: "btn btn-danger ml-1", buttonsStyling: !1
        }
        ).then(function (t) {
            t.value && Swal.fire( {
                type: "success", title: "Deleted!", text: "Your file has been deleted.", confirmButtonClass: "btn btn-success"
            }
            )
        }
        )
    }
    ), $("#confirm-color").on("click", function () {
        Swal.fire( {
            title: "Are you sure?", text: "You won't be able to revert this!", type: "warning", showCancelButton: !0, confirmButtonColor: "#3085d6", cancelButtonColor: "#d33", confirmButtonText: "Yes, delete it!", confirmButtonClass: "btn btn-primary", cancelButtonClass: "btn btn-danger ml-1", buttonsStyling: !1
        }
        ).then(function (t) {
            t.value ? Swal.fire( {
                type: "success", title: "Deleted!", text: "Your file has been deleted.", confirmButtonClass: "btn btn-success"
            }
            ): t.dismiss===Swal.DismissReason.cancel && Swal.fire( {
                title: "Cancelled", text: "Your imaginary file is safe :)", type: "error", confirmButtonClass: "btn btn-success"
            }
            )
        }
        )
    }
    )
});
</script>


<section id="input-group-buttons">
	<div class="row">
		<div class="col-12">
			<div class="card">
				<div class="card-header">
					<h4 class="card-title">Input Groups with Buttons</h4>
				</div>
				<div class="card-content">
					<div class="card-body">
						<p>Add span with <code>.input-group-btn</code> class and add button inside <b>before</b> or <b>after</b> <code>&lt;input&gt;</code>.</p>
						<div class="row">
							<div class="col-md-6 mb-1">
								<fieldset>
									<div class="input-group">
										<input type="text" class="form-control" placeholder="Button on right" aria-describedby="button-addon2">
										<div class="input-group-append" id="button-addon2">
											<button class="btn btn-primary" type="button">Go</button>
										</div>
									</div>
								</fieldset>
							</div>
							<div class="col-md-6 mb-1">
								<fieldset>
									<div class="input-group">
										<div class="input-group-prepend">
											<button class="btn btn-primary" type="button"><i class="bx bx-search"></i></button>
										</div>
										<input type="text" class="form-control" placeholder="Button on both side" aria-label="Amount">
										<div class="input-group-append">
											<button class="btn btn-primary" type="button">Search !</button>
										</div>
									</div>
								</fieldset>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</section>

<section id="input-group-dropdown">
	<div class="row">
		<div class="col-12">
			<div class="card">
				<div class="card-header">
					<h4 class="card-title">Input Groups with Dropdown</h4>
				</div>
				<div class="card-content">
					<div class="card-body">
						<p>Add <code>&lt;button&gt;</code> with <code>.dropdown-toggle</code> class and add dropdown-menu after it to get input group with dropdown.</p>
						<div class="row">
							<div class="col-md-6 mb-1">
								<fieldset>
									<div class="input-group">
										<div class="input-group-prepend">
											<button type="button" class="btn btn-primary dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
												Action
											</button>
											<div class="dropdown-menu" x-placement="bottom-start" style="position: absolute; will-change: transform; top: 0px; left: 0px; transform: translate3d(0px, 39px, 0px);">
												<a class="dropdown-item" href="#">Action</a>
												<a class="dropdown-item" href="#">Another action</a>
												<a class="dropdown-item" href="#">Something else here</a>
												<div role="separator" class="dropdown-divider"></div>
												<a class="dropdown-item" href="#">Separated link</a>
											</div>
										</div>
										<input type="text" class="form-control" placeholder="Dropdown on left">
									</div>
								</fieldset>
							</div>
							<div class="col-md-6 mb-1">
								<fieldset>
									<div class="input-group">
										<div class="input-group-prepend">
											<button type="button" class="btn btn-primary dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
												<i class="bx bxs-pencil"></i>
											</button>
											<div class="dropdown-menu" x-placement="bottom-start" style="position: absolute; will-change: transform; top: 0px; left: 0px; transform: translate3d(0px, 39px, 0px);">
												<a class="dropdown-item" href="#">Action</a>
												<a class="dropdown-item" href="#">Another action</a>
												<a class="dropdown-item" href="#">Something else here</a>
												<div role="separator" class="dropdown-divider"></div>
												<a class="dropdown-item" href="#">Separated link</a>
											</div>
										</div>
										<input type="text" class="form-control" placeholder="Dropdown on both side" aria-label="Amount">
										<div class="input-group-append">
											<button type="button" class="btn btn-primary dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
												Action
											</button>
											<div class="dropdown-menu dropdown-menu-right">
												<a class="dropdown-item" href="#">Action</a>
												<a class="dropdown-item" href="#">Another action</a>
												<a class="dropdown-item" href="#">Something else here</a>
												<div role="separator" class="dropdown-divider"></div>
												<a class="dropdown-item" href="#">Separated link</a>
											</div>
										</div>
									</div>
								</fieldset>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</section>

<!-- Form wizard with step validation section start -->
<section id="validation">
	<div class="row">
		<div class="col-12">
			<div class="card">
				<div class="card-header pb-0">
					<h4 class="card-title">Validation Example</h4>
				</div>
				<div class="card-content">
					<div class="card-body">
						<form action="#" class="wizard-validation">
							<!-- Step 1 -->
							<h6>
								<i class="step-icon"></i>
								<span>Baisc Information</span>
							</h6>
							<!-- Step 1 -->
							<!-- body content of step 1 -->
							<fieldset>
								<div class="row">
									<div class="col-md-6">
										<div class="form-group">
											<label for="firstName3">First Name </label>
											<input type="text" class="form-control required" id="firstName3" name="firstName"
											placeholder="Enter Your First Name">
										</div>
									</div>
									<div class="col-md-6">
										<div class="form-group">
											<label for="lastName3">Last Name</label>
											<input type="text" class="form-control required" id="lastName3" name="lastName"
											placeholder="Enter Your Last Name">
										</div>
									</div>
								</div>
								<div class="row">
									<div class="col-md-6">
										<div class="form-group">
											<label for="emailAddress5">Email</label>
											<input type="email" class="form-control required" id="emailAddress5" name="emailAddress"
											placeholder="Enter Your Email">
										</div>
									</div>
									<div class="col-md-6">
										<div class="form-group">
											<label for="location">City</label>
											<select class="custom-select form-control" id="location" name="location">
												<option value="">New York</option>
												<option value="Amsterdam">Chicago</option>
												<option value="Berlin">San Francisco</option>
												<option value="Frankfurt">Boston</option>
											</select>
										</div>
									</div>
								</div>
							</fieldset>
							<!-- body content of step 1 end -->
							<!-- Step 2 -->
							<h6>
								<i class="step-icon"></i>
								<span>Job Details</span>
							</h6>
							<!-- step 2 -->
							<!-- body content of step 2 end -->
							<fieldset>
								<div class="row">
									<div class="col-md-6">
										<div class="form-group">
											<label for="proposalTitle3">
												Proposal Title
											</label>
											<input type="text" class="form-control required" id="proposalTitle3" name="proposalTitle"
											placeholder="Enter Your Proposel Title">
										</div>
										<div class="form-group">
											<label for="jobTitle5">
												Job Title
											</label>
											<input type="text" class="form-control required" id="jobTitle5" name="jobTitle5"
											placeholder="Enter Your Job Title">
										</div>
									</div>
									<div class="col-md-6">
										<div class="form-group">
											<label for="shortDescription3">Short Description</label>
											<textarea name="shortDescription" id="shortDescription3" rows="4" class="form-control"
											placeholder="Please Enter short Description"></textarea>
										</div>
									</div>
								</div>
							</fieldset>
							<!-- body content of step 2 end -->
							<!-- Step 3 -->
							<h6>
								<i class="step-icon"></i>
								<span>Event Details</span>
							</h6>
							<!-- step 3 end -->
							<!-- step 3 content -->
							<fieldset>
								<div class="row">
									<div class="col-md-6">
										<div class="form-group">
											<label for="eventName3">
												Event Name
											</label>
											<input type="text" class="form-control required" id="eventName3" name="eventName"
											placeholder="Enter Event Name">
										</div>
										<div class="form-group">
											<label for="eventStatus3">Event Statuss</label>
											<select class="custom-select form-control required" id="eventStatus3" name="eventStatus">
												<option value="Planning">Planning</option>
												<option value="In Progress">In Progress</option>
												<option value="Finished">Finished</option>
											</select>
										</div>
									</div>
									<div class="col-md-6">
										<div class="form-group">
											<label for="eventLocation3">Event Location </label>
											<select class="custom-select form-control required" id="eventLocation3" name="eventStatus">
												<option value="Planning">New York</option>
												<option value="In Progress">Chicago</option>
												<option value="Finished">San Francisco</option>
												<option value="Finished">Boston</option>
											</select>
										</div>
										<div class="form-group">
											<label class="mr-2">Requirements :</label>
											<div class="c-inputs-stacked">
												<div class="d-inline-block mr-2">
													<fieldset>
														<div class="checkbox">
															<input type="checkbox" class="checkbox__input" id="checkbox6">
															<label for="checkbox6">Staffing</label>
														</div>
													</fieldset>
												</div>
												<div class="d-inline-block">
													<fieldset>
														<div class="checkbox">
															<input type="checkbox" class="checkbox__input" id="checkbox5">
															<label for="checkbox5">Catering</label>
														</div>
													</fieldset>
												</div>
											</div>
										</div>
									</div>
								</div>
							</fieldset>
							<!-- step 3 content end-->
						</form>
					</div>
				</div>
			</div>
		</div>
	</div>
</section>
<!-- Form wizard with step validation section end -->
<link rel="stylesheet" type="text/css" href="/assets/plugins/forms/form-steps/style.css">

<script src="/assets/plugins/forms/form-steps/jquery.steps.min.js"></script>
<script src="/assets/plugins/forms/validation/jquery.validate.min.js"></script>
<script src="/assets/plugins/forms/form-steps/wizard-steps.min.js"></script>
<!-- Form wizard with icon tabs section end -->


<!-- form default repeater -->
<div class="row">
	<div class="col-12">
		<div class="card">
			<div class="card-header">
				<h4 class="card-title">
					Repeating Forms
				</h4>
			</div>
			<div class="card-content">
				<div class="card-body">
					<form class="form repeater-default">
						<div data-repeater-list="group-a">
							<div data-repeater-item>
								<div class="row justify-content-between">
									<div class="col-md-2 col-sm-12 form-group">
										<label for="Email">Email </label>
										<input type="email" class="form-control" id="Email" placeholder="Enter email id" value="emailgmail.com">
									</div>
									<div class="col-md-2 col-sm-12 form-group">
										<label for="password">password</label>
										<input type="password" class="form-control" id="password" placeholder="Enter Password">
									</div>
									<div class="col-md-2 col-sm-12 form-group">
										<label for="gender">Gender</label>
										<select name="gender" id="gender" class="form-control">
											<option value="Male">Male</option>
											<option value="Female">female</option>
										</select>
									</div>
									<div class="col-md-2 col-sm-12 form-group">
										<label for="Profession">Profession</label>
										<select name="profession" id="Profession" class="form-control">
											<option value="FontEnd Developer">Designer</option>
											<option value="BackEnd Developer">Developer</option>
											<option value="Bussiness Analystic">Tester</option>
											<option value="Project Cordinator">Manager</option>
										</select>
									</div>
									<div class="col-md-2 col-sm-12 form-group d-flex align-items-center pt-2">
										<button class="btn btn-danger text-nowrap px-1" data-repeater-delete type="button"> <i
											class="bx bx-x"></i>
											Delete
										</button>
									</div>
								</div>
								<hr>
							</div>
						</div>
						<div class="form-group">
							<div class="col p-0">
								<button class="btn btn-primary" data-repeater-create type="button"><i class="bx bx-plus"></i>
									Add
								</button>
							</div>
						</div>
					</form>
				</div>
			</div>
		</div>
	</div>
</div>
<!--/ form default repeater -->
<script src="/assets/plugins/forms/repeater/jquery.repeater.min.js"></script>
<script type="text/javascript">
	$(document).ready(function(){$(".file-repeater, .contact-repeater, .repeater-default").repeater({show:function(){$(this).slideDown()},hide:function(e){confirm("Are you sure you want to delete this element?")&&$(this).slideUp(e)}})});
</script>


<div class="card" style="height: 393.4px;">
	<div class="card-header">
		<h4 class="card-title">Blockquotes with avatar <small class="text-muted">Default</small></h4>
	</div>
	<div class="card-content">
		<div class="card-body">
			<div class="card-text">
				<p>Blockquotes with avatar. it use Media Object. You can customize image type, border alignment &amp; style.</p>
				<blockquote class="blockquote pl-1">
					<div class="media">
						<div class="pr-1">
							<img class="round" src="/assets/images/portrait/small/avatar-s-5.jpg" alt="Generic placeholder image" style="width: 50px; height: 50px">
						</div>
						<div class="media-body">
							Sometimes life is going to hit you in the head with a brick. Don't lose faith.
						</div>
					</div>
					<footer class="blockquote-footer">Steve Jobs
						<cite title="Source Title">Entrepreneur</cite>
					</footer>
				</blockquote>
			</div>
		</div>
	</div>
</div>

<section id="text-option" class="card overflow-hidden">
  <div class="card-header">
    <h4 class="card-title">Text option</h4>
  </div>
  <div class="card-content">
    <div class="card-body">
      <div class="card-text">
        <h5 class="mb-1">Font size</h5>
        <p>Frest Admin provide font large &amp; small sizes variant classes to change font size.</p>
      </div>
    </div>
    <div class="table-responsive">
      <table class="table mb-0 table-mx-0">
        <thead>
          <tr>
            <th>Example</th>
            <th>Classes</th>
            <th>Snippet</th>
          </tr>
        </thead>
        <tbody>
          <tr>
            <td>
              <span class="font-large-3">Large lg text size.</span>
            </td>
            <td>
              <code>.font-large-3</code>
            </td>
            <td>
              <div class="prism-show-language"><div class="prism-show-language-label">HTML</div></div><pre class=" language-html"><code class=" language-html"><span class="token tag"><span class="token tag"><span class="token punctuation">&lt;</span>p</span> <span class="token attr-name">class</span><span class="token attr-value"><span class="token punctuation">=</span><span class="token punctuation">"</span>font-large-3<span class="token punctuation">"</span></span> <span class="token punctuation">&gt;</span></span>Large<span class="token space"> </span>lg<span class="token space"> </span>text<span class="token space"> </span>size.<span class="token tag"><span class="token tag"><span class="token punctuation">&lt;/</span>p</span><span class="token punctuation">&gt;</span></span></code></pre>
            </td>
          </tr>
          <tr>
            <td>
              <span class="font-large-2">Large lg text size.</span>
            </td>
            <td>
              <code>.font-large-2</code>
            </td>
            <td>
              <div class="prism-show-language"><div class="prism-show-language-label">HTML</div></div><pre class=" language-html"><code class=" language-html"><span class="token tag"><span class="token tag"><span class="token punctuation">&lt;</span>p</span> <span class="token attr-name">class</span><span class="token attr-value"><span class="token punctuation">=</span><span class="token punctuation">"</span>font-large-2<span class="token punctuation">"</span></span> <span class="token punctuation">&gt;</span></span>Large<span class="token space"> </span>lg<span class="token space"> </span>text<span class="token space"> </span>size.<span class="token tag"><span class="token tag"><span class="token punctuation">&lt;/</span>p</span><span class="token punctuation">&gt;</span></span></code></pre>
            </td>
          </tr>
          <tr>
            <td>
              <span class="font-large-1">Large lg text size.</span>
            </td>
            <td>
              <code>.font-large-1</code>
            </td>
            <td>
              <div class="prism-show-language"><div class="prism-show-language-label">HTML</div></div><pre class=" language-html"><code class=" language-html"><span class="token tag"><span class="token tag"><span class="token punctuation">&lt;</span>p</span> <span class="token attr-name">class</span><span class="token attr-value"><span class="token punctuation">=</span><span class="token punctuation">"</span>font-large-1<span class="token punctuation">"</span></span> <span class="token punctuation">&gt;</span></span>Large<span class="token space"> </span>lg<span class="token space"> </span>text<span class="token space"> </span>size.<span class="token tag"><span class="token tag"><span class="token punctuation">&lt;/</span>p</span><span class="token punctuation">&gt;</span></span></code></pre>
            </td>
          </tr>
          <tr>
            <td>
              <span class="font-medium-3">Large md text size.</span>
            </td>
            <td>
              <code>.font-medium-3</code>
            </td>
            <td>
              <div class="prism-show-language"><div class="prism-show-language-label">HTML</div></div><pre class=" language-html"><code class=" language-html"><span class="token tag"><span class="token tag"><span class="token punctuation">&lt;</span>p</span> <span class="token attr-name">class</span><span class="token attr-value"><span class="token punctuation">=</span><span class="token punctuation">"</span>font-medium-3<span class="token punctuation">"</span></span> <span class="token punctuation">&gt;</span></span>Large<span class="token space"> </span>md<span class="token space"> </span>text<span class="token space"> </span>size.<span class="token tag"><span class="token tag"><span class="token punctuation">&lt;/</span>p</span><span class="token punctuation">&gt;</span></span></code></pre>
            </td>
          </tr>
          <tr>
            <td>
              <span class="font-medium-2">Large md text size.</span>
            </td>
            <td>
              <code>.font-medium-2</code>
            </td>
            <td>
              <div class="prism-show-language"><div class="prism-show-language-label">HTML</div></div><pre class=" language-html"><code class=" language-html"><span class="token tag"><span class="token tag"><span class="token punctuation">&lt;</span>p</span> <span class="token attr-name">class</span><span class="token attr-value"><span class="token punctuation">=</span><span class="token punctuation">"</span>font-medium-2<span class="token punctuation">"</span></span> <span class="token punctuation">&gt;</span></span>Large<span class="token space"> </span>md<span class="token space"> </span>text<span class="token space"> </span>size.<span class="token tag"><span class="token tag"><span class="token punctuation">&lt;/</span>p</span><span class="token punctuation">&gt;</span></span></code></pre>
            </td>
          </tr>
          <tr>
            <td>
              <span class="font-medium-1">Large sm text size.</span>
            </td>
            <td>
              <code>.font-medium-1</code>
            </td>
            <td>
              <div class="prism-show-language"><div class="prism-show-language-label">HTML</div></div><pre class=" language-html"><code class=" language-html"><span class="token tag"><span class="token tag"><span class="token punctuation">&lt;</span>p</span> <span class="token attr-name">class</span><span class="token attr-value"><span class="token punctuation">=</span><span class="token punctuation">"</span>font-medium-1<span class="token punctuation">"</span></span> <span class="token punctuation">&gt;</span></span>Large<span class="token space"> </span>sm<span class="token space"> </span>text<span class="token space"> </span>size.<span class="token tag"><span class="token tag"><span class="token punctuation">&lt;/</span>p</span><span class="token punctuation">&gt;</span></span></code></pre>
            </td>
          </tr>
          <tr>
            <td>
              <p>Normal base text size.</p>
            </td>
            <td>
              N/A
            </td>
            <td>
              <div class="prism-show-language"><div class="prism-show-language-label">HTML</div></div><pre class=" language-html"><code class=" language-html"><span class="token tag"><span class="token tag"><span class="token punctuation">&lt;</span>p</span><span class="token punctuation">&gt;</span></span>Normal<span class="token space"> </span>base<span class="token space"> </span>text<span class="token space"> </span>size.<span class="token tag"><span class="token tag"><span class="token punctuation">&lt;/</span>p</span><span class="token punctuation">&gt;</span></span></code></pre>
            </td>
          </tr>
          <tr>
            <td>
              <span class="font-small-3">Small lg text size.</span>
            </td>
            <td>
              <code>.font-small-3</code>
            </td>
            <td>
              <div class="prism-show-language"><div class="prism-show-language-label">HTML</div></div><pre class=" language-html"><code class=" language-html"><span class="token tag"><span class="token tag"><span class="token punctuation">&lt;</span>p</span> <span class="token attr-name">class</span><span class="token attr-value"><span class="token punctuation">=</span><span class="token punctuation">"</span>font-small-3<span class="token punctuation">"</span></span> <span class="token punctuation">&gt;</span></span>Small<span class="token space"> </span>lg<span class="token space"> </span>text<span class="token space"> </span>size.<span class="token tag"><span class="token tag"><span class="token punctuation">&lt;/</span>p</span><span class="token punctuation">&gt;</span></span></code></pre>
            </td>
          </tr>
          <tr>
            <td>
              <span class="font-small-2">Small md text size.</span>
            </td>
            <td>
              <code>.font-small-2</code>
            </td>
            <td>
              <div class="prism-show-language"><div class="prism-show-language-label">HTML</div></div><pre class=" language-html"><code class=" language-html"><span class="token tag"><span class="token tag"><span class="token punctuation">&lt;</span>p</span> <span class="token attr-name">class</span><span class="token attr-value"><span class="token punctuation">=</span><span class="token punctuation">"</span>font-small-2<span class="token punctuation">"</span></span> <span class="token punctuation">&gt;</span></span>Small<span class="token space"> </span>md<span class="token space"> </span>text<span class="token space"> </span>size.<span class="token tag"><span class="token tag"><span class="token punctuation">&lt;/</span>p</span><span class="token punctuation">&gt;</span></span></code></pre>
            </td>
          </tr>
          <tr>
            <td>
              <span class="font-small-1">Small sm text size.</span>
            </td>
            <td>
              <code>.font-small-1</code>
            </td>
            <td>
              <div class="prism-show-language"><div class="prism-show-language-label">HTML</div></div><pre class=" language-html"><code class=" language-html"><span class="token tag"><span class="token tag"><span class="token punctuation">&lt;</span>p</span> <span class="token attr-name">class</span><span class="token attr-value"><span class="token punctuation">=</span><span class="token punctuation">"</span>font-small-1<span class="token punctuation">"</span></span> <span class="token punctuation">&gt;</span></span>Small<span class="token space"> </span>sm<span class="token space"> </span>text<span class="token space"> </span>size.<span class="token tag"><span class="token tag"><span class="token punctuation">&lt;/</span>p</span><span class="token punctuation">&gt;</span></span></code></pre>
            </td>
          </tr>
        </tbody>
      </table>
    </div>
    <div class="card-content">
      <div class="card-body">
        <div class="card-text">
          <h5 class="my-1">Font weight</h5>
          <p>Frest Admin provide font weight class <code>.text-bold-{weight}</code>, where
            <code>{weight} value can be 300, 400, 500, 600, 700.</code></p>
        </div>
      </div>
    </div>
    <div class="table-responsive">
      <table class="table mb-0 table-mx-0">
        <thead>
          <tr>
            <th>Example</th>
            <th>Classes</th>
            <th>Snippet</th>
          </tr>
        </thead>
        <tbody>
          <tr>
            <td>
              <p class="text-bold-300">Font weight 300</p>
            </td>
            <td><code>.text-bold-300</code></td>
            <td>
              <div class="prism-show-language"><div class="prism-show-language-label">HTML</div></div><pre class=" language-html"><code class=" language-html"><span class="token tag"><span class="token tag"><span class="token punctuation">&lt;</span>p</span> <span class="token attr-name">class</span><span class="token attr-value"><span class="token punctuation">=</span><span class="token punctuation">"</span>text-bold-300<span class="token punctuation">"</span></span><span class="token punctuation">&gt;</span></span>Font<span class="token space"> </span>weight<span class="token space"> </span>300.<span class="token tag"><span class="token tag"><span class="token punctuation">&lt;/</span>p</span><span class="token punctuation">&gt;</span></span></code></pre>
            </td>
          </tr>
          <tr>
            <td>
              <p class="text-bold-400">Font weight 400</p>
            </td>
            <td><code>.text-bold-400</code></td>
            <td>
              <div class="prism-show-language"><div class="prism-show-language-label">HTML</div></div><pre class=" language-html"><code class=" language-html"><span class="token tag"><span class="token tag"><span class="token punctuation">&lt;</span>p</span> <span class="token attr-name">class</span><span class="token attr-value"><span class="token punctuation">=</span><span class="token punctuation">"</span>text-bold-400<span class="token punctuation">"</span></span><span class="token punctuation">&gt;</span></span>Font<span class="token space"> </span>weight<span class="token space"> </span>400.<span class="token tag"><span class="token tag"><span class="token punctuation">&lt;/</span>p</span><span class="token punctuation">&gt;</span></span></code></pre>
            </td>
          </tr>
          <tr>
            <td>
              <p class="text-bold-600">Font weight 600</p>
            </td>
            <td><code>.text-bold-600</code></td>
            <td>
              <div class="prism-show-language"><div class="prism-show-language-label">HTML</div></div><pre class=" language-html"><code class=" language-html"><span class="token tag"><span class="token tag"><span class="token punctuation">&lt;</span>p</span> <span class="token attr-name">class</span><span class="token attr-value"><span class="token punctuation">=</span><span class="token punctuation">"</span>text-bold-600<span class="token punctuation">"</span></span><span class="token punctuation">&gt;</span></span>Font<span class="token space"> </span>weight<span class="token space"> </span>600.<span class="token tag"><span class="token tag"><span class="token punctuation">&lt;/</span>p</span><span class="token punctuation">&gt;</span></span></code></pre>
            </td>
          </tr>
          <tr>
            <td>
              <p class="text-bold-700">Font weight 700</p>
            </td>
            <td><code>.text-bold-700</code></td>
            <td>
              <div class="prism-show-language"><div class="prism-show-language-label">HTML</div></div><pre class=" language-html"><code class=" language-html"><span class="token tag"><span class="token tag"><span class="token punctuation">&lt;</span>p</span> <span class="token attr-name">class</span><span class="token attr-value"><span class="token punctuation">=</span><span class="token punctuation">"</span>text-bold-700<span class="token punctuation">"</span></span><span class="token punctuation">&gt;</span></span>Font<span class="token space"> </span>weight<span class="token space"> </span>700.<span class="token tag"><span class="token tag"><span class="token punctuation">&lt;/</span>p</span><span class="token punctuation">&gt;</span></span></code></pre>
            </td>
          </tr>
        </tbody>
      </table>
    </div>
    <div class="card-content">
      <div class="card-body">
        <div class="card-text">
          <h5 class="my-1">Inline text elements</h5>
          <p>Styling for common inline HTML5 elements.</p>
          <p><code class="highlighter-rouge">.mark</code> and <code class="highlighter-rouge">.small</code> classes are
            also available to apply the same styles as <code class="highlighter-rouge">&lt;mark&gt;</code> and <code class="highlighter-rouge">&lt;small&gt;</code> while avoiding any unwanted semantic implications that the
            tags would bring.</p>
          <p class="">While not shown above, feel free to use <code class="highlighter-rouge">&lt;b&gt;</code> and <code class="highlighter-rouge">&lt;i&gt;</code> in HTML5. <code class="highlighter-rouge">&lt;b&gt;</code> is
            meant to highlight words or phrases
            without conveying additional importance while <code class="highlighter-rouge">&lt;i&gt;</code> is mostly for
            voice, technical terms, etc.</p>
        </div>
      </div>
    </div>
    <div class="table-responsive">
      <table class="table mb-0 table-mx-0">
        <thead>
          <tr>
            <th>Example</th>
            <th>Snippet</th>
          </tr>
        </thead>
        <tbody>
          <tr>
            <td>
              <p>You can use the mark tag to
                <mark>highlight</mark> text.</p>
            </td>
            <td>
              <div class="prism-show-language"><div class="prism-show-language-label">HTML</div></div><pre class=" language-html"><code class=" language-html"><span class="token tag"><span class="token tag"><span class="token punctuation">&lt;</span>p</span><span class="token punctuation">&gt;</span></span>You<span class="token space"> </span>can<span class="token space"> </span>use<span class="token space"> </span>the<span class="token space"> </span>mark<span class="token space"> </span>tag<span class="token space"> </span>to<span class="token space"> </span><span class="token tag"><span class="token tag"><span class="token punctuation">&lt;</span>mark</span><span class="token punctuation">&gt;</span></span>highlight<span class="token tag"><span class="token tag"><span class="token punctuation">&lt;/</span>mark</span><span class="token punctuation">&gt;</span></span><span class="token space"> </span>text.<span class="token tag"><span class="token tag"><span class="token punctuation">&lt;/</span>p</span><span class="token punctuation">&gt;</span></span></code></pre>
            </td>
          </tr>
          <tr>
            <td>
              <p>
                <del>This line of text is meant to be treated as deleted text.</del>
              </p>
            </td>
            <td>
              <div class="prism-show-language"><div class="prism-show-language-label">HTML</div></div><pre class=" language-html"><code class=" language-html"><span class="token tag"><span class="token tag"><span class="token punctuation">&lt;</span>p</span><span class="token punctuation">&gt;</span></span><span class="token tag"><span class="token tag"><span class="token punctuation">&lt;</span>del</span><span class="token punctuation">&gt;</span></span>This<span class="token space"> </span>line<span class="token space"> </span>of<span class="token space"> </span>text<span class="token space"> </span>is<span class="token space"> </span>meant<span class="token space"> </span>to<span class="token space"> </span>be<span class="token space"> </span>treated<span class="token space"> </span>as<span class="token space"> </span>deleted<span class="token space"> </span>text.<span class="token tag"><span class="token tag"><span class="token punctuation">&lt;/</span>del</span><span class="token punctuation">&gt;</span></span><span class="token tag"><span class="token tag"><span class="token punctuation">&lt;/</span>p</span><span class="token punctuation">&gt;</span></span></code></pre>
            </td>
          </tr>
          <tr>
            <td>
              <p>
                <s>This line of text is meant to be treated as no longer accurate.</s>
              </p>
            </td>
            <td>
              <div class="prism-show-language"><div class="prism-show-language-label">HTML</div></div><pre class=" language-html"><code class=" language-html"><span class="token tag"><span class="token tag"><span class="token punctuation">&lt;</span>p</span><span class="token punctuation">&gt;</span></span><span class="token tag"><span class="token tag"><span class="token punctuation">&lt;</span>s</span><span class="token punctuation">&gt;</span></span>This<span class="token space"> </span>line<span class="token space"> </span>of<span class="token space"> </span>text<span class="token space"> </span>is<span class="token space"> </span>meant<span class="token space"> </span>to<span class="token space"> </span>be<span class="token space"> </span>treated<span class="token space"> </span>as<span class="token space"> </span>no<span class="token space"> </span>longer<span class="token space"> </span>accurate.<span class="token tag"><span class="token tag"><span class="token punctuation">&lt;/</span>s</span><span class="token punctuation">&gt;</span></span><span class="token tag"><span class="token tag"><span class="token punctuation">&lt;/</span>p</span><span class="token punctuation">&gt;</span></span></code></pre>
            </td>
          </tr>
          <tr>
            <td>
              <p>
                <ins>This line of text
                  is meant to be treated as an addition to the document.</ins>
              </p>
            </td>
            <td>
              <div class="prism-show-language"><div class="prism-show-language-label">HTML</div></div><pre class=" language-html"><code class=" language-html"><span class="token tag"><span class="token tag"><span class="token punctuation">&lt;</span>p</span><span class="token punctuation">&gt;</span></span><span class="token tag"><span class="token tag"><span class="token punctuation">&lt;</span>ins</span><span class="token punctuation">&gt;</span></span>This<span class="token space"> </span>line<span class="token space"> </span>of<span class="token space"> </span>text<span class="token space"> </span>is<span class="token space"> </span>meant<span class="token space"> </span>to<span class="token space"> </span>be<span class="token space"> </span>treated<span class="token space"> </span>as<span class="token space"> </span>an<span class="token space"> </span>addition<span class="token space"> </span>to<span class="token space"> </span>the<span class="token space"> </span>document.<span class="token tag"><span class="token tag"><span class="token punctuation">&lt;/</span>ins</span><span class="token punctuation">&gt;</span></span><span class="token tag"><span class="token tag"><span class="token punctuation">&lt;/</span>p</span><span class="token punctuation">&gt;</span></span></code></pre>
            </td>
          </tr>
          <tr>
            <td>
              <p><u>This line of text will render as underlined</u></p>
            </td>
            <td>
              <div class="prism-show-language"><div class="prism-show-language-label">HTML</div></div><pre class=" language-html"><code class=" language-html"><span class="token tag"><span class="token tag"><span class="token punctuation">&lt;</span>p</span><span class="token punctuation">&gt;</span></span><span class="token tag"><span class="token tag"><span class="token punctuation">&lt;</span>u</span><span class="token punctuation">&gt;</span></span>This<span class="token space"> </span>line<span class="token space"> </span>of<span class="token space"> </span>text<span class="token space"> </span>will<span class="token space"> </span>render<span class="token space"> </span>as<span class="token space"> </span>underlined.<span class="token tag"><span class="token tag"><span class="token punctuation">&lt;/</span>u</span><span class="token punctuation">&gt;</span></span><span class="token tag"><span class="token tag"><span class="token punctuation">&lt;/</span>p</span><span class="token punctuation">&gt;</span></span></code></pre>
            </td>
          </tr>
          <tr>
            <td>
              <p><small>This line of text is meant to be treated as fine print.</small></p>
            </td>
            <td>
              <div class="prism-show-language"><div class="prism-show-language-label">HTML</div></div><pre class=" language-html"><code class=" language-html"><span class="token tag"><span class="token tag"><span class="token punctuation">&lt;</span>p</span><span class="token punctuation">&gt;</span></span><span class="token tag"><span class="token tag"><span class="token punctuation">&lt;</span>small</span><span class="token punctuation">&gt;</span></span>This<span class="token space"> </span>line<span class="token space"> </span>of<span class="token space"> </span>text<span class="token space"> </span>is<span class="token space"> </span>meant<span class="token space"> </span>to<span class="token space"> </span>be<span class="token space"> </span>treated<span class="token space"> </span>as<span class="token space"> </span>fine<span class="token space"> </span>print.<span class="token tag"><span class="token tag"><span class="token punctuation">&lt;/</span>small</span><span class="token punctuation">&gt;</span></span><span class="token tag"><span class="token tag"><span class="token punctuation">&lt;/</span>p</span><span class="token punctuation">&gt;</span></span></code></pre>
            </td>
          </tr>
          <tr>
            <td>
              <p><strong>This line rendered as bold text.</strong></p>
            </td>
            <td>
              <div class="prism-show-language"><div class="prism-show-language-label">HTML</div></div><pre class=" language-html"><code class=" language-html"><span class="token tag"><span class="token tag"><span class="token punctuation">&lt;</span>p</span><span class="token punctuation">&gt;</span></span><span class="token tag"><span class="token tag"><span class="token punctuation">&lt;</span>strong</span><span class="token punctuation">&gt;</span></span>This<span class="token space"> </span>line<span class="token space"> </span>rendered<span class="token space"> </span>as<span class="token space"> </span>bold<span class="token space"> </span>text.<span class="token tag"><span class="token tag"><span class="token punctuation">&lt;/</span>strong</span><span class="token punctuation">&gt;</span></span><span class="token tag"><span class="token tag"><span class="token punctuation">&lt;/</span>p</span><span class="token punctuation">&gt;</span></span></code></pre>
            </td>
          </tr>
          <tr>
            <td>
              <p><em>This line rendered as italicized text.</em></p>
            </td>
            <td>
              <div class="prism-show-language"><div class="prism-show-language-label">HTML</div></div><pre class=" language-html"><code class=" language-html"><span class="token tag"><span class="token tag"><span class="token punctuation">&lt;</span>p</span><span class="token punctuation">&gt;</span></span><span class="token tag"><span class="token tag"><span class="token punctuation">&lt;</span>em</span><span class="token punctuation">&gt;</span></span>This<span class="token space"> </span>line<span class="token space"> </span>rendered<span class="token space"> </span>as<span class="token space"> </span>italicized<span class="token space"> </span>text.<span class="token tag"><span class="token tag"><span class="token punctuation">&lt;/</span>em</span><span class="token punctuation">&gt;</span></span><span class="token tag"><span class="token tag"><span class="token punctuation">&lt;/</span>p</span><span class="token punctuation">&gt;</span></span></code></pre>
            </td>
          </tr>
          <tr>
            <td>
              <p>Sample
                <abbr data-popup="tooltip" title="Abbr title">abbreviation</abbr>
              </p>
            </td>
            <td>
              <div class="prism-show-language"><div class="prism-show-language-label">HTML</div></div><pre class=" language-html"><code class=" language-html"><span class="token tag"><span class="token tag"><span class="token punctuation">&lt;</span>p</span><span class="token punctuation">&gt;</span></span><span class="token space"> </span>Sample<span class="token space"> </span><span class="token tag"><span class="token tag"><span class="token punctuation">&lt;</span>abbr</span><span class="token punctuation">&gt;</span></span>Abbreviations.<span class="token tag"><span class="token tag"><span class="token punctuation">&lt;/</span>abbr</span><span class="token punctuation">&gt;</span></span><span class="token tag"><span class="token tag"><span class="token punctuation">&lt;/</span>p</span><span class="token punctuation">&gt;</span></span></code></pre>
            </td>
          </tr>
          <tr>
            <td>
              <p>Sample
                <abbr title="HyperText Markup Language" class="initialism">HTML</abbr> title.</p>
            </td>
            <td>
              <div class="prism-show-language"><div class="prism-show-language-label">HTML</div></div><pre class=" language-html"><code class=" language-html"><span class="token tag"><span class="token tag"><span class="token punctuation">&lt;</span>p</span><span class="token punctuation">&gt;</span></span><span class="token space"> </span>Sample<span class="token space"> </span><span class="token tag"><span class="token tag"><span class="token punctuation">&lt;</span>abbr</span> <span class="token attr-name">title</span><span class="token attr-value"><span class="token punctuation">=</span><span class="token punctuation">"</span>HyperText Markup Language<span class="token punctuation">"</span></span> <span class="token attr-name">class</span><span class="token attr-value"><span class="token punctuation">=</span><span class="token punctuation">"</span>initialism<span class="token punctuation">"</span></span><span class="token punctuation">&gt;</span></span>HTML.<span class="token tag"><span class="token tag"><span class="token punctuation">&lt;/</span>abbr</span><span class="token punctuation">&gt;</span></span><span class="token tag"><span class="token tag"><span class="token punctuation">&lt;/</span>p</span><span class="token punctuation">&gt;</span></span></code></pre>
            </td>
          </tr>
          <tr>
            <td>
              <var>y</var> =
              <var>m</var>
              <var>x</var> +
              <var>b</var>
            </td>
            <td>
              <div class="prism-show-language"><div class="prism-show-language-label">HTML</div></div><pre class=" language-html"><code class=" language-html"><span class="token tag"><span class="token tag"><span class="token punctuation">&lt;</span>p</span><span class="token punctuation">&gt;</span></span><span class="token space"> </span>For<span class="token space"> </span>indicating<span class="token space"> </span>variables<span class="token space"> </span>use<span class="token space"> </span>the<span class="token space"> </span><span class="token tag"><span class="token tag"><span class="token punctuation">&lt;</span>var</span><span class="token punctuation">&gt;</span></span><span class="token space"> </span>tag.<span class="token tag"><span class="token tag"><span class="token punctuation">&lt;/</span>p</span><span class="token punctuation">&gt;</span></span></code></pre>
            </td>
          </tr>
          <tr>
            <td>
              <p>Edit settings, press
                <kbd>
                  <kbd>ctrl</kbd> +
                  <kbd>,</kbd>
                </kbd>
              </p>
            </td>
            <td>
              <div class="prism-show-language"><div class="prism-show-language-label">HTML</div></div><pre class=" language-html"><code class=" language-html"><span class="token tag"><span class="token tag"><span class="token punctuation">&lt;</span>p</span><span class="token punctuation">&gt;</span></span><span class="token space"> </span>Use<span class="token space"> </span>the<span class="token space"> </span><span class="token tag"><span class="token tag"><span class="token punctuation">&lt;</span>kbd</span><span class="token punctuation">&gt;</span></span><span class="token space"> </span><span class="token space"> </span>to<span class="token space"> </span>indicate<span class="token space"> </span>input<span class="token space"> </span>that<span class="token space"> </span>is<span class="token space"> </span>typically<span class="token space"> </span>entered<span class="token space"> </span>via<span class="token space"> </span>keyboard.<span class="token tag"><span class="token tag"><span class="token punctuation">&lt;/</span>p</span><span class="token punctuation">&gt;</span></span></code></pre>
            </td>
          </tr>
          <tr>
            <td>
              <samp>This text is meant to be treated as sample output from a computer program.</samp>
            </td>
            <td>
              <div class="prism-show-language"><div class="prism-show-language-label">HTML</div></div><pre class=" language-html"><code class=" language-html"><span class="token tag"><span class="token tag"><span class="token punctuation">&lt;</span>p</span><span class="token punctuation">&gt;</span></span><span class="token space"> </span>For<span class="token space"> </span>indicating<span class="token space"> </span>sample<span class="token space"> </span>output<span class="token space"> </span>from<span class="token space"> </span>a<span class="token space"> </span>program<span class="token space"> </span>use<span class="token space"> </span>the<span class="token space"> </span><span class="token space"> </span><span class="token tag"><span class="token tag"><span class="token punctuation">&lt;</span>samp</span><span class="token punctuation">&gt;</span></span><span class="token space"> </span><span class="token space"> </span>tag.<span class="token space"> </span><span class="token tag"><span class="token tag"><span class="token punctuation">&lt;/</span>p</span><span class="token punctuation">&gt;</span></span></code></pre>
            </td>
          </tr>
          <tr>
            <td>
              <code>Inline code snippet</code>
            </td>
            <td>
              <div class="prism-show-language"><div class="prism-show-language-label">HTML</div></div><pre class=" language-html"><code class=" language-html"><span class="token tag"><span class="token tag"><span class="token punctuation">&lt;</span>p</span><span class="token punctuation">&gt;</span></span><span class="token space"> </span>Wrap<span class="token space"> </span>inline<span class="token space"> </span>snippets<span class="token space"> </span>of<span class="token space"> </span>code<span class="token space"> </span>with<span class="token space"> </span><span class="token tag"><span class="token tag"><span class="token punctuation">&lt;</span>code</span><span class="token punctuation">&gt;</span></span><span class="token space"> </span>tag.<span class="token space"> </span><span class="token tag"><span class="token tag"><span class="token punctuation">&lt;/</span>p</span><span class="token punctuation">&gt;</span></span></code></pre>
            </td>
          </tr>
        </tbody>
      </table>
    </div>
  </div>
</section>


<section id="accordion-icon-wrapper">
	<h4 class="mt-3">Accordion With Icon</h4>
	<p>
		To properly achieve the accordion style, be sure to use <code>.accordion</code> as a wrapper. for
		icons on the right of collapse use <code> .collapse-icon</code>and for rotate icon in 90 degree
		use<code> .accordion-icon-rotate </code>.
	</p>
	<div class="accordion collapse-icon accordion-icon-rotate" id="accordionWrapa2">
		<div class="card collapse-header">
			<div id="heading5" class="card-header collapsed" data-toggle="collapse" data-target="#accordion5">
				<span class="collapse-title">
					<i class="bx bx-cloud align-middle"></i>
					<span class="align-middle">Accordion Item 1</span>
				</span>
			</div>
			<div id="accordion5" data-parent="#accordionWrapa2" class="collapse">
				<div class="card-content">
					<div class="card-body">
						Cheesecake cotton candy bonbon muffin cupcake tiramisu croissant. Tootsie roll sweet candy bear
						claw chupa chups lollipop toffee. Macaroon donut liquorice powder candy carrot cake macaroon
						fruitcake. Cookie toffee lollipop cotton candy ice cream dragée soufflé.
						Cake tiramisu lollipop wafer pie soufflé dessert tart. Biscuit ice cream pie apple pie topping
						oat cake dessert. Soufflé icing caramels. Chocolate cake icing ice cream macaroon pie cheesecake
						liquorice apple pie.
					</div>
				</div>
			</div>
		</div>
		<div class="card collapse-header">
			<div id="heading5" class="card-header collapsed" data-toggle="collapse" data-target="#accordion2">
				<span class="collapse-title">
					<i class="bx bx-cloud align-middle"></i>
					<span class="align-middle">Accordion Item 1</span>
				</span>
			</div>
			<div id="accordion2" data-parent="#accordionWrapa2" class="collapse">
				<div class="card-content">
					<div class="card-body">
						Cheesecake cotton candy bonbon muffin cupcake tiramisu croissant. Tootsie roll sweet candy bear
						claw chupa chups lollipop toffee. Macaroon donut liquorice powder candy carrot cake macaroon
						fruitcake. Cookie toffee lollipop cotton candy ice cream dragée soufflé.
						Cake tiramisu lollipop wafer pie soufflé dessert tart. Biscuit ice cream pie apple pie topping
						oat cake dessert. Soufflé icing caramels. Chocolate cake icing ice cream macaroon pie cheesecake
						liquorice apple pie.
					</div>
				</div>
			</div>
		</div>
	</div>
</section>


<div class="card">
	<div class="card-header">
		<h4 class="card-title">Button list Group</h4>
	</div>
	<div class="card-content">
		<div class="card-body">
			<div class="list-group">
				<button type="button" class="list-group-item list-group-item-action active">Cras justo odio</button>
				<button type="button" class="list-group-item list-group-item-action">Dapibus ac facilisis in</button>
				<button type="button" class="list-group-item list-group-item-action">Morbi leo risus</button>
				<button type="button" class="list-group-item list-group-item-action">Porta ac consectetur ac</button>
				<button type="button" class="list-group-item list-group-item-action">Vestibulum at eros</button>
			</div>
		</div>
	</div>
</div>

<div class="card" style="height: 357.9px;">
	<div class="card-header">
		<h4 class="card-title">Badges</h4>
	</div>
	<div class="card-content">
		<div class="card-body">
			<p>Use Utility classes <code>.d-flex</code> <code>.justify-content-between</code>
				<code>align-items-center</code> to create space between badge and your content
			</p>
			<ul class="list-group">
				<li class="list-group-item d-flex justify-content-between align-items-center">
					<span> Biscuit jelly beans macaroon danish pudding.</span>
					<span class="badge badge-warning badge-pill badge-round ml-1">8</span>
				</li>
				<li class="list-group-item d-flex justify-content-between align-items-center">
					<span> chocolate cheesecake candy</span>
					<span class="badge badge-info badge-pill badge-round ml-1">7</span>
				</li>
				<li class="list-group-item d-flex justify-content-between align-items-center">
					<span> Oat cake icing pastry pie carrot</span>
					<span class="badge badge-danger badge-pill badge-round ml-1">6</span>
				</li>
				<li class="list-group-item d-flex justify-content-between align-items-center">
					<span>space between badgeOat cake icing pastry pie carrot</span>
					<span class="badge badge-secondary badge-pill badge-round ml-1">5</span>
				</li>
			</ul>
		</div>
	</div>
</div>

<section class="list-group-navigation">
	<div class="row">
		<div class="col-lg-12">
			<div class="card">
				<div class="card-header">
					<h4 class="card-title">List group navigation</h4>
				</div>
				<div class="card-content">
					<div class="card-body">
						<div class="row">
							<div class="col-12 col-sm-12 col-md-4 ">
								<div class="list-group" role="tablist">
									<a class="list-group-item list-group-item-action" id="list-home-list" data-toggle="list" href="#list-home" role="tab" aria-selected="false">Home</a>
									<a class="list-group-item list-group-item-action active" id="list-profile-list" data-toggle="list" href="#list-profile" role="tab" aria-selected="true">Profile</a>
									<a class="list-group-item list-group-item-action" id="list-messages-list" data-toggle="list" href="#list-messages" role="tab" aria-selected="false">Messages</a>
									<a class="list-group-item list-group-item-action" id="list-settings-list" data-toggle="list" href="#list-settings" role="tab" aria-selected="false">Settings</a>
								</div>
							</div>
							<div class="col-12 col-sm-12 col-md-8 mt-1">
								<div class="tab-content text-justify" id="nav-tabContent">
									<div class="tab-pane show" id="list-home" role="tabpanel" aria-labelledby="list-home-list">
										Velit aute mollit ipsum ad dolor consectetur nulla officia culpa adipisicing exercitation fugiat
										tempor. Voluptate deserunt sit sunt nisi aliqua fugiat proident ea ut. Mollit voluptate
										reprehenderit
										occaecat nisi ad non minim tempor sunt voluptate consectetur exercitation id ut nulla. Ea et fugiat
										aliquip nostrud sunt incididunt consectetur culpa aliquip eiusmod dolor. Anim ad Lorem aliqua in
										cupidatat nisi enim eu nostrud do aliquip veniam minim. Lorem ipsum dolor sit amet consectetur,
										adipisicing elit. Aliquam itaque nisi obcaecati doloremque et est ex possimus quidem dolorem soluta.
									</div>
									<div class="tab-pane active" id="list-profile" role="tabpanel" aria-labelledby="list-profile-list">
										Cupidatat quis ad sint excepteur laborum in esse qui. Et excepteur consectetur ex nisi eu do cillum
										ad
										laborum. Mollit et eu officia dolore sunt Lorem culpa qui commodo velit ex amet id ex. Officia anim
										incididunt laboris deserunt anim aute dolor incididunt veniam aute dolore do exercitation. Dolor
										nisi
										culpa ex ad irure in elit eu dolore. Ad laboris ipsum reprehenderit irure non commodo enim culpa
									commodo veniam incididunt veniam ad.</div>
									<div class="tab-pane" id="list-messages" role="tabpanel" aria-labelledby="list-messages-list">Ut ut
										do pariatur aliquip aliqua aliquip exercitation do nostrud commodo reprehenderit aute ipsum
										voluptate.
										Irure Lorem et laboris nostrud amet cupidatat cupidatat anim do ut velit mollit consequat enim
										tempor.
										Consectetur est minim nostrud nostrud consectetur irure labore voluptate irure. Ipsum id Lorem sit
										sint voluptate est pariatur eu ad cupidatat et deserunt culpa sit eiusmod deserunt. Consectetur et
									fugiat anim do eiusmod aliquip nulla laborum elit adipisicing pariatur cillum.</div>
									<div class="tab-pane" id="list-settings" role="tabpanel" aria-labelledby="list-settings-list">Irure
										enim occaecat labore sit qui aliquip reprehenderit amet velit. Deserunt ullamco ex elit nostrud ut
										dolore nisi officia magna sit occaecat laboris sunt dolor. Nisi eu minim cillum occaecat aute est
										cupidatat aliqua labore aute occaecat ea aliquip sunt amet. Aute mollit dolor ut exercitation irure
										commodo non amet consectetur quis amet culpa. Quis ullamco nisi amet qui aute irure eu. Magna labore
									dolor quis ex labore id nostrud deserunt dolor eiusmod eu pariatur culpa mollit in irure</div>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</section>

<div class="card">
	<div class="card-header">
		<h4 class="card-title">Tooltip Positions</h4>
	</div>
	<div class="card-content">
		<div class="card-body">
			<p>Four options are available: top, right, bottom, and left aligned.</p>
			<div class="row">
				<div class="col-xl-3 col-md-6 mb-1">
					<h6>Basic Top Tooltip</h6>
					<p class="my-1">Add <code>data-placement="top"</code> to add top tooltip.</p>
					<button type="button" class="btn btn-outline-primary" data-toggle="tooltip" data-placement="top" title="Tooltip on top">
						Tooltip on top
					</button>
				</div>
			</div>
		</div>
	</div>
</div>


<section id="avatar-status">
	<div class="row">
		<div class="col-12">
			<div class="card">
				<div class="card-header">
					<h4 class="card-title">Status</h4>
				</div>
				<div class="card-content">
					<div class="card-body">
						<p>
							use class <code>.avatar-status-{online | offline | away | busy}</code> after <code>.avatar-content</code>
							to create avatar with status
						</p>
						<div class="avatar mr-1 bg-success bg-lighten-3">
							<span class="avatar-content"><i class="avatar-icon bx bxl-github"></i></span>
							<span class="avatar-status-online"></span>
						</div>
						<div class="avatar mr-1 bg-secondary bg-lighten-3">
							<span class="avatar-content">LD</span>
							<span class="avatar-status-offline"></span>
						</div>
						<div class="avatar mr-1 bg-warning bg-lighten-3">
							<span class="avatar-content">John</span>
							<span class="avatar-status-away"></span>
						</div>
						<div class="avatar mr-1">
							<img src="/assets/images/portrait/small/avatar-s-20.jpg" alt="avtar img holder" width="32" height="32">
							<span class="avatar-status-busy"></span>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</section>

<section id="avatar-sizes">
	<div class="row">
		<div class="col-12">
			<div class="card">
				<div class="card-header">
					<h4 class="card-title">Sizes</h4>
				</div>
				<div class="card-content">
					<div class="card-body">
						<p>
							use class <code>.avatar-{sm|lg|xl}</code> to modify size of your avatar.
						</p>
						<div class="avatar mr-1 avatar-sm bg-primary">
							<span class="avatar-content"><i class="avatar-icon bx bxl-github"></i></span>
						</div>
						<div class="avatar mr-1 bg-success">
							<span class="avatar-content">LD</span>
						</div>
						<div class="avatar mr-1 avatar-lg">
							<img src="/assets/images/portrait/small/avatar-s-20.jpg" alt="avtar img holder">
						</div>
						<div class="avatar mr-1 avatar-xl">
							<img src="/assets/images/portrait/small/avatar-s-20.jpg" alt="avtar img holder">
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</section>




<section id="divider-text-position">
	<div class="row">
		<div class="col-12">
			<div class="card">
				<div class="card-header">
					<h4 class="card-title">Text Position</h4>
				</div>
				<div class="card-content">
					<div class="card-body">
						<p>
							Use class <code>.divider-{left | left-center | right | right-center}</code> with <code>.divider</code>
							to set text position.
						</p>
						<div class="divider">
							<div class="divider-text">Center(Default)</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</section>

<section id="divider-colors">
	<div class="row">
		<div class="col-12">
			<div class="card">
				<div class="card-header">
					<h4 class="card-title">Colors</h4>
				</div>
				<div class="card-content">
					<div class="card-body">
						<p>
							Use class <code>.divider-{color-name}</code> to change color of divider
						</p>
						<div class="divider">
							<div class="divider-text">Default</div>
						</div>
						<div class="divider divider-primary">
							<div class="divider-text">Primary</div>
						</div>
						<div class="divider divider-success">
							<div class="divider-text">Success</div>
						</div>
						<div class="divider divider-danger">
							<div class="divider-text">Danger</div>
						</div>
						<div class="divider divider-info">
							<div class="divider-text">Info</div>
						</div>
						<div class="divider divider-warning">
							<div class="divider-text">Warning</div>
						</div>
						<div class="divider divider-dark">
							<div class="divider-text">Dark</div>
						</div>
						<div class="divider divider-light">
							<div class="divider-text">Light</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</section>

<section id="divider-style">
	<div class="row">
		<div class="col-12">
			<div class="card">
				<div class="card-header">
					<h4 class="card-title">Style</h4>
				</div>
				<div class="card-content">
					<div class="card-body">
						<p>
							use class
							<code>.divider-{dotted | dashed}</code> to change divider style. solid is default style you don't have to
							add any class for it.
						</p>
						<div class="divider divider-dotted">
							<div class="divider-text">Dotted</div>
						</div>
						<div class="divider divider-dashed">
							<div class="divider-text">Dashed</div>
						</div>

						<div class="divider">
							<div class="divider-text">Solid</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</section>

<section id="input-file-browser">
	<div class="row">
		<div class="col-md-12">
			<div class="card">
				<div class="card-header">
					<h4 class="card-title">File Input</h4>
				</div>
				<div class="card-content">
					<div class="card-body">
						<div class="row">
							<div class="col-lg-6 col-md-12">
								<fieldset class="form-group">
									<label for="basicInputFile">Simple File Input</label>
									<input type="file" class="form-control-file" id="basicInputFile">
								</fieldset>
							</div>
							<div class="col-lg-6 col-md-12">
								<fieldset class="form-group">
									<label for="basicInputFile">With Browse button</label>
									<div class="custom-file">
										<input type="file" class="custom-file-input" id="inputGroupFile01">
										<label class="custom-file-label" for="inputGroupFile01">Choose file</label>
									</div>
								</fieldset>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</section>

@endsection

@section('footer')
	@parent
@endsection

@section('footer-assets')
	@parent
	<script src="/assets/plugins/tooltip/tooltip.min.js"></script>
	<script type="text/javascript">
		$('[data-toggle="tooltip"]').tooltip()
	</script>

@endsection