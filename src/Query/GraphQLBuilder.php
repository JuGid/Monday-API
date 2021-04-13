<?php

namespace Manday\Query;

class GraphQLBuilder {

    /**
     * Mutation or Query
     */
    private $type;

    /**
     * Array represents the query/mutation
     */
    private $query = [];

    public function __construct() {}

    private function add(string $array, string $element, array $informations, array $parameters, bool $add = true) : self
    {
        $array_to_set = [
            'element'=>$element, 
            'informations'=>$informations,
            'parameters'=> $parameters
        ];

        if($add) {
            $this->query[$array][] = $array_to_set;
        } else {
            $this->query[$array] = $array_to_set;
        }
        
        return $this;
    }

    public function request(string $element, array $parameters = []) : self 
    {
        $this->type = 'query';
        return $this->add('request', $element, [], $parameters, false);
    }

    public function modify() : self
    {
        $this->type = 'mutation';
        return $this;
    }

    public function the(string $element, array $informations = [], array $parameters = []) : self 
    {
        return $this->add('fields', $element, $informations, $parameters);
    }

    public function getArrayQuery() : array 
    {
        return $this->query;
    }

    public function getQuery() : string
    {
        return (new ArrayToGraphQL())->transform($this);
    }

    public function getType() : string 
    {
        return $this->type;
    }
}