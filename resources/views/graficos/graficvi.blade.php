@extends('layout')

@section('title', 'Graficas general')

@section('content')
<h1>Gráficos del año: {{ $fechaFiltro ?? date('Y') }}</h1>
<div class="search-panel">
    <div class="filter-wrapper">
        <form method="GET" action="{{ route('graficos.index') }}" class="filter-row">

            <div class="filter-item">
                <label for="fecha_filtro" class="filter-label">Filtrar por Fecha:</label>
                <input type="number" id="fecha_filtro" name="fecha_filtro" class="filter-dropdown"
                       value="{{ old('fecha_filtro', $fechaFiltro) }}" min="2000" max="{{ date('Y') + 1 }}"
                       placeholder="Ej: 2023">
            </div>

            <div class="filter-item filter-button-container">
                <button type="submit" class="action-btn action-btn-primary">
                    <i class="fa-solid fa-search"></i> Buscar
                </button>
            </div>
        </form>
    </div>
</div>

@if(empty($dataPagos) && empty($dataVentas))
    <div class="pagos-vacio">
        <div class="pagos-vacio-icono">
            <i class="fa-solid fa-chart-line"></i>
        </div>
        <h3 class="pagos-vacio-texto">No hay datos disponibles</h3>
        <p class="pagos-vacio-subtexto">
            No se encontraron datos para el año {{ $fechaFiltro ?? date('Y') }}.
            Intente con un año diferente.
        </p>
    </div>
@else
    <!-- Sección: Análisis Financiero -->
    <div class="data-card">
        <div class="data-card-header">
            <h3 class="data-card-title" style="justify-content: center; display: flex; ">
                <i class="fa-solid fa-chart-line"></i> Análisis Financiero
            </h3>
        </div>
        <div class="data-card-content">
            <div class="filter-row">
                <div class="filter-item">
                    <div class="chart-container">
                        <canvas id="lineChart1"></canvas>
                    </div>
                </div>
                <div class="filter-item">
                    <div class="chart-container">
                        <canvas id="lineChart9"></canvas>
                    </div>
                </div>
            </div>
        </div>
        <div class="data-card-content">
            <div class="filter-row">
                <div class="filter-item">
                    <div class="chart-container" style="justify-content: center; display: flex; ">
                        <canvas id="lineChart10"></canvas>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Sección: Membresías y Renovaciones -->
    <div class="data-card">
        <div class="data-card-header">
            <h3 class="data-card-title">
                <i class="fa-solid fa-id-card"></i> Membresías y Renovaciones
            </h3>
        </div>
        <div class="data-card-content">
            <div class="filter-row">
                <div class="filter-item">
                    <div class="chart-container">
                        <canvas id="lineChart2"></canvas>
                    </div>
                </div>
                <div class="filter-item">
                    <div class="chart-container">
                        <canvas id="lineChart3"></canvas>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Sección: Gestión de Alumnos -->
    <div class="data-card">
        <div class="data-card-header">
            <h3 class="data-card-title">
                <i class="fa-solid fa-users"></i> Gestión de Alumnos
            </h3>
        </div>
        <div class="data-card-content">
            <div class="filter-row">
                <div class="filter-item">
                    <div class="chart-container">
                        <canvas id="lineChart4"></canvas>
                    </div>
                </div>
                <div class="filter-item">
                    <div class="chart-container">
                        <canvas id="lineChart7"></canvas>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Sección: Entrenadores -->
    <div class="data-card">
        <div class="data-card-header">
            <h3 class="data-card-title">
                <i class="fa-solid fa-dumbbell"></i> Gestión de Entrenadores
            </h3>
        </div>
        <div class="data-card-content">
            <div class="filter-row">
                <div class="filter-item">
                    <div class="chart-container">
                        <canvas id="lineChart5"></canvas>
                    </div>
                </div>
                <div class="filter-item">
                    <div class="chart-container">
                        <canvas id="lineChart6"></canvas>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Sección: Gastos Operativos -->
    <div class="data-card">
        <div class="data-card-header">
            <h3 class="data-card-title">
                <i class="fa-solid fa-receipt"></i> Gastos Operativos
            </h3>
        </div>
        <div class="data-card-content">
            <div class="filter-row">
                <div class="filter-item">
                    <div class="chart-container" style="justify-content: center; display: flex; ">
                        <canvas id="lineChart8"></canvas>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @endif

@endsection

@section('scripts')
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        const ctx = document.getElementById('lineChart1').getContext('2d');

        const data = {
            labels: @json($labels),
            datasets: [{
                    label: 'Pagos mensuales',
                    data:  @json($dataPagos),
                    fill: false,
                    borderColor: 'rgba(75, 192, 192, 1)',
                    backgroundColor: 'rgba(75, 192, 192, 0.2)',
                    tension: 0.1
                },
                {
                    label: 'Ventas mensuales',
                    data:  @json($dataVentas),
                    fill: false,
                    borderColor: 'rgb(85,7,7)',
                    backgroundColor: 'rgb(181,15,15)',
                    tension: 0.1
                }
            ]
        };

        const config = {
            type: 'line',
            data: data,
            options: {
                responsive: true,
                plugins: {
                    title: {
                        display: true,
                        text: 'Pagos y Ventas por mes'
                    },
                    tooltip: {
                        mode: 'index',
                        intersect: false
                    },
                    legend: {
                        position: 'bottom'
                    }
                },
                interaction: {
                    mode: 'nearest',
                    axis: 'x',
                    intersect: false
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        title: {
                            display: true,
                            text: 'Numero de Ventas'
                        }
                    }
                }
            }
        };

        new Chart(ctx, config);
    </script>
    <script>
        const ctx2 = document.getElementById('lineChart2').getContext('2d');

        const data2 = {
            labels: @json($labels),
            datasets:@json($dataMembresiasChart)
        };

        const config2 = {
            type: 'line',
            data: data2,
            options: {
                responsive: true,
                plugins: {
                    title: {
                        display: true,
                        text: 'Membresías vendidas por mes'
                    },
                    tooltip: {
                        mode: 'index',
                        intersect: false
                    },
                    legend: {
                        position: 'bottom'
                    }
                },
                interaction: {
                    mode: 'nearest',
                    axis: 'x',
                    intersect: false
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        title: {
                            display: true,
                            text: 'Cantidad vendida'
                        }
                    }
                }
            }
        };

        new Chart(ctx2, config2);
    </script>
    <script>
        const ctx3 = document.getElementById('lineChart3').getContext('2d');

        const data3 = {
            labels: @json($labels),
            datasets: @json($dataRenovacionesPorSede)
        };

        const config3 = {
            type: 'line',
            data: data3,
            options: {
                responsive: true,
                plugins: {
                    title: {
                        display: true,
                        text: 'Actualizaciones de membresias'
                    },
                    tooltip: {
                        mode: 'index',
                        intersect: false
                    },
                    legend: {
                        position: 'bottom'
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        title: {
                            display: true,
                            text: 'Cantidad de alumnos'
                        }
                    }
                }
            }
        };

        new Chart(ctx3, config3);
    </script>
    <script>
        const ctx4 = document.getElementById('lineChart4').getContext('2d');

        const data4 = {
            labels: @json($labels),
            datasets: @json($dataAlumnosPorSede)
        };

        const config4 = {
            type: 'line',
            data: data4,
            options: {
                responsive: true,
                plugins: {
                    title: {
                        display: true,
                        text: 'Alumnos nuevos por sede y mes'
                    },
                    tooltip: {
                        mode: 'index',
                        intersect: false
                    },
                    legend: {
                        position: 'bottom'
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        title: {
                            display: true,
                            text: 'Cantidad de alumnos'
                        }
                    }
                }
            }
        };

        new Chart(ctx4, config4);
    </script>
    <script>
        const ctx5 = document.getElementById('lineChart5').getContext('2d');

        const data5 = {
            labels: @json($labels),
            datasets:@json($dataEntrenadoresPorSede)
        };

        const config5 = {
            type: 'line',
            data: data5,
            options: {
                responsive: true,
                plugins: {
                    title: {
                        display: true,
                        text: 'Pagos Entrenadores'
                    },
                    tooltip: {
                        mode: 'index',
                        intersect: false
                    },
                    legend: {
                        position: 'bottom'
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        title: {
                            display: true,
                            text: 'Cantidad de Pagos'
                        }
                    }
                }
            }
        };

        new Chart(ctx5, config5);
    </script>
    <script>
        const ctx6 = document.getElementById('lineChart6').getContext('2d');

        const data6 = {
            labels: @json($labels),
            datasets: @json($dataEntrenadoresvPorSede)
        };

        const config6 = {
            type: 'line',
            data: data6,
            options: {
                responsive: true,
                plugins: {
                    title: {
                        display: true,
                        text: 'Ventas Entrenadores'
                    },
                    tooltip: {
                        mode: 'index',
                        intersect: false
                    },
                    legend: {
                        position: 'bottom'
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        title: {
                            display: true,
                            text: 'Cantidad de Ventas'
                        }
                    }
                }
            }
        };

        new Chart(ctx6, config6);
    </script>
    <script>
        const ctx7 = document.getElementById('lineChart7').getContext('2d');

        const data7 = {
            labels: @json($labels),
            datasets: @json($dataProductosVendidosPorSede)
        };

        const config7 = {
            type: 'line',
            data: data7,
            options: {
                responsive: true,
                plugins: {
                    title: {
                        display: true,
                        text: 'Sedes y Productos'
                    },
                    tooltip: {
                        mode: 'index',
                        intersect: false
                    },
                    legend: {
                        position: 'bottom'
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        title: {
                            display: true,
                            text: 'Cantidad de Productos vendidos'
                        }
                    }
                }
            }
        };

        new Chart(ctx7, config7);
    </script>
    <script>
        const ctxGastos = document.getElementById('lineChart8').getContext('2d');

        new Chart(ctxGastos, {
            type: 'line',
            data: {
                labels: @json($labels),
                datasets: @json($dataGastosPorSede)
            },
            options: {
                responsive: true,
                plugins: {
                    title: {
                        display: true,
                        text: 'Gastos por sede al mes'
                    },
                    tooltip: {
                        mode: 'index',
                        intersect: false
                    },
                    legend: {
                        position: 'bottom'
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        title: {
                            display: true,
                            text: 'Cantidad de Gastos'
                        }
                    }
                }
            }
        });
    </script>
    <script>
        const ctxGanancia = document.getElementById('lineChart9').getContext('2d');

        new Chart(ctxGanancia, {
            type: 'line',
            data: {
                labels: @json($labels),
                datasets: [{
                    label: 'Ganancia Neta',
                    data: @json($dataGananciaNeta),
                    fill: false,
                    borderColor: '#28a745',
                    backgroundColor: '#28a745',
                    tension: 0.1
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    title: {
                        display: true,
                        text: 'Ganancia Neta Mensual (Pagos + Ventas - Gastos)'
                    },
                    tooltip: {
                        mode: 'index',
                        intersect: false
                    },
                    legend: {
                        position: 'bottom'
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        title: {
                            display: true,
                            text: 'Cantidad neta'
                        }
                    }
                }
            }
        });
    </script>


    <script>
        const ctxMetodos = document.getElementById('lineChart10').getContext('2d');
        new Chart(ctxMetodos, {
            type: 'line',
            data: {
                labels: @json($labels),
                datasets: @json($dataMetodosPago)
            },
            options: {
                responsive: true,
                plugins: {
                    title: {
                        display: true,
                        text: 'Métodos de Pago por Mes ({{ $fechaFiltro }})'
                    },
                    tooltip: {
                        mode: 'index',
                        intersect: false
                    },
                    legend: {
                        position: 'bottom'
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        title: {
                            display: true,
                            text: 'Monto Total'
                        }
                    }
                }
            }
        });
    </script>


@endsection
