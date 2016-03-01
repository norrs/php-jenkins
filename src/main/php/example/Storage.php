<?php

namespace example;


interface Storage
{

    public function getExperiments();

    public function add($experiment);
}