<?php
namespace E4uTest\Common;

use PHPUnit\Framework\TestCase;
use E4u\Common\Template;

class TemplateTest extends TestCase
{
    /**
     * @covers Template::wolacz
     * @covers Template::replace
     * @covers Template::merge
     */
    public function testMerge()
    {
        $vars = [ 'name' => 'Karol Cypsalbozyps' ];
        $src = 'Witaj, [[wolacz]]! Twoje imię to: [[name]].';
        $dst = 'Witaj, Karolu! Twoje imię to: Karol Cypsalbozyps.';
        
        $this->assertEquals($dst, Template::merge($src, $vars));
    }
}
