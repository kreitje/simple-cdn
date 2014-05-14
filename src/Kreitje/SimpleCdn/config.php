<?php

return array(

	/**
	 * Set the type of "CDN" you are using. Currently "Local" and "S3" are available.
	 */
	'cdn_type' => 'S3',

	'cdn' => array(

		/**
		 * Configuration for using Amazon S3
		 */
		'S3' => array(

			/**
			 * Set the S3 API Key
			 */
			'key'    => '',

			/**
			 * Set the S3 API Secret
			 */
			'secret' => '',

			/**
			 * Set the region your bucket is in
			 */
			'region' => '', //example: \Aws\Common\Enum\Region::US_EAST_1,

			/**
			 * Set the name of your bucket
			 */
			'bucket' => '',

			/**
			 * Override the URL
			 */
			'cdn_url' => '',
		),

		/**
		 * Configuration for uploading to your local file system
		 */
		'Local' => array(

			/**
			 * Set the directory where the files are saved.
			 */
			'upload_dir' => public_path() . '/uploaded_images',

			/**
			 * Set the URL where the files can be accessed.
			 */
			'cdn_url' => '/uploaded_images',
		),
	),

);