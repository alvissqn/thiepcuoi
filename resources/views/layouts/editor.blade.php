@php
use \App\Services\NotificationServices;
$splitPath = explode('/', Request::path() );
$pageParams = [
'name' => $splitPath[1] ?? null,
'sname' => $splitPath[2] ?? null,
];
if( empty($pageParams['sname']) ){
$config = config('admin_menu_link')[ $pageParams['name'] ] ?? [];
}else{
$config = config('admin_menu_link')[ $pageParams['name'] ]['subs'][ $pageParams['sname'] ] ?? [];
}
$config['load_js_language'] = array_merge(
$config['load_js_language'],
['general']
);
Permission::required($config['permission'], '/');

@endphp
<!DOCTYPE html>
<html lang="{{ config('app.locale') }}">
    <head>
        @section('header')
        <title>@yield('title', $config['label'] )</title>
        <meta charset="UTF-8"/>
        <meta
                name="viewport"
                content="width=device-width, height=device-height, initial-scale=1.0, maximum-scale=1.0, user-scalable=no" />
        <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet"/>
        <link href="/assets/photo-editor/css/select2.css" rel="stylesheet" type="text/css"/>
        <link href="/assets/photo-editor/css/spectrum.css" rel="stylesheet" type="text/css"/>
        <link href="/assets/photo-editor/css/toastr.css" rel="stylesheet" type="text/css"/>
        <link href="/assets/photo-editor/css/style.css" rel="stylesheet" type="text/css"/>
        <!--    <link rel="stylesheet" href="css/ruler.css" type="text/css" />-->
        <link id="palleon-theme-link" href="/assets/photo-editor/css/dark.css" rel="stylesheet" type="text/css"/>
        <link href="/assets/photo-editor/css/custom.css" rel="stylesheet" type="text/css"/>
        <link rel="stylesheet" href="/assets/photo-editor/node_modules/ruler.js/dist/ruler.min.css">
        <script src="/assets/photo-editor/node_modules/ruler.js/dist/ruler.min.js"></script>
        <!-- <link id="palleon-theme-link" href="css/light.css" rel="stylesheet" type="text/css"> -->
        @show
    </head>
    <body id="palleon" class="backend">
        @yield('content')
        @section('footer')
        <!-- Scripts -->
        <script src="/assets/photo-editor/js/jquery.min.js"></script>
        <script src="/assets/photo-editor/js/jquery-ui.min.js"></script>
        <script src="/assets/photo-editor/js/fabric.min.js"></script>
        <script src="/assets/photo-editor/js/addons/centering_guidelines.js"></script>
        <script src="/assets/photo-editor/js/addons/aligning_guidelines.js"></script>
        <script src="/assets/photo-editor/js/plugins.min.js"></script>
        <script src="/assets/photo-editor/js/palleon.js"></script>
        <script src="/assets/photo-editor/js/custom.js"></script>
        <!-- Translation Strings -->
        <script>
            /* <![CDATA[ */
            var palleonParams = {
                textbox: 'Nhập văn bản',
                object: 'Đối tượng',
                loading: 'Đang tải...',
                loadmore: 'Tải thêm',
                saved: 'Đã lưu!',
                ajaxurl: "https://demo.palleon.website/wp-admin/admin-ajax.php",
                imgsaved: 'Ảnh đã được lưu.',
                tempsaved: 'Mẫu đã được lưu.',
                freeDrawing: 'Vẽ tự do',
                frame: 'Khung',
                image: 'Ảnh',
                circle: 'Tròn',
                square: 'Vuông',
                rectangle: 'Rectangle',
                triangle: 'Triangle',
                ellipse: 'Ellipse',
                trapezoid: 'Trapezoid',
                emerald: 'Emerald',
                star: 'Ngôi sao',
                element: 'Vật thể',
                customSvg: 'SVG tùy chỉnh',
                success: 'Thành công',
                error: 'Lỗi',
                delete: 'Xóa',
                duplicate: 'Nhân bản',
                showhide: 'Hiện/ẩn',
                lockunlock: 'Khóa/Mở khóa',
                text: 'Chữ',
                started: 'Bắt đầu sữa.',
                added: 'đã thêm.',
                removed: 'Đã xóa.',
                edited: 'đã sửa.',
                replaced: 'đã thay.',
                rotated: 'đã xoay.',
                moved: 'đã chuyển.',
                scaled: 'scaled.',
                flipped: 'đã xoay.',
                bg: 'Ảnh nền',
                filter: 'Bộ lọc',
            };
            /* ]]> */
        </script>
        <!-- Scripts END -->
        @show
    </body>
</html>
