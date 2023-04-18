@extends('layouts.admin')
@section('header')
    @parent
@endsection
@section('content')
    <main class="row mt-2">
        <section class="col-12">
            <div class="card">
                <div class="align-items-center">
                    <div class="row align-items-center mr-0 ml-0">
                        <div class="col-12 col-md-8">
                            <h4 class="card-header">
                                {{ __('users/list.heading_title') }}
                            </h4>
                        </div>
                        <div class="col-12 col-md-4 text-right">
                            <button class="btn btn-primary" onclick="userList.addUser.showModal()">
                                Thêm tài khoản
                            </button>
                            <i class="bx bx-sync link mr-2" onclick="runRefreshList()" style="font-size: 25px"></i>
                        </div>
                    </div>
                    <div class="card-header-filter">
                        <div class="row justify-content-end">
                            <div class="col-md-4 pt-0 pl-1 pr-1 pb-1">
                                {!!
                                    Form::text([
                                        'title'         => '',
                                        'placeholder'   => 'Tìm tên, email, SĐT, "id"',
                                        'name'          => 'keyword',
                                        'value'         => '',
                                        'class'         => 'item-list-filter',
                                        'attr'          => 'onkeyup="runRefreshList()"',
                                        'icon'          => 'bx-search',
                                        'icon_position' => 'left'
                                    ])
                                !!}
                            </div>
                            <div class="col-md-2 pt-0 pl-1 pr-1 pb-1"></div>
                            <div class="col-md-3 pt-0 pl-1 pr-1 pb-1">
                                {!!
                                    Form::select2([
                                        'title'         => 'Trạng thái',
                                        'placeholder'   => '',
                                        'name'          => 'status',
                                        'class'         => 'item-list-filter',
                                        'attr'          => 'onchange="runRefreshList()"',
                                        'icon'          => '',
                                        'icon_position' => 'right',
                                        'options'       => ( call_user_func(function(){
                                            $out = ['' => __('general.select_field_empty')];
                                            foreach(config('user.status.text') as $id => $label){
                                                $out[$id] = $label;
                                            }
                                            return $out;
                                        }) ),
                                        'selected'      => [ config('user.status.code.actived') ],
                                        'multiple'      => false,
                                        'search'        => false
                                    ])
                                !!}
                            </div>
                            <div class="col-md-3 pt-0 pl-1 pr-1 pb-1">
                                {!!
                                    Form::select2([
                                        'title'         => __('users/list.filter_by_role'),
                                        'placeholder'   => '',
                                        'name'          => 'role_id',
                                        'class'         => 'item-list-filter',
                                        'attr'          => 'onchange="runRefreshList()"',
                                        'icon'          => '',
                                        'icon_position' => 'right',
                                        'options' => ( call_user_func(function(){
                                            $out = ['' => __('general.select_field_empty')];
                                            foreach(\App\Role::all() as $item){
                                                $out[$item->id] = $item->name;
                                            }
                                            return $out;
                                        }) ),
                                        'selected' => [],
                                        'multiple' => false,
                                        'search'   => false
                                    ])
                                !!}
                            </div>
                        </div>
                    </div>
                </div>
                <hr>
                <div class="card-body card-body-table" id="item-list">
                    <div class="table-responsive table-responsive-v">
                        <table class="table table-hover">
                            <thead>
                            <tr>
                                <th style="width: 60px">
                                    ID
                                </th>
                                <th>
                                    {{ __('users/list.table_thead_user_name') }}
                                </th>
                                <th style="width: 200px">
                                    {{ __('users/list.table_thead_phone_number') }}
                                </th>
                                <th style="width: 200px">
                                    {{ __('users/list.table_thead_registered_at') }}
                                </th>
                                <th style="width: 200px">
                                    {{ __('users/list.table_thead_role') }}
                                </th>
                                <th class="text-center" style="width: 150px">
                                    {{ __('users/list.table_thead_action') }}
                                </th>
                            </tr>
                            </thead>
                            <tbody>
                            @php
                                $i = 0;
                            @endphp
                            @foreach($getItems as $item)
                                @php
                                    $i++;
                                    $item->display_name = UserServices::get($item->id, 'display_name');
                                    $item->time = $item->created_at->format( Option::get('settings__general_time_format') );
                                @endphp
                                <tr class="item-list-row" data-id="{{ $item->id }}">
                                    <td>
                                        <div class="table-sm-row">
                                            <div style="width: 40%">
                                                ID
                                            </div>
                                            <div style="width: 60%">
                                                {{ $item->id }}
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        @php
                                            $item->avatar        = UserServices::avatar($item->email);
                                            $item->active_status = UserServices::activeStatus($item->id);
                                        @endphp
                                        <div class="table-sm-row">
                                            <div style="width: 20%">

                                            </div>
                                            <div style="width: 80%">
                                                <div class="row align-items-center">
                                                    <div style="width: 65px">
                                                        {!! UserServices::showAvatar($item->id, '45px') !!}
                                                    </div>
                                                    <div style="width: calc(100% - 65px)">
                                                        <div>
                                                            {!! $item->display_name !!}
                                                            <i class="bx bx-{{ config("user.gender.text.{$item->gender}") }}"></i>
                                                        </div>
                                                        <div>
                                                            {!! $item->email !!}
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="table-sm-row">
                                            <div style="width: 40%">
                                                {{ __('users/list.table_thead_phone_number') }}
                                            </div>
                                            <div style="width: 60%">
                                                {!! $item->phone_number !!}
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="table-sm-row">
                                            <div style="width: 40%">
                                                {{ __('users/list.table_thead_registered_at') }}
                                            </div>
                                            <div style="width: 60%">
                                                {!! dateText( $item->created_at->timestamp ) !!}
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="table-sm-row">
                                            <div style="width: 40%">
                                                {{ __('users/list.table_thead_role') }}
                                            </div>
                                            <div style="width: 60%; color: {!! $item->role->color !!}">
                                                {!! $item->role->name !!}
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="table-sm-row">
                                            <div style="width: 40%">
                                                {{ __('users/list.table_thead_action') }}
                                            </div>
                                            <div style="width: 60%" class="text-center">
                                                <div class="btn-group">
                                                    <div class="dropdown">
                                                        <button class="btn btn-sm btn-primary dropdown-toggle" type="button"data-toggle="dropdown">
                                                            {{ __('users/list.btn_action_label') }}
                                                        </button>
                                                        <div class="dropdown-menu">
																<span class="dropdown-item link" onclick="itemList.showDetail(this, '#modal-item-list-detail', '{{ $item->name }}')">
																	<i class="bx bx-info-circle"></i>
																	{{ __('users/list.btn_action_info') }}
																</span>
                                                            <span class="dropdown-item"  onclick="itemList.showDetail(this, '#modal-user-change-password', '{{ $item->name }}')">
																	<i class="bx bx-lock"></i>
																	{{ __('users/list.btn_action_change_password') }}
																</span>
                                                            <span class="dropdown-item"  onclick="itemList.formInsertData('#modal-user-send-notification', this, '{{ $item->name }}'); showUserNotifications(this)">
																	<i class="bx bx-bell"></i>
																	{{ __('users/list.btn_action_send_notification') }}
																</span>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        @php
                                            // Tên quyền
                                            $item->role_name = '
                                                <span style="color: '.($getRole[ $item->role_id ]->color ?? null).'">
                                                    '.($getRole[ $item->role_id ]->name ?? null).'
                                                </span>
                                            ';

                                            // Thời gian đăng ký, online
                                            $item->last_online = dateText( UserDataServices::get('last_online', $item->id) ?? 0 );
                                            $item->registered_at = $item->created_at->format( Option::get('settings__general_time_format') );

                                            // Đăng nhập qua
                                            if( UserDataServices::get('social_google_id', $item->id) ){
                                                $item->register_via = __('user/general.register_via_google');
                                            }else if( UserDataServices::get('social_facebook_id', $item->id) ){
                                                $item->register_via = __('user/general.register_via_facebook');
                                            }else{
                                                $item->register_via = __('user/general.register_handmade');
                                            }

                                            // Link đăng nhập nhanh
                                            $item->login_with_private_key = url('/user/login-with-private-key/'.UserServices::createAuthPrivateKey($item->id, $item->password) );

                                            // Id người nhận thông báo
                                            $item->users = $item->id;

                                            // Danh sách thông báo
                                            $item->notifications = '
                                                <section class="accordion collapse-icon accordion-icon-rotate" id="notifications-list">
                                            ';
                                            $getUserNotifications = \App\Services\NotificationServices::getNotificationsByUserId([
                                                'userId'       => $item->id,
                                                'limit'        => 10,
                                                'replace_name' => false
                                            ] );
                                            foreach( $getUserNotifications['items'] as $nt){
                                                $item->notifications .= '
                                                    <div class="card collapse-header">
                                                        <div class="card-header collapsed" data-toggle="collapse" data-target="#notification-item-'.$nt->id.'">
                                                            <span class="collapse-title">
                                                                <span class="align-middle">
                                                                    '.( $nt->readed ?
                                                                        '
                                                                            <span class="badge badge-info">
                                                                                Readed
                                                                            </span>
                                                                        ' : '
                                                                            <span class="badge badge-light">
                                                                                Unread
                                                                            </span>
                                                                    ' ).'
                                                                    '.$nt->title.'
                                                                </span>
                                                            </span>
                                                        </div>
                                                        <div id="notification-item-'.$nt->id.'" data-parent="#notifications-list" class="collapse">
                                                            <div class="card-content">
                                                                <div class="card-body">
                                                                    <div class="row row-detail no-border">
                                                                        <div style="width: 30%">
                                                                            '.__('users/notifications.table_thead_created_by').':
                                                                        </div>
                                                                        <div style="width: 70%">
                                                                            '.UserServices::get($nt->created_user_id, 'display_name').'
                                                                        </div>
                                                                    </div>
                                                                    <div class="row row-detail no-border">
                                                                        <div style="width: 30%">
                                                                            '.__('users/notifications.table_item_created_at').':
                                                                        </div>
                                                                        <div style="width: 70%">
                                                                            '.date( \Option::get('settings__general_time_format'), timestamp($nt->created_at) ).'
                                                                        </div>
                                                                    </div>
                                                                    <div class="row row-detail no-border">
                                                                        <div style="width: 30%">
                                                                            '.__('users/notifications.table_item_expired').':
                                                                        </div>
                                                                        <div style="width: 70%">
                                                                            '.(empty($nt->expired) ?
                                                                                __('general.no')
                                                                            :
                                                                                date( Option::get('settings__general_time_format') , timestamp($nt->expired) )
                                                                            ).'
                                                                        </div>
                                                                    </div>
                                                                    <div class="row row-detail no-border">
                                                                        <div style="width: 30%">
                                                                            '.__('users/notifications.table_thead_send_mail').':
                                                                        </div>
                                                                        <div style="width: 70%">
                                                                            '.( $nt->send_mail ? __('general.yes') : __('general.no') ).'
                                                                        </div>
                                                                    </div>
                                                                    <div class="row row-detail no-border">
                                                                        <div style="width: 30%">
                                                                            '.__('users/notifications.table_item_read_status').':
                                                                        </div>
                                                                        <div style="width: 70%">
                                                                            '.( $nt->readed ?
                                                                                '
                                                                                    <span class="badge badge-info">
                                                                                        '.__('general.readed').'
                                                                                    </span>
                                                                                ' : '
                                                                                    <span class="badge badge-light">
                                                                                        '.__('general.unread').'
                                                                                    </span>
                                                                            ' ).'
                                                                        </div>
                                                                    </div>
                                                                    <div class="pt-1">
                                                                        '.str_replace('@name', $item->name, $nt->content).'
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                ';
                                            }
                                            $item->notifications .= '</section>';
                                        @endphp
                                        <textarea class="item-json-data hide">@json($item)</textarea>
                                    </td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </div>
                    @if( $getItems->count() == 0 )
                        <div class="text-center p-2">
                            {{ __('general.item_list_is_empty') }}
                        </div>
                    @endif
                    <div class="pagination-center pt-1">
                        {!! $getItems->links() !!}
                    </div>
                    <div class="row align-items-center px-md-2 py-1">
                        <div style="width: calc(100% - 150px)">
                            {{ __('general.total_record') }}: <b>{{ $getItems->total() }}</b>
                        </div>
                        <div style="width: 150px">
                            <div class="">
                                {!!
                                    Form::select2([
                                        'title'         => __('general.pagination_limit'),
                                        'placeholder'   => '',
                                        'name'          => 'pagination_limit',
                                        'class'         => 'item-list-filter',
                                        'attr'          => 'onchange="runRefreshList()"',
                                        'icon'          => '',
                                        'icon_position' => 'right',
                                        'options'       => array_replace( [Option::get('settings__general')['pagination_limit'] ?? 5 => Option::get('settings__general')['pagination_limit'] ?? 5], config('general.pagination_limit') ),
                                        'selected'      => [paginationLimit()],
                                        'multiple'      => false,
                                        'search'        => false
                                    ])
                                !!}
                            </div>
                        </div>
                    </div>
                </div>
            </div><!--/.card-->
        </section>
    </main>
@endsection
