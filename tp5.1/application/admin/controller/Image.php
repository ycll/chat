<?php

namespace app\admin\controller;

use app\common\util\Tools;

class Image {

	public function index() {
		$file = request()->file('file');	
		$info = $file->move('../public/static/upload');
		if (!empty($info))
		{
			$data = ['image'=> 'http://ycl.com:9503/upload/' . $info->getSaveName()];
			Tools::show(1, 'OK', $data);
		}	
		else
		{
			Tools::show(-1, 'upload fail');
		}


	}


}
