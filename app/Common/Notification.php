<?php
namespace App\Common;


class Notification {


	public static function Android(){


		return json_encode("hello android");
	}

	public static function Ios(){


		return json_encode("hello ios");
	}
}