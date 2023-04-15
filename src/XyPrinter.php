<?php

namespace xuezhitech\xyprinter;

class XyPrinter
{
    private $url = 'https://open.xpyun.net/api/openapi/xprinter';
    protected $config = [
        'user' => '',
        'key' => '',
        'mode' => 0, // 打印模式：默认0
        'expiresIn' => 60, //订单有效期，单位：秒。
        'backurlFlag' => 1, //打印订单状态回调标识。
    ];

    protected $result = [
        'status'=>false,
        'msg'=>'',
        'data'=>[]
    ];

    /*
     * 0 表示离线
     * 1 表示在线正常
     * 2 表示在线异常
     * 备注：异常一般情况是缺纸，离线的判断是打印机与服务器失去联系超过 30 秒
     *
     * */
    protected $printerStatus = [
        '0'=>'离线',
        '1'=>'正常',
        '2'=>'异常'
    ];

    public function __construct( $config=[] ){
        $this->config = array_merge($this->config,$config);
    }

    /**
     * 芯烨云 - 批量获取指定打印机状态
     *
     */
    public function queryPrintersStatus( $snlist=[],$debug=0 ){
        if ( empty($snlist) ) {
            $this->result['msg'] = '设备编号不能为空!';
            return $this->result;
        }
        $timestamp = time();
        //$url = 'https://open.xpyun.net/api/openapi/xprinter/queryPrintersStatus';
        $url = $this->url . '/queryPrintersStatus';
        $data = [
            'user' => $this->config['user'],
            'timestamp' => $timestamp,
            'sign' => $this->getSign($timestamp),
            'debug' => $debug,
            'snlist' => $snlist
        ];
        $result = $this->getCurlInfo($url,$data);
        if ( isset($result['code']) && intval($result['code'])==0 ) {
            $this->result['status'] = true;
            $this->result['data'] = $this->printerStatus[$result['data']];
        }else{
            $this->result['status'] = false;
            $this->result['msg'] = $result['msg'];
        }
        return $this->result;
    }

    /**
     * 芯烨云 - 查询订单是否打印成功
     *
     */
    public function queryOrderState($orderId,$debug=0) {
        if ( empty($orderId) ) {
            $this->result['msg'] = '订单编号不能为空!';
            return $this->result;
        }
        $timestamp = time();
        //$url = 'https://open.xpyun.net/api/openapi/xprinter/queryOrderState';
        $url = $this->url . '/queryOrderState';
        $data = [
            'user' => $this->config['user'],
            'timestamp' => $timestamp,
            'sign' => $this->getSign($timestamp),
            'debug' => $debug,
            'orderId' => $orderId
        ];
        $result = $this->getCurlInfo($url,$data);
        if ( isset($result['code']) && intval($result['code'])==0 ) {
            $this->result['status'] = true;
            $this->result['data'] = $result['data'];
        }else{
            $this->result['status'] = false;
            $this->result['msg'] = $result['msg'];
        }
        return $this->result;
    }

    /**
     * 芯烨云 - 清空待打印队列
     *
     */
    public function delPrinterQueue($sn,$debug=0) {
        if ( empty($sn) ) {
            $this->result['msg'] = '设备编号不能为空!';
            return $this->result;
        }
        $timestamp = time();
        //$url = 'https://open.xpyun.net/api/openapi/xprinter/delPrinterQueue';
        $url = $this->url . '/delPrinterQueue';
        $data = [
            'user' => $this->config['user'],
            'timestamp' => $timestamp,
            'sign' => $this->getSign($timestamp),
            'debug' => $debug,
            'sn' => $sn
        ];
        $result = $this->getCurlInfo($url,$data);
        if ( isset($result['code']) && intval($result['code'])==0 ) {
            $this->result['status'] = true;
            $this->result['data'] = $result['data'];
        }else{
            $this->result['status'] = false;
            $this->result['msg'] = $result['msg'];
        }
        return $this->result;
    }

    /**
     * 芯烨云 - 设置打印机语音类型
     *
     */
    public function setVoiceType($sn,$voiceType=3,$debug=0) {
        if ( empty($sn) ) {
            $this->result['msg'] = '设备编号不能为空!';
            return $this->result;
        }
        $timestamp = time();
        //$url = 'https://open.xpyun.net/api/openapi/xprinter/setVoiceType';
        $url = $this->url . '/setVoiceType';
        $data = [
            'user' => $this->config['user'],
            'timestamp' => $timestamp,
            'sign' => $this->getSign($timestamp),
            'debug' => $debug,
            'sn' => $sn,
            'voiceType' => $voiceType
        ];
        $result = $this->getCurlInfo($url,$data);
        if ( isset($result['code']) && intval($result['code'])==0 ) {
            $this->result['status'] = true;
            $this->result['data'] = $result['data'];
        }else{
            $this->result['status'] = false;
            $this->result['msg'] = $result['msg'];
        }
        return $this->result;
    }


    /**
     * 芯烨云 - 打印小票订单
     *
     */
    public function print($sn,$content,$debug=0) {
        if ( empty($sn) ) {
            $this->result['msg'] = '设备编号不能为空!';
            return $this->result;
        }
        if ( empty($content) ) {
            $this->result['msg'] = '打印内容不能为空!';
            return $this->result;
        }
        $timestamp = time();
        //$url = 'https://open.xpyun.net/api/openapi/xprinter/print';
        $url = $this->url . '/print';
        $data = [
            'user' => $this->config['user'],
            'timestamp' => $timestamp,
            'sign' => $this->getSign($timestamp),
            'debug' => $debug,
            'sn' => $sn,
            'content' => $content
        ];
        $result = $this->getCurlInfo($url,$data);
        if ( isset($result['code']) && intval($result['code'])==0 ) {
            $this->result['status'] = true;
            $this->result['data'] = $result['data'];
        }else{
            $this->result['status'] = false;
            $this->result['msg'] = $result['msg'];
        }
        return $this->result;
    }

    /**
     * 芯烨云 - 查询指定打印机某天的订单统计数
     *
     */
    public function queryOrderStatis( $sn,$date,$debug=0 ) {
        if ( empty($sn) ) {
            $this->result['msg'] = '设备编号不能为空!';
            return $this->result;
        }
        if ( empty($date) ) {
            $this->result['msg'] = '查询日期不能为空!';
            return $this->result;
        }
        $timestamp = time();
        //$url = 'https://open.xpyun.net/api/openapi/xprinter/queryOrderStatis';
        $url = $this->url . '/queryOrderStatis';
        $data = [
            'user' => $this->config['user'],
            'timestamp' => $timestamp,
            'sign' => $this->getSign($timestamp),
            'debug' => $debug,
            'sn' => $sn,
            'date' => date('Y-m-d',$date)
        ];
        $result = $this->getCurlInfo($url,$data);
        if ( isset($result['code']) && intval($result['code'])==0 ) {
            $this->result['status'] = true;
            $this->result['data'] = $result['data'];
        }else{
            $this->result['status'] = false;
            $this->result['msg'] = $result['msg'];
        }
        return $this->result;
    }

    /**
     * 芯烨云 - 获取指定打印机状态
     *
     */
    public function queryPrinterStatus( $sn,$debug=0 ){
        if ( empty($sn) ) {
            $this->result['msg'] = '设备编号不能为空!';
            return $this->result;
        }
        $timestamp = time();
        //$url = 'https://open.xpyun.net/api/openapi/xprinter/queryPrinterStatus';
        $url = $this->url . '/queryPrinterStatus';
        $data = [
            'user' => $this->config['user'],
            'timestamp' => $timestamp,
            'sign' => $this->getSign($timestamp),
            'debug' => $debug,
            'sn' => $sn
        ];
        $result = $this->getCurlInfo($url,$data);
        if ( isset($result['code']) && intval($result['code'])==0 ) {
            $this->result['status'] = true;
            $this->result['data'] = $this->printerStatus[$result['data']];
        }else{
            $this->result['status'] = false;
            $this->result['msg'] = $result['msg'];
        }
        return $this->result;
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
        //$url = 'https://open.xpyun.net/api/openapi/xprinter/updPrinter';
        $url = $this->url . '/updPrinter';
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
        //$url = 'https://open.xpyun.net/api/openapi/xprinter/delPrinters';
        $url = $this->url . '/delPrinters';
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
        //$url = 'https://open.xpyun.net/api/openapi/xprinter/addPrinters';
        $url = $this->url . '/addPrinters';
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
