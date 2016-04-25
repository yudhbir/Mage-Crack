<?php 
include_once('simple_html_dom.php');
$states = file_get_html('http://www.cbseguess.com/schools/cbse-schools-india.php');

foreach($states->find('a[class=statename]') as $state) {
    $item['state_link']     = $state->href; 
    $item['state_name']     = $state->plaintext; 
    $state_result[] = $item;
}

$final_result=array();
foreach($state_result as $k=>$state)
{

	$state_link= $state['state_link'];
	$cities_info = file_get_html($state_link);
	$cities=array();
	foreach($cities_info->find('a[class=statename]') as $city) 
	{
		$st_result=[];
		$st_result['city_link']     = $city->href; 
		$st_result['city_name']     = $city->plaintext; 

		$cities[] = $st_result;
	}
	$state_result[$k]['cities']=$cities;
	
 }
$string = serialize($state_result);
$fn= "information.txt";
$fh = fopen($fn, 'w');
fwrite($fh, $string);
fclose($fh);
$str = file_get_contents('information.txt');
$arr_info = unserialize($str);
// echo "<pre>";print_r($arr_info);echo "</pre>";
$state_result=$arr_info;

$i=0;
foreach($state_result as $schools)
{
	$school_cities=$schools['cities'];
	$state_name=$schools['state_name'];
	$final=array();
	if(!empty($school_cities))
	{
		foreach($school_cities as $cities)
		{
			$html = file_get_html($cities['city_link']);
			$elements=$html->find('table .school-wrper');
			if(!empty($elements))
			{
				foreach($elements as $school)
				{
					$item['school_img']     = $school->find('.school-pic')[$i]->find('img')[$i]->src;
					$item['school_link']     = $school->find('.school-dtl .text14 strong')[$i]->find('a')[$i]->href;
					$item['school_name']     = $school->find('.school-dtl .text14 strong')[$i]->find('a')[$i]->plaintext;	
					$item['location_country']     = $school->find('.school-dtl .bodytext',$i)->plaintext;
					$school_info[] = $item;
				}
			}else
			{
				$school_info[]="No School Found";
			}
		
			// $items['city_link']= $cities['city_link'];
			// $items['city_name']= $cities['city_name'];
			
			// $final[]=$items;
		}
	}
	
}
echo "<pre>";print_r($school_info);echo "</pre>";
die; 

// $html = file_get_html('http://www.cbseguess.com/schools/city/barpeta-752');
// foreach($html->find('table') as $article) {
    // $item['title']     = $article->find('.heading2', 0)->plaintext;
    // $item['searchresult']     = $article->find('.searchresult', 0)->plaintext;  
    // $search_result[] = $item;
// }

// $elements=$html->find('table .school-wrper');
// $i=0;
// if(!empty($elements)){
// foreach($elements as $school) {
	
    // $item['school_img']     = $school->find('.school-pic')[$i]->find('img')[$i]->src;
    // $item['school_link']     = $school->find('.school-dtl .text14 strong')[$i]->find('a')[$i]->href;
    // $item['school_name']     = $school->find('.school-dtl .text14 strong')[$i]->find('a')[$i]->plaintext;
    // $item['sec_section']     = $school->find('.school-dtl .greensmall', $i)->plaintext;
    // $item['location_country']     = $school->find('.school-dtl .bodytext',$i)->plaintext;
    // $school_info[] = $item;
	
	
// }
// }else{$school_info[]="NULL";}

// echo "<pre>";print_r($school_info);echo "</pre>";
 ?>