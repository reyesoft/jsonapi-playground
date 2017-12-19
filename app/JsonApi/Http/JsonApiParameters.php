<?php

namespace App\JsonApi\Http;

use Neomerx\JsonApi\Encoder\Parameters\EncodingParameters;

class JsonApiParameters
{
    /**
     * @var SortParameterInterface[]|null
     */
    private $sortParameters;

    /**
     * @var int
     */
    private $pageNumber;

    /**
     * @var int
     */
    private $pageSize;

    /**
     * @var array
     */
    private $filteringParameters;

    /**
     * @var array
     */
    private $includePaths;

    /**
     * @var array
     */
    private $unrecognizedParams;

    public function __construct(EncodingParameters $parameters) {
        $this->sortParameters = $parameters->getSortParameters() ?? [];
        $this->filteringParameters = $parameters->getFilteringParameters() ?? [];
        $this->unrecognizedParams = $parameters->getUnrecognizedParameters() ?? [];
        $this->includePaths = $parameters->getIncludePaths() ?? [];

        // page
        $this->pageNumber = $parameters->getPaginationParameters()['number'] ?? 1;
        $this->pageSize = $parameters->getPaginationParameters()['size'] ?? 5;
    }

    public function getIncludePaths(): array {
        return $this->includePaths;
    }

    public function getFilteringParameters() {
        return $this->filteringParameters;
    }

    public function getSortParameters(): array {
        return $this->sortParameters;
    }

    public function getPageSize(): int {
        return $this->pageSize;
    }

    public function getPageNumber(): int {
        return $this->pageNumber;
    }
}
