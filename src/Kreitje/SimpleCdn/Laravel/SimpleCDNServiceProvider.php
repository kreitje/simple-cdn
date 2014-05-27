<?php namespace Kreitje\SimpleCdn\Laravel;

use Kreitje\SimpleCdn\CDNs\CDNException;
use Kreitje\SimpleCdn\CDNs\Local;
use Kreitje\SimpleCdn\CDNs\S3;
use Illuminate\Support\ServiceProvider;


class SimpleCdnServiceProvider extends ServiceProvider {

	/**
	 * Indicates if loading of the provider is deferred.
	 *
	 * @var bool
	 */
	protected $defer = false;

	/**
	 * Register the service provider.
	 *
	 * @return void
	 */
	public function register() {

		$config = $this->app['config']->get('simple-cdn');

		$this->app->bind('cdn', function() use ($config) {

			switch( $config['cdn_type'] ) {
				case 'Local':
					return new Local( $this->app['config']->get('simple-cdn::cdn.Local', array() ) );
				break;
				case 'S3':
					return new S3( $this->app['config']->get('simple-cdn::cdn.S3', array() ) );
				break;
				default:
					throw new CDNException( 'Invalid CDN provider specified. [' . $config['cdn_type'] .']');
				break;
			}

		});

	}

	/**
	 * Get the services provided by the provider.
	 *
	 * @return array
	 */
	public function provides()
	{
		return array('cdn');
	}

}
