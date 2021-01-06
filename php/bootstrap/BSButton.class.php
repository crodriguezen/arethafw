<?php
class BSButton {
    private $type     = "button";
    private $class    = "";
    private $text     = "Botón";
    private $bscolor  = "btn-secondary";
    private $id       = "";
    private $name     = "";

    public function __construct($text = "", $color = "", $id = "", $name = "") {
        $this->BSButton($text, $color, $id, $name);
    }

    public function setType($type) {
        $this->type = $type;
    }

    public function setClass($class) {
        $this->class = $class;
    }

    public function setText($text) {
        $this->text = $text;
    }

    public function setBSColor($color) {
        switch ($color) {
               case 'primary'  : $this->bscolor = "btn-primary";   break;
               case 'secondary': $this->bscolor = "btn-secondary"; break;
               case 'success'  : $this->bscolor = "btn-success";   break;
               case 'danger'   : $this->bscolor = "btn-danger";    break;
               case 'warning'  : $this->bscolor = "btn-warning";   break;
               case 'info'     : $this->bscolor = "btn-info";      break;
               case 'light'    : $this->bscolor = "btn-light";     break;
               case 'dark'     : $this->bscolor = "btn-dark";      break;
               case 'link'     : $this->bscolor = "btn-link";      break;
           }   
    }

    public function BSButton($text = "", $color = "", $id = "", $name = "") {

        if (trim($text) != "") {
            $this->text = $text;
        }

        if (trim($color) != "") {
            $this->setBSColor($color);
        }

        if (trim($id) != "") {
            $this->id = $id;
        }

        if (trim($name) != "") {
            $this->name = $name;
        }
    }

    public function getHTML() {
        if ($this->class != "") {
            return '<button type="' . $this->type  .'" class="' . $this->class . '" id="' . $this->id . '" name="' . $this->name . '">' . strtoupper($this->text) . '</button>';
        } else {
            return '<button type="' . $this->type  .'" class="btn ' . $this->bscolor . '" id="' . $this->id . '" name="' . $this->name . '">' . strtoupper($this->text) . '</button>';
        }
    }
}
?>