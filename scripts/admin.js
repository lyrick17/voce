//Requests graph data from graph_query.php
const fetchPromise =  fetch('graph_query.php');
let graphData = [];

//Converts response to json after request is fulfilled
fetchPromise.then((response) =>
  response.json())
//Stores data in graphData array
  .then((data) => {
    graphData = data;
    console.log(graphData);
})

//Executes code after initializing graphData
  .then(() => {
  var chart = document.getElementById('myChart');
  let donut = document.getElementById("donutCanvas");
  let dlBtn = document.querySelector(".dlgraph-btn");
  var chartctx = chart.getContext('2d');

    // Set the background color
    chartctx.fillStyle = 'white';
    chartctx.fillRect(0, 0, chart.width, chart.height);

    // set the ctx to draw beneath your current content
    chartctx.globalCompositeOperation = 'destination-over';

  dlBtn.addEventListener("click", () => {
    const pngDataUrl = chart.toDataURL("image/png");

      // Create a temporary link element
      var link = document.createElement('a', 1);

      // Set the href attribute to the data URL
      link.href = pngDataUrl;

      // Set the download attribute with the desired file name
      link.download = 'website_graph.png';

      // Append the link to the document and trigger a click event
      document.body.appendChild(link);
      link.click();

      // Remove the link from the document
      document.body.removeChild(link);
  });

  let d = new Date();
  let dates = new Array(7);
  console.log(dates.toString());

  //Add date labels to date array
  for(let i = 0; i < 7; i++){
    if(i > 0)
      d.setDate(d.getDate() - 1);
    dates[i] = d.toDateString();
  }

  dates.reverse();
      //line graph data
      const line_data = {
          labels: dates,
          datasets: [{
            label: 'text-to-text translations',
            data: graphData['line_values']['t2t_totals'].reverse(),
            borderColor: [
              'rgba(255, 26, 104, 1)',
              'rgba(54, 162, 235, 1)',
              'rgba(255, 206, 86, 1)',
              'rgba(75, 192, 192, 1)',
              'rgba(153, 102, 255, 1)',
              'rgba(255, 159, 64, 1)',
              'rgba(0, 0, 0, 1)'
            ],
            borderWidth: 2
          },
          {
            label: 'audio-to-text translations',
            data: graphData['line_values']['a2t_totals'].reverse(),
            backgroundColor: [
              'rgba(255, 26, 104, 0.2)',
              'rgba(54, 162, 235, 0.2)',
              'rgba(255, 206, 86, 0.2)',
              'rgba(75, 192, 192, 0.2)',
              'rgba(153, 102, 255, 0.2)',
              'rgba(255, 159, 64, 0.2)',
              'rgba(0, 0, 0, 0.2)'
            ],
            borderColor: [
              'rgba(255, 26, 104, 1)',
              'rgba(54, 162, 235, 1)',
              'rgba(255, 206, 86, 1)',
              'rgba(75, 192, 192, 1)',
              'rgba(153, 102, 255, 1)',
              'rgba(255, 159, 64, 1)',
              'rgba(0, 0, 0, 1)'
            ],
            borderWidth: 2
          }
        ]
      };
    
      //Pie graph data
        const pie_data = {
          labels: ['# of text-to-text translations', '# of audio-to-text translations'],
          datasets: [{
            label: 'Total translations',
            data: graphData['pie_values'],
            backgroundColor: [
              'rgba(255, 26, 104, 0.2)',
              'rgba(54, 162, 235, 0.2)',
              'rgba(255, 206, 86, 0.2)',
              'rgba(75, 192, 192, 0.2)',
              'rgba(153, 102, 255, 0.2)',
              'rgba(255, 159, 64, 0.2)',
              'rgba(0, 0, 0, 0.2)'
            ],
            borderColor: [
              'rgba(255, 26, 104, 1)',
              'rgba(54, 162, 235, 1)',
              'rgba(255, 206, 86, 1)',
              'rgba(75, 192, 192, 1)',
              'rgba(153, 102, 255, 1)',
              'rgba(255, 159, 64, 1)',
              'rgba(0, 0, 0, 1)'
            ],
    
            borderWidth: 2
          },
        ]
        };
    
        
        const plugin = {
          id: 'customCanvasBackgroundColor',
          beforeDraw: (chart, args, options) => {
            const {ctx} = chart;
            ctx.save();
            ctx.globalCompositeOperation = 'destination-over';
            ctx.fillStyle = options.color || '#fccdcd';
            ctx.fillRect(0, 0, chart.width, chart.height);
            ctx.restore();
          }
        };

        // config 
        const config = {
          type: 'line',
          data: line_data,
          options: {
            plugins: {
              customCanvasBackgroundColor: {
                color: '#fccdcd',
              }
            }
          },
          plugins: [plugin],
          options: {
            scales: {
              x: {
                ticks: {
                  autoSkip: false,
                  maxRotation: 0,
                  minRotation: 0
                }
              },
              y: {
                beginAtZero: true,
                
              }
            }
          }
        };
    
        // render init block
        Chart.defaults.font.size = 13;
        let myChart = new Chart(
          chart,
          config
        );

        const config2 = {
          type: 'doughnut',
          data: pie_data,
          options: {
            scales: {
              y: {
                beginAtZero: true
              }
            }
          }
        };
    
        // render init block
        Chart.defaults.font.size = 13;
        Chart.defaults.backgroundColor = "black";
        let myDonut = new Chart(
          donut,
          config2
        );
        


});
