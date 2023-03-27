fileSelecter = {
	change: function(self){
		var fileName = $(self).val().split('\\').slice(-1).pop();
		if( fileName.length == 0 ){
			var fileName = '-';
		}
		$(self).next().text( fileName );
	}
};