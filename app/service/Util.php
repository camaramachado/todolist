<?php

/**
 * Util
 *
 * Utilities functions
 * @package service
 * @author Ricardo Câmara (camaramachado@gmail.com)
 * @version 1.0
 */
class Util
{
	
	/**
     * method formatDate()
     * format date on TDataGrids for brasilian mask
     * @param $value value of the column want to format
     * @param $object data object stdClass with the data represented in the datagrid line
     * @param $row datagrid's row
     * @param $seconds show the hour's seconds 
     * @returns $value date in brazilian format
     */
    public static function formatDate($value, $object = null, $row = null, $seconds = false): string
    {
		if($value and $seconds)
		{
			return TDate::date2br($value) . substr($value, 10);
		}
		elseif($value)
		{
			return TDate::date2br($value) . substr($value, 10, 6);
		}		
	}
    
    
    /**
     * method debug()
     * Shows human-readable information about a variable
     * @param $value the expression to be printed (string, array, double...)
     * @param $die inform if the execution to be terminated
     * @returns void
     */
    public static function debug(mixed $value, $die = FALSE): void
    {        
        echo '<pre>';
        print_r($value);
        echo '</pre>';	
        if($die)
        {
			die;
		}					
    }

	
    /**
     * Method cleanFilters
     * clears the filters used on the TPage and calls your onReload
     * @returns void
     */
    public static function cleanFilters(): void
    {
        $backtrace = debug_backtrace();  //backtrace element 0 will point to the current function
        array_shift($backtrace);  //remove the first element
        $classe = $backtrace[0]['class'];  //contains the name of the class that called this function
        
        if( is_subclass_of($classe, 'TPage') )
        {
            $param               = array();
            $param['offset']     = 0;
            $param['first_page'] = 1;
        
            TSession::setValue($classe . '_filter_data', '');
            TSession::setValue($classe . '_filters', ''); 
        
            TApplication::loadPage($classe, 'onReload', $param);
        }
    }
    
    
    /**
     * Method generatePassword()
     * @param $length int amount of characters
     * @param $capital boolean if it will have capital letters
     * @param $numeros boolean if it will have numbers
     * @param $simbolos boolean if it will have simbols
     * @returns string password generated
     */
    public static function generatePassword($length = 8, $capital = true, $numbers = true, $simbols = false): string
    {
        $lmin       = 'abcdefghijklmnopqrstuvwxyz';
        $lmai       = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $num        = '1234567890';
        $simb       = '!@#$%*-';
        $return     = '';
        $characters = '';
        
        $characters               .= $lmin;
        if ($capital) $characters .= $lmai;
        if ($numbers) $characters .= $num;
        if ($simbols) $characters .= $simb;
        
        $len = strlen($characters);
        for ($n = 1; $n <= $length; $n++) 
        {
            $rand    = mt_rand(1, $len);
            $return .= $characters[$rand-1];
        }
        
        return $return;
    }
    
    
    /**
     * method formatImage()
     * returns the formatted image defining width and height
     * @param $value string path to image file
     * @param $object data object stdClass with the data represented in the datagrid line
     * @param $row datagrid's row
     * @returns string formated tag img
     */	
	public static function formatImage($value, $object, $row): string
	{				
		if($value)
		{
			$img        = new TElement('img');
            $img->src   = $value;
            $img->style = 'max-width:200px; height:150px; border-radius: 6px; border: 1px solid #ddd;';
            return $img;
		}					
	}
	
    
}

