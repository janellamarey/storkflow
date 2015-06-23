<?php
class Custom_Controller_Action_Helper_DateFormatter extends Zend_Controller_Action_Helper_Abstract
{    

    public function formatUIDateToDbDate($sDate)
    {
        $aDateParts = explode('/', $sDate);
        $aDateConfig = array(
                                'year'=> $aDateParts[2],
                                'month'=> $aDateParts[0],
                                'day'=> $aDateParts[1]
                            );
        $oDate = new Zend_Date($aDateConfig);
        return $oDate->toString('YYYY-MM-dd');
    }


    public function formatDbDateToUIDate($sDate)
    {
        $oDate = new Zend_Date($sDate, Zend_Date::ISO_8601);
        return $oDate->toString('MMMM dd, YYYY');
    }

}