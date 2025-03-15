<?php
    include __DIR__.'/../../functions/security.php'; 
	@session_start();

    if(!empty($_POST)){
        echo "<pre>";
        print_r($_POST);
        echo "</pre>";
    }else{
        foreach ($datos_secciones_ine as $id_seccion_ine => $datos) {
            //['orden_votos_individual']['semaforo']
            $seccion = $datos['numero'];
            $color = $datos['orden_votos_individual']['semaforo']['color'];
            $diferencia = $datos['orden_votos_individual']['semaforo']['diferencia'];

            //fillColor
            //strokeColor

            if($color=='rojo'){
                $fillColor = '#FF0000';
            }elseif ($color=='amarillo') {
                $fillColor = '#ffff00';
            }elseif ($color=='gris') {
                $fillColor = '#808080';
            }elseif ($color=='verde') {
                $fillColor = '#008000';
            }else{
                $fillColor = '#000000';
            }

            $grafica_diferencia[] = array('x' => $seccion, 'y' => $diferencia, 'fillColor' => $fillColor, 'strokeColor' => $fillColor);
            $grafica_colores[] = $color;

            $grafica_programas_inversion[] = array('x' => $seccion, 'y' => $datos['acciones_obras']);
            $grafica_programas_gobierno[] = array('x' => $seccion, 'y' => $datos['apoyos_programas']);
            
            $grafica_ciudadanos_registrados[] = array('x' => $seccion, 'y' => $datos['ciudadanos_registrados']);
            $grafica_funcionarios[] = array('x' => $seccion, 'y' => $datos['funcionarios']);
            $grafica_militantes[] = array('x' => $seccion, 'y' => $datos['militantes']);
            $grafica_grupos_interes[] = array('x' => $seccion, 'y' => $datos['grupos_interes']);
            $test[] = $diferencia;
        }
    }

    echo json_encode($grafica_ciudadanos_registrados);

    ?>
    <script>
        function generateDayWiseTimeSeries(baseval, count, yrange) {
            var i = 0;
            var series = [];
            while (i < count) {
            var x = baseval;
            var y = Math.floor(Math.random() * (yrange.max - yrange.min + 1)) + yrange.min;
        
            series.push([x, y]);
            baseval += 86400000;
            i++;
            }
            return series;
        }
        
        var data = generateDayWiseTimeSeries(new Date('11 Feb 2017').getTime(), 185, {
            min: 30,
            max: 90
        })
    </script>
    <div id="appTerritorio">
        <div id="app">
            <div id="wrapper">
                <div id="chart-line2">
                    <apexchart type="line" height="230" :options="chartOptions" :series="series"></apexchart>
                </div>
                <div id="chart-line">
                    <apexchart type="line" height="130" :options="chartOptionsLine" :series="seriesLine"></apexchart>
                </div>
            </div>
        </div>
    </div>
    <script>
        new Vue({
            el: '#app',
            components: {
                apexchart: VueApexCharts
            },
            data: {

                series: [
                    {
                        data: <?= json_encode($grafica_diferencia); ?>,
                    }
                ],
                chartOptions: {
                    chart: {
                        id: 'chart2',
                        type: 'line',
                        height: 230,
                        toolbar: {
                            autoSelected: 'pan',
                            show: false
                        }
                    },
                    colors: ['#546E7A'],
                    stroke: {
                        width: 3
                    },
                    dataLabels: {
                        enabled: false,
                    },
                    fill: {
                        opacity: 1
                    },
                    markers: {
                        size: 0
                    },
                },

                seriesLine: [
                    {
                        data:  <?= json_encode($grafica_diferencia); ?>,
                    }
                ],
                enabled: true,
                
                chartOptionsLine: {
                    chart: {
                        id: 'chart1',
                        height: 130,
                        type: 'line',
                        brush: {
                            target: 'chart2',
                            enabled: true
                        },
                        selection: {
                            enabled: true,
                            xaxis: {
                                min: 1,
                                max: 15
                            }
                        },
                        plotOptions: {
                            line: {
                                horizontal: false,
                                dataLabels: {
                                    position: 'bottom'
                                }
                            },
                        },
                        dataLabels: {
                            dropShadow: {
                                enabled: true,
                                left: 2,
                                top: 2,
                                opacity: 0.5
                            }
                        },
                    },
                    plotOptions: {
                        line: {
                            horizontal: false,
                            dataLabels: {
                                position: 'bottom'
                            }
                        },
                    },
                    legend: {
                        show: false,
                    },
                    dataLabels: {
                        dropShadow: {
                            enabled: true,
                            left: 2,
                            top: 2,
                            opacity: 0.5
                        }
                    },
                    colors: ['#008FFB'],
                    fill: {
                        type: 'gradient',
                        gradient: {
                            opacityFrom: 0.91,
                            opacityTo: 0.1
                        }
                    },
                    xaxis: {
                        legend: {
                            show: false,
                        },
                        tooltip: {
                            enabled: false
                        },
                    },
                    yaxis: {
                        show: false,
                        tickAmount: 2,
                        tooltip: {
                            enabled: false
                        },
                    }
                }
            }
        })
    </script>