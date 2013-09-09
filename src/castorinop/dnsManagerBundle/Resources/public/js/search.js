$(document).ready(function () {
	var data = $("#search").data();
	var options = {
	    callback: function(){ 
		    $("#list").load(
				    data['remote'], 
				    { 'text': $("#search").val() }, 
				    function() { }
				 	);
		    },
	    wait: 750,
	    highlight: true,
	    captureLength: 2
	}

	$("#search").typeWatch( options );
	
});
