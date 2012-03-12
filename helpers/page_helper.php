<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * Page Helpers
 *
 * @package		CodeIgniter
 * @subpackage	Helpers
 * @author		Topic Design
 * @license		http://creativecommons.org/licenses/BSD/
 */

// ------------------------------------------------------------------------

/**
 * get page title
 *
 * @access  public 
 * @param   void 
 *
 * @return  string
 **/
if ( ! function_exists('page_title'))
{
    function page_title()
    {
        $CI = get_instance();
        return $CI->page->title;
    }
}

// --------------------------------------------------------------------

/**
 * get a page partial
 *
 * @access  public 
 * @param   string      $name 
 *
 * @return  string
 **/
if ( ! function_exists('page_partial'))
{
    function page_partial($name)
    {
        $CI = get_instance();
        return $CI->page->partial($name);
    }
}

// --------------------------------------------------------------------

/**
 * get the page content
 *
 * @access  public 
 * @param   void 
 *
 * @return  string
 **/
if ( ! function_exists('page_content'))
{
    function page_content()
    {
        $CI = get_instance();
        return $CI->page->content;
    }
}

// --------------------------------------------------------------------

/**
 * get some page data
 *
 * @access  public 
 * @param   string      $name 
 *
 * @return  mixed
 **/
if ( ! function_exists('page_data'))
{
    function page_data($name)
    {
        $CI = get_instance();
        return $CI->page->data($name);
    }
}

// ------------------------------------------------------------------------
/* End of file page_helper.php */
/* Location: ./helpers/page_helper.php */
