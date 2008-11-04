<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
/**
 * BackendPro
 *
 * A website backend system for developers for PHP 4.3.2 or newer
 *
 * @package         BackendPro
 * @author          Adam Price
 * @copyright       Copyright (c) 2008
 * @license         http://www.gnu.org/licenses/lgpl.html
 * @link            http://backendpro.kaydoo.co.uk
 */

// ---------------------------------------------------------------------------

/**
 * Image Controller
 *
 * Allows images to be resized and cropped. Has a feature so any work done
 * is cached for next time
 *
 * @package			BackendPro
 * @subpackage		Controllers
 */
class Image extends Controller
{
	var $cache;		// Cache Folder Location
	var $img_path;	// Image path

	function Image()
	{
		parent::Controller();

		// Load BackendPro config file
		$this->load->config('backendpro');

		$this->img_path = NULL;

		// Setup the cache path so it uses that declared in the
		// CodeIgniter config file
		if ($this->config->item('cache_path') == "")
			$this->cache = BASEPATH . "cache/";
		else
			$this->cache = $this->config->item('cache_path');
	}

	/**
	 * Get Image
	 *
	 * Process an image and output it to the browser
	 *
	 * @return Image
	 */
	function get()
	{
		// Get the properties from the URI
		$default = array('file','width','height','watermark','crop','quality','nocache');
		$uri_array = $this->uri->uri_to_assoc(3, $default);

		if( $uri_array['file'] == NULL)
		{
			// Don't continue
			log_message("error","Badly formed image request string:".$this->uri->uri_string());
			return;
		}

		// Try to find the image
		foreach($this->config->item('backendpro_image_folders') as $folder)
		{
			if ( file_exists($folder.$uri_array['file']))
				$this->img_path = $folder.$uri_array['file'];
		}

		// Image couldn't be found
		if ($this->img_path == NULL)
		{
			log_message("error","Image dosn't exisit: ".$uri_array['file']);
			return;
		}

		// Get the size and MIME type of the requested image
		$size = GetImageSize($this->img_path);
		$width = $size[0];
		$height = $size[1];

		// Make sure that the requested file is actually an image
		if (substr($size['mime'], 0, 6) != 'image/')
		{
			log_message("error","Requested file is not an accepted type: ".$this->img_path);
			return;
		}

		// Before we start to check for caches and alike, lets just see if the image
		// was requested with no changes, if so just return the normal image
		if( $uri_array['width'] == NULL AND $uri_array['height'] == NULL AND $uri_array['watermark'] == NULL AND $uri_array['crop'] == NULL AND $uri_array['quality'] == NULL)
		{
			$data	= file_get_contents($this->img_path);
			header("Content-type:". $size['mime']);
			header('Content-Length: ' . strlen($data));
			echo $data;
			return;
		}

		// We know we have to do something, so before we do lets see if there is
		// cache of the image already
		if( $uri_array['nocache'] == NULL)
		{
			$image_cache_string = $this->img_path . " - " . $uri_array['width'] . "x" . $uri_array['height'];
			$image_cache_string.= "x" . $uri_array['watermark'] . "x" . $uri_array['crop'] . "x" . $uri_array['quality'];
			$image_cache_string = md5($image_cache_string);

			if (file_exists($this->cache_path.$image_cache_string))
			{
				// Yes a cached image exists
				$data	= file_get_contents($this->cache_path.$image_cache_string);
				header("Content-type:". $size['mime']);
				header('Content-Length: ' . strlen($data));
				echo $data;
				return;
			}
		}

		// CROP IMAGE
		$offsetX = 0;
		$offsetY = 0;
		if( $uri_array['crop'] != NULL)
		{
			$crop = explode(':',$uri_array['crop']);
			if(count($crop) == 2)
			{
				$actualRatio = $width / $height;
				$requestedRatio = $crop[0] / $crop[1];

				if ($actualRatio < $requestedRatio)
				{ 	// Image is too tall so we will crop the top and bottom
					$origHeight	= $height;
					$height		= $width / $requestedRatio;
					$offsetY	= ($origHeight - $height) / 2;
				}
				else if ($actualRatio > $requestedRatio)
				{ 	// Image is too wide so we will crop off the left and right sides
					$origWidth	= $width;
					$width		= $height * $requestedRatio;
					$offsetX	= ($origWidth - $width) / 2;
				}
			}
		}

		// RESIZE
		$ratio = $width / $height;
		$new_width = $width;
		$new_height = $height;
		if( $uri_array['width'] != NULL AND $uri_array['height'] != NULL)
		{	// Resize image to the largest dimension
			if($ratio > 1)
				$uri_array['width'] = NULL;		// Height is larger
			else
				$uri_array['height'] = NULL;	// Width is larger
		}

		if ( $uri_array['width'] == NULL AND $uri_array['height'] != NULL)
		{	// Keep height ratio
			$new_height = $uri_array['height'];
			$new_width = $new_height * $ratio;
		}
		else if ( $uri_array['width'] != NULL AND $uri_array['height'] == NULL)
		{	// Keep width ratio
			$new_width = $uri_array['width'];
			$new_height = $new_width / $ratio;
		}

		// WATERMARK
		// @TODO: Implement watermarking

		// QUALITY
		$quality = ($uri_array['quality'] != NULL) ? $uri_array['quality'] : $this->config->item('backendpro_image_default_quality');

		$dst_image = imagecreatetruecolor($new_width, $new_height);
		$src_image = imagecreatefromjpeg($this->img_path);
		imagecopyresampled($dst_image,$src_image,0, 0, $offsetX, $offsetY, $new_width, $new_height, $width, $height  );

		// SAVE CACHE
		if( $uri_array['nocache'] == NULL)
		{
			// Make sure Cache dir is writable
			if ( !is_really_writable($this->cache_path))
			{
				log_message('error',"Cache folder isn't writable: ".$this->cache_path);
				return;
			}

			// Write image to cache
			imagejpeg($dst_image,$this->cache_path.$image_cache_string,$quality);
		}

		header("Content-type:". $size['mime']);
		imagejpeg($dst_image,NULL,$quality);
	}
}
// END Image

/* End of file image.php */
/* Location: system/applications/controllers/image.php */