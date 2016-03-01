<?php

namespace example;


class Experiment {
    private $name;
    private $rule;


    /**
     * People constructor.
     * @param string $name Name of the person
     */
    public function __construct($name) {
        $this->name = $name;
        $this->variants = [];
        $this->rule = null;
    }

    public function getVariants() {
        return $this->variants;
    }

    public function addVariant(Variant $varitant) {
        $this->variants[] = $varitant;
    }

    public function addRule(Rule $rule) {
        $this->rule = $rule;
    }

    /**
     * @return Rule
     */
    public function getRule() {
        return $this->rule;
    }

    public function isActive()
    {
        return true;
    }
}