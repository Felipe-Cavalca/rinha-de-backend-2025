<?php

namespace Bifrost\Model;

use Bifrost\Class\Payment as ClassPayment;
use Bifrost\Class\Processor;
use Bifrost\Core\Cache;
use Bifrost\Core\Database;
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
    public static function get(UUID $id): ?array
    {
        $cache = new Cache();
        $keyCache = Cache::buildCacheKey(entity: "payment", conditions: ["id" => (string) $id]);

        $result = $cache->get($keyCache, function () use ($id) {
            $database = new Database();
            return $database->query(
                from: "payments",
                where: ["id" => (string) $id],
            );
        });

        if (!empty($result)) {
            foreach ($result as &$processor) {
                $processor["id"] = new UUID($processor["id"]);
                $processor["amount"] = new Money($processor["amount"]);
                $processor["processed_by"] = $processor["processed_by"] ? new Processor(id: new UUID($processor["processed_by"])) : null;
                $processor["processed_at"] = $processor["processed_at"] ? new DateTime($processor["processed_at"]) : null;
            }
        }

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
                "amount" => (float) $amount
            ],
        );

        return new ClassPayment($id);
    }
}
