<?php

class CURL {

    protected $url;
    protected $ch;

    function __construct(string $api = '') {
        $this->url = $api;
        $this->ch = curl_init();
    }

    function post(array $data) {
        curl_setopt($this->ch, CURLOPT_POST, 1);
        curl_setopt($this->ch, CURLOPT_POSTFIELDS, $data);
        return $this;
    }

    function put(array $data = []) {
        curl_setopt($this->ch, CURLOPT_CUSTOMREQUEST, 'PUT');
        curl_setopt($this->ch, CURLOPT_POSTFIELDS,$data);
        return $this;
    }

    function delete(array $data = null) {
        curl_setopt($this->ch, CURLOPT_CUSTOMREQUEST, 'DELETE');
        if($data)
            curl_setopt($this->ch, CURLOPT_POSTFIELDS,$data);
        return $this;
    }

    function auth($token) {
        curl_setopt($this->ch, CURLOPT_HTTPHEADER, ['Authentication: ' . $token]);
        return $this;
    }

    function method($method = 'GET') {
        curl_setopt($this->ch, CURLOPT_CUSTOMREQUEST, strtoupper($method));
        if($method = 'PUT')
            curl_setopt($this->ch, CURLOPT_POSTFIELDS,[]);
        return $this;
    }

    function exec() {
        curl_setopt($this->ch, CURLOPT_URL, $this->url);
        curl_setopt($this->ch, CURLOPT_RETURNTRANSFER, 1);
        $data = curl_exec($this->ch);
        curl_close($this->ch);
       return json_decode($data, true);
    }
}