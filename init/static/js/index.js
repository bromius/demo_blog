$(function() {
	var request = function(url, params, callback) {
		var formData = new FormData();
		
		for (var key in params)
			formData.append(key, params[key]);
		
		$.ajax({
			url: url,
			method: 'POST',
			processData: false,
			contentType: false,
			data: formData,
			error: function (error) {
				console.error(error);
			},
			success: function (response) {
				if (!callback)
					return;

				var json = $.parseJSON(response);

				if (json)
					callback(json.result, json.data);
				else
					console.error(response);
			}
		});
	};
	
	var submitPost = function (params, callback) {
		var $form = $('.js-modal-post-edit form');
		var fileData = $form.find('[name="img"]').prop('files')[0];
		
		var params = $.extend($form.serializeObject(), params || []);
			params['img'] = fileData;
		
		request('/post/save', params, function(result, data) {
			if (callback)
				callback(result, data);
		});
	};
	
	$('.js-post-add').click(function() {
		$('.js-modal-post-edit').find('input[name!="csrf_token"], textarea').val('');
		$('.js-modal-post-edit').modal('show');
	});
	
	$('.js-modal-post-edit form').submit(function(ev) {
		ev.preventDefault();
		submitPost(null, function (result, data) {
			if (!result)
				return alert(data);
			document.location.href = data;
		});
	});
	
	$('.js-post-edit').click(function() {
		request('/post/get', {
			id: $(this).data('id')
		}, function(result, data) {
			if (!result)
				return alert(data);
			
			for (var key in data)
				$('.js-modal-post-edit').find('[name="' + key + '"]').val(data[key]);
			
			$('.js-modal-post-edit').modal('show');
		});
	});
	
	$('.js-post-remove').click(function() {
		if (confirm('Удалить этот пост?')) {
			request('/post/remove', {
				id: $(this).data('id')
			}, function(result, data) {
				if (!result)
					return alert(data);
				document.location.href = '/';
			});
		}
	});
	
	$('.js-btn-signup').click(function() {
		$('.js-modal-signup').modal('show');
	});
	
	$('.js-btn-login').click(function() {
		$('.js-modal-login').modal('show');
	});
	
	$('.js-modal-signup form').submit(function(ev) {
		ev.preventDefault();
		request('/auth/signup', $(this).serializeObject(), function(result, data) {
			if (!result)
				return alert(data);
			document.location.reload();
		});
	});
	
	$('.js-modal-login form').submit(function(ev) {
		ev.preventDefault();
		request('/auth/signin', $(this).serializeObject(), function(result, data) {
			if (!result)
				return alert(data);
			document.location.reload();
		});
	});
});