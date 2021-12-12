<?php

namespace App\Onlineshop;

use Illuminate\Http\Client\Response;
use Illuminate\Support\Facades\Http;

class Client
{
    protected const ADD_TO_CART_ENDPOINT = '/api/add-to-cart';
    protected const CART_ENDPOINT = '/api/cart';
    protected const CHECKOUT_ENDPOINT = '/api/checkout';
    protected const CREATE_ORDER_ENDPOINT = '/api/order/create';

    protected $base_url;

    public function __construct($base_url)
    {
        $this->base_url = rtrim(trim($base_url), '/');
    }
    
    public function add_to_cart($payload)
    {
        $response = $this->post(self::ADD_TO_CART_ENDPOINT, $payload);

        return $response;
    }

    public function cart($payload)
    {
        $response = $this->get(self::CART_ENDPOINT);

        return $response;
    }

    public function checkout($payload)
    {
        $response = $this->post(self::CHECKOUT_ENDPOINT, $payload);

        return $response;
    }

    public function create_order($payload)
    {
        $response = $this->post(self::CREATE_ORDER_ENDPOINT, $payload);

        return $response;
    }

    public function post($endpoint, $data = []): Response
    {
        $http_client = $this->resolve_http_client();

        return $http_client->post($endpoint, $data);
    }
    
    public function get($endpoint, $query = null)
    {
        $http_client = $this->resolve_http_client();

        return $http_client->get($endpoint, $query);
    }
    
    protected function resolve_http_client()
    {
        $client = Http::baseUrl($this->base_url)->asJson();

        return $client;
    }
}
