<?php

namespace Manday;

use Manday\Query\GraphQLBuilder;

class Manday {

    private $data;

    private const API_TOKEN = 'eyJhbGciOiJIUzI1NiJ9.eyJ0aWQiOjEwNjIyNzAzNiwidWlkIjoyMTQzODExMCwiaWFkIjoiMjAyMS0wNC0xMlQxNzoyNjoxNC4wMDBaIiwicGVyIjoibWU6d3JpdGUiLCJhY3RpZCI6ODcxOTg3NiwicmduIjoidXNlMSJ9.t8_CyXtPCW0aWX0_SS3vmGlQZSN7VUz_iUrVe8tZjTE';

    private $parameters = [
        'header'=>[
            'Content-Type: application/json', 
            'Authorization: ' . self::API_TOKEN
        ],
        'url'=>'https://api.monday.com/v2'
    ];

    public function createGraphQL() 
    {
        return new GraphQLBuilder();
    }

    public function send(GraphQLBuilder $builder, bool $print_query = false)
    {
        $ch = curl_init();

        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'POST'); 
        curl_setopt($ch, CURLOPT_URL, $this->parameters['url']);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $this->parameters['header']);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $builder->getQuery());
        curl_setopt($ch, CURLOPT_AUTOREFERER, TRUE);
        curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); 

        $this->data = curl_exec($ch);
        curl_close($ch);
        $this->data = json_decode($this->data, true);

        if($print_query) {
            echo 'Query : ', $builder->getQuery(), "\n";
        }

        if(isset($this->data['errors'])) {
            throw new \Exception($this->data['errors'][0]['message']);
        }

        return $this;
    }
    
    public function getResult() 
    {
        return $this->data;
    }
}