<meta name="language" content="Vietnamese" />
<meta charset="UTF-8" />
<meta name="viewport" content="width=device-width, initial-scale=1.0" />
<link rel="shortcut icon" type="image/png" href="" />
<link rel="icon" type="image/png" href="" />

{{-- CSS --}}
{{-- <link href="https://fonts.googleapis.com/css?family=Roboto:400,700&display=swap&subset=vietnamese" rel="stylesheet"> --}}
<link href="/assets/components/bootstrap-lite.css" rel="stylesheet" type="text/css">
<link href="/assets/components/style.css" rel="stylesheet" type="text/css">

{{-- JS --}}
<script src="/assets/vendors/jquery/jquery.min.js"></script>
<script src="/assets/components/scripts.js"></script>

{{-- Load ngôn ngữ --}}
@if( !empty($config['load_js_language']) )
    {!! loadJSLanguage($config['load_js_language']) !!}
@endif