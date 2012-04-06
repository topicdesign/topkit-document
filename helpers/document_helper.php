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
if ( ! function_exists('document_title'))
{
    function document_title()
    {
        $CI = get_instance();
        return $CI->document->title;
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
if ( ! function_exists('document_partial'))
{
    function document_partial($name)
    {
        $CI = get_instance();
        return $CI->document->partial($name);
    }
}

// --------------------------------------------------------------------

/**
 * get page metadata
 *
 * @access  public 
 * @param   void
 *
 * @return  string
 **/
if ( ! function_exists('document_metadata'))
{
    function document_metadata()
    {
        $CI = get_instance();
        return $CI->document->metadata;
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
if ( ! function_exists('document_content'))
{
    function document_content()
    {
        $CI = get_instance();
        return $CI->document->content;
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
if ( ! function_exists('document_data'))
{
    function document_data($name)
    {
        $CI = get_instance();
        return $CI->document->data($name);
    }
}

// ------------------------------------------------------------------------
/* End of file document_helper.php */
/* Location: ./helpers/document_helper.php */
