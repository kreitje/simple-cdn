<?php namespace Kreitje\SimpleCdn\CDNs;

interface CDNInterface {

	public function __construct( array $options );

	/**
	 * Save a file to the CDN
	 */
	public function save( $file, $path );

	/**
	 * Delete a file from the CDN
	 */
	public function delete( $file );

	/**
	 * Return the CDN provider you are using
	 */
	public function type();

	/**
	 * Return the URL of the CDN object
	 */
	public function getLastURL();


}