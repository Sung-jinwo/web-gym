@extends('layout')

@section('title','Home - Ivonne Gym')

@section('content')


<div class="home-container">
    <!-- Banner Principal -->

    <section class="home-banner" style="background-image: url('{{ asset('img/ivonneBaner.jpg') }}');">
        <div class="banner-content">
            <h1>¡Bienvenido a Ivonne Gym!</h1>
            <p class="banner-text">Tu salud y bienestar son nuestra prioridad. Únete a nosotros y alcanza tus metas.</p>
            <a href="{{ route('asistencia.create') }}" class="btn btn-primary">Registra tu Asistencia</a>
        </div>
    </section>

    <!-- Información del Gimnasio -->
    <section class="home-about">
        <h2>¿Quiénes Somos?</h2>
        <p>
            En Ivonne Gym, nos dedicamos a ayudarte a alcanzar tus objetivos de fitness en un ambiente amigable y profesional.
            Ofrecemos entrenamiento personalizado, clases grupales y planes de nutrición adaptados a tus necesidades.
        </p>
        <div class="about-details">
            <div class="detail-item">
                <i class="fa-solid fa-clock"></i>
                <p>Horario: Lunes a Viernes 6:00 AM - 10:00 PM</p>
            </div>
            <div class="detail-item">
                <i class="fa-solid fa-location-dot"></i>
                <p>Horario: Sabados 6:00 AM - 7:00 PM</p>
            </div>
        </div>
        {{-- <div class="about-details">
            <div class="detail-item">
                <i class="fa-solid fa-location-dot"></i>
                <p>Contacto: +51 --------</p>
            </div>
            <div class="detail-item">
                <i class="fa-solid fa-phone"></i>
                <p>Contacto: +51 943297293</p>
            </div>
        </div> --}}
        
    </section>

    <!-- Servicios Destacados -->
    <section class="home-services">
        <h2>Nuestros Servicios</h2>
        <div class="services-grid">
            <div class="service-item">
                <i class="fa-solid fa-dumbbell"></i>
                <h3>Entrenamiento Personalizado</h3>
                <p>Programas diseñados específicamente para ti.</p>
            </div>
            <div class="service-item">
                <i class="fa-solid fa-users"></i>
                <h3>Clases Grupales</h3>
                <p>Zumba, spinning, yoga y más.</p>
            </div>
            <div class="service-item">
                <i class="fa-solid fa-utensils"></i>
                <h3>Nutrición</h3>
                <p>Planes alimenticios para complementar tu entrenamiento.</p>
            </div>
        </div>
    </section>

    <!-- Galería de Fotos -->
    <section class="home-gallery">
        <h2>Galería</h2>
        <div class="gallery-grid" >
            <img src="{{ asset('img/Jose_Crespo.jpg') }}" alt="Gimnasio 3">
            <img src="{{ asset('img/Jaime_Blanco.jpg') }}" alt="Gimnasio 2">
            <img src="{{ asset('img/Rio_seco.jpg') }}" alt="Gimnasio 1">
        </div>
        <div class="about-details">
            <div class="detail-item">
                <i class="fa-solid fa-location-dot"></i>
                <p>Sede: Jose Crespo</p>
            </div>
            <div class="detail-item">
                <i class="fa-solid fa-location-dot"></i>
                <p>Sede: Jaime blanco</p>
            </div>
            <div class="detail-item">
                <i class="fa-solid fa-location-dot"></i>
                <p>Sede: Rio seco</p>
            </div>
        </div>

        <div class="about-details">
            <div class="detail-item">
                <i class="fa-solid fa-phone"></i>
                <p>Contacto: +51 907851770</p>
            </div>
            <div class="detail-item">
                <i class="fa-solid fa-phone"></i>
                <p>Contacto: +51 919631342</p>
            </div>
            <div class="detail-item">
                <i class="fa-solid fa-phone"></i>
                <p>Contacto: +51 907543162</p>
            </div>
        </div>
    </section>

    <!-- Testimonios -->
    <section class="home-testimonials">
        <h2>Lo que dicen nuestros clientes</h2>
        <div class="testimonials-grid">
            <div class="testimonial-item">
                <p>"Ivonne Gym ha cambiado mi vida. ¡Los entrenadores son increíbles!"</p>
                <span>- María Pérez</span>
            </div>
            <div class="testimonial-item">
                <p>"Las clases grupales son muy divertidas y efectivas. ¡Las recomiendo!"</p>
                <span>- Juan López</span>
            </div>
        </div>
    </section>

    <!-- Llamado a la Acción -->
    <section class="home-cta">
        <h2>¿Listo para empezar?</h2>
        <p>Únete a Ivonne Gym hoy mismo y comienza tu transformación.</p>
        <a href="{{ route('asistencia.create') }}" class="btn btn-primary">Registra Tu Asistencia Ahora</a>
    </section>
</div>
@endsection
