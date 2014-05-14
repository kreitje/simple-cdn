<?php namespace Kreitje\SimpleCdn\CDNs;

use NetDNA;
use NetDNA\RWSException;

class MaxCDN implements CDNInterface {

	/**
	 * Alias
	 */
	private $alias = '';

	/**
	 * Consumer key
	 */
	private $consumer_key = '';

	/**
	 * Consumer secret
	 */
	private $consumer_secret = '';

	/**
	 * The zone where a file is uploaded or deleted from
	 */
	private $zone = '';

	/**
	 * Set a custom URL to serve the file from
	 */
	private $cdn_url = '';

	/**
	 * The NetDNA object
	 */
	private $netdna = null;

	/**
	 * The last saved object URL 
	 */
	public $last_url = null;

	public function __construct( array $options = array() ) {

		$required_options = array(
			'alias', 'consumer_key', 'consumer_secret', 'zone',
		);

		foreach($required_options as $key) {

			if ( !isset($options[ $key ]) ) {
				throw new CDNException( 'You must specify an [' . $key . '] when using the MaxCDN/NetDNA CDN');
			}

			$this->{{$key}} = $options[ $key ];

		}
		
		/**
		 * Set a custom URL if they are using CNAMES or CloudFront
		 */
		if ( isset($options['cdn_url']) && $options['cdn_url'] != '' ) {
			$this->url = rtrim($options['cdn_url'], '/');
		}

		/** 
		 * Load up S3
		 */
		$cdn = new NetDNA( $this->alias, $this->consumer_key, $this->consumer_secret );

		$this->netdna = $cdn;
	}

	/**
	 * Save the file to an S3 bucket
	 *
	 * @throws CDNException
	 * @return bool
	 */
	public function save( $file, $path ) {
		try {

		} catch(RWSExcpetion $e) {
			/** Catch the exception and throw our own **/
			throw new CDNException( $e->getMessage() );
		}
	}

	/**
	 * Delete a file from an S3 bucket
	 *
	 * @throws CDNException
	 * @return bool
	 */
	public function delete( $path ) {

		try {

			$this->netdna->delete( '/zones/pull.json/' . $this->zone . '/cache', array(
				'file' => '/' . $path
			) );

			return true;
		} catch(RWSExcpetion $e) {
			/** Catch the exception and throw our own **/
			throw new CDNException( $e->getMessage() );
		}
	}

	/**
	 * Get the URL to the object
	 *
	 * @return string
	 */
	public function getLastURL() {
		return $this->last_url;
	}

	/**
	 * Return the type of CDN
	 *
	 * @return string
	 */
	public function type() {
		return 'S3';
	}

}