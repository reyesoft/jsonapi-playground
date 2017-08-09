<?php
declare(strict_types=1);

namespace App\Yin\Utils;

abstract class AbstractRepository
{
    /**
     * @return array|null
     */
    public static function getItemById(string $id, array $items)
    {
        foreach ($items as $item) {
            if (isset($item["id"]) && $item["id"] === $id) {
                return $item;
            }
        }

        return null;
    }

    public static function getItemsByIds(array $ids, array $items): array
    {
        $result = [];

        foreach ($ids as $id) {
            foreach ($items as $item) {
                if (isset($item["id"]) && $item["id"] === $id) {
                    $result[] = $item;
                }
            }
        }

        return $result;
    }
}
