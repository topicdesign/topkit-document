<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Page Class
 *
 * Templating and Layout Abstraction
 *
 * @package     CodeIgniter
 * @subpackage  Libraries
 * @category    Templating
 * @author      Topic Deisgn
 * @license     http://creativecommons.org/licenses/BSD/
 * @version     0.0.1
 */

class Page {

    /*
    | -------------------------------------------------------------------------
    | Config
    | -------------------------------------------------------------------------
    */

    protected $layout_dir = 'layouts/';
    protected $layout = 'default';
    protected $title_separator = ' &#124; ';
    protected $prepend_title = TRUE;
    protected $extract_data = TRUE;
    protected $cache_lifetime = 0;

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
     * Meta-Data storage
     *
     * @var array
     **/
    protected $metadata = array();

    /**
     * Data storage
     *
     * @var array
     **/
    protected $data = array();

    /**
     * Page content
     *
     * @var string
     **/
    protected $content = '';

    /**
     * local instance of CodeIgniter
     *
     * @var object
     **/
    private $ci;

    // --------------------------------------------------------------------

    /**
     * constructor
     *
     * @access  public
     * @param   void
     * @return  void
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
     * @access  public
     * @param   array       $config
     * @return  void
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
     * @param   string      $name
     * @return  mixed
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
            return $this->data($name);
        }
    }

    // --------------------------------------------------------------------

    /**
     * __set
     *
     * @access  public
     * @param   string      $name
     * @param   mixed       $value
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
            $this->data($name, $value);
        }
    }

    // --------------------------------------------------------------------

    /**
     * get page content
     *
     * @access  protected
     * @param   void 
     * @return  string
     **/
    protected function get_content()
    {
        return $this->content;
    }

    // --------------------------------------------------------------------

    /**
     * set new path to layout file
     *
     * @access  protected
     * @param   string      $view
     * @return  void
     **/
    protected function set_layout($view = NULL)
    {
        if (is_null($view))
        {
            $this->layout = NULL;
            return;
        }
        if ( ! is_file(APPPATH.'views/'.$this->layout_dir.$view.'.php'))
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
     * @param   string      $title
     * @return  void
     **/
    protected function set_title($title)
    {
        $this->title($title, TRUE);
    }

    // --------------------------------------------------------------------

    /**
     * set title or title segments
     *
     * @access  protected
     * @param   mixed       $segs
     * @param   bool        $replace
     * @return  object
     **/
    public function title($segs, $replace = FALSE)
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
     * get title
     *
     * @access  protected
     * @param   void
     * @return  string
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
     * get/set partial
     *
     * @access  public
     * @param   string      $name
     * @param   string      $view
     * @param   array       $data
     * @return  mixed
     **/
    public function partial($name, $view = NULL, $data = array())
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
        return $this;
    }

    // --------------------------------------------------------------------

    /**
     * get rendered partial
     *
     * @access  protected
     * @param   string      $name
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
        extract($this->partials[$name]);
        if ($this->extract_data)
        {
            $data = array_merge($this->data, $data);
        }
        return $this->ci->load->view($view, $data, TRUE);
    }

    // --------------------------------------------------------------------

    /**
     * set/get metadata
     *
     * @access  public 
     * @param   mixed       $lines
     * @return  void
     **/
    public function metadata($lines = NULL)
    {
        if (is_null($lines))
        {
            return $this->get_metadata();
        }
        if (is_string($lines))
        {
            $lines = array($lines);
        }
        foreach ($lines as $l)
        {
            $this->set_metadata($l);
        }
    }

    // --------------------------------------------------------------------

    /**
     * set_metadata
     *
     * @access  protected 
     * @param   string      $str
     * @return  void
     **/
    protected function set_metadata($str)
    {
       $this->metadata[] = $str; 
    }
    
    // --------------------------------------------------------------------

    /**
     * get_metadata
     *
     * @access  protected 
     * @param   
     * @return  string
     **/
    protected function get_metadata()
    {
        return implode("\n", $this->metadata);
    }

    // --------------------------------------------------------------------

    /**
     * get/set data to page object
     *
     * @access  public 
     * @param   string      $name
     * @param   mixed       $value
     * @return  mixed
     **/
    public function data($name = NULL, $value = NULL)
    {
        if (is_null($name))
        {
            return $this->data;
        }
        if (is_string($name) && is_null($value))
        {
            if ( ! isset($this->data[$name]))
            {
                return NULL;
            }
            else
            {
                return $this->data[$name];
            }
        }
        // setter
        if (is_string($name))
        {
            $name = array($name=>$value);
        }
        foreach ($name as $key => $value)
        {
            $this->data[$key] = $value;
        }
        return $this;
    }

    // --------------------------------------------------------------------

    /**
     * get all page data
     *
     * @access  protected 
     * @param   void
     * @return  array
     **/
    protected function get_data()
    {
        return $this->data;
    }

    // --------------------------------------------------------------------

    /**
     * generate title based on uri segments
     *
     * @access  private 
     * @param   void 
     * @return  void
     **/
    private function generate_title()
    {
        $segs = $this->ci->uri->segment_array();
        if ($site_title = config_item('site_title'))
        {
            $segs[] = $site_title;
        }
        $this->ci->load->helper('inflector');
        foreach ($segs as &$s)
        {
            $s = humanize($s); 
        }
        $this->title(array_reverse($segs));
    }

    // --------------------------------------------------------------------

    /**
     * build the final output
     *
     * @access  public 
     * @param   string      $view
     * @param   array       $data
     * @param   bool        $return
     * @return  mixed
     **/
    public function build($view, $data = array(), $return = FALSE)
    {
        if ($this->extract_data)
        {
            $data = array_merge($this->data, $data);
        }
        // ensure a title exists
        if ( ! $this->get_title())
        {
            $this->generate_title();
        }
        // Cache bits imported from Phil Sturgeon's Template Lib
        $this->ci->output
            ->set_header('Expires: Sat, 01 Jan 2000 00:00:01 GMT')
            ->set_header('Cache-Control: no-store, no-cache, must-revalidate')
            ->set_header('Cache-Control: post-check=0, pre-check=0, max-age=0')
            ->set_header('Last-Modified: ' . gmdate('D, d M Y H:i:s') . ' GMT' )
            ->set_header('Pragma: no-cache')
            ->cache($this->cache_lifetime)
            ;
        // build the requested output
        $output = $this->content = $this->ci->load->view($view, $data, TRUE);
        // wrap in a layout?
        if ($this->layout)
        {
            $output = $this->ci->load->view($this->layout_dir.$this->layout, NULL, TRUE);
        }
        // returned as string or output to browser
        if ($return)
        {
            return $output;
        }
        $this->ci->output->set_output($output);
    }

    // --------------------------------------------------------------------

} // END Page class
// --------------------------------------------------------------------
/* End of file Page.php */
/* Location: ./application/libraries/Page.php */
