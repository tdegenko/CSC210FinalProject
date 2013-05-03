$(document).ready(function() {
	$('a').click(function(e) {
		e.preventDefault();
		$('#tables').fadeOut();
		var data = $('#month').val() + $('#year').val();
		$.get(this.href, {}, function(data) {
			$('#tables').html(data).slideDown();
		});
	});
});