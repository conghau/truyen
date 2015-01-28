<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');


/**
* @name CI Smarty
* @copyright Dwayne Charrington, 2012.
* @author Dwayne Charrington and other Github contributors
* @license (DWYWALAYAM)
           Do What You Want As Long As You Attribute Me Licence
* @version 1.3
* @link http://ilikekillnerds.com
*/

class MY_Parser extends CI_Loader {

    protected $CI;
    protected $theme_location;

    private $_module = '';

    public function __construct()
    {
        $this->CI =& get_instance();
        $this->CI->load->library('smarty');

        // Modular Separation / Modular Extensions has been detected
        if ( method_exists( $this->CI->router, 'fetch_module' ) )
        {
            $this->_module = $this->CI->router->fetch_module();
        }

    }

    /**
    * Parse
    * Parses a template using Smarty 3 engine
    *
    * @param string $template
    * @param array $data
    * @param boolean $return
    * @param mixed $caching
    */
    public function parse($template, $data = array(), $return = FALSE, $caching = TRUE)
    {
        // Make sure we have a template, yo.
        if (empty($template))
        {
            return FALSE;
        }

        // If we don't want caching, disable it
        if ($caching === FALSE)
        {
            $this->CI->smarty->disable_caching();
        }

        // If no file extension dot has been found default to defined extension for view extensions
        if ( !stripos($template, '.') )
        {
            $template = $template.".".$this->CI->smarty->template_ext;
        }

        if ( !empty($this->_module) )
        {
            $template = APPPATH . 'modules/' . $this->_module . '/views/' . $template;
        }

        // If we have variables to assign, lets assign them
        if (!empty($data))
        {
            foreach ($data as $key => $val)
            {
                $this->CI->smarty->assign($key, $val);
            }
        }

        // Get our template data as a string
		log_message('debug', sprintf("Template => template file : %s", $template));
        $template_string = $this->CI->smarty->fetch($template);
        // If we're returning the templates contents, we're displaying the template
        if ($return === FALSE)
        {
            $this->CI->output->append_output($template_string);
        }

        // We're returning the contents, fo' shizzle
        return $template_string;
    }


    /**
    * Parse
    * Parses a template using Smarty 3 engine
    *
    * @param string $template
    * @param array $data
    * @param boolean $return
    * @param mixed $caching
    */
    public function html_parse($template, $data = array(), $return = FALSE, $caching = TRUE, $encoding = "UTF-8")
    {
        // Make sure we have a template, yo.
        if (empty($template))
        {
            return FALSE;
        }

        // If we don't want caching, disable it
        if ($caching === FALSE)
        {
            $this->CI->smarty->disable_caching();
        }

        // If no file extension dot has been found default to defined extension for view extensions
        if ( !stripos($template, '.') )
        {
            $template = $template.".".$this->CI->smarty->template_ext;
        }

        if ( !empty($this->_module) )
        {
            $template = APPPATH . 'modules/' . $this->_module . '/views/' . $template;
        }

        // If we have variables to assign, lets assign them
        if (!empty($data))
        {
            foreach ($data as $key => $val)
            {
                $this->CI->smarty->assign($key, $val);
            }
        }

        // Get our template data as a string
		log_message('debug', sprintf("Template => template file : %s", $template));
        $template_string = $this->CI->smarty->fetch($template);
		if ($encoding != 'UTF-8') {
			$template_string = mb_convert_encoding($template_string, $encoding, 'UTF-8');
		}
        // If we're returning the templates contents, we're displaying the template
        if ($return === FALSE)
        {
            $this->CI->output->append_output($template_string);
        }

        // We're returning the contents, fo' shizzle
        return $template_string;
    }

    /**
    * Parse
    * Parses a template using Smarty 3 engine
    *
    * @param string $template
    * @param array $data
    * @param boolean $return
    * @param mixed $caching
    */
    public function mail_parse($template, $data = array(), $caching = TRUE)
    {
        // Make sure we have a template, yo.
        if (empty($template))
        {
            return FALSE;
        }

        // If we don't want caching, disable it
        if ($caching === FALSE)
        {
            $this->CI->smarty->disable_caching();
        }

        // If no file extension dot has been found default to defined extension for view extensions
        if ( !stripos($template, '.') )
        {
            $template = $template.".".$this->CI->smarty->template_ext;
        }

        if ( !empty($this->_module) )
        {
            $template = APPPATH . 'modules/' . $this->_module . '/mail_template/' . $template;
        }

        // If we have variables to assign, lets assign them
        if (!empty($data))
        {
            foreach ($data as $key => $val)
            {
                $this->CI->smarty->assign($key, $val);
            }
        }

        // Get our template data as a string
		log_message('debug', sprintf("Mail Template => template file : %s", $template));
        $template_string = $this->CI->smarty->fetch($template);

        // We're returning the contents, fo' shizzle
        return $template_string;
    }


    /**
    * String Parse
    * Parses a string using Smarty 3
    *
    * @param string $template
    * @param array $data
    * @param boolean $return
    * @param mixed $is_include
    */
    function string_parse($template, $data = array(), $return = FALSE, $is_include = FALSE)
    {
        return $this->CI->smarty->fetch('string:'.$template, $data);
    }

    /**
    * Parse String
    * Parses a string using Smarty 3. Never understood why there
    * was two identical functions in Codeigniter that did the same.
    *
    * @param string $template
    * @param array $data
    * @param boolean $return
    * @param mixed $is_include
    */
    function parse_string($template, $data = array(), $return = FALSE, $is_include = false)
    {
        return $this->string_parse($template, $data, $return, $is_include);
    }

}
