<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019\5\29 0029
 * Time: 10:09
 */
namespace Home\Controller;

use Think\Controller;
use Think\Think;
class TaskController extends Controller
{

    //每天下午8点执行没有完成付款的订单进入到抢单大厅的任务
    public function wfk_to_qgdt_task()
    {
        //指定时间段内【后台指定】，晚上8点之前或者指定时间点未付款的订单
        //【统一执行修改ts_zt状态为1的方法，自动投诉,ppdd表中将is_qgdt的状态修改为1，将该ppdd记录对应的tgbz表中的记录的isreset的状态修改为1】
        //【自动投诉后订单在交易大厅展示】
        zdsjwfk_ppdd_to_ts_zt1();
        exit(0);
    }

    //平台的当前的开仓量【明日的开仓量】，每天八点后开始计算，第二天的开仓总量
    public function plan_get_cur_opening_quantity()
    {
        get_cur_opening_quantity();
        exit(0);
    }

}