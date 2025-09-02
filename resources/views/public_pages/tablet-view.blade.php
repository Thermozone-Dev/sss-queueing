@extends('public_pages.vue-layout')
@section('content')
    {{-- @dd($settings) --}}
    @php
        $settings = app(App\Settings\GeneralSettings::class);
        $theme  = $settings->site_theme;
    @endphp


    <router-view></router-view>
@endsection

<script>
    window.appTheme = @json($theme);
</script>