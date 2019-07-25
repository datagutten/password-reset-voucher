<?php
/**
 * Created by PhpStorm.
 * User: Anders
 * Date: 29.04.2019
 * Time: 16.57
 */
require 'vendor/autoload.php';

//Lag koder for 10 sider med 84 koder per side
if(isset($argv[1]))
    $pages=$argv[1];
else
    $pages=10;
$vouchers = '';
for($i=1; $i<=84*$pages; $i++)
{
    $vouchers.= \askommune\PasswordResetVoucher\Vouchers::random(4).mt_rand(111,999)."\n";
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