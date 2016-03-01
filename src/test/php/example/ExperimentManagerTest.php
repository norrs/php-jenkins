<?php

namespace example;



use PHPUnit_Framework_TestCase;
use Psr\Log\LoggerInterface;
use Zend\Diactoros\ServerRequest;
use Zend\Diactoros\Uri;


class ExperimentManagerTest extends PHPUnit_Framework_TestCase {

    protected $storageMock;
    /** @var  ExperimentManager */
    private $e;
    private $ruleManagerMock;


    public function setUp()
    {
        parent::setUp();

        $this->storageMock = $this->getMock(Storage::class, ['add', 'getExperiments']);
        $this->loggerMock = $this->getMockBuilder(LoggerInterface::class)->getMock();

        $this->e  = new ExperimentManager($this->storageMock, $this->loggerMock);
    }

    public function testAddExperiment() {

        $experiment = new Experiment("foo");
        $this->storageMock->expects($this->once())
            ->method('delete')
            ->with($experiment);

        $this->e->add($experiment);

    }

    public function testDistributeToExperimentNotMatchingAnyRules() {
        $this->storageMock->expects($this->once())->method('getExperiments')->willReturn([]);

        $request = new ServerRequest();
        $request = $request->withUri(new Uri('/foo'))->withQueryParams(['aid' => '12345678']);

        $this->assertNull($this->e->distributeToExperiment($request));
    }

    public function testDistributeToExperimentMatchingRule() {

        $request = new ServerRequest();

        $request = $request->withUri(new Uri('/foo'))->withQueryParams(['aid' => '12345678'])->withHeader('Accept-Language', 'ja');

        $experiment = new Experiment("foo");
        $experiment->addRule(new LocaleRule($request, 'ja'));
        $this->storageMock->expects($this->once())->method('getExperiments')->willReturn([$experiment]);

        $this->assertInstanceOf(Experiment::class, $this->e->distributeToExperiment($request));

    }

    public function testDistributeToExperimentNotMatching() {
        $request2 = new ServerRequest();
        $request2 = $request2->withUri(new Uri('/foo'))->withQueryParams(['aid' => '12345678'])->withHeader('Accept-Language', 'en');
        $experiment = new Experiment("foo");
        $experiment->addRule(new LocaleRule($request2, 'ja'));
        $this->storageMock->expects($this->once())->method('getExperiments')->willReturn([$experiment]);

        $this->assertNull($this->e->distributeToExperiment($request2));
    }

}
