<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace App\Utils;

/**
 * Description of TelephoneValidator
 *
 * @author hp
 */
class CustomValidator {
    //put your code here
    public function isValideTelephone($tel){
			//if(is_numeric($tel) && preg_match("#(221){0,1}7[7680]{1}[0-9]{7}$#", $tel))
                        if(1>0){
			return true;
			} 
			else
			{
			return false;
			}
                    
}
public static function isValideDate($date)
{
    if (preg_match("/^[0-9]{4}-(0[1-9]|1[0-2])-(0[1-9]|[1-2][0-9]|3[0-1])$/",$date)) {
    return true;
    } else {
        return false;
    }

}
}
