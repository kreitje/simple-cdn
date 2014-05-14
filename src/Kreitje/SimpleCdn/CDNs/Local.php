<?php namespace Kreitje\SimpleCdn\CDNs;

/**
 * Save files to the local file system
 */
class Local implements CDNInterface {

	/**
	 * The directory on the file server to save files to
	 */
	private $upload_directory 	= '';

	/**
	 * Set a custom URL to serve the file from
	 */
	private $cdn_url 			= '';

	/**
	 *  Set the file mode a directory should be created with
	 */
	private $mode = 0777;

	/**
	 * The last saved object URL 
	 */
	public $last_url = null;

	public function __construct( array $options = array() ) {
		
		/** Make sure the upload directory configuration is set **/
		if ( !isset( $options['upload_directory'] ) ) {
			throw new CDNException( 'The Local CDN must have an upload_directory configration value.' );
		}

		$this->upload_directory = $options['upload_directory'];

		if ( !isset( $options['cdn_url'] ) ) {
			throw new CDNException( 'Please set the cdn_url configuration option.');
		}

		$this->cdn_url = rtrim( $options['cdn_url'] );

	}

	/**
	 * Save the file to the local file system
	 */
	public function save( $file, $path ) {
		
		$folders = explode('/', $path);
		if ( count($folders) > 0 ) {
			$temp_path = $folders;
			unset( $temp_path[ count( $folders ) - 1] );
			$my_path = implode('/', $temp_path);

			/**
			 * Make the directories if they don't exist
			 */
			if ( !is_dir( $this->upload_directory . '/' . $my_path ) ) {
				mkdir( $this->upload_directory . '/' . $my_path, $this->mode, true );
			}
		}

		$ret = copy( $file, $this->upload_dir . '/' . $path );

		if ($ret === true) {
			$this->last_url = $this->url . '/'. $path;
			return true;
		}

		throw new CDNException( 'Unable to save file to the CDN.' );

	}

	/**
	 * Delete the file from the local file system
	 *
	 * @return bool
	 */
	public function delete( $path ) {
		
		if ( @unlink( $this->upload_dir . '/' . $path ) ) {
			return true;	
		}

		throw new CDNException( 'Unable to remove CND file.' );
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
		return 'Local';
	}
}
