/*
 * Lịch sử thao tác
 */
 actionLogs = {
 	// Hiện chi tiết
 	showDetail: function(self){
 		var data = JSON.parse( $(self).parent().children('.item-json-data').val() );
 		var oldHTML = '';
 		$.each(data.old_value, function(key, value){
 			if( value == data.new_value[key] ){
 				return;
 			}
 			if( typeof value == 'object' ){
 				var value = JSON.stringify(value, null, 4);
 			}
 			oldHTML += `
 				<tr>
	 				<td>
	 					${key}
	 				</td>
	 				<td>
		 				${value}
		 			</td>
	 			</tr>
 			`;
 		});
 		var newHTML = '';
 		$.each(data.new_value, function(key, value){
 			if( value == data.old_value[key] ){
 				return;
 			}
 			if( typeof value == 'object' ){
 				var value = JSON.stringify(value, null, 4);
 			}
 			newHTML += `
 				<tr>
	 				<td>
	 					${key}
	 				</td>
	 				<td>
		 				${value}
		 			</td>
	 			</tr>
 			`;
 		});
 		var html = `
 			<section class="row">
 				<div class="col-md-6">
 					<div class="card">
 						<div class="card-header">
 							<i class="bx bx-history"></i>
 							Old data
 						</div>
 						<hr>
 						<div class="card-body mt-0">
 							<div class="table-responsive">
		 						<table class="table table-hover">
		 							${oldHTML}
		 						</table>
		 					</div>
	 					</div>
 					</div>
 				</div>
 				<div class="col-md-6">
 					<div class="card">
 						<div class="card-header">
 							<i class="bx bx-check"></i>
 							New data
 						</div>
 						<hr>
 						<div class="card-body mt-0">
 							<div class="table-responsive">
		 						<table class="table table-hover">
		 							${newHTML}
		 						</table>
		 					</div>
	 					</div>
 					</div>
 				</div>
 			</section>
 		`;
 		$('#modal-logs-detail .modal-body').html(html);
 		$('#modal-logs-detail .modal-title').html(`
 			${data.display_name}
 		`);
 		$('#modal-logs-detail .modal-footer').html(`
 			<i class="bx bx-time mr-1"></i>
 			${data.time}
 		`);
 		$('#modal-logs-detail').modal('show');
 	}
 };