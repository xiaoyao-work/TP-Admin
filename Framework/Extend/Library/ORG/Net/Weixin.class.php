<?php
/**
  * 微信接口公共类
  */
require "QqUrl.class.php";
class Weixin {
  private $token, $appid, $appsecret;
  function __construct($token, $appid ="", $appsecret="") {
    $this->token = $token;
    $this->appid = $appid;
    $this->appsecret = $appsecret;
  }

  public function valid() {
    $echoStr = $_GET["echostr"];
    //valid signature , option
    if($this->checkSignature()){
      echo $echoStr;
      exit;
    }
  }

  public function responseMsg() {
    //get post data, May be due to the different environments
    $postStr = $GLOBALS["HTTP_RAW_POST_DATA"];
    //extract post data
    if (!empty($postStr)){
      $postObj = simplexml_load_string($postStr, 'SimpleXMLElement', LIBXML_NOCDATA);
      $fromUsername = $postObj->FromUserName;
      $toUsername = $postObj->ToUserName;
      $keyword = trim($postObj->Content);
      $time = time();
      $textTpl = "<xml>
      <ToUserName><![CDATA[%s]]></ToUserName>
      <FromUserName><![CDATA[%s]]></FromUserName>
      <CreateTime>%s</CreateTime>
      <MsgType><![CDATA[%s]]></MsgType>
      <Content><![CDATA[%s]]></Content>
      <FuncFlag>0</FuncFlag>
      </xml>";
      if(!empty( $keyword )) {
        $msgType = "text";
        $contentStr = "Welcome to wechat world!";
        $resultStr = sprintf($textTpl, $fromUsername, $toUsername, $time, $msgType, $contentStr);
        echo $resultStr;
      }else {
        echo "Input something...";
      }
    } else {
      echo "";
      exit;
    }
  }

  private function checkSignature() {
    $signature = $_GET["signature"];
    $timestamp = $_GET["timestamp"];
    $nonce = $_GET["nonce"];  
    $token = $this->token;
    $tmpArr = array($token, $timestamp, $nonce);
    sort($tmpArr, SORT_STRING);
    $tmpStr = implode( $tmpArr );
    $tmpStr = sha1( $tmpStr );
    if( $tmpStr == $signature ){
      return true;
    } else {
      return false;
    }
  }
}
?>