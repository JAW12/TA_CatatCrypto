@props(['dir'])
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" dir="{{$dir ? 'rtl' : 'ltr'}}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title')</title>

    @include('partials.dashboard._head')
</head>
<body class="" >
@include('partials.dashboard._body')
@if(in_array("loading", $options))
<script>
    $(function(){
        loaderInit();
    });
</script>
@endif
@if(in_array("datatable", $options))
<script>
    $(function(){
        datatableInit();
    });
</script>
@endif
</body>

</html>
