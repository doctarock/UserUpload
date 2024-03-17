<?php

require 'foobar.php';

use PHPUnit\Framework\TestCase;

class FooBarTest extends TestCase
{
    public function testOutput()
    {
        ob_start();
        include 'foobar.php';
        $output = ob_get_clean();

        // Split the output into an array of lines
        $lines = explode("\n", $output);

        // Verify the output for each number from 1 to 100
        for ($i = 1; $i <= 100; $i++) {
            $expected = '';

            if ($i % 3 === 0 && $i % 5 === 0) {
                $expected = 'foobar';
            } elseif ($i % 3 === 0) {
                $expected = 'foo';
            } elseif ($i % 5 === 0) {
                $expected = 'bar';
            } else {
                $expected = strval($i);
            }

            $this->assertEquals($expected, $lines[$i - 1]);
        }
    }
}