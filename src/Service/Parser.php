<?php

namespace  Laijim\PlaybookConclusion\Service;
/**
 * Interface Parser
 * @package Laijim\PlaybookConclusion\Service
 */
interface Parser
{
    /**
     * @param $result
     * @return mixed
     */
    public function parse($result);

    /**
     * @param array $data
     * @return mixed
     */
    public function parseTaskExecuteResult(array $data);

}