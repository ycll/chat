<?php

namespace app\common\util;

class Tools {
	public static function show($status, $message = '', $data = []) {
			echo json_encode([
				'status' => $status,
				'message' => $message,
				'data' => $data,
				]
			);
		}
	
}
