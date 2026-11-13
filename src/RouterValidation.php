<?php

namespace Firewwdev\EasyRouter;

use Firewwdev\EasyRouter\Router;

class RouterValidation
{

    public function validateRoute(
        array $route,
        array $uri
    ): array
    {

        $parameters = [];

        if(count($route) !== count($uri)){
            return [
                    'is_valid' => false,
                    'parameters' => $parameters
                ];
        }

        foreach($route as $route_key => $route_value){

            $uri_value = $uri[$route_key];

            $isParameter = $this->isParameter($route_value);

            if(!$isParameter && $route_value !== $uri_value){
                return [
                    'is_valid' => false,
                    'parameters' => $parameters
                ];
            }

            if($isParameter){
                $parameter_name = str_replace(['{', '}'], '', $route_value);
                $parameters[$parameter_name] = $uri_value;
            }

        }

        return [
            'is_valid' => true,
            'parameters' => $parameters
        ];

    }

    public function isParameter(string $route_value): bool
    {
        return str_starts_with($route_value, '{') && str_ends_with($route_value, '}');
    }

}