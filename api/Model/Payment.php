<?php

namespace Bifrost\Model;

use Bifrost\Class\Payment as ClassPayment;
use Bifrost\Class\Processor;
use Bifrost\Core\Cache;
use Bifrost\Core\Database;
use Bifrost\Core\Settings;
use Bifrost\DataTypes\DateTime;
use Bifrost\DataTypes\Money;
use Bifrost\DataTypes\UUID;

class Payment
{

    /**
     * Retorna os dados do pagamento pelo UUID
     * @param UUID $id
     * @return array|null
     */
    public static function get(UUID $id): array
    {
        $cache = new Cache();
        $settings = new Settings();
        $keyCache = Cache::buildCacheKey(entity: "payment", conditions: ["id" => (string) $id]);

        $result = $cache->get($keyCache, function () use ($id) {
            $database = new Database();
            return $database->query(
                select: "*",
                from: "payments",
                where: ["id" => (string) $id],
            );
        }, $settings->CACHE_QUERY_TIME);

        if($result === false){
            return [];
        }

        $result = $result[0];
        $result["id"] = new UUID($result["id"]);
        $result["amount"] = new Money($result["amount"]);
        $result["processed_by"] = $result["processed_by"] ? new Processor(id: new UUID($result["processed_by"])) : null;
        $result["processed_at"] = $result["processed_at"] ? new DateTime($result["processed_at"]) : null;

        return $result;
    }

    /**
     * Cria um novo pagamento
     * @param UUID $id
     * @param Money $amount
     * @return ClassPayment
     */
    public static function new(UUID $id, Money $amount): ClassPayment
    {
        $database = new Database();
        $database->query(
            into: "payments",
            insert: [
                "id" => (string) $id,
                "amount" => $amount->getValue()
            ],
            returning: "id"
        );

        return new ClassPayment($id);
    }
}
