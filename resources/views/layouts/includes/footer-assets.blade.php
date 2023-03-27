{{-- Assets --}}
<link href="/assets/vendors/bx-icon/style.css" rel="stylesheet" type="text/css">
<script src="/assets/components/vendor.js"></script>
<link href="/assets/plugins/toastr/style.css" rel="stylesheet" type="text/css">
<script src="/assets/plugins/toastr/scripts.js"></script>

{{-- Thông báo --}}
@if(session()->has('notify'))
    <script>
        toastr.{{ array_keys( session('notify') )[0] }}("{{ array_values( session('notify') )[0] }}", "", {timeOut: 15000, progressBar: !0})
    </script>
@endif

{{-- Sửa ngôn ngữ nhanh cho admin --}}
@if( Permission::has('admin') && Option::get('settings__admin_quick_edit_language') )
    <link href="/assets/pages/admin/language-quick-edit/style.css" rel="stylesheet" type="text/css">
    <script src="/assets/pages/admin/language-quick-edit/scripts.js"></script>
@endif

{{-- Nhúng mã dưới trang --}}
{!! \App\Helpers\Assets::show() !!}