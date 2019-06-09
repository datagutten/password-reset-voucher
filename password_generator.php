<?php
/**
 * Created by PhpStorm.
 * User: Anders
 * Date: 29.04.2019
 * Time: 17.37
 */

/**
 * @param array $words Array with words to be used
 * @param int $num_low
 * @param int $num_high
 * @return string
 */
function generate_password($words=null, $num_low = 100, $num_high = 999)
{
    if(!is_array($words))
        $words=array('Skorpion','Flaggermus','Edderkopp','Grevling','Moskus','Leopard','Tiger', 'Klapperslange'); //Ordliste for pasord (minst 5 tegn)

    $word_key = mt_rand(0,count($words)-1);
    return $words[$word_key].mt_rand($num_low,$num_high); //Lag tilfeldig passord med ordliste og tall
}