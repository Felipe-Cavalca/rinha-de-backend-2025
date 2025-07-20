<?php

namespace Bifrost\Model;

use Bifrost\Core\Cache;
use Bifrost\Core\Database;
use Bifrost\Core\Settings;
use Bifrost\DataTypes\Tax;
use Bifrost\DataTypes\Url;
use Bifrost\DataTypes\UUID;

class Processor
{

    /**
     * Retorna um processador pelo UUID
     * @param UUID $id
     * @return array|null
     */
    public static function get(UUID $id): ?array
    {
        $cache = new Cache();
        $settings = new Settings();
        $keyCache = Cache::buildCacheKey(entity: 'processor', conditions: ["id" => (string) $id]);

        $result = $cache->get($keyCache, function () use ($id) {
            $database = new Database();
            return $database->query(
                from: 'processors',
                where: ['id' => (string) $id],
            );
        }, $settings->CACHE_QUERY_TIME);

        if (!empty($result)) {
            foreach ($result as &$processor) {
                $processor['id'] = new UUID($processor['id']);
                $processor['tax'] = isset($processor['tax']) ? new Tax($processor['tax']) : null;
                $processor['url'] = isset($processor['url']) ? new Url($processor['url']) : null;
            }
        }

        return $result;
    }

    /**
     * Retorna uma lista de UUID de processadores ordenados pela menor taxa
     * @return UUID[]|null
     */
    public static function lowerTax(): ?array
    {
        $cache = new Cache();

        $result = $cache->get("processor:orderLowerTax", function () {
            $database = new Database();
            return $database->query(
                select: ["id"],
                from: 'processors',
                order: 'tax ASC',
            );
        });

        if (!empty($result)) {
            foreach ($result as &$processor) {
                $processor['id'] = new UUID($processor['id']);
            }
        }

        return $result;
    }
}
