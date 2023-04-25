@php
    use App\Services\UserServices;
@endphp

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
                                Danh sách mẫu thiệp
                            </h4>
                        </div>
                        <div class="col-12 col-md-4 text-right">
                            <button class="btn btn-primary" onclick="userList.addUser.showModal()">
                                Thêm Template
                            </button>
                            <i class="bx bx-sync link mr-2" onclick="runRefreshList()" style="font-size: 25px"></i>
                        </div>
                    </div>
                    <div class="card-header-filter">
                        <div class="row justify-content-end">
                            <div class="col-md-4 pt-0 pl-1 pr-1 pb-1">

                                <div class="form-group position-relative input-label has-icon-left">
                                    <label>Tìm tên template</label>
                                    <input class="form-control item-list-filter" type="text" name="keyword" onfocusin="inputLabel.onFocus(this)" onfocusout="inputLabel.outFocus(this)" value="" onkeyup="runRefreshList()">

                                    <div class="form-control-position">
                                        <i class="bx bx-search"></i>
                                    </div>

                                </div>

                            </div>
{{--                            <div class="col-md-2 pt-0 pl-1 pr-1 pb-1"></div>--}}
{{--                            <div class="col-md-3 pt-0 pl-1 pr-1 pb-1">--}}

{{--                                <div class="select2-outer position-relative ">--}}
{{--                                    <label>Trạng thái</label>--}}
{{--                                    <select class="select2 form-control item-list-filter select2-hidden-accessible" name="status" data-minimum-results-for-search="Infinity" onchange="runRefreshList()" data-select2-id="1" tabindex="-1" aria-hidden="true">--}}

{{--                                        <option value="">Tất cả</option>--}}

{{--                                        <option value="0">Tạm khóa</option>--}}

{{--                                        <option value="1" selected="" data-select2-id="3">Hoạt động</option>--}}

{{--                                    </select><span class="select2 select2-container select2-container--default" dir="ltr" data-select2-id="2" style="width: 100%;"><span class="selection"><span class="select2-selection select2-selection--single" role="combobox" aria-haspopup="true" aria-expanded="false" tabindex="0" aria-labelledby="select2-status-db-container"><span class="select2-selection__rendered" id="select2-status-db-container" role="textbox" aria-readonly="true" title="Hoạt động">Hoạt động</span><span class="select2-selection__arrow" role="presentation"><b role="presentation"></b></span></span></span><span class="dropdown-wrapper" aria-hidden="true"></span></span>--}}

{{--                                </div>--}}

{{--                            </div>--}}
{{--                            <div class="col-md-3 pt-0 pl-1 pr-1 pb-1">--}}

{{--                                <div class="select2-outer position-relative ">--}}
{{--                                    <label>Chức vụ</label>--}}
{{--                                    <select class="select2 form-control item-list-filter select2-hidden-accessible" name="role_id" data-minimum-results-for-search="Infinity" onchange="runRefreshList()" data-select2-id="4" tabindex="-1" aria-hidden="true">--}}

{{--                                        <option value="" data-select2-id="6">Tất cả</option>--}}

{{--                                        <option value="1">Admin</option>--}}

{{--                                        <option value="2">Member</option>--}}

{{--                                    </select><span class="select2 select2-container select2-container--default" dir="ltr" data-select2-id="5" style="width: 100%;"><span class="selection"><span class="select2-selection select2-selection--single" role="combobox" aria-haspopup="true" aria-expanded="false" tabindex="0" aria-labelledby="select2-role_id-5f-container"><span class="select2-selection__rendered" id="select2-role_id-5f-container" role="textbox" aria-readonly="true" title="Tất cả">Tất cả</span><span class="select2-selection__arrow" role="presentation"><b role="presentation"></b></span></span></span><span class="dropdown-wrapper" aria-hidden="true"></span></span>--}}

{{--                                </div>--}}

{{--                            </div>--}}
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
                                    #
                                </th>
                                <th>
                                    Tên Template
                                </th>
                                <th>
                                    Người Dùng
                                </th>
{{--                                <th>--}}
{{--                                    Thao tác--}}
{{--                                </th>--}}
{{--                                <th>--}}
{{--                                    Thời gian--}}
{{--                                </th>--}}
                                <th class="text-center">
                                    Chi tiết
                                </th>
                            </tr>
                            </thead>
                            <tbody>
                            @php $i = 1 @endphp
                            @foreach($data as $item)

                            <tr class="item-list-row" data-id="11">
                                <td>
                                    {!! $i !!}
                                </td>
                                <td>
                                    <a href="/{!! $item->templateid !!}" target="_blank">
                                        {!! $item->name !!}
                                    </a>
                                </td>
                                <td>
                                    {!! UserServices::get($item->userid, 'display_name');!!}
                                </td>
{{--                                <td>--}}
{{--                                    Đăng nhập bằng mật khẩu--}}
{{--                                    : <b style="margin-left: 5px">admin@azwebsite.vn</b>--}}
{{--                                </td>--}}
{{--                                <td>--}}
{{--                                    6 Phút trước--}}
{{--                                </td>--}}
                                <td>
                                    <button class="btn btn-sm btn-primary" onclick="actionLogs.showDetail(this)">
                                        Chi tiết
                                    </button>
                                </td>
                            </tr>
                                @php $i++ @endphp
                            @endforeach
                            </tbody>
                        </table>
                    </div>

                </div>
            </div><!--/.card-->
        </section>
    </main>
@endsection
