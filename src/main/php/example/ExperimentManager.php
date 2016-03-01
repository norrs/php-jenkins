<?php

namespace example;


use Psr\Http\Message\ServerRequestInterface;
use Psr\Log\LoggerInterface;

class ExperimentManager {

    private $storage;
    private $logger;


    public function __construct(Storage $storage, LoggerInterface $logger) {
        $this->storage = $storage;
        $this->logger = $logger;

    }

    public function add(Experiment $experiment) {
        $this->storage->add($experiment);
        $this->logger->info("Added an experiment " . $experiment->getName());
    }

    /**
     * @return Experiment[]
     */
    protected function getLiveExperiments() {
        $live = [];
        /** @var Experiment $experiment */
        foreach ($this->storage->getExperiments() as $experiment) {
            if ($experiment->isActive()) {
                $live[] = $experiment;
            }
        }
        return $live;
    }

    public function distributeToExperiment(ServerRequestInterface $request) {

        if (array_key_exists('aid', $request->getQueryParams())) {
            $argument = $request->getQueryParams()['aid'];

            foreach ($this->getLiveExperiments() as $experiment) {
                if ($experiment->getRule()->evaluate()) {
                    return $experiment;
                }
            }
        }
        return null;
    }

}