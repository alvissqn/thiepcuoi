@php    
    $configPath = config_path( 'pages/'.str_replace('.', '/', $http_status_code ?? Request::route()->getName() ) ).'.php';
    if( !file_exists($configPath) ){
        putArrayToFile(
            $configPath,
            [
                'title'            => null,
                'description'      => null,
                'background_image' => null,
                'image'            => null,
                'redirect'         => null
            ]
        );
    }
    $config = array_replace(include $configPath, $config);
    if( $config['redirect'] ){
        header('location: '.$config['redirect']); die;
    }
    if( !isset($config['search_engine_index']) ){
        $config['search_engine_index'] = Option::get('settings__general_search_engine_index');
    }
    $config['canonical']   = $config['canonical'] ?? request()->url();
    $config['title']       = cutWords($config['title'], 20);
    $config['description'] = cutWords($config['description'], 35);
@endphp
<!DOCTYPE html>
<html lang="{{ config('app.locale') }}" data-textdirection="ltr">
<head>
    @section('header')
        <title>{{ $config['title'] }}</title>
        <meta name="description" content="{!! $config['description'] !!}" />
        <meta property="og:title" content="{!! $config['title'] !!}"/>
        <meta property="og:description" content="{!! $config['description'] !!}" />
        <meta property="og:url" content="{!! $config['canonical'] !!}">
        <link rel="canonical" href="{!! $config['canonical'] !!}" />
        <meta property="og:type" content="article">
        @if( !empty($config['image']) )
            <meta property="og:image" content="{!! $config['image']!!} " />
        @endif
        @if( !empty($config['facebook_appID']) )
            <meta property="fb:app_id" content="{{ $config['facebook_appID'] }}" />
        @endif
        <meta name="robots" content="{{ $config['search_engine_index'] ? 'index, follow' : 'noindex, nofollow' }}" />
        
        @include('layouts.includes.header-assets')
    @show
    @section('header-tag')
    @show
</head>
<body style="{!! empty($config['background_image']) ? '' : 'background: url('.$config['background_image'].') fixed center center no-repeat;
    background-size: cover;' !!}">
    <!-- Content-->
    <main class="main-layout">
	    @yield('content')
    </main>
    <!-- /Content-->

    @section('footer')
        <footer class="footer text-right pd-20">
            <p>
                Copyright by <a href="/">{{ request()->getHost() }}</a>
            </p>
        </footer>
    @show


    @section('footer-assets')
        @include('layouts.includes.footer-assets')
    @show

</body>

</html>