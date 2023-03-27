/*
 * Tìm người dùng
 */
 
$('.select2-select-user-ajax').select2({
	minimumInputLength: 1,
	//tags: [],
	ajax: {
		url: '/web-api/get-users',
		dataType: 'json',
		type: "POST",
		quietMillis: 50,
		data: function (params) {
			return {
				keyword: params.term
			};
		},
		processResults: function (data) {
			data.push({
				name: '---',
				id: ''
			});
			return {
				results: $.map(data, function (item) {
					return {
						text: item.name+' '+(typeof item.email == 'undefined' ? '' : '('+item.email+')'),
						id: item.id,
						avatar: item.avatar
					}
				})
			};
		}
	},
	templateResult: function (item) {
		if( typeof item.avatar == 'undefined' ){
			return $('<span>'+item.text+'</span>');
		}
		return $('<span><img src="'+item.avatar+'" style="width: 30px; height: 30px"> '+item.text+'</span>');
	},
	width: '100%'
});