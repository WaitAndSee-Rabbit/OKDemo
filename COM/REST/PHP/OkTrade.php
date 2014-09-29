<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=gb2312" />
<title>无标题文档</title>
</head>
<?php

$Ok=new OkAPI ;
//var_dump( $Ok->Fund());
//var_dump( $Ok->CancelOrder(23456));
 var_dump( $Ok->Trade(1000,0.11,"buy"));
//var_dump($Ok->GetOrder("2345"));
class OkAPI {


var  $partner=// "45678912";
var  $secretKey ="";//"19535CF3D949D4EF56F8D3D4ED78C505";

  
 
 
protected   function ok_query($parameters, $url){
      
	  
	  $post_data =http_build_query($parameters, '', '&');

	   echo  $post_data;
	  $sign=md5 ($post_data.$this->secretKey );
	  $sign=strtoupper($sign);
	  
	  var_dump($sign);
	  
	  $post="partner=".$this->partner."&sign=".$sign."&".$post_data;
	  var_dump($post);
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_URL, $url);
	    curl_setopt($ch, CURLOPT_POSTFIELDS, $post);
		
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
        $res = curl_exec($ch);
		$res=json_decode ($res,true);
		return $res;

}





   function MarketDepth($N=5){
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, "https://www.okcoin.com/api/depth.do ");
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
$res= curl_exec($ch);
       
	 $res=json_decode ($res,true);
	  
     $res_ask=array_reverse(array_slice($res["asks"] , -$N, $N));
	 
	 $res_bid=array_slice($res["bids"] , 0, $N) ;
	 
	 $ans=array("asks"=>$res_ask,"bids"=>$res_bid);
	 return $ans;
  }
       
	   
function  Trade($Price,$Amount,$Direction){

					  
	 $parameters=array("amount"=>$Amount,"partner"=>$this->partner,"rate"=>$Price,"symbol"=>'btc_cny',
	                  "type"=>strtolower($Direction));
					  
      $url= 'https://www.okcoin.com/api/trade.do';
       
	  $res=$this->ok_query($parameters, $url);
	  return $res;
}		
		
	
	 
  function	CancelOrder($OrderID){
	 
	
	 $parameters=array("order_id"=>$OrderID,"partner"=>$this->partner,"symbol"=>"btc_cny");
     $url='https://www.okcoin.com/api/cancelorder.do';
	  $res=$this->ok_query($parameters, $url);
	  return $res;
	}
 
 function Fund()
{
      $parameters=array("partner"=>$this->partner);
     
      $url='https://www.okcoin.com/api/userinfo.do';
	  $res=$this->ok_query($parameters, $url);
	//var_dump($res);
	 if($res["result"] ){
	  $res=array("result"=>true, "Frozen"=>array("BTC"=>$res["info"]["funds"]["freezed"]["btc"], "CNY"=>$res["info"]["funds"][     "freezed"]["cny"]),
	   "Free"=>array("BTC"=>$res["info"]["funds"]["free"]["btc"], "CNY"=>$res["info"]["funds"]["free"]["cny"]) ); 
	  return $res;
	  }
	 
	  $res=array("result"=>false);
	  return $res;
}	

 function  GetOrder($OrderID){
         $parameters=array("order_id"=>$OrderID,"partner"=>$this->partner,"symbol"=>"btc_cny");
		 $url= 'https://www.okcoin.com/api/getorder.do';
		 $res=$this->ok_query($parameters, $url);
		 return $res;
}
}
?>
<body>
</body>
</html>
