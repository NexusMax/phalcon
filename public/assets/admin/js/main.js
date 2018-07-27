feather.replace();

var ctx = document.getElementById("myChart");
if(ctx){
	var myChart = new Chart(ctx, {
		type: 'line',
		data: {
			labels: ["Sunday", "Monday", "Tuesday", "Wednesday", "Thursday", "Friday", "Saturday"],
			datasets: [{
				data: [15339, 21345, 18483, 24003, 23489, 24092, 12034],
				lineTension: 0,
				backgroundColor: 'transparent',
				borderColor: '#007bff',
				borderWidth: 4,
				pointBackgroundColor: '#007bff'
			}]
		},
		options: {
			scales: {
				yAxes: [{
					ticks: {
						beginAtZero: false
					}
				}]
			},
			legend: {
				display: false,
			}
		}
	});
}


$(document).ready( function() {


	$('.editor').each(function(i){
		CKEDITOR.replace( $(this).attr('id'), {
		    language: 'ru',
		    filebrowserBrowseUrl: '/assets/admin/library/elFinder/elfinder.html'
		});

		CKEDITOR.config.protectedSource.push(/<\?[\s\S]*?\?>/g);
	});



	$(document).on('change', '.btn-file :file', function() {
		var input = $(this),
		label = input.val().replace(/\\/g, '/').replace(/.*\//, '');
		input.trigger('fileselect', [label]);
	});

	$(document).on('fileselect', '.btn-file :file', function(event, label) {

		var input = $(this).parents('.input-group').find(':text'),
		log = label;

		if( input.length ) {
			input.val(log);
		} else {
			if( log ) alert(log);
		}

	});
	function readURL(input) {
		if (input.files && input.files[0]) {
			var reader = new FileReader();

			reader.onload = function (e) {
				if($(input).hasClass('btn-file-document')){
					if(!$(input).parent().parent().parent().find('.btn-del-file').length){
						$(input).parent().parent().parent().append('<span class="btn btn-primary btn-del-file btn-focus">Удалить</span>');
					}
				}else{
					$('#img-upload').attr('src', e.target.result);
					if(!$('.btn-del').length){
						$('.input-group-btn-del').append('<span class="btn btn-primary btn-del">Удалить</span>');
					}
				}
			}

			reader.readAsDataURL(input.files[0]);
		}
	}

	$(document).on('change', '#imgInp, .btn-file-document', function(){
		readURL(this);
	}); 


	$(document).on('click', '.btn-del', function(e){

		$('#imgInp').val('');
		$('#img-upload').attr('src', '');
		$(this).remove();

		if($(this).data('url')){
			var urlAd = $(this).data('url');
			var id = $(this).data('id');
			$.ajax({
				type: 'POST',
				url: urlAd,
				data: {id: id},
				success: function (message) {
					console.log(message);
				}
			});
		}
		
	});

	$(document).on('click', '.btn-del-file', function(){
		$(this).find('input[type="file"]').val('');
		$(this).parent().find('.clear').val('');

		if($(this).parent().parent().find('.documents').length > 1){
			$(this).parent().remove();
		}else{
			$(this).remove();
		}

		if($(this).data('url')){
			var urlAd = $(this).data('url');
			var id = $(this).data('id');
			var type = $(this).data('type');
			$.ajax({
				type: 'POST',
				url: urlAd,
				data: {id: id, type: type},
				success: function (message) {
					console.log(message);
				}
			});
		}
	});

	$(document).on('click', '.btn-add-file', function(e){
		var clon = $(this).parent().next().clone();
		$(clon).find('input[type="file"]').val('');
		$(clon).find('input[type="text"]').val('');
		$(clon).find('.clear').val('');

		$(this).parent().parent().append(clon);
	});

	$('.selectpicker').selectpicker();
	$('[data-toggle="datetimepicker"]').datetimepicker({
		format: 'YYYY\-MM\-DD',
		locale: 'ru',
		useCurrent: false
	});
	// $('#datetimepicker5').datetimepicker();
	// $('#datetimepicker1').datetimepicker();
	$("[data-role='tagsinput']").tagsinput('items');




	$(document).on('change', '.type-menu', function(){
		var type = $(this).val();

		if($(this).data('url')){
			var dataUrl = $(this).data('url');
			$.ajax({
				type: 'POST',
				url: dataUrl,
				data: {type: type},
				success: function (message) {
					$('.type-menu-option').html(message);
				}
			});
		}
	})

	/*fileupload*/
	
	$(document).on('click', '.block-delete', function(){
		var imageId = $(this).data('id'), _this = $(this), dataUrl = $(this).data('url');
		$.ajax({
			type: 'POST',
			url: dataUrl,
			data: {id: imageId},
			success: function (message) {
				$(_this).parent().remove();
			}
		});
	});
	/*fileupload*/


	$(document).ready(function() {
	$('.popup-gallery').magnificPopup({
		delegate: 'a',
		type: 'image',
		tLoading: 'Loading image #%curr%...',
		mainClass: 'mfp-img-mobile',
		gallery: {
			enabled: true,
			navigateByImgClick: true,
			preload: [0,1] // Will preload 0 - before current, and 1 after the current image
		},
		image: {
			tError: '<a href="%url%">The image #%curr%</a> could not be loaded.',
			titleSrc: function(item) {
				return item.el.attr('title') + '<small>by Marsel Van Oosten</small>';
			}
		}
	});
});

});