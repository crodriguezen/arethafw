<?php
class AFPlugin {

	private $jsFiles = array();
	private $cssFiles = array();

	public function addJS($js) {
		$this->jsFiles[] = $js;
	}

	public function addCSS($css) {
		$this->cssFiles[] = $css;
	}

	public function init($json) {
		$config = json_decode($json);

		$script  = '<script type="text/javascript" id="af-plugins">';
		if (count($config->javascript) > 0) {
			$script .= 'loadScripts(';
			$script .= '	[';
			foreach ($config->javascript as $file) {
				$scripts .= '	"' . $file . '",';
			}
			$script .= substr($scripts, 0, strlen($scripts) -1);
			$script .= '	],';
			$script .= '	function() { console.log("ArethaFW Plugins Loaded!"); }';
			$script .= ');';
		}
		$script .= '</script>';

		foreach ($config->css as $file) {
			
		}


	}


}
?>