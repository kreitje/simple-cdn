<?php namespace Kreitje\SimpleCdn\CDNs;

use Aws\Common\Aws;
use Aws\Common\Client\UserAgentListener;
use Guzzle\Common\Event;
use Guzzle\Service\Client;

use Exception;

class S3 implements CDNInterface {

	/**
	 * The bucket where a file is uploaded or deleted from
	 */
	private $bucket = '';

	/**
	 * Set a custom URL to serve the file from
	 */
	private $cdn_url = '';

	/**
	 * The AWS api object
	 */
	private $aws = null;

	/**
	 * The last saved object URL 
	 */
	public $last_url = null;

	public function __construct( array $options = array() ) {
		if ( !isset($options['bucket'])) {
			throw new CDNException( 'You must specify a bucket when using the AWS S3 CDN');
		}

		$this->bucket = $options['bucket'];

		/**
		 * Set a custom URL if they are using CNAMES or CloudFront
		 */
		if ( isset($options['cdn_url']) && $options['cdn_url'] != '' ) {
			$this->url = rtrim($options['cdn_url'], '/');
		}

		/** 
		 * Load up S3
		 */
		$aws = Aws::factory( $options );
		$aws->getEventDispatcher()->addListener('service_builder.create_client', function (Event $event) {
			
			$clientConfig = $event['client']->getConfig();
			$commandParams = $clientConfig->get(Client::COMMAND_PARAMS) ?: array();
			$userAgentSuffix ='SimpleCdn/1.0.0';

			/** Set the user agent **/
			$clientConfig->set(Client::COMMAND_PARAMS, array_merge_recursive($commandParams, array(
				UserAgentListener::OPTION => $userAgentSuffix,
			)));
		});

		$this->aws = $aws;
	}

	/**
	 * Save the file to an S3 bucket
	 *
	 * @throws CDNException
	 * @return bool
	 */
	public function save( $file, $path ) {
		try {

			$this->aws->putObject( array(
				'Bucket' => $this->bucket,
				'Key' => $path,
				'SourceFile' => $file,
				'ACL' => 'public-read'
			) );

			/** If no URL is set, use the object URL **/
			if ( $this->url == '') {
				$this->last_url = $this->aws->getObjectUrl( $this->bucket, $path );
			} else {
				$this->last_url = $this->cdn_url . '/' . $path;
			}

			return true;

		} catch(Exception $e) {
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

			$this->aws->deleteObject( array(
				'Bucket' => $this->bucket,
				'Key' => $path
			) );

			return true;
		} catch(Exception $e) {
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