let linebtn = document.querySelector(".line-btn");
let piebtn = document.querySelector(".pie-btn");
let chart = document.getElementById('myChart');

piebtn.addEventListener("click", (e) => {
    changeActiveBtn("pie");
    changeGraph("pie", chart, pie_data);
});

linebtn.addEventListener("click", (e) => {
    changeActiveBtn("line");
    changeGraph("line", chart, line_data);
});

    //line graph data
    const line_data = {
        labels: ['Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat', 'Sun'],
        datasets: [{
          label: 'text-to-text translations',
          data: [2, 3, 4, 5, 9, 1, 8],
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
          data: [18, 12, 6, 9, 12, 3, 9],
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
  
      const pie_data = {
        labels: ['# of text-to-text translations', '# of audio_to-text translations'],
        datasets: [{
          label: '# of text-to-text translations',
          data: [52, 69],
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
  
      
      // config 
      const config = {
        type: 'line',
        data: line_data,
        options: {
          scales: {
            y: {
              beginAtZero: true
            }
          }
        }
      };
  
      // render init block
      Chart.defaults.font.size = 20;
      let myChart = new Chart(
        chart,
        config
      );



function changeGraph(chartType, ctx, data, ){
    //Removes active graph
    if(myChart){
        myChart.destroy();
    }
    
    //re-initializes chart configurations
    const config = {
        type: chartType,
        data: data,
        options: {
          scales: {
            y: {
              beginAtZero: true
            }
          }
        }
      };

    //create new graph
      Chart.defaults.font.size = 20;
      myChart = new Chart(
    ctx,
    config
    );


}
function changeActiveBtn(activeBtn, color1 = "gray", color2 = "pink"){

    if(activeBtn == "pie")
    {
        piebtn.style.backgroundColor = color1;
        linebtn.style.backgroundColor = color2;
    }

    else if(activeBtn == "line"){
    linebtn.style.backgroundColor = color1;
    piebtn.style.backgroundColor = color2;
    }
}
