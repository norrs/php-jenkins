<?php

namespace example;

use PHPUnit_Framework_TestCase;
use Psr\Log\LoggerInterface;
use Tracy\Logger;

/**
 * Class ExampleTest
 * @package example
 *
 * Test doubles
 */
class ExampleTest extends PHPUnit_Framework_TestCase {
    public function setUp() {


    }

    public function testDummy() {
        $logger = $this->getMock(LoggerInterface::class);
        
        $this->assertNull($logger->emergency("foo"));
    }

    public function testStub() {
        $logger = $this->getMock('Logger');
        $logger
            ->expects($this->any())
            ->method('getLevel')
            ->will($this->returnValue(Logger::INFO));
    }


    public function testFake() {
        $urlsByRoute = [
            'index' => '/',
            'about_me' => '/about-me'
        ];

        $urlGenerator = $this->getMock('UrlGenerator');
        $urlGenerator
            ->expects($this->any())
            ->method('generate')
            ->will($this->returnCallback(
                function ($routeName) use ($urlsByRoute) {
                    if (isset($urlsByRoute[$routeName])) {
                        return $urlsByRoute[$routeName];
                    }
                    throw new \InvalidArgumentException("Unknown route");
                }
            ));

        $urlGenerator
            ->expects($this->any())
            ->method('match')
            ->willReturnArgument(0);

    }

    public function testSpy() {
        $collectedMessages = array();

        $logger = $this->getMock('Logger');
        $logger
            ->expects($this->any())
            ->method('debug')
            ->will($this->returnCallback(
                function ($message) use (&$collectedMessages) {
                    $collectedMessages[] = $message;
                }
            )
        );
    }

    public function testMock() {
        $logger = $this->getMock('Logger');
        $logger
            ->expects($this->at(0))
            ->method('debug')
            ->with('A debug message');
        $logger
            ->expects($this->at(1))
            ->method('info')
            ->with('An info message');
    }
}