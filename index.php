<?php
	$dataFilename = 'data.txt';

	function countLinesInRepo() {
		$clone = 'git clone https://github.com/robertparasca/boilerplate-react.git';
		exec($clone);

		$cdIn = './boilerplate-react/';
		chdir($cdIn);
		
		$countLines = 'git ls-files | grep "\.js$" | xargs wc -l';
		$lines = exec($countLines);

		chdir('../');
		$rm = 'rm -rf ./boilerplate-react';
		exec($rm);

		return intval($lines);
	}

	function write($filename) {
		$lines = countLinesInRepo();
		// $dateAndLines = $lines . '|' . date('j.n.o') . " ";
		$dateAndLines = rand(0, 100) . '|' . rand(0, 10) . " ";
		file_put_contents($filename, $dateAndLines, FILE_APPEND);
	}

	function read($filename) {
		$data = file_get_contents($filename);
		$dataArray = explode(" ", $data);
		$obj = [];
		foreach($dataArray as $item) {
			if (strlen($item) > 1) {
				$lines = explode('|', $item)[0];
				$date = explode('|', $item)[1];
				$obj[$date] = $lines;
			}
		}

		return json_encode($obj);
	}

	write($dataFilename);

	$frontendData = read($dataFilename);
?>
<!DOCTYPE html>
<html>
<head>
	<title>Track degree</title>
<head>
<body>
	<canvas id="chart" width="300" height="150"></canvas>

  	<script src="https://cdn.jsdelivr.net/npm/chart.js@2.8.0"></script>
	<script type="text/javascript">
		const data = <?php echo $frontendData; ?>;
		console.log(data);
		const chartEl = document.getElementById('chart');
		const chart = new Chart(chartEl, {
		    type: 'line',
		    data: {
		    	labels: Object.keys(data),
		    	datasets: [{
		    		label: 'Lines of code per day',
		    		data: Object.values(data),
		    		backgroundColor: 'transparent',
      				borderColor: '#ff0000'
		    	}]
		    },
		    options: {
		        scales: {
		            yAxes: [{
		                ticks: {
		                    beginAtZero: true
		                }
		            }]
		        }
		    }
		});
	</script>
</body>
</html>