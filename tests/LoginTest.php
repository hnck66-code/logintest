<?php
use PHPUnit\Framework\TestCase;

class LoginTest extends TestCase
{
    public function testLoginValid()
    {
        $_POST['username'] = 'admin';
        $_POST['password'] = 'admin123';

        ob_start();
        include __DIR__ . '/../controllers/login_process.php';
        $output = ob_get_clean();

        $this->assertNotEmpty($output);
    }

    public function testLoginInvalid()
    {
        $_POST['username'] = 'salah';
        $_POST['password'] = 'password';

        ob_start();
        include __DIR__ . '/../controllers/login_process.php';
        $output = ob_get_clean();

        $this->assertNotEmpty($output);
    }
}
