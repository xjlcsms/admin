<?php
namespace Ku;
/**
 * 
 *借贷相关的计算器
 * @author wuzhh
 */
class FinanceTool {



    public static function count(float $money, float $rate, float $month, $startDate, int $repayType){


    }











    /**
     * 返回数组
     * 计算器计算结果
     * @param array $data 必须  需包含字段 money rate month time repayType
     * @param bool $all 0||1 可选  是否只返回全部应还本息,默认0
     * @param num $sericesFee 可选  传入管理费率,例如3 则代表费率为3%,没传则不返回管理费
     * @return array
     */
    public static function CounterResult($data,$all='',$sericesFee=0)
    {
        if(isset($data['repayType']))
        {
            $total = (float)$data['money'];
            $rates = (float)$data['rate'];
            $month = (float)$data['month'];
            $halfMonth='';
            if ((int)$month != $month)
            {
                if (preg_match('/^[0-9]\d*([.][1-9])?$/', $month) && $data['repayType']=='2')
                {
                    $a = explode('.',$month);
                    $month = floor($month);
                    $halfMonth = $a['1'];
                }
            }
            $time = date('Y-m-d',strtotime($data['time']));
            switch ($data['repayType'])
            {
                case 1:$datares = self::equal($total,$rates,$month,$time,$all,$sericesFee);
                    break;
                case 2:$datares = self::debtService($total,$rates,$month,$time,$all,$sericesFee,$halfMonth);
                    break;
                default:return false;break;
            }
            return $datares;
        }
        else 
        {
            return false;
        }
    }
    /**
     *  $total 为贷款总金额,$rates为年利率,$month为借款月数,$time 借款完成的日期(起息日的前一天)
     * 计算等额本息每月付的本息金额
     * @param str $total,$rates,$month,$time
     * @param bool $all 0||1 是否返回全部应还本息
     * @param num $sericesFee 有参数则返回的数组里有管理费
     * @return array
     */
    public static function equal($total,$rates,$month,$time,$all='',$sericesFee=0)
    {
        $repayOneMonth = ($total*$rates/1200*pow((1+$rates/1200),$month))/(pow((1+$rates/1200),$month)-1);
        $equal = self::noRound($repayOneMonth,2);
        if ($all)
        {
            return $equal*$month;
        }
        if ($month>1)
        {
            $data = self::left($total, $rates, $equal, $month,$time,$sericesFee);
            return $data;
        }else if ($month=='1')
        {
           $repayTime = $this->repayTime($time);
           $data[$repayTime]['equal'] =$equal;
           $data[$repayTime]['principal'] = $total;
           $data[$repayTime]['interest'] = bcsub($equal , $total,2);
           $data[$repayTime]['leftToal'] = 0;//剩余本金
           $data[$repayTime]['PrincipalBalance']=0;//剩余本息
           $data[$repayTime]['sericesFee'] = $total*$sericesFee/1200;//管理费
           $data[$repayTime]['time'] = 1;
           return $data;
        }else 
        {
            return false;
        }
    }
    /*
     * 循环计算每个月应还的本息及本金和剩余本金
     */
    private static function left($total,$rates,$equal,$month,$time,$sericesFee)
    {
        $dealSericesFee=$total*$sericesFee/1200;//管理费
        for ($i=1;$i<$month;$i++)
        {
            /*
             * 当前还款日日期如果为空 则传入成交日期
             */
            if (!empty($repayTime))
            {
                $repayTime = self::repayTime($time,$repayTime);
            }else 
            {
                $repayTime = self::repayTime($time);
            }
            
            $interest = self::noRound($total*$rates/1200,2);
            $principal = bcsub($equal , $interest,2);
            $left = bcsub($total , $principal,2);
            $data[$repayTime]['equal'] = $equal;//月还本息
            $data[$repayTime]['interest'] = $interest;//月还利息
            $data[$repayTime]['principal'] = $principal;//月还本金
            $data[$repayTime]['leftToal'] = $left;//剩余本金
            $data[$repayTime]['PrincipalBalance']=$equal*($month-$i);//剩余本息
            $data[$repayTime]['sericesFee'] = $dealSericesFee;//管理费
            $data[$repayTime]['time'] = 1;
            $total =$left;
        }
        $repayTime = self::repayTime($time,$repayTime);
        $data[$repayTime]['equal'] =$equal;
        $data[$repayTime]['interest'] = bcsub($equal , $left,2);
        $data[$repayTime]['principal'] = $left;
        $data[$repayTime]['leftToal'] = 0;
        $data[$repayTime]['PrincipalBalance']=0;
        $data[$repayTime]['sericesFee'] = $dealSericesFee;//管理费
        $data[$repayTime]['time'] = 1;
        return $data;
    }
    /**
     * 按月付息到期还本计算每月还款金额
     * @param str $total,$rates,$month,$time
     * @param bool $all 0||1 是否返回全部应还本息
     * @param num $sericesFee 有参数则返回的数组里有管理费
     * @return array
     */
    public static function debtService($total,$rates,$month,$time,$all='',$sericesFee=0,$halfMonth='')
    {
        $dayRates = $rates/36500; //计算每天的利息,年利息 除以365天在除以100
        $lastDay1 = strtotime("+1 day");//起息日
        $lastDay2 = strtotime(date('Y-m',$lastDay1).'-10');//构造一个时间,判断最后一个月是否有这天
        $lastDay3 = date('t',strtotime("+$month month",$lastDay2));//取出最后一个月的天数
        if ($lastDay3>=date('d',strtotime("+1 day",strtotime($time)))){
            $lastDay = date('Y-m-d',strtotime("+$month month",$lastDay1));
        }else{
            $lastDay = date('Y-m',strtotime("+$month month",$lastDay2)).'-'.$lastDay3;
        }
        if ($halfMonth)
        {
            $day = 3*$halfMonth;
            $lastDay = date('Y-m-d',strtotime("+$day day",strtotime($lastDay)));
        }
        if ($all)
        {
            $firstDay = date('Y-m-d',strtotime("+1 day",strtotime($time)));//第一天计息日
            $datetime5 = new \DateTime($firstDay);
            $datetime6 = new \DateTime($lastDay);
            $totalDays = date_diff($datetime5, $datetime6);
            $days = $totalDays->format('%a');//计算利息的天数
            return self::noRound($total+($total*$dayRates*$days),4);
        }
        for ($i=1;$i<=$month;$i++)
        {
            
            /*
             * 当前还款日日期如果为空 则传入成交日期
            */
            if (!empty($repayTime))
            {
                $valueDate = $repayTime;//计算每个月的起息日
                $repayTime = self::repayTime($time,$repayTime);
            }else
            {
                $valueDate = date('Y-m-d',strtotime('next day',strtotime($time)));//计算每个月的起息日
                $repayTime = self::repayTime($time);
            }
            /*
             * 计算利息天数
             */
            $datetime1 = new \DateTime($valueDate);
            $datetime2 = new \DateTime($repayTime);
            $interval = date_diff($datetime1, $datetime2);
            $days = $interval->format('%a');//计算利息的天数
            /*
             * 计算剩余的利息天数
             */
            $datetime3 = new \DateTime($repayTime);
            $datetime4 = new \DateTime($lastDay);
            $interval1 = date_diff($datetime3, $datetime4);
            $leftDays = $interval1->format('%a');//计算利息剩余的天数
            /*
             * 计算利息
             */
            $data[$repayTime]['interest'] =self::noRound($total*$dayRates*$days,4);//月还利息
            if ($i == $month && !$halfMonth)
            {
                $data[$repayTime]['leftToal'] =0;
                $data[$repayTime]['principal'] = $total;//月还本金
                $data[$repayTime]['PrincipalBalance'] =0;
            }else if ($i == $month && $halfMonth)
            {
                $data[$repayTime]['leftToal'] =$total;
                $data[$repayTime]['principal'] = 0;
                $data[$repayTime]['PrincipalBalance'] = self::noRound($total+($total*$dayRates*$leftDays),4);
            }else
            {
                $data[$repayTime]['leftToal'] =$total;
                $data[$repayTime]['principal'] = 0;
                $data[$repayTime]['PrincipalBalance'] = self::noRound($total+($total*$dayRates*$leftDays),4);
            }
            $data[$repayTime]['sericesFee'] = $total*$sericesFee/1200;//管理费
            $data[$repayTime]['equal'] = $data[$repayTime]['interest']+$data[$repayTime]['principal'];//月还本息
            $data[$repayTime]['time'] = 1;
        }
        if ($halfMonth)
        {
            $data[$lastDay]['leftToal'] =0;
            $data[$lastDay]['principal'] = $total;//月还本金
            $data[$lastDay]['PrincipalBalance'] =0;
            $data[$lastDay]['interest'] =self::noRound($total*$dayRates*$day,4);//月还利息
            $data[$lastDay]['sericesFee'] = $total*$sericesFee/1200/10*$halfMonth;//管理费
            $data[$lastDay]['equal'] = $data[$lastDay]['interest']+$data[$lastDay]['principal'];//月还本息
            $data[$lastDay]['time'] ='0.'.$halfMonth;
        }
        return $data;
    }


    /**
     *根据月份计算总的利息
     *
     *
     */
    public static function statIntByday(float $money, float $rate, float $month, $startDate){
        $dayRates = $rate/36500; //计算每天的利息,年利息 除以365天在除以100
        $startDate = date('Y-m-d',strtotime($startDate));
        $startTime = strtotime("+1 day", strtotime($time));//起息日
        $floatDay = 0;
        if(intval($month) == $month){
            $lastTime = strtotime("+$month month",$startDate);
        }else{
            $monthArr = explode('.', $month);
            $float  = $monthArr[1]%10;
            $floatDay = 3*$float;
            $lastTime = strtotime("+$monthArr[0] month",$startDate);
        }
        $day = ($lastTime-$startTime)/(24*3600)+$floatDay;
        return $money*$dayRates*$day;
    }


    public static function statIntBymonth(float $money, float $rate, float $month, $startDate){
        $dayRates = $rate/36500; //计算每天的利息,年利息 除以365天在除以100
        $startDate = date('Y-m-d',strtotime($startDate));
        $startTime = strtotime("+1 day", strtotime($time));//起息日
        $floatDay = 0;
         if(intval($month) == $month){
            $intMonth = $month;
         }else{
            $monthArr = explode('.', $month);
            $float  = $monthArr[1]%10;
            $floatDay = 3*$float;
            $intMonth = $monthArr[0];
        }
        $dataArr = array();
        $totalInt = self::statIntByday($money, $rate, $month ,$startDate);
        $countMonth = 0;
        $countInt = 0;
        do{
            $theDate = !isset($theDate)?$startDate:$theDate;
            $theTime = strtotime($theDate);
            $nextTime = strtotime("+1 month",strtotime($repayDate));
            $nextDate = date('Y-m-d',$nextTime);
            $theDay = ($nextTime - $theTime)/(24*3600);
            $data[$nextTime]['money'] =$money;
            $int = self::noRound(($money*$theDay*$dayRates),4);
            $data[$nextTime]['int'] = $int;
            //下个月
            $theDate = date('Y-m-d',$nextTime);
            $countInt += $int;
            $countMonth++;
        }while ($countMonth<$intMonth);
        //最后一个月的利息用总天数的计算数值减去其他月份的还款，保证分开计算的总利息等于合起来计算的总利息
        if($floatDay){
            $lastTime = strtotime("+$floatDay day",strtotime($theDate));
            $lastDate = date('Y-m-d', $lastTime);
            $data[$lastDate]['money'] = $money;
            $data[$lastDate]['int'] = $totalInt - $countInt;
        }else{

            $lastDate = $theDate;
            $data[$lastDate]['int'] = $totalInt - ($countInt- $data[$lastDate]['int']);
        }
        return $data;
    }



    
    /*
     * 计算下一个月的还款日期
     */
    public static function repayTime($time,$repayTime='')
    {
        $time = strtotime($time);
        if (!$repayTime)
        {
            $repayTime=date('Y-m-d',strtotime('next day',$time));
        }
        $day = date('d',strtotime('next day',$time));//计算计算利息日期天
        if ($repayTime)
        {
            $repayTime=strtotime($repayTime);
            $TecDay = date('Y-m',$repayTime).'-10'; //构造一个当前还款日时间当月的10号,为了计算下一个月最后一天是几号
        } else 
        {
            $TecDay = date('Y-m',$time).'-10'; //构造一个完成时间当月的10号,为了计算下一个月最后一天是几号
        }
        $TecTime=strtotime($TecDay);
        $nextMonthLastDay = date('t',strtotime('next month',$TecTime));//计算下一个月天数
        $nextTime = ($day<$nextMonthLastDay)?date('Y-m',strtotime('next month',$repayTime)).'-'.$day:date('Y-m-t',strtotime('next month',$TecTime));
        return $nextTime;
    }


    public static function noRound($num,$digit='2',$format='')
    {
    if (!$num)
    {
        $num = '0.00';
        return $num;
    }
    if(strstr($num,'E-') || strstr($num,'e-')){
        $num = '0.00';
        return $num;
    }
    $num1 = $num;
    $num1 = (string)($num1);
    if (ceil($num1) == $num1 && $num >= 0)
    {
        if ($format)
        {
            return number_format($num,$digit);
        }
        return number_format($num,$digit,".","");
    }
    $p= stripos($num, '.')+1;
    $number = substr($num,0,$p+$digit);
    if ($format)
    {
        return number_format($number,$digit);
    }
    return number_format($number,$digit,".","");
}



}