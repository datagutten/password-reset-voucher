<?php
/**
 * Created by PhpStorm.
 * User: Anders
 * Date: 29.04.2019
 * Time: 17.04
 */

/**
 * Validate a voucher and remove it from the file
 * @param string $voucher Voucher to be checked
 * @return bool|string Return bool if the voucher is invalid or a string with the file it was found in
 */
function check_voucher($voucher)
{
    $files = glob('vouchers/*.txt');

    foreach ($files as $file) {
        $vouchers = file_get_contents($file);
        if(preg_match($format = sprintf('/(?:^|\s)%s(?:\s|$)/', $voucher), $vouchers, $matches))
        {
            $vouchers = str_replace($matches[0], "\n", $vouchers);
            file_put_contents($file, $vouchers);
            return $file;
        }
    }
    return false;
}