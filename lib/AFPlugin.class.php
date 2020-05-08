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

		foreach ($config->javascript as $file) {
			
		}

		foreach ($config->css as $file) {
			
		}

		
	}


}
?>