'use strict';

$(document).ready(function() {

	function generateData(baseval, count, yrange) {
		var i = 0;
		var series = [];
		while (i < count) {
			var x = Math.floor(Math.random() * (750 - 1 + 1)) + 1;;
			var y = Math.floor(Math.random() * (yrange.max - yrange.min + 1)) + yrange.min;
			var z = Math.floor(Math.random() * (75 - 15 + 1)) + 15;

			series.push([x, y, z]);
			baseval += 86400000;
			i++;
		}
		return series;
	}


	// Column chart
    if($('#sales_chart').length > 0 ){
    	var columnCtx = document.getElementById("sales_chart"),
    	columnConfig = {
    		colors: ['#7638ff', '#fda600'],
    		series: [
    			{
    			name: "Received",
    			type: "column",
    			data: [70, 150, 80, 180, 150, 175, 201, 60, 200, 120, 190, 160, 50]
    			},
    			{
    			name: "Pending",
    			type: "column",
    			data: [23, 42, 35, 27, 43, 22, 17, 31, 22, 22, 12, 16, 80]
    			}
    		],
    		chart: {
    			type: 'bar',
    			fontFamily: 'Poppins, sans-serif',
    			height: 350,
    			toolbar: {
    				show: false
    			}
    		},
    		plotOptions: {
    			bar: {
    				horizontal: false,
    				columnWidth: '60%',
    				endingShape: 'rounded'
    			},
    		},
    		dataLabels: {
    			enabled: false
    		},
    		stroke: {
    			show: true,
    			width: 2,
    			colors: ['transparent']
    		},
    		xaxis: {
    			categories: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct'],
    		},
    		yaxis: {
    			title: {
    				text: '$ (thousands)'
    			}
    		},
    		fill: {
    			opacity: 1
    		},
    		tooltip: {
    			y: {
    				formatter: function (val) {
    					return "$ " + val + " thousands"
    				}
    			}
    		}
    	};
    	var columnChart = new ApexCharts(columnCtx, columnConfig);
    	columnChart.render();
    }

	//Pie Chart
    if($('#invoice_chart').length > 0 ){
    	var pieCtx = document.getElementById("invoice_chart"),
    	pieConfig = {
    		colors: ['#7638ff', '#ff737b', '#fda600', '#1ec1b0'],
    		series: [55, 40, 20, 10],
    		chart: {
    			fontFamily: 'Poppins, sans-serif',
    			height: 350,
    			type: 'donut',
    		},
    		labels: ['Paid', 'Unpaid', 'Overdue', 'Draft'],
    		legend: {show: false},
    		responsive: [{
    			breakpoint: 480,
    			options: {
    				chart: {
    					width: 200
    				},
    				legend: {
    					position: 'bottom'
    				}
    			}
    		}]
    	};
    	var pieChart = new ApexCharts(pieCtx, pieConfig);
    	pieChart.render();
	}
	
	// Simple Line
    if($('#s-line').length > 0 ){
    var sline = {
      chart: {
        height: 350,
        type: 'line',
        zoom: {
          enabled: false
        },
        toolbar: {
          show: false,
        }
      },
      // colors: ['#4361ee'],
      dataLabels: {
        enabled: false
      },
      stroke: {
        curve: 'straight'
      },
      series: [{
        name: "Desktops",
        data: [10, 41, 35, 51, 49, 62, 69, 91, 148]
      }],
      title: {
        text: 'Product Trends by Month',
        align: 'left'
      },
      grid: {
        row: {
          colors: ['#f1f2f3', 'transparent'], // takes an array which will be repeated on columns
          opacity: 0.5
        },
      },
      xaxis: {
        categories: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep'],
      }
    }

    var chart = new ApexCharts(
      document.querySelector("#s-line"),
      sline
    );

    chart.render();
    }


// Simple Line Area
 if($('#s-line-area').length > 0 ){
var sLineArea = {
    chart: {
        height: 350,
        type: 'area',
        toolbar: {
          show: false,
        }
    },
    // colors: ['#4361ee', '#888ea8'],
    dataLabels: {
        enabled: false
    },
    stroke: {
        curve: 'smooth'
    },
    series: [{
        name: 'series1',
        data: [31, 40, 28, 51, 42, 109, 100]
    }, {
        name: 'series2',
        data: [11, 32, 45, 32, 34, 52, 41]
    }],

    xaxis: {
        type: 'datetime',
        categories: ["2018-09-19T00:00:00", "2018-09-19T01:30:00", "2018-09-19T02:30:00", "2018-09-19T03:30:00", "2018-09-19T04:30:00", "2018-09-19T05:30:00", "2018-09-19T06:30:00"],                
    },
    tooltip: {
        x: {
            format: 'dd/MM/yy HH:mm'
        },
    }
}

var chart = new ApexCharts(
    document.querySelector("#s-line-area"),
    sLineArea
);

chart.render();
}

// Simple Column
if($('#s-col').length > 0 ){
var sCol = {
    chart: {
        height: 350,
        type: 'bar',
        toolbar: {
          show: false,
        }
    },
    plotOptions: {
        bar: {
            horizontal: false,
            columnWidth: '55%',
            endingShape: 'rounded'  
        },
    },
    // colors: ['#888ea8', '#4361ee'],
    dataLabels: {
        enabled: false
    },
    stroke: {
        show: true,
        width: 2,
        colors: ['transparent']
    },
    series: [{
        name: 'Net Profit',
        data: [44, 55, 57, 56, 61, 58, 63, 60, 66]
    }, {
        name: 'Revenue',
        data: [76, 85, 101, 98, 87, 105, 91, 114, 94]
    }],
    xaxis: {
        categories: ['Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct'],
    },
    yaxis: {
        title: {
            text: '$ (thousands)'
        }
    },
    fill: {
        opacity: 1

    },
    tooltip: {
        y: {
            formatter: function (val) {
                return "$ " + val + " thousands"
            }
        }
    }
}

var chart = new ApexCharts(
    document.querySelector("#s-col"),
    sCol
);

chart.render();
}


// Simple Column Stacked
if($('#s-col-stacked').length > 0 ){
var sColStacked = {
    chart: {
        height: 350,
        type: 'bar',
        stacked: true,
        toolbar: {
          show: false,
        }
    },
    // colors: ['#4361ee', '#888ea8', '#e3e4eb', '#d3d3d3'],
    responsive: [{
        breakpoint: 480,
        options: {
            legend: {
                position: 'bottom',
                offsetX: -10,
                offsetY: 0
            }
        }
    }],
    plotOptions: {
        bar: {
            horizontal: false,
        },
    },
    series: [{
        name: 'PRODUCT A',
        data: [44, 55, 41, 67, 22, 43]
    },{
        name: 'PRODUCT B',
        data: [13, 23, 20, 8, 13, 27]
    },{
        name: 'PRODUCT C',
        data: [11, 17, 15, 15, 21, 14]
    },{
        name: 'PRODUCT D',
        data: [21, 7, 25, 13, 22, 8]
    }],
    xaxis: {
        type: 'datetime',
        categories: ['01/01/2011 GMT', '01/02/2011 GMT', '01/03/2011 GMT', '01/04/2011 GMT', '01/05/2011 GMT', '01/06/2011 GMT'],
    },
    legend: {
        position: 'right',
        offsetY: 40
    },
    fill: {
        opacity: 1
    },
}

var chart = new ApexCharts(
    document.querySelector("#s-col-stacked"),
    sColStacked
);

chart.render();
}

// Simple Bar
if($('#s-bar').length > 0 ){
var sBar = {
    chart: {
        height: 350,
        type: 'bar',
        toolbar: {
          show: false,
        }
    },
    // colors: ['#4361ee'],
    plotOptions: {
        bar: {
            horizontal: true,
        }
    },
    dataLabels: {
        enabled: false
    },
    series: [{
        data: [400, 430, 448, 470, 540, 580, 690, 1100, 1200, 1380]
    }],
    xaxis: {
        categories: ['South Korea', 'Canada', 'United Kingdom', 'Netherlands', 'Italy', 'France', 'Japan', 'United States', 'China', 'Germany'],
    }
}

var chart = new ApexCharts(
    document.querySelector("#s-bar"),
    sBar
);

chart.render();
}

// Mixed Chart
if($('#mixed-chart').length > 0 ){
var options = {
  chart: {
    height: 350,
    type: 'line',
    toolbar: {
      show: false,
    }
  },
  // colors: ['#4361ee', '#888ea8'],
  series: [{
    name: 'Website Blog',
    type: 'column',
    data: [440, 505, 414, 671, 227, 413, 201, 352, 752, 320, 257, 160]
  }, {
    name: 'Social Media',
    type: 'line',
    data: [23, 42, 35, 27, 43, 22, 17, 31, 22, 22, 12, 16]
  }],
  stroke: {
    width: [0, 4]
  },
  title: {
    text: 'Traffic Sources'
  },
  labels: ['01 Jan 2001', '02 Jan 2001', '03 Jan 2001', '04 Jan 2001', '05 Jan 2001', '06 Jan 2001', '07 Jan 2001', '08 Jan 2001', '09 Jan 2001', '10 Jan 2001', '11 Jan 2001', '12 Jan 2001'],
  xaxis: {
    type: 'datetime'
  },
  yaxis: [{
    title: {
      text: 'Website Blog',
    },

  }, {
    opposite: true,
    title: {
      text: 'Social Media'
    }
  }]

}

var chart = new ApexCharts(
  document.querySelector("#mixed-chart"),
  options
);

chart.render();
}

// Donut Chart

if($('#donut-chart').length > 0 ){
var donutChart = {
    chart: {
        height: 350,
        type: 'donut',
        toolbar: {
          show: false,
        }
    },
    // colors: ['#4361ee', '#888ea8', '#e3e4eb', '#d3d3d3'],
    series: [44, 55, 41, 17],
    responsive: [{
        breakpoint: 480,
        options: {
            chart: {
                width: 200
            },
            legend: {
                position: 'bottom'
            }
        }
    }]
}

var donut = new ApexCharts(
    document.querySelector("#donut-chart"),
    donutChart
);

donut.render();
}

// Radial Chart
if($('#radial-chart').length > 0 ){
var radialChart = {
    chart: {
        height: 350,
        type: 'radialBar',
        toolbar: {
          show: false,
        }
    },
    // colors: ['#4361ee', '#888ea8', '#e3e4eb', '#d3d3d3'],
    plotOptions: {
        radialBar: {
            dataLabels: {
                name: {
                    fontSize: '22px',
                },
                value: {
                    fontSize: '16px',
                },
                total: {
                    show: true,
                    label: 'Total',
                    formatter: function (w) {
                        return 249
                    }
                }
            }
        }
    },
    series: [44, 55, 67, 83],
    labels: ['Apples', 'Oranges', 'Bananas', 'Berries'],    
}

var chart = new ApexCharts(
    document.querySelector("#radial-chart"),
    radialChart
);

chart.render();
}	
	
if($('#sales_charts').length > 0) {
	var options = {
		series: [{
		name: 'Sales',
		data: [130, 210, 300, 290, 150, 50, 210, 280, 105],
	  }, {
		name: 'Purchase',
		data: [-150, -90, -50, -180, -50, -70, -100, -90, -105]
	  }],
	  colors: ['#28C76F', '#EA5455'],
		chart: {
		type: 'bar',
		height: 310,
		stacked: true,
		
		zoom: {
		  enabled: true
		}
	  },
	  responsive: [{
		breakpoint: 280,
		options: {
		  legend: {
			position: 'bottom',
			offsetY: 0
		  }
		}
	  }],
	  plotOptions: {
		bar: {
		  horizontal: false,
          borderRadius: 4,
        borderRadiusApplication: "end", // "around" / "end" 
        borderRadiusWhenStacked: "all", // "all"/"last"
		  columnWidth: '20%',
		},
	  },
      dataLabels: {
      enabled: false
    },
      yaxis: {
          min: -200,
          max: 300,
          tickAmount: 5,
        },
	  xaxis: {
		categories: [' Jan ', 'Feb', 'Mar', 'Apr',
		  'May', 'Jun' , 'Jul' , 'Aug', 'Sep'
		],
	  },
    yaxis: {
      labels: {
        offsetX: -15, // Adjust horizontal alignment
      },
    },
	  legend: {show: false},
	  fill: {
		opacity: 1
	  }
	  };

	  var chart = new ApexCharts(document.querySelector("#sales_charts"), options);
	  chart.render();
	}

    if($('#sales-analysis').length > 0 ){
    var options = {
      series: [{
        name: "Sales Analysis",
        data: [25, 50, 55, 30, 40, 50, 30, 20, 40, 35, 40, 20]
    }],
      chart: {
      height: 310,
      type: 'area',
      zoom: {
        enabled: false
      }
    },
    colors: ['#FF9F43'],
    dataLabels: {
      enabled: false
    },
    stroke: {
      curve: 'straight'
    },
    title: {
      text: '',
      align: 'left'
    },
    xaxis: {
      categories: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep'],
    },
    yaxis: {
      min: 10,
      max: 60,
      tickAmount: 5,
          labels: {
            offsetX: -15,
            formatter: (val) => {
              return val / 1 + 'K'
            }
          }
        },
        legend: {
          position: 'top',
          horizontalAlign: 'left'
        }
    };

    var chart = new ApexCharts(document.querySelector("#sales-analysis"), options);
    chart.render();
  }

  // Radial Chart
if($('#customer-chart').length > 0 ){

  
  var radialChart = {
      chart: {
          height: '150px',
          width: '100%',
          type: 'radialBar',
          parentHeightOffset: 0,
          offsetX: 0, // Adjust horizontal alignment if needed
          offsetY: 0, 
          toolbar: {
            show: false,
          }
      },
      plotOptions: {
        radialBar: {
          hollow: {
            margin: 10, // Gap between radial bars
            size: '30%', // Inner hollow size
          },
          track: {
            background: '#E6EAED', // Background color for unfilled track
            strokeWidth: '100%',
            margin: 5, // Margin between tracks
          },
          strokeWidth: 'rounded',
          dataLabels: {
            name: {
              offsetY: -5, // Fine-tune label position
            },
            value: {
              offsetY: 5, // Adjust value position
            },
          },
        },
      },
      grid: {
        padding: {
          top: 0, // Remove extra padding at the top
          bottom: 0, // Remove extra padding at the bottom
          left: 0, // Remove extra padding on the left
          right: 0, // Remove extra padding on the right
        },
      },
      stroke: {
        lineCap: 'round', // Rounded stroke ends
      },
      colors: ['#E04F16', '#0E9384'],
      series: [70, 70],
      labels: ['First Time', 'Return'],    
  }
  
  var chart = new ApexCharts(
      document.querySelector("#customer-chart"),
      radialChart
  );
  
  //chart.render();

  chart.render().then(() => {
    // Access the rendered SVG here
    const svg = document.querySelector("#customer-chart .apexcharts-svg");
    if (svg) {
      svg.style.height = "130px"; // Ensure SVG matches the desired height
      svg.style.width = "auto";
    } else {
      console.error("SVG element not found");
    }
  });
  }	

  // sales income

  if ($('#sales-purchase').length > 0) {
    var sColStacked = {
      chart: {
        height: 245,
        type: 'bar',
        stacked: true,
        toolbar: {
          show: false,
        }
      },
      colors: ['#FE9F43', '#FFE3CB'],
      responsive: [{
        breakpoint: 480,
        options: {
          legend: {
            position: 'bottom',
            offsetX: -10,
            offsetY: 0
          }
        }
      }],
      plotOptions: {
        bar: {
          borderRadius: 8, 
          borderRadiusWhenStacked: 'all',
          horizontal: false,
          endingShape: 'rounded'
        },
      },
      series: [{
        name: 'Sales',
        data: [18, 25, 10, 18, 25, 18, 10, 20, 40, 8, 30, 20]
      }, {
        name: 'Purchase',
        data: [50, 40, 30, 50, 40, 50, 30, 30, 50, 30, 40, 30]
      }],
      xaxis: {
        categories: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct','Nov', 'Dec'],
        labels: {
          style: {
            colors: '#6B7280', 
            fontSize: '13px',
          }
        }
      },
      yaxis: {
        labels: {
          formatter: (val) => {
            return val / 1 + 'K'
          },
          offsetX: -15,
          style: {
            colors: '#6B7280', 
            fontSize: '13px',
          }
        }
      },
      grid: {
        borderColor: '#E5E7EB',
        strokeDashArray: 5,
        padding: {
          left: -16,
          top: 0,
      bottom: 0,
      right: 0, 
        },
      },
      legend: {
        show: false
      },
      dataLabels: {
        enabled: false // Disable data labels
      },
      fill: {
        opacity: 1
      },
    }

    var chart = new ApexCharts(
      document.querySelector("#sales-purchase"),
      sColStacked
    );

    chart.render();
  }

  if ($('#sales-daychart').length > 0) {
    var sColStacked = {
      chart: {
        height: 245,
        type: 'bar',
        stacked: true,
        toolbar: {
          show: false,
        }
      },
      colors: ['#FE9F43', '#FFE3CB'],
      responsive: [{
        breakpoint: 480,
        options: {
          legend: {
            position: 'bottom',
            offsetX: -10,
            offsetY: 0
          }
        }
      }],
      plotOptions: {
        bar: {
          borderRadius: 8, 
          borderRadiusWhenStacked: 'all',
          horizontal: false,
          endingShape: 'rounded'
        },
      },
      series: [{
        name: 'Sales',
        data: [18, 20, 10, 18, 25, 18, 10, 20, 40, 8, 30, 20]
      }, {
        name: 'Purchase',
        data: [40, 30, 30, 50, 40, 50, 30, 30, 50, 30, 40, 30]
      }],
      xaxis: {
        categories: ['2 am', '4 am', '6 am', '8 am', '10 am', '12 am', '14 pm', '16 pm', '18 pm', '20 pm','22 pm', '24 pm'],
        labels: {
          style: {
            colors: '#6B7280', 
            fontSize: '13px',
          }
        }
      },
      yaxis: {
        labels: {
          formatter: (val) => {
            return val / 1 + 'K'
          },
          offsetX: -15,
          style: {
            colors: '#6B7280', 
            fontSize: '13px',
          }
        }
      },
      grid: {
        borderColor: '#E5E7EB',
        strokeDashArray: 5,
        padding: {
          left: -16,
          top: 0,
      bottom: 0,
      right: 0, 
        },
      },
      legend: {
        show: false
      },
      dataLabels: {
        enabled: false // Disable data labels
      },
      fill: {
        opacity: 1
      },
    }

    var chart = new ApexCharts(
      document.querySelector("#sales-daychart"),
      sColStacked
    );

    chart.render();
  }

  if ($('#sales-weekchart').length > 0) {
    var sColStacked = {
      chart: {
        height: 245,
        type: 'bar',
        stacked: true,
        toolbar: {
          show: false,
        }
      },
      colors: ['#FE9F43', '#FFE3CB'],
      responsive: [{
        breakpoint: 480,
        options: {
          legend: {
            position: 'bottom',
            offsetX: -10,
            offsetY: 0
          }
        }
      }],
      plotOptions: {
        bar: {
          borderRadius: 8, 
          borderRadiusWhenStacked: 'all',
          horizontal: false,
          endingShape: 'rounded',
          columnWidth: '50%',
        },
      },
      series: [{
        name: 'Sales',
        data: [20,  18, 25,  10,  40, 8, 30]
      }, {
        name: 'Purchase',
        data: [30,  50, 40,  30, 50, 30, 30]
      }],
      xaxis: {
        categories: ['23-1-25', '24-1-25', '25-1-25', '26-1-25', '27-1-25', '28-1-25', '28-1-25'],
        labels: {
          style: {
            colors: '#6B7280', 
            fontSize: '13px',
          }
        }
      },
      yaxis: {
        labels: {
          formatter: (val) => {
            return val / 1 + 'K'
          },
          offsetX: -15,
          style: {
            colors: '#6B7280', 
            fontSize: '13px',
          }
        }
      },
      grid: {
        borderColor: '#E5E7EB',
        strokeDashArray: 5,
        padding: {
          left: -16,
          top: 0,
      bottom: 0,
      right: 0, 
        },
      },
      legend: {
        show: false
      },
      dataLabels: {
        enabled: false // Disable data labels
      },
      fill: {
        opacity: 1
      },
    }

    var chart = new ApexCharts(
      document.querySelector("#sales-weekchart"),
      sColStacked
    );

    chart.render();
  }

  if ($('#sales-monthchart').length > 0) {
    var sColStacked = {
      chart: {
        height: 245,
        type: 'bar',
        stacked: true,
        toolbar: {
          show: false,
        }
      },
      colors: ['#FE9F43', '#FFE3CB'],
      responsive: [{
        breakpoint: 480,
        options: {
          legend: {
            position: 'bottom',
            offsetX: -10,
            offsetY: 0
          }
        }
      }],
      plotOptions: {
        bar: {
          borderRadius: 8, 
          borderRadiusWhenStacked: 'all',
          horizontal: false,
          endingShape: 'rounded'
        },
      },
      series: [{
        name: 'Sales',
        data: [18, 25, 10, 18, 25, 18, 10, 20, 40, 8, 30, 20]
      }, {
        name: 'Purchase',
        data: [50, 40, 30, 50, 40, 50, 30, 30, 50, 30, 40, 30]
      }],
      xaxis: {
        categories: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct','Nov', 'Dec'],
        labels: {
          style: {
            colors: '#6B7280', 
            fontSize: '13px',
          }
        }
      },
      yaxis: {
        labels: {
          formatter: (val) => {
            return val / 1 + 'K'
          },
          offsetX: -15,
          style: {
            colors: '#6B7280', 
            fontSize: '13px',
          }
        }
      },
      grid: {
        borderColor: '#E5E7EB',
        strokeDashArray: 5,
        padding: {
          left: -16,
          top: 0,
      bottom: 0,
      right: 0, 
        },
      },
      legend: {
        show: false
      },
      dataLabels: {
        enabled: false // Disable data labels
      },
      fill: {
        opacity: 1
      },
    }

    var chart = new ApexCharts(
      document.querySelector("#sales-monthchart"),
      sColStacked
    );

    chart.render();
  }

  if ($('#sales-quarterchart').length > 0) {
    var sColStacked = {
      chart: {
        height: 245,
        type: 'bar',
        stacked: true,
        toolbar: {
          show: false,
        }
      },
      colors: ['#FE9F43', '#FFE3CB'],
      responsive: [{
        breakpoint: 480,
        options: {
          legend: {
            position: 'bottom',
            offsetX: -10,
            offsetY: 0
          }
        }
      }],
      plotOptions: {
        bar: {
          borderRadius: 8, 
          borderRadiusWhenStacked: 'all',
          horizontal: false,
          endingShape: 'rounded'
        },
      },
      series: [{
        name: 'Sales',
        data: [18, 25, 10, 18, 25, 18, 10, 20, 40, 8, 30, 20]
      }, {
        name: 'Purchase',
        data: [50, 40, 30, 50, 40, 50, 30, 30, 50, 30, 40, 30]
      }],
      xaxis: {
        categories: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct','Nov', 'Dec'],
        labels: {
          style: {
            colors: '#6B7280', 
            fontSize: '13px',
          }
        }
      },
      yaxis: {
        labels: {
          formatter: (val) => {
            return val / 1 + 'K'
          },
          offsetX: -15,
          style: {
            colors: '#6B7280', 
            fontSize: '13px',
          }
        }
      },
      grid: {
        borderColor: '#E5E7EB',
        strokeDashArray: 5,
        padding: {
          left: -16,
          top: 0,
      bottom: 0,
      right: 0, 
        },
      },
      legend: {
        show: false
      },
      dataLabels: {
        enabled: false // Disable data labels
      },
      fill: {
        opacity: 1
      },
    }

    var chart = new ApexCharts(
      document.querySelector("#sales-quarterchart"),
      sColStacked
    );

    chart.render();
  }

  if ($('#sales-halfchart').length > 0) {
    var sColStacked = {
      chart: {
        height: 245,
        type: 'bar',
        stacked: true,
        toolbar: {
          show: false,
        }
      },
      colors: ['#FE9F43', '#FFE3CB'],
      responsive: [{
        breakpoint: 480,
        options: {
          legend: {
            position: 'bottom',
            offsetX: -10,
            offsetY: 0
          }
        }
      }],
      plotOptions: {
        bar: {
          borderRadius: 8, 
          borderRadiusWhenStacked: 'all',
          horizontal: false,
          endingShape: 'rounded'
        },
      },
      series: [{
        name: 'Sales',
        data: [18, 25, 10, 18, 25, 18, 10, 20, 40, 8, 30, 20]
      }, {
        name: 'Purchase',
        data: [50, 40, 30, 50, 40, 50, 30, 30, 50, 30, 40, 30]
      }],
      xaxis: {
        categories: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct','Nov', 'Dec'],
        labels: {
          style: {
            colors: '#6B7280', 
            fontSize: '13px',
          }
        }
      },
      yaxis: {
        labels: {
          formatter: (val) => {
            return val / 1 + 'K'
          },
          offsetX: -15,
          style: {
            colors: '#6B7280', 
            fontSize: '13px',
          }
        }
      },
      grid: {
        borderColor: '#E5E7EB',
        strokeDashArray: 5,
        padding: {
          left: -16,
          top: 0,
      bottom: 0,
      right: 0, 
        },
      },
      legend: {
        show: false
      },
      dataLabels: {
        enabled: false // Disable data labels
      },
      fill: {
        opacity: 1
      },
    }

    var chart = new ApexCharts(
      document.querySelector("#sales-halfchart"),
      sColStacked
    );

    chart.render();
  }

  if ($('#heat_chart').length > 0) {
    var options = {
      chart: {
        type: 'heatmap',
        height: 370,
    },
    plotOptions: {
      heatmap: {
        radius: 4,
        enableShades: false,
        colorScale: {
          ranges: [{
              from: 0,
              to: 99,
              color: '#FFE3CB'
            },
            {
              from: 100,
              to: 200,
              color: '#FE9F43'
            },
          ],
        },
    
      }
    },
    legend: {
      show: false
    },
    dataLabels: {
      enabled: false
    },
    grid: {
      padding: {
        top: -20,
        bottom: 0,
        left: 0,
        right: 0, // Minimize padding around the heatmap
      },
    },
    yaxis: {
      labels: {
        offsetX: -15, // Adjust horizontal alignment
      },
    },
      series: [
        {
          name: "2 Am",
          data: [{
            x: 'Mon',
            y: 100
          },
          {
            x: 'Tue',
            y: 100
          }, 
          {
            x: 'Wed',
            y: 100
          }, 
          {
            x: 'Thu',
            y: 32
          },
          {
            x: 'Fri',
            y: 32
          },
          {
            x: 'Sat',
            y: 32
          },
          {
            x: 'Sun',
            y: 32
          },
        ]
        },
        {
          name: "4 Am",
          data: [{
            x: 'Mon',
            y: 100,
            color: '#ff5722'
          },
          {
            x: 'Tue',
            y: 100
          }, 
          {
            x: 'Wed',
            y: 100
          }, 
          {
            x: 'Thu',
            y: 120
          },
          {
            x: 'Fri',
            y: 32
          },
          {
            x: 'Sat',
            y: 50
          },
          {
            x: 'Sun',
            y: 40
          },
        ]
        },
        {
          name: "6 Am",
          data: [{
            x: 'Mon',
            y: 22
          },
          {
            x: 'Tue',
            y: 29
          }, 
          {
            x: 'Wed',
            y: 13
          }, 
          {
            x: 'Thu',
            y: 32
          },
          {
            x: 'Fri',
            y: 32
          },
          {
            x: 'Sat',
            y: 32
          },
          {
            x: 'Sun',
            y: 32
          },
        ]
        },
        {
          name: "8 Am",
          data: [{
            x: 'Mon',
            y: 0
          },
          {
            x: 'Tue',
            y: 29
          }, 
          {
            x: 'Wed',
            y: 13
          }, 
          {
            x: 'Thu',
            y: 32
          },
          {
            x: 'Fri',
            y: 30
          },
          {
            x: 'Sat',
            y: 100
          },
          {
            x: 'Sun',
            y: 100
          },
        ]
        },
        {
          name: "10 Am",
          data: [{
            x: 'Mon',
            y: 200
          },
          {
            x: 'Tue',
            y: 200
          }, 
          {
            x: 'Wed',
            y: 200
          }, 
          {
            x: 'Thu',
            y: 32
          },
          {
            x: 'Fri',
            y: 0
          },
          {
            x: 'Sat',
            y: 0
          },
          {
            x: 'Sun',
            y: 32
          },
        ]
        },
        {
          name: "12 Am",
          data: [{
            x: 'Mon',
            y: 0
          },
          {
            x: 'Tue',
            y: 0
          }, 
          {
            x: 'Wed',
            y: 75
          }, 
          {
            x: 'Thu',
            y: 0
          },
          {
            x: 'Fri',
            y: 0
          },
          {
            x: 'Sat',
            y: 0
          },
          {
            x: 'Sun',
            y: 0
          },
        ]
        },
        {
          name: "14 Pm",
          data: [{
            x: 'Mon',
            y: 0
          },
          {
            x: 'Tue',
            y: 20
          }, 
          {
            x: 'Wed',
            y: 13
          }, 
          {
            x: 'Thu',
            y: 32
          },
          {
            x: 'Fri',
            y: 0
          },
          {
            x: 'Sat',
            y: 0
          },
          {
            x: 'Sun',
            y: 32
          },
        ]
        },
        {
          name: "16 Pm",
          data: [{
            x: 'Mon',
            y: 13
          },
          {
            x: 'Tue',
            y: 20
          }, 
          {
            x: 'Wed',
            y: 13
          }, 
          {
            x: 'Thu',
            y: 32
          },
          {
            x: 'Fri',
            y: 200
          },
          {
            x: 'Sat',
            y: 13
          },
          {
            x: 'Sun',
            y: 32
          },
        ]
        },
        
        {
          name: "18 Am",
          data: [{
            x: 'Mon',
            y: 0
          },
          {
            x: 'Tue',
            y: 20
          }, 
          {
            x: 'Wed',
            y: 13
          }, 
          {
            x: 'Thu',
            y: 32
          },
          {
            x: 'Fri',
            y: 0
          },
          {
            x: 'Sat',
            y: 200
          },
          {
            x: 'Sun',
            y: 200
          },
        ]
        },
      ]
    };
    var chart = new ApexCharts(document.querySelector("#heat_chart"), options);
    chart.render();
  }

  if($('#sales-statistics').length > 0) {
    var options = {
      series: [{
      name: 'Revenue',
      data: [9, 25, 25, 20, 20, 18, 25, 15 , 20, 12, 8, 20],
      }, {
      name: 'Expenses',
      data: [-10, -18, -9, -20, -20, -10, -20, -20, -8, -15, -18, -20]
      }],
      grid: {
        padding: {
          top: 5, // Adds space on the left
          right: 5, // Adds space on the right
        },
      },
      colors: ['#0E9384', '#E04F16'],
      chart: {
        type: 'bar',
        height: 290,
        stacked: true,        
        zoom: {
          enabled: true
        }
      },
      responsive: [{
      breakpoint: 280,
      options: {
        legend: {
        position: 'bottom',
        offsetY: 0
        }
      }
      }],
      plotOptions: {
      bar: {
        horizontal: false,
            borderRadius: 4,
          borderRadiusApplication: "around", // "around" / "end" 
          borderRadiusWhenStacked: "all", // "all"/"last"
        columnWidth: '20%',
      },
      },
        dataLabels: {
        enabled: false
      },
        yaxis: {
          labels: {
            offsetX: -15,
            formatter: (val) => {
              return val / 1 + 'K'
            },
          },
            min: -30,
            max: 30,
            tickAmount: 6,
          },
      xaxis: {
      categories: [' Jan ', 'Feb', 'Mar', 'Apr',
        'May', 'Jun' , 'Jul' , 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'
      ],
      },
      legend: {show: false},
      fill: {
      opacity: 1
      }
      };
  
      var chart = new ApexCharts(document.querySelector("#sales-statistics"), options);
      chart.render();
    }

// Company Chart

if ($('#company-chart').length > 0) {
  var sColStacked = {
    chart: {
      height: 240,
      type: 'bar',
      toolbar: {
        show: false,
      }
    },
    colors: ['#212529'],
    responsive: [{
      breakpoint: 480,
      options: {
        legend: {
          position: 'bottom',
          offsetX: -10,
          offsetY: 0
        }
      }
    }],
    plotOptions: {
      bar: {
        borderRadius: 10, 
        borderRadiusWhenStacked: 'all',
        horizontal: false,
        endingShape: 'rounded',
        colors: {
          backgroundBarColors: ['#f3f4f5'], // Background color for bars
          backgroundBarOpacity: 0.5,
          hover: {
            enabled: true,
            borderColor: '#F26522', // Color when hovering over the bar
          }
        }
      },
    },
    series: [{
      name: 'Company',
      data: [40, 60, 20, 80, 60, 60, 60]
    }],
    xaxis: {
      categories: ['M', 'T', 'W', 'T', 'F', 'S', 'S'],
      labels: {
        style: {
          colors: '#6B7280', 
          fontSize: '13px',
        }
      }
    },
    yaxis: {
      labels: {
        offsetX: -15,
        show: false 
      }
    },
    grid: {
      borderColor: '#E5E7EB',
      strokeDashArray: 5,
      padding: {
        left: -8,
      },
    },
    legend: {
      show: false
    },
    dataLabels: {
      enabled: false // Disable data labels
    },
    fill: {
      opacity: 1
    },
  }

  var chart = new ApexCharts(
    document.querySelector("#company-chart"),
    sColStacked
  );

  chart.render();
}

 // Revenue income

 if ($('#revenue-income').length > 0) {
  var sColStacked = {
    chart: {
      height: 230,
      type: 'bar',
      stacked: true,
      toolbar: {
        show: false,
      }
    },
    colors: ['#FF6F28', '#F8F9FA'],
    responsive: [{
      breakpoint: 480,
      options: {
        legend: {
          position: 'bottom',
          offsetX: -10,
          offsetY: 0
        }
      }
    }],
    plotOptions: {
      bar: {
        borderRadius: 5, 
        borderRadiusWhenStacked: 'all',
        horizontal: false,
        endingShape: 'rounded'
      },
    },
    series: [{
      name: 'Income',
      data: [40, 30, 45, 80, 85, 90, 80, 80, 80, 85, 20, 80]
    }, {
      name: 'Expenses',
      data: [60, 70, 55, 20, 15, 10, 20, 20, 20, 15, 80, 20]
    }],
    xaxis: {
      categories: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct','Nov', 'Dec'],
      labels: {
        style: {
          colors: '#6B7280', 
          fontSize: '13px',
        }
      }
    },
    yaxis: {
      min: 0,    // Set the minimum value of the Y-axis to 0
      max: 100,
      labels: {
        offsetX: -15,
        style: {
          colors: '#6B7280', 
          fontSize: '13px',
        },
        formatter: function (value) {
          return value + "K"; // Divide by 1000 and append 'K'
        }
      }
    },
    grid: {
      borderColor: 'transparent',
      strokeDashArray: 5,
      padding: {
        left: -8,
      },
    },
    legend: {
      show: false
    },
    dataLabels: {
      enabled: false 
    },
    tooltip: {
      y: {
        formatter: function (val) {
          return  val / 10 + " k"
        }
      }
    },
    fill: {
      opacity: 1
    },
  }

  var chart = new ApexCharts(
    document.querySelector("#revenue-income"),
    sColStacked
  );

  chart.render();
}

// Plan Chart

if ($('#plan-overview').length > 0) {
  var donutChart = {
    chart: {
      height: 240,
      type: 'donut',
      toolbar: {
        show: false,
      }
    },
    colors: ['#FFC107', '#1B84FF', '#F26522'],
    series: [20, 60, 20],
    labels: ['Enterprise', 'Premium', 'Basic'],
    plotOptions: {
      pie: {
        donut: {
          size: '50%',
          labels: {
            show: false
          },
          borderRadius: 30
        }
      }
    },
    stroke: {
      lineCap: 'round',
      show: true,
      width: 0,    // Space between donut sections
      colors: '#fff'
    },
    dataLabels: {
      enabled: false
    },
    legend: { show: false },
    responsive: [{
      breakpoint: 480,
      options: {
        chart: {
          height: 180,
        },
        legend: {
          position: 'bottom'
        }
      }
    }]
  }

  var donut = new ApexCharts(
    document.querySelector("#plan-overview"),
    donutChart
  );

  donut.render();
}

// Total Company

if ($('#total-chart').length > 0) {

  var options = {
    series: [{
      name: "Messages",
      data: [25, 66, 41, 12, 36, 9, 21]
    }],
    fill: {
      type: 'gradient',
      gradient: {
        opacityFrom: 0, // Start with 0 opacity (transparent)
        opacityTo: 0    // End with 0 opacity (transparent)
      }
    },
    chart: {
      foreColor: '#fff',
      type: "area",
      width: 50,
      toolbar: {
        show: !1
      },
      zoom: {
        enabled: !1
      },
      dropShadow: {
        enabled: 0,
        top: 3,
        left: 14,
        blur: 4,
        opacity: .12,
        color: "#fff"
      },
      sparkline: {
        enabled: !0
      }
    },
    markers: {
      size: 0,
      colors: ["#F26522"],
      strokeColors: "#fff",
      strokeWidth: 2,
      hover: {
        size: 7
      }
    },
    plotOptions: {
      bar: {
        horizontal: !1,
        columnWidth: "35%",
        endingShape: "rounded"
      }
    },
    dataLabels: {
      enabled: !1
    },
    stroke: {
      show: !0,
      width: 2.5,
      curve: "smooth"
    },
    colors: ["#F26522"],
    xaxis: {
      categories: ["Jan", "Feb", "Mar", "Apr", "May", "Jun", "Jul", "Aug", "Sep"]
    },
    tooltip: {
      theme: "dark",
      fixed: {
        enabled: !1
      },
      x: {
        show: !1
      },
      y: {
        title: {
          formatter: function (e) {
            return ""
          }
        }
      },
      marker: {
        show: !1
      }
    }
  };

  var chart = new ApexCharts(document.querySelector("#active-chart"), options);
  chart.render();
}

// Active Company

if ($('#active-chart').length > 0) {

  var options = {
    series: [{
      name: "Active Company",
      data: [25, 40, 35, 20, 36, 9, 21]
    }],
    fill: {
      type: 'gradient',
      gradient: {
        opacityFrom: 0, // Start with 0 opacity (transparent)
        opacityTo: 0    // End with 0 opacity (transparent)
      }
    },
    chart: {
      foreColor: '#fff',
      type: "area",
      width: 50,
      toolbar: {
        show: !1
      },
      zoom: {
        enabled: !1
      },
      dropShadow: {
        enabled: 0,
        top: 3,
        left: 14,
        blur: 4,
        opacity: .12,
        color: "#fff"
      },
      sparkline: {
        enabled: !0
      }
    },
    markers: {
      size: 0,
      colors: ["#F26522"],
      strokeColors: "#fff",
      strokeWidth: 2,
      hover: {
        size: 7
      }
    },
    plotOptions: {
      bar: {
        horizontal: !1,
        columnWidth: "35%",
        endingShape: "rounded"
      }
    },
    dataLabels: {
      enabled: !1
    },
    stroke: {
      show: !0,
      width: 2.5,
      curve: "smooth"
    },
    colors: ["#F26522"],
    xaxis: {
      categories: ["Jan", "Feb", "Mar", "Apr", "May", "Jun", "Jul", "Aug", "Sep"]
    },
    tooltip: {
      theme: "dark",
      fixed: {
        enabled: !1
      },
      x: {
        show: !1
      },
      y: {
        title: {
          formatter: function (e) {
            return ""
          }
        }
      },
      marker: {
        show: !1
      }
    }
  };

  var chart = new ApexCharts(document.querySelector("#total-chart"), options);
  chart.render();
}

// Inactive Company

if ($('#inactive-chart').length > 0) {

  var options = {
    series: [{
      name: "Inactive Company",
      data: [25, 10, 35, 5, 25, 28, 21]
    }],
    fill: {
      type: 'gradient',
      gradient: {
        opacityFrom: 0, // Start with 0 opacity (transparent)
        opacityTo: 0    // End with 0 opacity (transparent)
      }
    },
    chart: {
      foreColor: '#fff',
      type: "area",
      width: 50,
      toolbar: {
        show: !1
      },
      zoom: {
        enabled: !1
      },
      dropShadow: {
        enabled: 0,
        top: 3,
        left: 14,
        blur: 4,
        opacity: .12,
        color: "#fff"
      },
      sparkline: {
        enabled: !0
      }
    },
    markers: {
      size: 0,
      colors: ["#F26522"],
      strokeColors: "#fff",
      strokeWidth: 2,
      hover: {
        size: 7
      }
    },
    plotOptions: {
      bar: {
        horizontal: !1,
        columnWidth: "35%",
        endingShape: "rounded"
      }
    },
    dataLabels: {
      enabled: !1
    },
    stroke: {
      show: !0,
      width: 2.5,
      curve: "smooth"
    },
    colors: ["#F26522"],
    xaxis: {
      categories: ["Jan", "Feb", "Mar", "Apr", "May", "Jun", "Jul", "Aug", "Sep"]
    },
    tooltip: {
      theme: "dark",
      fixed: {
        enabled: !1
      },
      x: {
        show: !1
      },
      y: {
        title: {
          formatter: function (e) {
            return ""
          }
        }
      },
      marker: {
        show: !1
      }
    }
  };

  var chart = new ApexCharts(document.querySelector("#inactive-chart"), options);
  chart.render();
}

// Location Company

if ($('#location-chart').length > 0) {

  var options = {
    series: [{
      name: "Inactive Company",
      data: [30, 40, 15, 23, 20, 23, 25]
    }],
    fill: {
      type: 'gradient',
      gradient: {
        opacityFrom: 0, // Start with 0 opacity (transparent)
        opacityTo: 0    // End with 0 opacity (transparent)
      }
    },
    chart: {
      foreColor: '#fff',
      type: "area",
      width: 50,
      toolbar: {
        show: !1
      },
      zoom: {
        enabled: !1
      },
      dropShadow: {
        enabled: 0,
        top: 3,
        left: 14,
        blur: 4,
        opacity: .12,
        color: "#fff"
      },
      sparkline: {
        enabled: !0
      }
    },
    markers: {
      size: 0,
      colors: ["#F26522"],
      strokeColors: "#fff",
      strokeWidth: 2,
      hover: {
        size: 7
      }
    },
    plotOptions: {
      bar: {
        horizontal: !1,
        columnWidth: "35%",
        endingShape: "rounded"
      }
    },
    dataLabels: {
      enabled: !1
    },
    stroke: {
      show: !0,
      width: 2.5,
      curve: "smooth"
    },
    colors: ["#F26522"],
    xaxis: {
      categories: ["Jan", "Feb", "Mar", "Apr", "May", "Jun", "Jul", "Aug", "Sep"]
    },
    tooltip: {
      theme: "dark",
      fixed: {
        enabled: !1
      },
      x: {
        show: !1
      },
      y: {
        title: {
          formatter: function (e) {
            return ""
          }
        }
      },
      marker: {
        show: !1
      }
    }
  };

  var chart = new ApexCharts(document.querySelector("#location-chart"), options);
  chart.render();
}
});