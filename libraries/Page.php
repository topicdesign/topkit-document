<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed'); 

/**
 * Page Class
 *
 * Templating and Layout Abstraction
 *
 * @package		CodeIgniter
 * @subpackage	Libraries
 * @category	Templating
 * @author      Topic Deisgn
 * @license		http://creativecommons.org/licenses/BSD/
 * @version		0.0.1
 */

class Page {

	/**
	 * directory of layout files (relative to APPPATH)
	 *
	 * @var string
	 **/
	protected $layout_dir = 'views/layouts';

    /**
     * file name of default layout file
     *
     * @var string
     **/
    protected $layout = 'default';

    /**
     * string to use to separate concatenated titles
     *
     * @var string
     **/
    protected $title_separator = ' &#124; ';

    /**
     * should new title strings be prepended
     *
     * @var bool
     **/
    protected $prepend_title = TRUE;

    /**
     * should we extract the data to local variables in layouts/views)
     *
     * @var string
     **/
    protected $extract_data = TRUE;

	/**
	 * local instance of CodeIgniter
	 *
	 * @var object
	 **/
	private $ci;

	// ------------------------------------------------------------------------

	/**
	 * constructor
	 *
	 * @access	public 
	 * @param	void
	 * @return	void
	 **/
	public function __construct($config = array())
	{
		//$this->ci = get_instance();
		if ( ! empty($config))
		{
			$this->initialize($config);
		}
        log_message('debug', get_class() . ' Library Initialized');
	}
	
	// --------------------------------------------------------------------
    
    /**
	 * initialize config
	 *
	 * @access	public
	 * @param	array   $config
	 * @return	void
	 */
	public function initialize($config = array())
    {
        if ( ! is_array($config))
        {
            return FALSE;
        }
		foreach ($config as $name => $value)
		{
            $this->__set($name, $value);
		}
    }

    // --------------------------------------------------------------------

    /**
     * __get
     *
     * @access  public 
     * @param   $name
     * @return  void
     **/
    public function __get($name)
    {
        $method = 'get_'.$name;
        if (method_exists($this,$method))
        {
            return $this->$method();
        }
        else
        {
            return $this->$name;
        }
    }

    // --------------------------------------------------------------------
    
    /**
     * __set
     *
     * @access  public 
     * @param   $name
     * @param   $value
     *
     * @return  void
     **/
    public function __set($name, $value)
    {
        $method = 'set_'.$name;
        if (method_exists($this,$method))
        {
            return $this->$method($value);
        }
        else
        {
            $this->$name = $value;
        }
    }
    
    // --------------------------------------------------------------------

    /**
     * set_layout
     *
     * @access  protected 
     * @param   $name
     * @return  void
     **/
    protected function set_layout($name)
    {
        $this->layout = $name;
    }

    // --------------------------------------------------------------------

} // END Page class
// --------------------------------------------------------------------
/* End of file Page.php */
/* Location: ./application/libraries/Page.php */
