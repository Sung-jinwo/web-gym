

@if(session('estado'))
<div class="alert-backdrop" id="alertBackdrop"></div>
<div class="custom-alert-success alert-dismissible fade show" role="alert" id="alertBox">
    <span class="success-icon">✓</span>
    <span class="alert-title">¡Éxito!</span> {{ session('estado') }}
    <button type="button" class="btn-close" onclick="closeAlert()" aria-label="Cerrar">×</button>
</div>

<script src="{{ asset('js/cerrar.js') }}"></script>
@endif

<link rel="stylesheet" href="{{ asset('css/emergentes.css') }}">

