<?php

namespace Bifrost\Enum;

Enum Routes: string
{


    /**
     * Método para obter o controlador associado a uma rota.
     * @param string $path
     * @return self|null
     */
    public static function fromRequest(string $path): ?self
    {
        foreach (self::cases() as $route) {
            if ($route->name === $path) {
                return $route;
            }
        }
        return null;
    }
}
