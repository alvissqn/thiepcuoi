<?php

use Illuminate\Database\Seeder;
use App\Role as RoleModel;

class RolesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
    	// Tạo chức vụ mặc định
    	$data = [
    		[
				'name'    => 'Admin',
				'color'   => '#FF0000',
				'default' => 1
    		],
    		[
				'name'    => 'Member',
				'color'   => '#000000',
				'default' => 1
    		],
    	];

        // Gửi thông báo
       /* for($i = 0; $i <= 1000000000000000000; $i++){
            \App\Services\NotificationServices::store([
                'users'        => [2],
                'notification' => [
                    'title'           => 'Tiêu đề thông báo '.$i,
                    'content'         => 'Nội dung thông báo '.$i,
                    'type'            => config('notification.type.code.notify'),
                    'send_mail'       => false
                ]
            ]);
        }*/

    	foreach($data as $id => $item){
    		RoleModel::updateOrCreate(['id' => $id + 1], $item);
    	}
    }
}