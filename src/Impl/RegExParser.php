<?php

namespace Laijim\PlaybookConclusion\Impl;

use Laijim\PlaybookConclusion\Entity\Result;
use Laijim\PlaybookConclusion\Entity\TaskResult;
use Laijim\PlaybookConclusion\Exception\ParameterIllegalException;
use Laijim\PlaybookConclusion\Service\Parser;

/**
 * Class RegExParser
 * @package Laijim\PlaybookConclusion\Impl
 */
class RegExParser implements Parser
{

    /**
     * @var Result
     */
    private $result;

    /**
     * RegExParser constructor.
     * @param Result $result
     */
    public function __construct(Result $result)
    {
        $this->result = $result;
    }

    /**
     * @var string
     */
    private $parseSegment = "#^(\s*)\n#m";
    /**
     * @var string
     */
    private $parseContent = '#^(?<type>[A-Z\s]+)\s(?<name>\[[a-zA-Z0-9\s]+\])?\s?\*+(?<result>.*)?$#ms';
    /**
     * @var string
     */
    private $parseHostRunResult = '#^(?<flag>[a-z]+):\s\[(?<host>[^\]]+)\]#m';
    /**
     * @var string
     */
    private $parseStatisticsResult = '#^(?<host>[^\s]+)\s+:\sok=(?<ok>\d)+\s+changed=(?<changed>\d+)\s+unreachable=(?<unreachable>\d+)\s+failed=(?<failed>\d+)$#m';

    /**
     * @param $result
     * @return Result
     * @throws ParameterIllegalException
     */
    public function parse($result)
    {
        if (empty($result)) {
            throw new ParameterIllegalException("result is empty!");
        }
        $segments = preg_split($this->parseSegment, $result);

        if (empty($segments)) {
            throw new ParameterIllegalException("Incorrect data!");
        }

        $this->ParseRow($segments);

        return $this->result;
    }


    /**
     * @return Result
     */
    public function getResult()
    {
        return $this->result;
    }

    /**
     * @param array $segmentRow
     * @throws ParameterIllegalException
     */
    public function parseTaskExecuteResult(array $segmentRow)
    {
        $resultList = [];
        if (!array_key_exists('result', $segmentRow)) {
            throw new ParameterIllegalException("can't fetch result!");
        }
        $result = $segmentRow['result'];
        preg_match_all($this->parseStatisticsResult, $result, $match);

        $hosts = $match['host'];
        $ok = $match['ok'];
        $changed = $match['changed'];
        $unreachable = $match['unreachable'];
        $failed = $match['failed'];

        $hostCount = count($hosts);
        for ($i = 0; $i < $hostCount; $i++) {
            if ($failed[$i] > 0 || $unreachable[$i] > 0) {
                $this->result->finalResult = false;
            }

            $resultList[$hosts[$i]] = [
                "ok" => $ok[$i],
                "changed" => $changed[$i],
                "unreachable" => $unreachable[$i],
                "failed" => $failed[$i],
            ];
        }

        $this->result->statistics = $resultList;
    }


    /**
     * @param array $segment
     * @throws ParameterIllegalException
     */
    public function ParseRow(array $segment): void
    {

        foreach ($segment as $segmentRow) {

            preg_match($this->parseContent, $segmentRow, $segmentAnalyzeData);

            if (empty($segmentAnalyzeData)) continue;

            if (!array_key_exists('type', $segmentAnalyzeData)) continue;

            if (in_array($segmentAnalyzeData['type'], ['PLAY'])) continue;

            if ($segmentAnalyzeData['type'] === 'PLAY RECAP') {
                $this->parseTaskExecuteResult($segmentAnalyzeData);
                continue;
            }
            $taskExecResult = new TaskResult();

            $taskExecResult->type = $segmentAnalyzeData['type'];
            $taskExecResult->taskName = $segmentAnalyzeData['name'];
            $taskExecResult->parsedResult = [];
            $taskExecResult->originalOutput = $segmentRow;

            $nodeInfo = $segmentAnalyzeData['result'];
            preg_match_all($this->parseHostRunResult, $nodeInfo, $allHostsResult);

            $flagLists = $allHostsResult['flag'];
            $hostLists = $allHostsResult['host'];
            $hostCount = count($flagLists);
            for ($i = 0; $i < $hostCount; $i++) {
                $taskExecResult->parsedResult[$hostLists[$i]] = $flagLists[$i];
            }

            $this->result->verbose[] = $taskExecResult;

        }
    }

}