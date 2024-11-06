<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>


<div class="col s12">
	<div class="row center-align">
		<h2>Dashboard Estadístico</h2>
	</div>
	<div class="row">
		<canvas id="myChart"></canvas>
	</div>
</div>


<script>
  const ctx = document.getElementById('myChart');

  new Chart(ctx, {
    type: 'bar',
    data: {
      labels: ['Red', 'Blue', 'Yellow', 'Green', 'Purple', 'Orange'],
      datasets: [{
        label: '# of Votes',
        data: [12, 19, 3, 5, 2, 3],
        borderWidth: 1
      }]
    },
    options: {
      scales: {
        y: {
          beginAtZero: true
        }
      }
    }
  });
</script>