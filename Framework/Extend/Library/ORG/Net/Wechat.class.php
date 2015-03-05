<?php
class Wechat {
  
  /**
   * 微信推送过来的数据或响应数据
   * @var array
   */
  private $data = array();
  private $token;

  /**
   * 构造方法，用于实例化微信SDK
   * @param string $token 微信开放平台设置的TOKEN
   */
  public function __construct($token){
    $this->token = $token;
    $this->auth($token) || exit;

    if(IS_GET){
      exit($_GET['echostr']);
    } else {
      $xml = file_get_contents("php://input");
      $xml = new SimpleXMLElement($xml);
      $xml || exit;

          foreach ($xml as $key => $value) {
            $this->data[$key] = strval($value);
          }
    }
  }

  /**
   * 获取微信推送的数据
   * @return array 转换为数组后的数据
   */
  public function request(){
        return $this->data;
  }

  /**
   * * 响应微信发送的信息（自动回复）
   * @param  string $to      接收用户名
   * @param  string $from    发送者用户名
   * @param  array  $content 回复信息，文本信息为string类型
   * @param  string $type    消息类型
   * @param  string $flag    是否新标刚接受到的信息
   * @return string          XML字符串
   */
  public function response($content, $type = 'text', $flag = 0){
    /* 基础数据 */
    $this->data = array(
      'ToUserName'   => $this->data['FromUserName'],
      'FromUserName' => $this->data['ToUserName'],
      'CreateTime'   => NOW_TIME,
      'MsgType'      => $type,
    );

    /* 添加类型数据 */
    $this->$type($content);

    /* 添加状态 */
    $this->data['FuncFlag'] = $flag;

    /* 转换数据为XML */
    $xml = new SimpleXMLElement('<xml></xml>');
    $this->data2xml($xml, $this->data);
    exit($xml->asXML());
  }

  /**
   * 回复文本信息
   * @param  string $content 要回复的信息
   */
  private function text($content){
    $this->data['Content'] = $content;
  }

  /**
   * 回复音乐信息
   * @param  string $content 要回复的音乐
   */
  private function music($music){
    list(
      $music['Title'], 
      $music['Description'], 
      $music['MusicUrl'], 
      $music['HQMusicUrl']
    ) = $music;
    $this->data['Music'] = $music;
  }

  /**
   * 回复图文信息
   * @param  string $news 要回复的图文内容
   */
  private function news($news){
    $articles = array();
    foreach ($news as $key => $value) {
      list(
        $articles[$key]['Title'],
        $articles[$key]['Description'],
        $articles[$key]['PicUrl'],
        $articles[$key]['Url']
      ) = $value;
      if($key >= 9) { break; } //最多只允许10调新闻
    }

    file_put_contents(RUNTIME_PATH.'Logs/weixin.log',array2string($articles)."\n",FILE_APPEND);

    $this->data['ArticleCount'] = count($articles);
    $this->data['Articles'] = $articles;
  }

  /**
     * 数据XML编码
     * @param  object $xml  XML对象
     * @param  mixed  $data 数据
     * @param  string $item 数字索引时的节点名称
     * @return string
     */
    private function data2xml($xml, $data, $item = 'item') {
        foreach ($data as $key => $value) {
            /* 指定默认的数字key */
            is_numeric($key) && $key = $item;

            /* 添加子元素 */
            if(is_array($value) || is_object($value)){
                $child = $xml->addChild($key);
                $this->data2xml($child, $value, $item);
            } else {
              if(is_numeric($value)){
                $child = $xml->addChild($key, $value);
              } else {
                $child = $xml->addChild($key);
                  $node  = dom_import_simplexml($child);
            $node->appendChild($node->ownerDocument->createCDATASection($value));
              }
            }
        }
    }

    /**
   * 对数据进行签名认证，确保是微信发送的数据
   * @param  string $token 微信开放平台设置的TOKEN
   * @return boolean       true-签名正确，false-签名错误
   */
  private function auth($token){
    $signature = $_GET["signature"];
    $timestamp = $_GET["timestamp"];
    $nonce = $_GET["nonce"];  
    // $token = $this->token;
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
