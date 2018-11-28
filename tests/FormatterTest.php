<?php

namespace Tests;

use PHPUnit\Framework\TestCase;
use ExtPHP\Formatter\Formatter;

class FormatterTest extends TestCase
{
    public function testSample()
    {
        $data = [
            'id'            => '153',
            'first_name'    => 'mary j.',
            'last_name'     => 'blige',
            'female'        => 1,
            'married'       => 'false',
            'cash'          => '2761,32'
        ];

        $rules = [
            'id'            => 'cast:int',
            'first_name'    => 'ucwords',
            'last_name'     => 'strtoupper',
            'female'        => 'cast:ebool',
            'married'       => 'cast:ebool',
            'cash'          => 'str_replace:\,,.|cast:float|number_format:4,\,,.'
        ];

        $formatter = new Formatter($data, $rules);
        $formatted = $formatter->format();

        $this->assertTrue(is_int($formatted['id']));

        $this->assertTrue(is_string($formatted['first_name']));
        $this->assertEquals('Mary J.', $formatted['first_name']);

        $this->assertEquals('BLIGE', $formatted['last_name']);

        $this->assertTrue(is_bool($formatted['female']));
        $this->assertTrue($formatted['female']);

        $this->assertTrue(is_bool($formatted['married']));
        $this->assertFalse($formatted['married']);

        $this->assertEquals('2.761,3200', $formatted['cash']);

        var_dump($formatted);
    }
}