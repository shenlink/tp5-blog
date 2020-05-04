// Set new default font family and font color to mimic Bootstrap's default styling
Chart.defaults.global.defaultFontFamily = 'Nunito', '-apple-system,system-ui,BlinkMacSystemFont,"Segoe UI",Roboto,"Helvetica Neue",Arial,sans-serif';
Chart.defaults.global.defaultFontColor = '#858796';

$(document).ready(function () {
  let categoryArticleCount = document.querySelector('#new-category-article');
  getCategoryCount(categoryArticleCount);
});

function getCategoryCount(temp) {
  let time = temp.getAttribute('data-time');
  $.ajax({
    type: "POST",
    url: '/article/getCategoryCount',
    data: {
      time: time,
    },
    dataType: 'json',
    success: function (results) {
      result = JSON.parse(results);
      label = result['category'];
      data = result['number'];

      var resultData = {
        labels: label,
        datasets: [{
          label:"分类文章数量",
          fill: true,
          lineTension: 0.1,
          backgroundColor: "rgba(75,192,192,0.4)",
          borderColor: "rgba(75,192,192,1)",
          borderCapStyle: 'butt',
          borderDash: [],
          borderDashOffset: 0.0,
          borderJoinStyle: 'miter',
          pointBorderColor: "rgba(75,192,192,1)",
          pointBackgroundColor: "#fff",
          pointBorderWidth: 1,
          pointHoverRadius: 5,
          pointHoverBackgroundColor: "rgba(75,192,192,1)",
          pointHoverBorderColor: "rgba(220,220,220,1)",
          pointHoverBorderWidth: 2,
          pointRadius: 1,
          pointHitRadius: 10,
          backgroundColor: ['#4e73df', '#1cc88a', '#36b9cc'],
          hoverBackgroundColor: ['#2e59d9', '#17a673', '#2c9faf'],
          hoverBorderColor: "rgba(234, 236, 244, 1)",
          data: data,
          spanGaps: false,
        }]
      }
      // Pie Chart Example
      $('#categoryArticle').remove();
      $('#categoryArticleParent').append('<canvas id="categoryArticle"></canvas>');

      var ctx = document.getElementById("categoryArticle");
      var myLineChart = new Chart(ctx, {
        type: 'doughnut',
        data: resultData,
        options: {
          maintainAspectRatio: false,
          tooltips: {
            backgroundColor: "rgb(255,255,255)",
            bodyFontColor: "#858796",
            borderColor: '#dddfeb',
            borderWidth: 1,
            xPadding: 15,
            yPadding: 15,
            displayColors: false,
            caretPadding: 10,
          },
          legend: {
            display: false
          },
          cutoutPercentage: 80,
        },
      });
    }
  });
}