<?php

namespace Manday\Query;

class ArrayToGraphQL
{

    public function parameter(string $element, array $parameters) {
        $parameters_array = [];
        foreach($parameters as $k=>$value) {
            if(is_array($value)) {
                $parameters_array[] = $k.':['.implode(',',$value).']';
            } else {
                if(is_string($value)) {
                    $value = '"'.$value.'"';
                }
                $parameters_array[] = $k.':'.$value;
            }
        }

        $parameters_str = $element;
        
        if(!empty($parameters_array)) {
            $parameters_str .= '('.implode(',',$parameters_array).')';
        }
        
        return $parameters_str;
    }

    public function informations(array $informations)
    {
        $informations_array = [];
        foreach($informations as $k=>$info)
        {
            if(is_array($info))
            {
                $informations_array[] = $k.'{'. implode(',',$info) .'}';
            } else {
                $informations_array[] = $info;
            }
        }

        return '{'. implode(',', $informations_array) .'}';
    }

    public function transform(GraphQLBuilder $builder) : string
    {
        $query = $builder->getArrayQuery();

        if(isset($query['request'])) {
            $query_str = $this->parameter(
                $query['request']['element'], 
                $query['request']['parameters']) . '{%query%}';
        } else {
            $query_str = '%query%';
        }
        
        $query_temp = '{"'.$builder->getType().'":"{%query%}"}';
        $query_temp = str_replace('%query%', $query_str, $query_temp);

        $informations_array = [];
        foreach($query['fields'] as $content) {
            $array_str = $this->parameter($content['element'], $content['parameters']);
            
            if(!empty($content['informations'])) {
                $array_str .= $this->informations($content['informations']);
            } 
            
            $informations_array[] = $array_str;
        }

        $query_temp = str_replace('%query%', implode(',', $informations_array), $query_temp);
        return $query_temp;
    }
}