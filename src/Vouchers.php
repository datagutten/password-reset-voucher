<?php
/**
 * Created by PhpStorm.
 * User: abi
 * Date: 25.07.2019
 * Time: 10:16
 */

namespace askommune\PasswordResetVoucher;


class Vouchers
{
    /**
     * Generate a random password
     * @param int $length Password length
     * @return string Generated password
     */
    public static function random($length = 8)
    {
        //http://php.net/manual/en/function.mt-rand.php#106645
        $chars = 'bcdfghjklmnprstvwxzaeiou';
        $result='';
        for ($p = 0; $p < $length; $p++)
        {
            $result .= ($p%2) ? $chars[mt_rand(19, 23)] : $chars[mt_rand(0, 18)];
        }
        return $result;
    }

    /**
     * Validate a voucher and remove it from the file
     * @param string $voucher Voucher to be checked
     * @return bool|string Return bool if the voucher is invalid or a string with the file it was found in
     */
    public static function check_voucher($voucher)
    {
        $files = glob(__DIR__.'/../vouchers/*.txt');

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
}