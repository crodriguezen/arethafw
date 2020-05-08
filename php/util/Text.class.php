<?php
namespace aretha\php\util;

class Text {
    private static $replaceChar = '';
    
	public static function removeSpecialChars($string) {
        // Removes special chars
        return preg_replace('/[^A-Za-z0-9\-]/', Text::$replaceChar, $string);
        
    }
    
    public static function replaceSpecialChars($string, $char = null) {
        if ($char !== null) {
	        // Replace special chars
            return preg_replace('/[^A-Za-z0-9\-]/', $char, $string);

        }
	    // Replace special chars
        return preg_replace('/[^A-Za-z0-9\-]/', Text::$replaceChar, $string);
    }
    
    public static function replaceSpacesWithHyphens($string) {
	    // Replaces all spaces with hyphens
        return str_replace(' ', '-', $string);
    }
    
    public static function replaceMultipleHyphensWithSingleOne() {
	    // Replaces multiple hyphens with single one
        return preg_replace('/-+/', '-', $string);
    }
    
}
?>