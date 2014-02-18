#!/usr/bin/php
<?php
require_once(dirname(__FILE__)."/RestAPI.class.php");

#$api_params = array("proxyCompany"=>"TruCraft", "proxyUser"=>"jtruman");
$api_params = array("proxyCompany"=>"aol", "proxyUser"=>"adminsupport");
$rest = new RestAPI($api_params);

$params = array("rs_types"=>"standard");

$rsids = json_decode($rest->call("Company.GetReportSuites", $params), true);

foreach($rsids['report_suites'] as $rsid_obj)
{
	#if(strstr($rsid_obj['rsid'], "dev"))
		echo $rsid_obj['rsid']."\n";
	#if(!in_array($rsid_obj['rsid'], $valid_rsids)) continue;
	#$params = array(array("rsid_list"=>array($rsid_obj['rsid'])));

	#$evars = json_decode($rest->call("ReportSuite.GetEVars", $params), true);
}

?>
