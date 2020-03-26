<?php

namespace Laijim\PlaybookConclusion\Entity;

/**
 * Class TaskResult
 * @package Laijim\PlaybookConclusion\Entity
 */
class TaskResult
{
    /**
     * @var string
     */
    public $type = "";
    /**
     * @var string
     */
    public $taskName = '';
    /**
     * @var array
     */
    public $parsedResult = [];
    /**
     * @var string
     */
    public $originalOutput = '';
}