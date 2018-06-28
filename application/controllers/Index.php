<?php

class IndexController extends \Base\ApplicationController
{
    public function indexAction()
    {
       return $this->returnData('test');
    }
}
