<?php
/**
 * Created by PhpStorm.
 * User: rockj
 * Date: 2/29/16
 * Time: 7:13 PM
 */

namespace example;


class JsonStore implements Storage
{

    private $experiments;

    /**
     * JsonStore constructor.
     * @param $experiments
     */
    public function __construct()
    {
        $this->experiments = [];
    }


    public function getExperiments()
    {
        return $this->experiments;
    }

    public function add($experiment)
    {
        $this->experiments[] = $experiment;
    }
}