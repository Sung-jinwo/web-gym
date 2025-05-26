
@if(session('error'))
<div class="alert-backdrop" id="alertBackdrop"></div>
<div class="custom-alert-danger alert-dismissible fade show" role="alert" id="alertBox">
    <span class="alert-title">¡Error!</span> {{ session('error') }}
    <button type="button" class="btn-close" onclick="closeAlert()" aria-label="Cerrar">×</button>
</div>
<script>
    startAlertTimer();
</script>
@endif


@if(session('info'))
<div class="alert-backdrop" id="alertBackdrop"></div>
<div class="custom-alert-info alert-dismissible fade show" role="alert" id="alertBox">
    <span class="alert-title">¡Información!</span> {{ session('info') }}
    <button type="button" class="btn-close" onclick="closeAlert()" aria-label="Cerrar">×</button>
</div>
<script>
    startAlertTimer();
</script>
@endif


{{--@if(session('warningfond'))--}}
{{--<div class="alert-backdrop" id="alertBackdrop"></div>--}}
{{--<div class="custom-alert-warningg alert-dismissible fade show" role="alert" id="alertBox">--}}
{{--    <span class="alert-title">¡Advertencia!</span> {{ session('warningfond') }}--}}
{{--    <button type="button" class="btn-close" onclick="closeAlert()" aria-label="Cerrar">×</button>--}}
{{--</div>--}}
{{--<script>--}}
{{--    startAlertTimer();--}}
{{--</script>--}}
{{--@endif--}}

@if(session('warning'))
    <!-- No necesitamos backdrop para la notificación de advertencia -->
    <div class="custom-alert-warning alert-dismissible fade show" role="alert" id="warningBox">
        <span class="warning-icon">⚠</span>
        <span class="alert-title">¡Advertencia!</span> {{ session('warning') }}
        <button type="button" class="btn-close" onclick="closeAlert()" aria-label="Cerrar">×</button>
    </div>
    <script>
        startWarningTimer();
    </script>
@endif

<link rel="stylesheet" href="{{ asset('css/emergentes.css') }}">
<script src="{{ asset('js/cerrar.js') }}"></script>
