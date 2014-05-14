<?php namespace Kreitje\SimpleCdn\Laravel\Facades;

use Illuminate\Support\Facades\Facade;

class CDN extends Facade {

	public static function getFacadeAccessor() {
		return 'cdn';
	}

}