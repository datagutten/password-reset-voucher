<?php
/**
 * Created by PhpStorm.
 * User: Anders
 * Date: 29.04.2019
 * Time: 19.40
 */
require 'vendor/autoload.php';
$reset=new reset_password();
echo $reset->reset_password_sms();