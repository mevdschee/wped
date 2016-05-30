#!/usr/bin/php
<?php
$user_agent = 'Wped/0.21 (http://github.com/mevdschee/wped) PHP-Curl';
$domains = array('wped'=>'wikipedia.org','wikt'=>'wiktionary.org');

$args = $argv;
$arg0 = substr($argv[0],strrpos($argv[0],'/')+1);

$full = false;
$site = $arg0;
$lang = `locale | grep LANGUAGE | cut -d= -f2 | cut -d_ -f1`;
$lang = strlen($lang)==2?$lang:'en';
$url = 'https://{lang}.{domain}/w/api.php';

array_shift($args);
while (count($args) && $args[0]) {
	if ($args[0]=='-f') {
		array_shift($args);
		$full = true;
	} elseif ($args[0]=='-s`') { 
		array_shift($args);
		$site = array_shift($args);
	} elseif ($args[0]=='-l') { 
		array_shift($args);
		$lang = array_shift($args);
	} elseif ($args[0]=='-u') { 
		array_shift($args);
		$url = array_shift($args);
	} else {
		break;
	}
}

$domain = isset($domains[$site])?$domains[$site]:array_shift($domains);
$url = str_replace(array('{lang}','{domain}'),array($lang,$domain),$url);

$search = implode(' ',$args);

if (!count($args)) die("Usage: $arg0 [-f] [-l lang] <search keyword(s)>\n");

$curl = curl_init();
curl_setopt($curl, CURLOPT_FOLLOWLOCATION, true);
curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
curl_setopt($curl, CURLOPT_USERAGENT, $user_agent);

$arguments = array(
		'action'=>'opensearch',
		'search'=>$search,
		'limit'=>3,
		'namespace'=>0,
		'format'=>'xml'
);

curl_setopt($curl, CURLOPT_URL, $url.'?'.http_build_query($arguments));
$results = simplexml_load_string(curl_exec($curl));
if (!$results) die("Error searching '$search'\n");

$text = '';
if ($results->Section->Item->count()<1) {
	$text = '<h1>No results found</h1>';
} else if ($full) {
	$title = (string)$results->Section->Item->Text;
	$arguments = array('action'=>'parse','prop'=>'text','page'=>$title,'format'=>'xml');

	curl_setopt($curl, CURLOPT_URL, $url.'?'.http_build_query($arguments));
	$page = simplexml_load_string(curl_exec($curl));

	if (!$page) die("Error retrieving '$search'\n");

	$attributes = $page->parse->attributes();

	$text = '<h1>'.$attributes['title'].'</h1>'.$page->parse->text;
	$text.= '<p align="center">source: '.$results->Section->Item->Url.'</p>';
} else {
	$text = '<h1>Search results</h1>';
	foreach ($results->Section->Item as $item) {
		$text.= '<h2><a href="#">'.$item->Text.'</a></h2><p>'.$item->Description.'</p>';
	}
}

$elinks = trim(`which elinks`);

if (!$elinks) die("Could not find elinks (is it installed?)\n");

$descs = array(array('pipe', 'r'),array('pipe', 'w'),array('file', 'php://stderr', 'a'));
$opts = ' -force-html -localhost 1 -no-connect 1 -no-numbering -no-references -dump';
$proc = proc_open($elinks.$opts, $descs, $fp);

if (!is_resource($proc)) die("Could not start elinks\n");

fwrite($fp[0], "\n$text\n");
fclose($fp[0]);

echo fgets($fp[1]);
while (!feof($fp[1])) echo fgets($fp[1]);
fclose($fp[1]);
