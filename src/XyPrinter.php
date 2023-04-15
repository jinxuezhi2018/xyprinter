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
        'backurlFlag' => 0, //打印订单状态回调标识。
        'debug' => 0 //调试模式。
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
    public function queryPrintersStatus( $snlist=[] ) {
        if ( empty($snlist) ) {
            $this->result['msg'] = '设备编号不能为空!';
            return $this->result;
        }
        //业务域名
        $url = $this->url . '/queryPrintersStatus';
        //获得公共参数
        $data = $this->getPublicData();
        //获得业务参数
        $data['snlist'] = $snlist;
        //调用curl
        $result = $this->getCurlInfo($url,$data);
        //重组数据结棍
        $this->result = $this->getResultData($result);
        //返回结果
        return $this->result;
    }

    /**
     * 芯烨云 - 查询订单是否打印成功
     *
     */
    public function queryOrderState( $orderId ) {
        if ( empty($orderId) ) {
            $this->result['msg'] = '订单编号不能为空!';
            return $this->result;
        }
        //业务域名
        $url = $this->url . '/queryOrderState';
        //获得公共参数
        $data = $this->getPublicData();
        //获得业务参数
        $data['orderId'] = $orderId;
        //调用curl
        $result = $this->getCurlInfo($url,$data);
        //重组数据结棍
        $this->result = $this->getResultData($result);
        //返回结果
        return $this->result;
    }

    /**
     * 芯烨云 - 清空待打印队列
     *
     */
    public function delPrinterQueue( $sn ) {
        if ( empty($sn) ) {
            $this->result['msg'] = '设备编号不能为空!';
            return $this->result;
        }
        //业务域名
        $url = $this->url . '/delPrinterQueue';
        //获得公共参数
        $data = $this->getPublicData();
        //获得业务参数
        $data['sn'] = $sn;
        //调用curl
        $result = $this->getCurlInfo($url,$data);
        //重组数据结棍
        $this->result = $this->getResultData($result);
        //返回结果
        return $this->result;
    }

    /**
     * 芯烨云 - 设置打印机语音类型
     *
     */
    public function setVoiceType($sn,$voiceType=3 ) {
        if ( empty($sn) ) {
            $this->result['msg'] = '设备编号不能为空!';
            return $this->result;
        }
        //业务域名
        $url = $this->url . '/setVoiceType';
        //获得公共参数
        $data = $this->getPublicData();
        //获得业务参数
        $data['sn'] = $sn;
        $data['voiceType'] = $voiceType;
        //调用curl
        $result = $this->getCurlInfo($url,$data);
        //重组数据结棍
        $this->result = $this->getResultData($result);
        //返回结果
        return $this->result;
    }


    /**
     * 芯烨云 - 打印小票订单
     *
     */
    public function xprint($sn,$content ) {
        if ( empty($sn) ) {
            $this->result['msg'] = '设备编号不能为空!';
            return $this->result;
        }
        if ( empty($content) ) {
            $this->result['msg'] = '打印内容不能为空!';
            return $this->result;
        }
        //业务域名
        $url = $this->url . '/print';
        //获得公共参数
        $data = $this->getPublicData();
        //获得业务参数
        $data['sn'] = $sn;
        $data['content'] = $content;
        //调用curl
        $result = $this->getCurlInfo($url,$data);
        //重组数据结棍
        $this->result = $this->getResultData($result);
        //返回结果
        return $this->result;
    }

    /**
     * 芯烨云 - 查询指定打印机某天的订单统计数
     *
     */
    public function queryOrderStatis( $sn,$date ) {
        if ( empty($sn) ) {
            $this->result['msg'] = '设备编号不能为空!';
            return $this->result;
        }
        if ( empty($date) ) {
            $this->result['msg'] = '查询日期不能为空!';
            return $this->result;
        }
        //业务域名
        $url = $this->url . '/queryOrderStatis';
        //获得公共参数
        $data = $this->getPublicData();
        //获得业务参数
        $data['sn'] = $sn;
        $data['date'] = date('Y-m-d',$date);
        //调用curl
        $result = $this->getCurlInfo($url,$data);
        //重组数据结棍
        $this->result = $this->getResultData($result);
        //返回结果
        return $this->result;
    }

    /**
     * 芯烨云 - 获取指定打印机状态
     *
     */
    public function queryPrinterStatus( $sn ){
        if ( empty($sn) ) {
            $this->result['msg'] = '设备编号不能为空!';
            return $this->result;
        }
        //业务域名
        $url = $this->url . '/queryPrinterStatus';
        //获得公共参数
        $data = $this->getPublicData();
        //获得业务参数
        $data['sn'] = $sn;
        //调用curl
        $result = $this->getCurlInfo($url,$data);
        //重组数据结棍
        $this->result = $this->getResultData($result);
        //返回结果
        return $this->result;
    }

    /**
     * 芯烨云 - 修改打印机信息
     *
     */
    public function updPrinters( $snlist ){
        if ( empty($snlist['sn']) || empty($snlist['name']) ) {
            $this->result['msg'] = '设备编号/名称不能为空!';
            return $this->result;
        }
        //业务域名
        $url = $this->url . '/updPrinter';
        //获得公共参数
        $data = $this->getPublicData();
        //获得业务参数
        $data['sn'] = $snlist['sn'];
        $data['name'] = $snlist['name'];
        if ( isset($snlist['cardno']) ) {
            $data['cardno'] = $snlist['cardno'];
        }
        //调用curl
        $result = $this->getCurlInfo($url,$data);
        //重组数据结棍
        $this->result = $this->getResultData($result);
        //返回结果
        return $this->result;
    }

    /**
     * 芯烨云 - 批量删除打印机
     *
     */
    public function delPrinters( $snlist=[] ){
        if ( empty($snlist) ) {
            $this->result['msg'] = '设备编号不能为空!';
            return $this->result;
        }
        //业务域名
        $url = $this->url . '/delPrinters';
        //获得公共参数
        $data = $this->getPublicData();
        //获得业务参数
        $data['snlist'] = $snlist;
        //调用curl
        $result = $this->getCurlInfo($url,$data);
        //重组数据结棍
        $this->result = $this->getResultData($result);
        //返回结果
        return $this->result;
    }

    /**
     * 芯烨云 - 批量添加打印机
     *
     */
    public function addPrinters( $items ){
        //开发者ID/开发者KEY/items 不能为空
        if ( empty($items) ) {
            $this->result['msg'] = '设备信息不能为空!';
            return $this->result;
        }
        //业务域名
        $url = $this->url . '/addPrinters';
        //获得公共参数
        $data = $this->getPublicData();
        //获得业务参数
        $data['items'] = $items;
        //调用curl
        $result = $this->getCurlInfo($url,$data);
        //重组数据结棍
        $this->result = $this->getResultData($result);
        //返回结果
        return $this->result;
    }

    private function getResultData($data){
        if ( empty($data) ) {
            $this->result['msg'] = '返回信息为空!';
            return $this->result;
        }
        if ( isset($data['code']) && intval($data['code'])==0 ) {
            $this->result['status'] = true;
            $this->result['data'] = $this->printerStatus[$data['data']];
        }else{
            $this->result['status'] = false;
            $this->result['msg'] = $data['msg'];
        }
    }

    private function getPublicData(){
        $timestamp = time();
        $data = [
            'user' => $this->config['user'],
            'timestamp' => $timestamp,
            'sign' => $this->getSign($timestamp),
            'debug' => $this->config['debug']
        ];
        return $data;
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
