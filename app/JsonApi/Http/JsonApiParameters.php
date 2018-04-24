<?php
/**
 * Copyright (C) 1997-2018 Reyesoft <info@reyesoft.com>.
 *
 * This file is part of JsonApiPlayground. JsonApiPlayground can not be copied and/or
 * distributed without the express permission of Reyesoft
 */

declare(strict_types=1);

namespace App\JsonApi\Http;

use App\JsonApi\Core\QueryParser;

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

    public function __construct(QueryParser $parameters)
    {
        $this->sortParameters = $parameters->getSorts() ?? [];
        $this->filteringParameters = $parameters->getFiltering();
        // @todo
        // $this->unrecognizedParams = $parameters->getUnrecognized() ?? [];
        $this->unrecognizedParams = [];
        $this->includePaths = $this->iterableToArray($parameters->getIncludes());

        // page
        // @todo
        // $this->pageNumber = $parameters->getPagination()['number'] ?? 1;
        $this->pageNumber = 1;
        // @todo
        // $this->pageSize = $parameters->getPagination()['size'] ?? config('paginate.general', 5);
        $this->pageSize = config('paginate.general', 5);
        $pageSizesAllowed = config('paginate.allowed', null);
        if ($pageSizesAllowed !== null && !isset($pageSizesAllowed[$this->pageSize])) {
            throw \Exception('pagesize param no valid. Accepted values: ' . implode(pageSizesAllowed, ', ') . '.');
        }
    }

    /**
     * @param iterable $iterable
     *
     * @return array
     */
    private function iterableToArray(iterable $iterable): array
    {
        $result = [];
        foreach ($iterable as $key => $value) {
            $result[$key] = $value instanceof Generator ? $this->iterableToArray($value) : $value;
        }

        return $result;
    }

    public function getIncludePaths(): array
    {
        return $this->includePaths;
    }

    public function getFilteringParameters()
    {
        return $this->filteringParameters;
    }

    public function getSortParameters(): array
    {
        return $this->sortParameters;
    }

    public function getPageSize(): int
    {
        return $this->pageSize;
    }

    public function getPageNumber(): int
    {
        return $this->pageNumber;
    }
}
