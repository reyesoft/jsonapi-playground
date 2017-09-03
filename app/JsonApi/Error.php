<?php

namespace App\JsonApi;

use Neomerx\JsonApi\Document\Error as NeomerxError;

class Error extends NeomerxError
{
    /**
     * @var mixed|null
     */
    protected $meta = ['x', 'y'];

    public function __construct(
            $status = null,
            $code = null,
            $title = null,
            $detail = null,
            array $source = null,
            $meta = null
    ) {
        return parent::__construct(
            null,   //idx
            null,   // LinkInterface $aboutLink = null,
            $status,
            $code,
            $title,
            $detail,
            $meta
        );
    }

    /**
     * {@inheritdoc}
     */
    public function getMeta()
    {
        return $this->meta;
    }

    public function setMeta(array $array) {
        $this->meta = $array;
    }
}
