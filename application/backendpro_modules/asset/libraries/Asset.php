<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
/**
 * BackendPro
 *
 * A website backend system for developers for PHP 5.2.6 or newer
 *
 * @package         BackendPro
 * @author          Adam Price <adam@kaydoo.co.uk>
 * @copyright       2008-2010, Adam Price
 * @license         http://www.opensource.org/licenses/mit-license.php MIT
 * @license         http://www.gnu.org/licenses/gpl.html GPL
 * @link            http://www.kaydoo.co.uk/projects/backendpro
 * @filesource
 */

/**
 * The asset class allows assets to be loaded and then rendered to screen
 */
class Asset
{
    /**
     * Collection of all assets
     * 
     * @var array
     */
    private $assets = array();

    /**
     * The assets folder relative to base_url
     * @var string
     */
    private $folder = 'assets/';

    /**
     * The asset group to render
     * @var string
     */
    public $group = NULL;

    public function __construct()
    {
        $CI =& get_instance();

        $CI->load->helper('html');

        $this->load_assets();

        log_message('debug', 'Asset Library loaded');
    }

    /**
     * Load all assets from config
     * 
     * @return void
     */
    private function load_assets()
    {
        $CI =& get_instance();
        log_message('debug', 'Loading all asset configs');

        // First lets load all asset files
        $CI->load->config('core/backendpro_assets', TRUE);
        $CI->load->config('assets', TRUE, TRUE);

        // Load the core assets
        $this->assets = $CI->config->item('assets', 'backendpro_assets');

        // Now get the custom assets
        $assets = $CI->config->item('assets', 'assets');

        // Merge them if we have any
        if(is_array($assets))
        {
            log_message('debug', 'Custom assets file found, loading');
            $this->assets = array_merge_recursive($this->assets, $assets);
        }
    }

    /**
     * Render assets to screen
     *
     * @param string $type Asset type to render css/js/both
     * @return void
     */
    public function render($type = 'both')
    {
        if(!empty($this->group) && isset($this->assets[$this->group]))
        {
            foreach($this->assets[$this->group] as $asset)
            {
                $ext = substr($asset, strrpos($asset, '.') + 1);

                if($ext == 'css' && ($type == 'css' || $type == 'both'))
                {
                    $this->render_css($asset);
                }

                if($ext == 'js' && ($type == 'js' || $type == 'both'))
                {
                    $this->render_js($asset);
                }
            }
        }
    }

    /**
     * Render a CSS asset file
     *
     * @param string $asset Asset path
     * @return void
     */
    private function render_css($asset)
    {
        if(!$this->is_url($asset))
        {
            $asset = base_url() . $this->folder . $asset;
        }

        print link_tag($asset, 'stylesheet', 'text/css', '', '', FALSE) . "\n";
    }

    /**
     * Render a JS asset file
     *
     * @param string $asset Asset path
     * @return void
     */
    private function render_js($asset)
    {
        if(!$this->is_url($asset))
        {
            $asset = base_url() . $this->folder . $asset;
        }
        
        print "<script type='text/javascript' src='" . $asset . "'></script>\n";
    }

    /**
     * Check to see if a string is a URL
     *
     * @param string $string Value to check
     * @return bool
     */
    private function is_url($string)
    {
        $pattern = '@(https?://([-\w\.]+)+(:\d+)?(/([\w/_\.]*(\?\S+)?)?)?)@';
        return preg_match($pattern, $string) == 1;
    }
}
 
/* End of Asset.php */
/* Location: ./application/backendpro_modules/asset/libraries/Asset.php */