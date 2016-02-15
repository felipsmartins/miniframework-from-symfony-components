<?php 

namespace Calendar\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Calendar\Model\LeapYear;

class LeapYearController
{
    public function index(Request $request, $year)
    {
        $leapyear = new LeapYear();
        
        if ($leapyear->isLeapYear($year)) {
            $response = 'Yep, this is a leap year!';
        } else {
        	$response = 'Nope, this is not a leap year.';
        }

        // $response->setTtl(10); # cache 10s

        return ($response);
    }
}