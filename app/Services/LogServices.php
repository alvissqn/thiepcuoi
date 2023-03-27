<?php
namespace App\Services;
use Illuminate\Support\Facades\Hash;
use App\Log;
use App\LogCategory;
use Option;
use App\Services\UserServices;

class LogServices
{
	/*
	 * Lưu lịch sử
	 */
	public static function new($data = []){

		if( is_object($data['old_value']) ){
			$data['old_value'] = $data['old_value']->toArray();
		}
		if( is_object($data['new_value']) ){
			$data['new_value'] = $data['new_value']->toArray();
		}
		$data['old_value'] = serialize( (array)$data['old_value'] );
		$data['new_value'] = serialize( (array)$data['new_value'] );
		$data['user_id']   = $data['user_id'] ?? \Auth::id();
		Log::create($data);
		LogCategory::updateOrCreate(
			[
				'name'   => $data['category'],
				'action' => $data['action']
			],
			[
				'name'   => $data['category'],
				'action' => $data['action']
			]
		);
	}

	/*
	 * Hiện lịch sử thao tác
	 */
	public static function timeline($params = []){
		// Xóa bản ghi cũ hơn 60 ngày
		Log::where('created_at', '<', date('Y-m-d 00:00:00', strtotime('-'.config('general.logs_cleanup_months').' months') ) )->delete();
		
		extract($params);
		\App\Helpers\Assets::showOnFooter('/assets/plugins/action-logs/scripts.js', '/assets/plugins/action-logs/style.css');
		$body = '';
		foreach( Log::where('category', $category)->orderBy('id', 'DESC')->limit($limit)->get() as $item){
			$item->old_value = unserialize($item->old_value);
			$item->new_value = unserialize($item->new_value);
			$item->display_name = UserServices::get($item->user_id, 'display_name');
			$item->time = $item->created_at->format( Option::get('settings__general_time_format') );
			$body .= '
				<li class="timeline-items timeline-icon-'.$item->action_color.' active">
					<div class="timeline-time">
						<i class="bx bx-time"></i>
						'.dateText($item->created_at->timestamp).'
					</div>
					<p class="timeline-text">
						'.$item->display_name.'
					</p>
					<div class="timeline-content link" onclick="actionLogs.showDetail(this)">
						'.__('admin/tools/logs_action.'.$item->action).'
						'.($item->action_value ? ': <b style="margin-left: 5px">'.$item->action_value.'</b>' : '').'
					</div>
					<textarea class="item-json-data hide">'.json_encode($item).'</textarea>
				</li>
			';
		}
		$out = '
			<section class="modal fade text-left" id="modal-logs-detail">
				<div class="modal-dialog modal-dialog-centered modal-dialog-scrollable" style="max-width: 1100px">
					<div class="modal-content">
						<div class="modal-header">
							<h4 class="modal-title"></h4>
							<button type="button" class="close" data-dismiss="modal">
								<i class="bx bx-x"></i>
							</button>
						</div>
						<div class="modal-body custom-scrollbar">
							
						</div>
						<div class="modal-footer"></div>
					</div>
				</div>
			</section><!-- /.modal -->

			<section class="timline-card">
				<div class="card ">
					<div class="card-header">
						<h4 class="card-title">
							'.$title.'
						</h4>
					</div>
					<hr>
					<div class="card-content">
						<div class="card-body">
							<ul class="widget-timeline custom-scrollbar" style="overflow: auto;">
								'.$body.'
							</ul>
							<div class="text-center">
								<a href="'.route('admin.tools.logs').'">
									<i class="bx bx-plus"></i>
									'.__('admin/tools/logs.see_all_logs').'
								</a>
							</div>
						</div>
					</div>
				</div>
			</section>
		';
    	return $out;
	}
}
