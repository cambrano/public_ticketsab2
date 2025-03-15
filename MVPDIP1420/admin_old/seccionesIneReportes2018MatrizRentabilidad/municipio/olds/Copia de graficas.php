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
        }
    }
    ?>
    <div id="appTerritorio">
        <div id="chart" style="width:100%">
            <apexchart type="line" height="400" width="100%" :options="chartOptionsTerritorio" :series="series"></apexchart>
        </div>
    </div>
    <script>
        new Vue({
            el: '#appTerritorio',
            components: {
                apexchart: VueApexCharts
            },
            barOptions: {
                spaceRatio: 0.25
            },
            data: {
                series: [
                    {
                        name: 'Diferencia de la segunda fuerza',
                        type: 'bar',
                        labels: <?= json_encode($grafica_colores); ?>,
                        data: <?= json_encode($grafica_diferencia); ?>,
                    }, 
                    {
                        name: 'Programas Inversion ',
                        type: 'line',
                        labels: <?= json_encode($grafica_colores); ?>,
                        data: <?= json_encode($grafica_programas_inversion); ?>,
                    }, 
                    {
                        name: 'Programas de Gobierno ',
                        type: 'area',
                        labels: <?= json_encode($grafica_colores); ?>,
                        data: <?= json_encode($grafica_programas_gobierno); ?>,
                    }, 
                ],
                chartOptionsTerritorio: {
                    chart: {
                        height: 350,
                        type: 'bar',
                        animations: {
                            enabled: true,
                            easing: 'easeinout',
                            speed: 800,
                            animateGradually: {
                                enabled: true,
                                delay: 150
                            },
                            dynamicAnimation: {
                                enabled: true,
                                speed: 350
                            }
                        },
                        toolbar: {
                            show: true,
                            offsetX: 0,
                            offsetY: 0,
                            tools: {
                                download: false,
                                selection: true,
                                zoom: true,
                                zoomin: true,
                                zoomout: true,
                                pan: false,
                                reset:  true | '<img src="reset.png" width="20">',
                                customIcons: []
                            },
                            export: {
                            csv: {
                                filename: 'analisis_territorio',
                                columnDelimiter: ',',
                                headerCategory: 'Secciones',
                                headerValue: 'value',
                                dateFormatter(timestamp) {
                                    return new Date(timestamp).toDateString()
                                }
                            },
                            svg: {
                                title:'alex',
                                filename: 'analisis_territorio',
                            },
                            png: {
                                filename: 'analisis_territorio',
                            }
                            },
                            autoSelected: 'zoom' 
                        },
                    },
                    plotOptions: {
                        bar: {
                            horizontal: false,
                            columnWidth: '80%'
                        }
                    },
                    colors: ['#3B5998', '#00C4CC', '#25d366',],
                    stroke: {
                        width: [0,1,2,3],
                        curve: ['straight','straight','straight']
                    },
                    title: {
                        text: 'Análisis de territorio'
                    },
                    dataLabels: {
                        enabled: true,
                        enabledOnSeries: [0],
                        style: {
                            colors: ['#000']
                        },
                    },
                    fill: {
                        
                    },
                    yaxis: [
                        {
                            show: true,
                            title: {
                                text: 'Diferencia Votos',
                                style: {
                                    fontWeight: 900,
                                    color: '#3B5998'
                                }
                            },
                            labels: {
                                formatter: function (val) {
                                    return val.toLocaleString()
                                }
                            },
                            tooltip: {
                                enabled: false,
                                offsetX: 0,
                            },
                        }, 
                        {
                            show: true,
                            opposite: true,
                            title: {
                                text: 'Programas de Inversion',
                                style: {
                                    fontWeight: 900,
                                    color: '#00C4CC'
                                }
                            },
                            labels: {
                                formatter: function (val) {
                                    return val.toLocaleString()
                                }
                            }

                        }, 
                        {
                            show: true,
                            opposite: true,
                            title: {
                                text: 'Programas de Gobierno',
                                style: {
                                    fontWeight: 900,
                                    color: '#25d366'
                                }
                            },
                            labels: {
                                formatter: function (val) {
                                    return val.toLocaleString();
                                }
                            }

                        },
                    ],
                    xaxis: {
                        labels: {
                            formatter: function (value) {
                                return parseFloat(value).toLocaleString();
                            }
                        },
                        title: {
                            text: 'Secciones',
                            style: {
                                color: '#ff0000',
                                fontSize: '12px',
                                fontFamily: 'Helvetica, Arial, sans-serif',
                                fontWeight: 600,
                                cssClass: 'apexcharts-xaxis-title',
                            },
                        },
                        tooltip: {
                            enabled: false,
                            offsetX: 0,
                        },
                    },
                    tooltip: {
                        enabled: true,
                        shared: true,
                        followCursor: true,
                        intersect: false,
                        inverseOrder: false,
                        custom: undefined,
                        fillSeriesColor: false,
                        onDatasetHover: {
                            highlightDataSeries: false,
                        }, 
                        x: {
                            formatter: function(value, { series, seriesIndex, dataPointIndex, w }) {
                                seccion = w.config.series[seriesIndex].data[dataPointIndex].x ;
                                color = w.config.series[seriesIndex].labels[dataPointIndex];

                                if(color=='rojo'){
                                    bg_color = 'rgba(255,0,0,0.6)';
                                    bg_color = '#FF6961';
                                }else if(color=='amarillo'){
                                    bg_color = '#efa94a';
                                }else if(color=='gris'){
                                    bg_color = '#9b9b9b';
                                }else if(color=='verde'){
                                    bg_color = '#77dd77';
                                }else{
                                    bg_color = '#000000';
                                }
                                div = '<table><tr><td style="opacity: 0.97;background-color:'+bg_color+'">                </td><td>   Sección : ' + parseFloat(seccion).toLocaleString() +'</td></tr></table>';
                                //<div style="display:table"><div style="background-color:red; width:10px"> </div><div>'+'Sección : ' + parseFloat(seccion).toLocaleString()+'</div></div>
                                return div;
                            }
                        },
                        marker: {
                            show: true,
                        },
                        fixed: {
                            enabled: true,
                            position: 'topRight',
                            offsetX: 0,
                            offsetY: 0,
                        },
                        
                    },
                    fill: {
                        opacity: ['0.6','0.4','0.4'],
                        type: ['solid','solid','solid'],
                        pattern: {
                            style: "verticalLines",
                            
                        }
                    },
                    markers: {
                        size: 5,
                        hover: {
                            size: 9
                        }
                    },
                    stroke: {
                        show: true,
                        curve: 'straight',
                        lineCap: 'butt',
                        colors: ['#34495E','#1A5276'],
                        width: 2,
                        dashArray: 1,      
                    },
                    grid: {
                        row: {
                            colors: ['#f3f3f3', 'transparent'], // takes an array which will be repeated on columns
                            opacity: 0.5
                        },
                    },
                },
            }
        })
    </script>