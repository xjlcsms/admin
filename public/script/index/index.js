(function() {
	$.get('/data/tempnum',function(res) {
		$('#tempnum').text(res.data.tempnum);
	})
	$.get('/data/jobnum',function(res) {
		$('#jobnum').text(res.data.jobnum);
	})
	$.get('/data/daysend', function(res) {
		$('#generalSend').text(res.data.generalSend);
		$('#marketSend').text(res.data.marketSend);
	})
	$.get('/data/dayrecharge', function(res) {
		$('#general').text(res.data.general);
		$('#market').text(res.data.market);
	})
})();
