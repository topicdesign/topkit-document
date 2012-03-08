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

    /*
    | -------------------------------------------------------------------------
    | Config
    | -------------------------------------------------------------------------
    */
    
	protected $layout_dir = 'views/layouts/';
    protected $layout = 'default';
    protected $title_separator = ' &#124; ';
    protected $prepend_title = TRUE;
    protected $extract_data = TRUE;

    /*
    | -------------------------------------------------------------------------
    | Properties
    | -------------------------------------------------------------------------
    */

	/**
	 * Page Title segments
	 *
	 * @var array
	 **/
	protected $title_segs = array();

	/**
	 * Partials storage
	 *
	 * @var array
	 **/
	protected $partials = array();

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
        $this->ci = get_instance();
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
     * set new path to layout file
     *
     * @access  protected 
     * @param   string      $view   path to file from $layout_dir
     * @return  void
     **/
    protected function set_layout($view)
    {
        if ( ! is_file(APPPATH.$this->layout_dir.$view.'.php'))
        {
            return;
        }
        $this->layout = $view;
    }

    // --------------------------------------------------------------------

    /**
     * replace title with new string
     *
     * @access  protected 
     * @param   $name
     * @return  void
     **/
    protected function set_title($title)
    {
        $this->title($title, TRUE);
    }

    // --------------------------------------------------------------------

    /**
     * someFunc
     *
     * @access  protected 
     * @param   
     * @return  void
     **/
    public function title($segs, $replace=FALSE)
    {
        if (is_string($segs))
        {
            $segs = array($segs);
        }
        if ($replace)
        {
            $this->title_segs = array();
        }
        $this->title_segs = array_merge($this->title_segs, $segs);
        return $this;
    }

    // --------------------------------------------------------------------

    /**
     * get_title
     *
     * @access  protected 
     * @param   
     * @return  void
     **/
    protected function get_title()
    {
        $segs = $this->title_segs;
        if ($this->prepend_title)
        {
            $segs = array_reverse($segs);
        }
        return implode($this->title_separator, $segs);
    }
    
    // --------------------------------------------------------------------

    /**
     * partial
     *
     * @access  public 
     * @param   $name, $view, $data
     * @return  void
     **/
    public function partial($name, $view=NULL, $data=array())
    {
        if (is_null($view) && empty($data))
        {
            return $this->get_partial($name);
        }
        // determine if passed view or injecting string
        $lines = explode(PHP_EOL, $view);
        if (count($lines) == 1 && is_file(APPPATH.'views/'.$lines[0].'.php'))
        {
            $this->partials[$name] = array(
                'view'  => $view,
                'data'  => $data
            );
        }
        else
        {
            $this->partials[$name] = $view;
        }
    }

    /**
     * get_partial
     *
     * @access  protected 
     * @param   $name
     * @return  string
     **/
    protected function get_partial($name)
    {
        if ( ! isset($this->partials[$name]))
        {
            return FALSE;
        }
        if (is_string($this->partials[$name]))
        {
            return $this->partials[$name];
        }
        $p = $this->partials[$name]; 
        return $this->ci->load->view($p['view'], $p['data'], TRUE);
    }

    /**
     * find_view
     *
     * @access  private 
     * @param   $view
     * @return  string
     **/
    private function find_view($view)
    {
        //FIXME: do a smart view lookup
        foreach ($this->view_dirs as $dir)
        {
            $path = $dir.$view.'.php';
            if (is_file(APPPATH.$path))
            {
                return $path;
            }
        }
        return FALSE;
    }
    // --------------------------------------------------------------------

} // END Page class
// --------------------------------------------------------------------
/* End of file Page.php */
/* Location: ./application/libraries/Page.php */
