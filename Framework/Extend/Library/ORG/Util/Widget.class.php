<?php
  /**
  * 小工具类
  */
  class Widget {
    public static function systemInfo() {
      if (function_exists('gd_info')) {
        $gd = gd_info();
        $gd = $gd['GD Version'];
      } else {
        $gd = "不支持";
      } ?>
      <div class="list">
        <h1><b>系统信息</b></h1>
        <ul>
          <li><span>操作系统:</span><?php echo PHP_OS; ?></li>
          <li><span>运行方式:</span><?php echo php_sapi_name(); ?></li>
          <li><span>PHP版本:</span><?php echo PHP_VERSION; ?></li>
          <li><span>MYSQL版本:</span><?php echo function_exists("mysql_close") ? mysql_get_client_info() : '不支持'; ?></li>
          <li><span>上传限制:</span><?php echo ini_get('upload_max_filesize'); ?></li>
          <li><span>GD库版本:</span><?php echo $gd; ?></li>
        </ul>
      </div>
      <?php
    }

    /* 安全信息 */
    public static function securityInfo() { ?>
    <div class="list">
      <h1><b>安全信息</b></h1>
      <ul>
        <li style="color:#F00;"><span style="color:#F00;">提示:</span>请妥善保管好自己的帐号密码，谨防被盗</li>
        <?php if( defined("APP_DEBUG") && APP_DEBUG ) { ?>
        <li style="color:#F00;"><span style="color:#F00;">提示:</span>强烈建议您网站上线后，建议关闭 DEBUG （前台SQL错误提示）</li>
        <?php } ?>
      </ul>
    </div>
    <?php }

    /* 企业会员信息 */
    public static function memberInfo() {
      $corporation_count = D('FdcMember')->where('level = 2')->count();
      $broker_count = D('FdcMember')->where('level = 7')->count();
      $waite_audit_broker_count = D('FdcMember')->where('level = 7 and `show` = 0')->count();
      $waite_audit_corporation_count = D('FdcMember')->where('level = 2 and `show` = 0')->count();
      ?>
    <div class="list">
      <h1><b>企业会员信息</b></h1>
      <ul>
        <li>经纪公司：<font>(<?php echo $corporation_count; ?>家)</font></li>
        <li>待审经纪公司：<font>(<?php echo $waite_audit_corporation_count; ?>家)</font></li>
        <li>经纪人：<font>(<?php echo $corporation_count; ?>个)</font></li>
        <li>待审经纪人：<font>(<?php echo $waite_audit_broker_count; ?>个)</font></li>
      </ul>
    </div>
    <?php }

    /* 最新出售 */
    public static function lastSale() {
      $lastsales = D('SaleCommon')->order('id desc')->limit(5)->select();
      ?>
      <div class="list">
        <h1><b>最新出售</b></h1>
        <ul>
          <?php foreach ($lastsales as $key => $value) { ?>
          <li><a href="<?php echo U( 'Sale' . ucwords($value['type']) . '/edit?id=' . $value['foreign_id'] ); ?>"><?php echo $value['title']; ?></a></li>
          <?php } ?>
        </ul>
      </div>
      <?php
    }

    /* 待审核出售 */
    public static function waiteAuditSales() {
      $waite_audit_sales = D('SaleCommon')->where( '`status` != 1' )->order('id desc')->select();
      ?>
      <div class="list">
        <h1><b>待审核出售</b></h1>
        <ul>
          <?php foreach ($waite_audit_sales as $key => $value) { ?>
          <li><a href="<?php echo U( 'Sale' . ucwords($value['type']) . '/edit?id=' . $value['foreign_id'] ); ?>"><?php echo $value['title']; ?></a></li>
          <?php } ?>
        </ul>
      </div>
      <?php
    }

    /* 待审核出租 */
    public static function waiteAuditRents() {
      $waite_audit_rents = D('RentCommon')->where( '`status` != 1' )->order('id desc')->select();
      ?>
      <div class="list">
        <h1><b>待审核出租</b></h1>
        <ul>
          <?php foreach ($waite_audit_rents as $key => $value) { ?>
          <li><a href="<?php echo U( 'Rent' . ucwords($value['type']) . '/edit?id=' . $value['foreign_id'] ); ?>"><?php echo $value['title']; ?></a></li>
          <?php } ?>
        </ul>
      </div>
      <?php
    }

    /* 最新出租 */
    public static function lastRent() {
      $lastrents = D('RentCommon')->order('id desc')->limit(5)->select();
      ?>
      <div class="list">
        <h1><b>最新出租</b></h1>
        <ul>
          <?php foreach ($lastrents as $key => $value) { ?>
          <li><a href="<?php echo U( 'Rent' . ucwords( self::moduleParse( $value['type'] )) . '/edit?id=' . $value['foreign_id'] ); ?>"><?php echo $value['title']; ?></a></li>
          <?php } ?>
        </ul>
      </div>
      <?php
    }

    /* 模型转换 */
    public static function moduleParse( $module ) {
      if ( $module == 'rent' ) {
        return "house";
      }
      return $module;
    }

    /* 概括 */
    public static function overview() {
      $sale_count = D('SaleCommon')->where( "DATEDIFF( now(), `created_at` ) < 1" )->count();
      $sale_house_count = D('SaleCommon')->where( "DATEDIFF( now(), `created_at` ) < 1 and `type` = 'house'" )->count();
      $sale_shop_count = D('SaleCommon')->where( "DATEDIFF( now(), `created_at` ) < 1 and `type` = 'shop'" )->count();
      $sale_office_count = D('SaleCommon')->where( "DATEDIFF( now(), `created_at` ) < 1 and `type` = 'office'" )->count();

      $sale_wait_audit = D('SaleCommon')->where( "`status` != 1" )->count();

      $rent_count = D('RentCommon')->where( "DATEDIFF( now(), `created_at` ) < 1" )->count();
      $rent_house_count = D('RentCommon')->where( "DATEDIFF( now(), `created_at` ) < 1 and `type` = 'rent'" )->count();
      $rent_shop_count = D('RentCommon')->where( "DATEDIFF( now(), `created_at` ) < 1 and `type` = 'shop'" )->count();
      $rent_office_count = D('RentCommon')->where( "DATEDIFF( now(), `created_at` ) < 1 and `type` = 'office'" )->count();
      $rent_wait_audit = D('RentCommon')->where( "`status` != 1" )->count();
      ?>
      <div class="list">
        <h1><b>二手房数据概括</b></h1>
        <ul>
          <li>今日发布出售: <font>(<?php echo $sale_count; ?>套)</font></li>
          <li>今日住宅出售: <font>(<?php echo $sale_house_count; ?>套)</font></li>
          <li>今日商铺出售: <font>(<?php echo $sale_shop_count; ?>套)</font></li>
          <li>今日写字楼出售: <font>(<?php echo $sale_office_count; ?>套)</font></li>
          <li>待审核出售: <font>(<?php echo $sale_wait_audit; ?>套)</font></li>
          <li></li>
          <li>今日出租总计: <font>(<?php echo $rent_count; ?>套)</font></li>
          <li>今日住宅出租: <font>(<?php echo $rent_house_count; ?>套)</font></li>
          <li>今日商铺出租: <font>(<?php echo $rent_shop_count; ?>套)</font></li>
          <li>今日写字楼出租: <font>(<?php echo $rent_office_count; ?>套)</font></li>
          <li>待审核出租: <font>(<?php echo $rent_wait_audit; ?>套)</font></li>
        </ul>
      </div>
      <?php
    }
  }