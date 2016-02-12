<?php 

class IndexTest extends \PHPUnit_Framework_TestCase 
{
    public function testHello()
    {
        $_GET['name'] = 'paul';
        // start buffering
        ob_start();
        include 'index.php';
        $content = ob_get_clean();
        
        $this->assertEquals('hello bob', $content);
    }
}