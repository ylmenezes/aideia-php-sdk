<?php

namespace Aideia;

class Request
{
    private $endpoints;
    private $config;
    private $url;
    private $headers;

    public function __construct(array $options = null)
    {
        $this->config = Config::get('CONFIG');
        $this->endpoints = Config::get('ENDPOINTS');
        $this->url = isset($options['sandbox']) && $options['sandbox'] === false ? $this->config['URL_PRD'] : $this->config['URL_QA'];
        $this->headers = ["Content-type:application/json"];
    }

    public function send(String $endpoint, array $data = null, array $requestOptions = null)
    {
        try {

            if( empty($this->endpoints[$endpoint]['route']) ){
                throw new AideiaException('Endpoint não é válido.', 404);
            }

            $route = $this->endpoints[$endpoint]['route'];
            if (isset($requestOptions['params'])){
                $route = Utils::getRoute($this->endpoints[$endpoint]['route'], $requestOptions['params']);
            }

            $exec = curl_init();
            curl_setopt($exec, CURLOPT_CUSTOMREQUEST, $this->endpoints[$endpoint]['method']);
            curl_setopt($exec, CURLOPT_URL,  $this->url . $route);

            curl_setopt($exec, CURLOPT_HTTPHEADER, $this->headers);
            if ($requestOptions  !== null && isset($requestOptions['headers'])){
                $this->headers = array_merge($this->headers,$requestOptions['headers']);
                curl_setopt($exec, CURLOPT_HTTPHEADER, $this->headers);
            }


            if ($data !== null && is_array($data))
                curl_setopt($exec, CURLOPT_POSTFIELDS, json_encode($data));

            curl_setopt_array($exec, array(
                CURLOPT_ENCODING        => "UTF-8",
                CURLOPT_MAXREDIRS       => 2,
                CURLOPT_POST            => TRUE,
                CURLOPT_FOLLOWLOCATION  => TRUE,
                CURLOPT_RETURNTRANSFER  => TRUE,
            ));


            $response = curl_exec($exec);
            $error = curl_error($exec);
            curl_close($exec);

            if (empty($error)):
                $return = json_decode($response);
                if($return->code === 200)
                    return $return;
                else
                    throw new AideiaException($return->message, $return->code);
            else:
                throw new AideiaException($error, 400);
            endif;
        } catch (AideiaException $e) {
            die($e);
        }
    }
}
