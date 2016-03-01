<?php

namespace example;


use Psr\Http\Message\ServerRequestInterface;

class ExperimentManager {

    private $storage;


    public function __construct(Storage $storage) {
        $this->storage = $storage;

    }

    public function add(Experiment $experiment) {
        $this->storage->add($experiment);
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