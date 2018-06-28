<?php

namespace Cron;

class Test extends \Cron\CronAbstract {

    public function main() {
        $beginTime = time();
        $token = '859476648b3de65d76804906dd1a1c6a';
        while (time() - $beginTime < 60) {
            $ip = rand(1, 255) . '.' . rand(1, 255) . '.' . rand(1, 255) . '.' . rand(1, 255);
            $url = 'http://api.ip138.com/query/?ip=' . $ip . '&datatype=jsonp&callback=find&token=' . $token;
            $http = new \Ku\Http();
            $http->setUrl($url);
            $http->send();
            $this->mobile();
            sleep(1);
        }
    }
    
    
    
    public function mobile(){
        $token = '40076e6976cd11325eaef4e0bf6995e8';
        $mobile = '13'.  rand(100000000, 999999999);
        $url = 'http://api.dev.ip138.com/mobile/?mobile=' . $mobile . '&datatype=jsonp&callback=find&token=' . $token;
        echo $url."\r\n";
        $http = new \Ku\Http();
        $http->setUrl($url);
        $http->send();  
    }
}
