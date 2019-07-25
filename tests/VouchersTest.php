<?php
/**
 * Created by PhpStorm.
 * User: abi
 * Date: 25.07.2019
 * Time: 10:52
 */

use askommune\PasswordResetVoucher\Vouchers;
use PHPUnit\Framework\TestCase;

class VouchersTest extends TestCase
{
    public function setUp(): void
    {
        if(!file_exists(__DIR__.'/../vouchers'))
            mkdir(__DIR__.'/../vouchers');
        copy(__DIR__.'/test_data/test_vouchers.txt', __DIR__.'/../vouchers/test_vouchers.txt');
    }

    public function testCheck_voucher()
    {
        $file = Vouchers::check_voucher('cuza224');
        $this->assertIsNotBool($file);
        $path = realpath(__DIR__.'/..');
        $this->assertEquals(realpath($path.'/vouchers/test_vouchers.txt'), realpath($file));
    }

    public function testCheck_voucher_invalid()
    {
        $file = Vouchers::check_voucher('asdf123');
        $this->assertIsBool($file);
        $this->assertEquals(false, $file);
    }

    public function testRandom()
    {
        $random = Vouchers::random(4);
        $this->assertIsString($random);
        $this->assertEquals(4, strlen($random));
    }
    public function tearDown(): void
    {
        unlink(__DIR__.'/../vouchers/test_vouchers.txt');
    }
}
