<?php
/**
 * $Id: stylesheetparser.class.inc.php 271 2008-04-23 21:38:56Z maff $
 *
 * @title:		StylesheetParser
 * @author:		Mathias Geat <mathias@ailoo.net>
 * @version:	1.1
 * @license:	GPL
 */
class StylesheetParser {
	
	private $values = array();
	private $delimiters = array(
							'left' => '{{',
							'right' => '}}'
						);
	private $stylesheets = array();
	private $style;
	
	public $sectiondelimiter;
	public $compressAll = false;
	public $compressPatterns = array();
	public $nonCompressPatterns = array();
	public $showSections = true;
	
	/**
	 * constructor
	 *
	 * @return StylesheetParser
	 */
	public function __construct()
	{
		$this->sectiondelimiter = '/* --------------------------------------------------------------';
		$this->sectiondelimiter .= "\n\n\t";
		$this->sectiondelimiter .= 'section';
		$this->sectiondelimiter .= "\n\n";
		$this->sectiondelimiter .= '-------------------------------------------------------------- */';
		$this->sectiondelimiter .= "\n\n";
	}
	
	/**
	 * set left and right delimiters for the replacement tags - defaults to {{ and }}
	 *
	 * @param string $left
	 * @param string $right
	 * @return bool
	 */
	public function setDelimiters($left, $right)
	{
		if(!empty($left) && !empty($right))
		{
			$this->delimiters = array(
									'left' => $left,
									'right' => $right
								);
			return true;
		}		
		return false;
	}
	
	/**
	 * add a stylesheet file
	 *
	 * @param string $stylesheet - the path to the file
	 * @param string $name - optional name to define a section header
	 * @return bool
	 */
	public function addStylesheet($stylesheet, $name = '')
	{
		if(file_exists($stylesheet) && is_readable($stylesheet))
		{
			$i = count($this->stylesheets);
			$this->stylesheets[$i]['file'] = $stylesheet;
			if(!empty($name)) $this->stylesheets[$i]['name'] = $name;
			return true;
		}
		return false;
	}
	
	/**
	 * add a stylesheet directory
	 * files will be sorted by filename, so if you want them to get included in a specific order
	 * you have to name them by the order you want (e.g. 01_reset.css, 02_html.css, 03_layout.css).
	 *
	 * @param string $dir - directory to be scanned
	 * @param bool $recursive - scan recursively?
	 * @return void
	 */
	public function addStylesheetDir($dir, $recursive = false)
	{
		$stylesheets = $this->_scanCssDir($dir, $recursive);		
		if(is_array($stylesheets) && count($stylesheets) > 0)
		{
			foreach($stylesheets as $stylesheet)
			{
				$this->addStylesheet($stylesheet);
			}
		}
	}
	
	/**
	 * add one specific key/value pair (e.g. red/#ff0000)
	 *
	 * @param string $key - e.g. red
	 * @param string $value - e.g. #ff0000
	 * @return bool
	 */
	public function addValue($key, $value)
	{
		if(!empty($key))
		{
			$this->values[$key] = $value;
			return true;
		}
		return false;
	}
	
	/**
	 * add an one-dimensional array of value pairs
	 *
	 * @param array $array
	 * @return bool
	 */
	public function addValues($array)
	{
		if(is_array($array) && count($array) > 0)
		{
			foreach($array as $key => $value)
			{
				if(!is_array($key) && !is_array($value) && !empty($key))
				{
					$this->addValue($key, $value);
				}
			}
			return true;
		}
		return false;
	}
	
	/**
	 * scan a directory for css files recursively
	 *
	 * @param string $dir
	 * @param bool $recursive
	 * @param array $filearray
	 * @return array
	 */
	private function _scanCssDir($dir, $recursive = true, $filearray = array())
	{
		if(substr($dir, strlen($dir)-1, strlen($dir)) != '/') $dir .= '/';
		
		if(is_dir($dir))
		{
			$dirlist = opendir($dir);
			while ($file = readdir($dirlist))
			{
				$i = count($filearray);
				if($file != '.' && $file != '..' && substr($file, 0, 1) != '.')
				{
					$newpath = $dir.$file;

					if (is_dir($newpath))
					{
						if($recursive === true)
						{
							$filearray = $this->_scanCssDir($newpath, $recursive, $filearray);
						}
					}
					else
					{
						if(file_exists($newpath) && is_readable($newpath))
						{
							if(substr($newpath, strlen($newpath)-4, strlen($newpath)) == '.css')
							{
								$filearray[$i] = $newpath;
							}
						}
					}
					
				}
			}
			closedir($dirlist);
			
			sort($filearray);
			return $filearray;
		}
		return false;
	}
	
	/**
	 * replaces placeholders with values
	 *
	 * @return void
	 */	
	private function _replaceValues()
	{
		if(is_array($this->values) && count($this->values) > 0)
		{
			foreach($this->values as $key => $value)
			{
				$search = $this->delimiters['left'].$key.$this->delimiters['right'];
				$this->style = str_replace($search, $value, $this->style);
			}
		}	
	}
	
	/**
	 * removes comments, white-space and line-breaks from code
	 * thanks to http://exscale.se/archives/2008/01/15/css-constants-and-compression-php-class/
	 *
	 * @param string $style
	 * @return string
	 */
	private function _compress($style)
	{
		$style = preg_replace('!/\*[^*]*\*+([^/][^*]*\*+)*/!', '', $style);
		$style = str_replace(array("\r\n", "\r", "\n", "\t", '  ', '    ', '    '), '', $style);
		$style = str_replace('{ ', '{', $style);
		$style = str_replace(' }', '}', $style);
		$style = str_replace('; ', ';', $style);
		return $style;
	}
	
	/**
	 * render the resulting stylesheet
	 * if you set the return parameter to true, the function will return the resulting string
	 * else it will set the corrent header and output the stylesheet
	 *
	 * @param bool $return
	 * @return mixed
	 */
	public function Render($return = false)
	{
		if(count($this->stylesheets) > 0)
		{
			if($this->compressAll)
			{
				$this->compressPatterns[] = '/.*/';
			}
		
			foreach($this->stylesheets as $stylesheet)
			{
				$compressFile = false;
				if(is_array($this->compressPatterns) && count($this->compressPatterns) > 0)
				{
					foreach($this->compressPatterns as $pattern)
					{
						if(preg_match($pattern, $stylesheet['file']))
						{
							$compressFile = true;
						}
					}
				}
				
				if($compressFile || $this->compressAll)
				{
					if(is_array($this->nonCompressPatterns) && count($this->nonCompressPatterns) > 0)
					{
						foreach($this->nonCompressPatterns as $pattern)
						{
							if(@preg_match($pattern, $stylesheet['file']))
							{
								$compressFile = false;
							}
						}
					}
				}
				
				$style = '';			
				if($this->showSections && !$compressFile)
				{
					if(!empty($stylesheet['name']))	$section = str_replace('section', $stylesheet['name'], $this->sectiondelimiter);
					else $section = str_replace('section', $stylesheet['file'], $this->sectiondelimiter);
					$style .= $section;
				}
				
				$style .= file_get_contents($stylesheet['file']);
				if($compressFile) 
				{
					$style = $this->_compress($style);
				}
				else
				{
					$style = "\n\n" . $style . "\n\n";
				}
				
				$this->style .= $style;
			}
			
			if(!empty($this->style))
			{
				$this->_replaceValues();
				
				if($return === true)
				{
					return $this->style;
				}
				else
				{
					header("Content-type: text/css");
					echo $this->style;
				}
			}
		}
		return false;
	}
}