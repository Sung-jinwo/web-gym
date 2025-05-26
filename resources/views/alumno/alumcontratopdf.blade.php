<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Acuerdo de Prestación de Servicios - Ivonne Gym</title>
    <style>
        @page {
            size: letter;
            margin: 0.5cm;
        }

        body {
            font-family: Arial, sans-serif;
            line-height: 1.4;
            margin: 0;
            padding: 10px;
            max-width: 215.9mm;
            margin: 0 auto;
            font-size: 11px;
            text-align: justify;
        }

        .header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 10px;
            position: relative;
            margin-top: 5px;
        }

        .logo-container {
            width: 150px;
            margin-right: 15px;
        }

        .logo {
            max-width: 100%;
            height: auto;
        }

        .header-content {
            flex-grow: 1;
            text-align: center;
        }

        .contract-number {
            position: absolute;
            top: 0;
            right: 0;
            font-size: 11px;
        }

        .title {
            font-weight: bold;
            font-size: 16px;
            text-align: center;
            margin-bottom: 10px;
            text-decoration: underline;
        }

        .section-title {
            font-weight: bold;
            margin-bottom: 3px;
        }
        .black-bg-text {
            display: inline-block;
            background-color: #000;
            color: #fff;
            padding: 2px 4px;
            font-weight: bold;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 5px;
            text-align: justify;
        }

        table th {
            font-weight: bold;
            padding: 2px;
        }

        table td {
            padding: 2px;
            vertical-align: top;
            line-height: 1.2;
        }


        .client-info-table td {
            padding: 1px;
        }

        .form-field {
            display: inline-block;
            border-bottom: 1px solid #000;
            min-width: 150px;
            padding: 0 2px;
            position: relative;
            top: 3px;
        }

        .checkbox-group {
            display: inline-block;
            margin-right: 5px;
            vertical-align: middle;
        }

        .checkbox-container {
            display: inline-block;
            margin-right: 1px;
            vertical-align: middle;
            position: relative;
            top: 3px;
        }

        .checkbox {
            display: inline-block;
            width: 10px;
            height: 10px;
            border: 1px solid #000;
            margin-right: 2px;
            vertical-align: middle;
            position: relative;
            top: 1px;
        }

        .checkbox.checked {
            background-color: #000;
        }

        .signature-line {
            border-top: 1px solid #000;
            width:50%;
            margin-bottom: 3px;
            padding-top: 3px;
        }


        .clauses {
            margin-top: 5px;
            text-align: justify;
        }

        .clause {
            margin-bottom: 2px;
            font-size: 10px;
            line-height: 1.1;
            text-align: justify;
        }

        p {
            text-align: justify;
        }

        @media print {
            body {
                width: 215.9mm;
                height: 279.4mm;
                margin: 0;
                padding: 10mm;
                box-sizing: border-box;
            }

            .page-break {
                page-break-before: always;
            }
        }

        @media (max-width: 600px) {
            .header {
                flex-direction: column;
            }

            .logo-container {
                margin-right: 0;
                margin-bottom: 10px;
            }
        }
    </style>
</head>
<body>
<div class="header">
    <div class="logo-container">
        <img src="img/ivonnegym.png" alt="Logo Ivonne Gym" width="150" class="logo">
    </div>
    <div class="header-content">
        <div class="contract-number">C.V. N° {{ str_pad($lastMembresia->id_pag ?? '0', 9, '0', STR_PAD_LEFT) }}</div>
        <div class="title">ACUERDO DE PRESTACION DE SERVICIOS</div>
    </div>
</div>

<table class="client-info-table">
    <tr>
        <td colspan="4">
            <span class="black-bg-text">INFORMACION DEL CLIENTE</span>
        </td>
    </tr>
    <tr>
        <td colspan="4">
            Nombres y Apellidos: <span class="form-field" style="min-width: 250px;" >{{ $lastMembresia->alumno->alum_nombre ?? '_______________________________' }}</span>
            <div class="checkbox-group">
                <div class="checkbox-container">
                    <span class="checkbox {{ $lastMembresia->alumno->alum_documento == 'DNI' ? 'checked' : '' }}"></span> DNI
                </div>
                <div class="checkbox-container">
                    <span class="checkbox {{ $lastMembresia->alumno->alum_documento == 'DNIe' ? 'checked' : '' }}"></span> DNIE
                </div>
                <div class="checkbox-container">
                    <span class="checkbox {{ $lastMembresia->alumno->alum_documento == 'PASS' ? 'checked' : '' }}"></span> PASS
                </div>
                <div class="checkbox-container">
                    <span class="checkbox {{ $lastMembresia->alumno->alum_documento == 'CE' ? 'checked' : '' }}"></span> CE
                </div>
                : <span class="form-field" style="min-width: 80px;text-align: center; ">{{ $lastMembresia->alumno->alum_numDoc ?? '__________' }}</span>
            </div>
        </td>
    </tr>
    <tr>
        <td colspan="4">
            Fecha de Nac: <span class="form-field" style=" text-align: center; min-width: 80px;">{{ $lastMembresia->alumno->alumno_formato  ??'___________'}}</span>
            Dirección: <span class="form-field" style="min-width: 356px;">{{ $lastMembresia->alumno->alum_direccion ?? '_______________________________' }}</span>
        </td>
    </tr>
    <tr>
        <td colspan="4">
            Celular: <span class="form-field" style=" text-align: center; min-width: 80px;">{{ $lastMembresia->alumno->alum_telefo ?? '__________' }}</span>
            Email: <span class="form-field" style="min-width: 408px;">{{ $lastMembresia->alumno->alum_correro ?? '_______________________________' }}</span>
        </td>
    </tr>
    <tr>
        <td colspan="4">
            Plan:
            <div class="checkbox-group">
                <div class="checkbox-container">
                    <span class="checkbox {{ $lastMembresia->Membresia->mem_durac == 30 ? 'checked' : '' }}"></span> 1 mes
                </div>
                <div class="checkbox-container">
                    <span class="checkbox {{ $lastMembresia->Membresia->mem_durac == 90 ? 'checked' : '' }}"></span> 3 meses
                </div>
                <div class="checkbox-container">
                    <span class="checkbox {{ $lastMembresia->Membresia->mem_durac == 180 ? 'checked' : '' }}"></span> 6 meses
                </div>
                <div class="checkbox-container">
                    <span class="checkbox {{ $lastMembresia->Membresia->mem_durac == 360 ? 'checked' : '' }}"></span> 1 año
                </div>
                <div class="checkbox-container">
                    otros <span class="form-field" style="min-width: 120px;">{{ !in_array($lastMembresia->Membresia->mem_durac, [30, 90, 180, 360]) ? $lastMembresia->Membresia->mem_durac . ' días' : '' }}</span>
                </div>
            </div>
        </td>
    </tr>
    <tr>
        <td colspan="4">
            Fecha de inicio del contrato del plan: <span class="form-field" style=" text-align: center; min-width: 80px;">{{ $lastMembresia->pag_inicio_formato ?? '__________' }}</span>
            hasta el <span class="form-field" style="text-align: center; min-width: 80px;">{{ $lastMembresia->pag_fin_formato  ?? '__________' }}</span>
        </td>
    </tr>
</table>

<table class="client-info-table">
    <tr>
        <td style="  border-right: 3px solid #000; ">
            <span class="black-bg-text">¿PADECE DE ALGUNA ENFERMEDAD QUE PONGA EN RIESGO SU SALUD?</span>
        </td>
        <td style=" padding-left: 8px; ">
            <span class="black-bg-text">CONTACTO DE EMERGENCIA</span>
        </td>
    </tr>
    <tr>
        <td style="border-right: 3px solid #000; ">
            <span class="form-field" style="width: 90%;">{{ $lastMembresia->alumno->alum_condi ?? '_______________________________' }}</span>
        </td>
        <td style="padding-left: 8px;">
            Celular: <span class="form-field" style=" text-align: center; min-width: 90px;">
                        {{$padre->padre_telefono ?? '__________' }}
            </span>
        </td>
    </tr>
</table>

<table class="client-info-table">
    <tr>
        <td colspan="4">
            Cantidad acordada <span class="form-field" style="min-width: 70px;">S/ {{ number_format($lastMembresia->pago, 2?? '_______') }}</span>
            <div class="checkbox-group">
                <div class="checkbox-container">
                    <span class="checkbox {{ $lastMembresia->metodo->tipo_pago == 'Yape' ? 'checked' : '' }}"></span> yape
                </div>
                <div class="checkbox-container">
                    <span class="checkbox {{ $lastMembresia->metodo->tipo_pago == 'Tarjeta' ? 'checked' : '' }}"></span> tarjeta
                </div>
                <div class="checkbox-container">
                    <span class="checkbox {{ $lastMembresia->metodo->tipo_pago == 'Efectivo' ? 'checked' : '' }}"></span> efectivo
                </div>
                <div class="checkbox-container">
                    otros <span class="form-field" style="min-width: 90px;">{{ !in_array($lastMembresia->metodo->tipo_pago, ['Yape', 'Tarjeta', 'Efectivo']) ? $lastMembresia->metodo->tipo_pago : '' }}</span>
                </div>
            </div>
        </td>
    </tr>
    <tr>
        <td colspan="4">
            Cantidad pagada <span class="form-field" style=" text-align: center; min-width: 80px;">S/ {{ $lastMembresia->monto_pagado ?? '_______' }}</span>
            Fecha de cancelación <span class="form-field" style=" text-align: center; min-width: 163px;">{{ $lastDetalle->pago_formato ?? '__________' }}</span>
        </td>
    </tr>
    <tr>
        <td colspan="4">
            Cantidad pendiente <span class="form-field" style=" text-align: center; min-width: 70px;">
              @if($lastMembresia->saldo_pendiente)
                    S/ {{ $lastMembresia->saldo_pendiente }}
                @endif
            </span>
            Fecha de Limite de cancelación <span class="form-field" style="text-align: center; min-width: 114px;">{{ $lastMembresia->limite_formato ?? '__________' }}</span>

        </td>
    </tr>
</table>

<p style="margin-top: 3px; margin-bottom: 3px;">A quien en adelante se le denominará "EL CLIENTE", hemos celebrado el presente contrato de prestación de servicios (en adelante, el "CONTRATO") que se regirá por los siguientes términos y condiciones:</p>

<br>
<div class="clauses">
    <div class="section-title"><span class="black-bg-text">CLAUSULAS:</span></div>

    <div class="clause">
        <strong>Primera. -</strong> IVONNE GYM, pondrá a disposición de los clientes las instalaciones, adecuándose plenamente al tipo de membresía elegida y al reglamento interno de esta institución deportiva, asimismo recibirá la asesoría de un plan de ejercicios diseñado por un entrenador.
    </div>

    <div class="clause">
        <strong>Segunda. -</strong> El derecho de membresía es intransferible, solo es para el uso del cliente que solicita el servicio.
    </div>

    <div class="clause">
        <strong>Tercera. -</strong> El cliente permitirá y cederá el uso de sus datos para la formación de un expediente, el cual se mantendrá en confidencialidad. A su vez el usuario permite ser fotografiado o grabado dentro de las instalaciones de IVONNE GYM, para "solo" uso de publicidad en las distintas plataformas o redes sociales.
    </div>

    <div class="clause">
        <strong>Cuarto. -</strong> El cliente acepta plenamente al leer y firmar este contrato que IVONNE GYM, NO REEMBOLSARA cuotas de inscripción ya pagadas en el plan mensual, trimestral, semestral, anual entre otros.
    </div>

    <div class="clause">
        <strong>Quinto. -</strong> El cliente tendrá que respetar los horarios de atención y de clases grupales, los mismos que podrán ser cambiados a discreción del gimnasio, tendrá que seguir las instrucciones dadas por los trabajadores del gimnasio. Seguirá las normas implementadas por el gimnasio en las diferentes áreas.
    </div>

    <div class="clause">
        <strong>Sexta. -</strong> El cliente puede realizar transferencia de los días que le quedan de plan, antes de 30 días de terminado este contrato; puede ser a otro cliente sumándole al tiempo actual o a uno cliente, entregando el tiempo respectivo. El importe de traspaso es de S/50.00 soles. No se admite congelamiento de planes.
    </div>

    <div class="clause">
        <strong>Séptima. -</strong> las pertenencias de cada cliente son responsabilidad de si mismos, por esta razón, el gimnasio no se hace responsable por ninguna perdida de objetos personales. Se recomienda no traer objetos de valor a los clientes. La responsabilidad de lo dejado en los lockers es del cliente – el uso de lockers disponibles es diario, al terminar cada día, se abrirán y se guardarán las pertenencias encontradas durante dos semanas, de no ser reclamadas previa acreditación de propiedad serán obsequiadas a una entidad de beneficiencia.
    </div>

    <div class="clause">
        <strong>Octava. -</strong> Esta prohibido hacer uso de las instalaciones con ropa que no sea deportiva como: jeans, short drill o cualquier otro material que pueda deteriorar el tapiz de las maquinas, entrenar sin polo o prenda que haga sus veces. Se debe entrenar con ropa deportiva adecuada a la practica de los ejercicios, así mismo se prohíbe entrenar con anillos, collares, pulseras que puedan dañar las maquinas y/o instalaciones del gimnasio.
    </div>

    <div class="clause">
        <strong>Novena. -</strong> Esta prohibida la comercialización de cualquier producto dentro de las instalaciones - utilizar- dentro del gimnasio sustancias que atenten contra la buena salud – ingresar en estado de embriaguez o bajo el efecto de sustancias alucinógenas.
    </div>

    <div class="clause">
        <strong>Decimo. -</strong> Durante el entrenamiento se prohíbe portar artículos, celular u otros objetos que puedan atentar la integridad física y mental de los alumnos.
    </div>

    <div class="clause">
        <strong>Decimo primero. -</strong> Esta prohibido el ingreso a las instalaciones y hacer uso de los servicios portando armas de fuego, explosivos y/o instrumentos punzocortantes.
    </div>

    <div class="clause">
        <strong>Décimo segundo. -</strong> cualquier comportamiento que note irrespeto e intolerancia entre los alumnos o contra los empleados, será evaluado y sancionado por la administración, quien a su vez definirá la sanción a imponer. Las sanciones pueden ser las siguientes: 15 días menos de su membresía, 1 mes menos de su membresía, expulsión del gimnasio y perdida de la connotación de alumno. Se precisa que las sanciones a imponer quedan a criterio de la administración del gimnasio.
    </div>

    <div class="clause">
        <strong>Décimo Tercero. -</strong> De no cancelar el saldo en la fecha estipulada automáticamente el sistema tomara el importe cancelado por el cliente en la membresía regular más cercana.
    </div>

    <div class="clause">
        <strong>Décimo Cuarto. -</strong> Los alumnos que deseen solicitar un Entrenador personalizado, se le informa que está totalmente prohibido este servicio, solamente Ivonne Rodríguez brinda entrenamiento personalizado.
    </div>

    <div class="clause">
        <strong>Décimo Quinto. -</strong> La fecha de inicio o termino de la membresía deberán ser respetados estrictamente según lo estipulado en el contrato.
    </div>

    <div class="clause">
        <strong>Décimo Sexto. -</strong> Las clases grupales empezaran con un mínimo de 10 alumnos, el alumno deberá respetar las ubicaciones y las indicaciones del instructor y asesor de sede.
    </div>

    <div class="clause">
        <strong>Décimo Séptimo. -</strong> La inscripción de los alumnos a las clases grupales será 30 minutos antes, es personal y presencial (no es valido inscribir a otra persona ni tampoco hacerlo de manera virtual). De no cumplir con las normas y después de haber recibido 3 llamadas de atención al cliente, se le descontara 15 días de su membresía.
    </div>

    <div class="clause">
        <strong>Décimo Octavo. -</strong> Por seguridad, se prohíbe el ingreso de niños u otros acompañantes dentro de las instalaciones.
    </div>

    <div class="clause">
        <strong>Decimo Noveno. -</strong> Cualquier daño y/o avería causada por el alumno en las instalaciones o equipos del gimnasio será responsabilidad del cliente y deberá hacerse cargo del costo que implique la reposición o reparación del daño causado, de lo contrario se suspenderá su membresía.
    </div>

    <div class="clause">
        <strong>Vigésimo. -</strong> el gimnasio no se responsabiliza por cualquier tipo de lesión, accidente u incidente que se suscrite dentro de las instalaciones, quedando en potestad del usuario su propio cuidado. Ante cualquier acontecimiento no estipulado en el presente contrato, el alumno queda sujeto a lo decidido por la administración del gimnasio.
    </div>

    <div class="clause">
        <strong>Vigésimo primero. -</strong> La recesión de este contrato se causará por incumplimiento de cualquiera de las clausulas establecidas o por fallas graves del reglamento
    </div>

    <div class="clause">
        <strong>Vigésimo Segundo. -</strong> Debe solicitar a la brevedad posible la copia de su contrato y su carnet en el área de recepción, de lo contrario se restringe la entrada al gimnasio hasta no cumplir con dicha cláusula.
    </div>
</div>

<p style="margin-top: 3px; margin-bottom: 3px;">El presente contrato inicia en el momento que se acepta las clausulas anteriormente expuestas, el {{ $lastMembresia->alumno->registro_formato  }}</p>
<br>
<br>
<table>
    <tr>
        <td style="width: 50%;">
            <div style="text-align: center;">
                <div class="signature-line" style=" width: 50%; " >
                    <div>GIMNASIO IVONNE GYM</div>

                </div>
            </div>
        </td>
        <td style="width: 50%;">
            <div style="text-align: center;">
                <div class="signature-line" style=" width: 50%; float: right; ">
                    <div>FIRMA DEL CLIENTE</div>
                </div>
            </div>

        </td>
    </tr>
    <tr>
        <td colspan="2"  style="padding-left: 75%;">
            <div>DNI {{ $lastMembresia->alumno->documento ?? '________________________' }}</div>
        </td>
    </tr>
</table>

</body>
</html>
