<?php

namespace xuezhitech\xyprinter;

class XyPrinter
{
    protected $config = [
        'user' => '',
        'key' => ''
    ];

    protected $result = [
        'status'=>false,
        'msg'=>'',
        'data'=>[]
    ];

    public function __construct( $config=[] ){
        $this->config = array_merge($this->config,$config);
    }

    /**
     * 芯烨云 - 修改打印机信息
     *
     */
    public function updPrinters( $snlist,$debug=0 ){
        if ( empty($snlist['sn']) || empty($snlist['name']) ) {
            $this->result['msg'] = '设备编号/名称不能为空!';
            return $this->result;
        }
        $timestamp = time();
        $url = 'https://open.xpyun.net/api/openapi/xprinter/updPrinter';
        $data = [
            'user' => $this->config['user'],
            'timestamp' => $timestamp,
            'sign' => $this->getSign($timestamp),
            'debug' => $debug,
            'sn' => $snlist['sn'],
            'name' => $snlist['name'],
        ];
        if ( isset($snlist['cardno']) ) {
            $data['cardno'] = $snlist['cardno'];
        }
        $result = $this->getCurlInfo($url,$data);
        if ( isset($result['code']) && intval($result['code'])==0 ) {
            $this->result['status'] = true;
            $this->result['data'] = $result;
        }else{
            $this->result['status'] = false;
            $this->result['msg'] = $result['msg'];
        }
        return $this->result;
    }

    /**
     * 芯烨云 - 批量删除打印机
     *
     */
    public function delPrinters( $snlist,$debug=0 ){
        if ( empty($snlist) ) {
            $this->result['msg'] = '设备编号不能为空!';
            return $this->result;
        }
        $timestamp = time();
        $url = 'https://open.xpyun.net/api/openapi/xprinter/delPrinters';
        $data = [
            'user' => $this->config['user'],
            'timestamp'=> $timestamp,
            'sign' => $this->getSign($timestamp),
            'debug' => $debug,
            'snlist' => $snlist,
        ];
        $result = $this->getCurlInfo($url,$data);
        if ( isset($result['code']) && intval($result['code'])==0 ) {
            $this->result['status'] = true;
            $this->result['data'] = $result;
        }else{
            $this->result['status'] = false;
            $this->result['data'] = $result['msg'];
        }
        return $this->result;
    }

    /**
     * 芯烨云 - 批量添加打印机
     *
     */
    public function addPrinters( $items,$debug=0 ){
        //开发者ID/开发者KEY/items 不能为空
        if ( empty($items) ) {
            $this->result['msg'] = '设备信息不能为空!';
            return $this->result;
        }
        $timestamp = time();
        $url = 'https://open.xpyun.net/api/openapi/xprinter/addPrinters';
        $data = [
            'user' => $this->config['user'],
            'timestamp'=> $timestamp,
            'sign' => $this->getSign($timestamp),
            'debug' => $debug,
            'items' => $items,
        ];
        $result = $this->getCurlInfo($url,$data);
        if ( isset($result['code']) && intval($result['code'])==0 ) {
            $this->result['status'] = true;
            $this->result['data'] = $result;
        }else{
            $this->result['status'] = false;
            $this->result['data'] = $result['msg'];
        }
        return $this->result;
    }

    private function getSign($timestamp){

        if ( empty($this->config['user'])) {
            $this->result['msg'] = '开发者账户不能为空!';
            return $this->result;
        }
        if ( empty($this->config['key'])) {
            $this->result['msg'] = '开发者KEY不能为空!';
            return $this->result;
        }
        return sha1($this->config['user'].$this->config['key'].$timestamp);
    }

    private function getCurlInfo($url,$data=[]){
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
        curl_setopt($ch, CURLOPT_HEADER, FALSE);
        curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type:application/json;charset=UTF-8']);
        if ( $data ){
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
        }
        $response = curl_exec($ch);
        curl_close($ch);
        return json_decode($response,true);
    }
}
