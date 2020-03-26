<?php

namespace Laijim\PlaybookConclusion;

use Laijim\PlaybookConclusion\Impl\RegExParser;
use Laijim\PlaybookConclusion\Service\Parser;

/**
 * Class Conclusion
 * @package Laijim\PlaybookConclusion
 */
class Conclusion
{
    /**
     * @var Parser | RegExParser
     */
    private $parser;

    /**
     * Conclusion constructor.
     * @param Parser $parser
     */
    public function __construct(Parser $parser)
    {
        $this->parser = $parser;
    }

    /**
     * @param $result
     * @return Entity\Result
     * @throws Exception\ParameterIllegalException
     */
    public function parse($result)
    {
        return $this->parser->parse($result);
    }
}