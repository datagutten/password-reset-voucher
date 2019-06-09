<?php
/**
 * Created by PhpStorm.
 * User: Anders
 * Date: 29.04.2019
 * Time: 16.57
 */

/**
 * Generate a random password
 * @param int $length Password length
 * @return string Generated password
 */
function random($length = 8)
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
//Lag koder for 10 sider med 84 koder per side
if(isset($argv[1]))
    $pages=$argv[1];
else
    $pages=10;
$vouchers = '';
for($i=1; $i<=84*$pages; $i++)
{
    $vouchers.= random(4).mt_rand(111,999)."\n";
}
$file=sprintf('vouchers/%s.txt', $argv[2]);

if(!file_exists('vouchers'))
    mkdir('vouchers');

if(file_exists($file))
{
    $oldfile = $file.'_old_'.date('Y-m-d');
    rename($file, $oldfile);
    printf("Existing file %s renamed to %s\n", $file, $oldfile);
}


file_put_contents($file, $vouchers);
printf("Wrote %d vouchers to %s\n", 84*$pages, $file);