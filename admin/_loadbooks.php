<?php
// PHP DOM http://se2.php.net/manual/en/book.dom.php
// PHP XPATH (används av PHP DOM) http://www.w3schools.com/XPath/xpath_syntax.asp

/*
http://bookitpub.karlstad.se/web/pub/search?p_p_id=searchResult_WAR_arenaportlets&p_p_lifecycle=1&p_p_state=normal&p_p_mode=view&p_p_col_id=column-2&p_p_col_pos=1&p_p_col_count=4&_searchResult_WAR_arenaportlets__wu=%2FsearchResult%2F%3Fwicket%3Ainterface%3D%3A4%3AsearchResultPanel%3AcontainerNavigatorTop%3AnavigatorTop%3Anavigation%3A1%3ApageLink%3A%3AILinkListener%3A%3A
http://bookitpub.karlstad.se/web/pub/search?p_p_id=searchResult_WAR_arenaportlets&p_p_lifecycle=1&p_p_state=normal&p_p_mode=view&p_p_col_id=column-2&p_p_col_pos=1&p_p_col_count=4&_searchResult_WAR_arenaportlets__wu=%2FsearchResult%2F%3Fwicket%3Ainterface%3D%3A4%3AsearchResultPanel%3AcontainerNavigatorTop%3AnavigatorTop%3Anavigation%3A2%3ApageLink%3A%3AILinkListener%3A%3A
*/



ini_set('display_errors',1);
ini_set('display_startup_errors',1);
error_reporting(-1);
?>

<h1>Bokimport</h1>
<?php
$url = "http://bookitpub.karlstad.se/web/pub/search?p_p_id=searchResult_WAR_arenaportlets&p_p_lifecycle=1&p_p_state=normal&p_p_mode=view&p_p_col_id=column-2&p_p_col_pos=1&p_p_col_count=4&facet_queries=&search_item_no=0&sort_advice=field%3DRelevance%26direction%3DDescending&arena_member_id=383116721&agency_name=ASE100112&search_type=solr&search_query=departmentId_index%3AASE100112%7C10004%7C10121%7C316";
//$url = "http://bookitpub.karlstad.se/web/pub/search?p_p_id=searchResult_WAR_arenaportlets&p_p_lifecycle=1&p_p_state=normal&p_p_mode=view&p_p_col_id=column-2&p_p_col_pos=1&p_p_col_count=4&facet_queries=&search_item_no=0&sort_advice=field%3DRelevance%26direction%3DDescending&arena_member_id=383116721&agency_name=ASE100112&search_type=solr&search_query=departmentId_index%3AASE100112%7C10004%7C10121%7C316&_searchResult_WAR_arenaportlets__wu=%2FsearchResult%2F%3Fwicket%3Ainterface%3D%3A4%3AsearchResultPanel%3AcontainerNavigatorTop%3AnavigatorTop%3Anavigation%3A6%3ApageLink%3A%3AILinkListener%3A%3A";

/*
// använd CURL om nedan - file_get_contents inte är tillåtet
$html =  file_get_contents($url) or die("Kunde inte läsa webbsidan $url");
*/




/*
print "<h2>DOM get</h2>";
$elements = $doc->getElementsByTagName('a');

if (!is_null($elements)) {
  foreach ($elements as $element) {
    $class = $element->getAttribute('class');
	echo "<br/>". $element->nodeName. "(class: " . $class . "): ";

    $nodes = $element->childNodes;
    foreach ($nodes as $node) {
      echo $node->nodeValue. "\n";
    }
  }
}
*/

/*
if(!$doc = loadPage($url)){
	die("Kunda inte ladda första sidan");	
}
*/

$collectedBookLinks = array();

print "<h2>Samlar</h2>";

$index = 1;
while($url){
	if(!$doc = loadPage($url)){
		die("Kunda inte ladda första sidan");	
	}
	
	print "<h2>Page $index</h2>";
	$url = collectBookLinks($doc, $collectedBookLinks);
	
	if($index == 2){
		break;	
	}
	$index++;
}
//print "<p>NEXT: $next</p>";

?>
<?php
// functions

function loadPage($url){
	$doc = new DOMDocument();
	if($doc->loadHTMLFile($url)){
		//print $doc->saveHTML();	
		return $doc;	
	} else {
		return false;	
	}
}

function collectBookLinks($domPage, &$addToArray){
	$xpath = new DOMXpath($domPage);
	$atags = $xpath->query("//div[@class='arena-record-title']/a");
	$nextPage = $xpath->query("//span[@class='arena-record-right']");
	if(!is_null($nextPage)){
		$span = $nextPage->item(0);
		$a = $span->parentNode;
		$nextPageURL = $a->getAttribute('href');
	} else {
		$nextPageURL = false;
	}

	if (!is_null($atags)) {
  		foreach ($atags as $element) {
    		//$class = $element->getAttribute('class');
			//print "<br/>". $element->nodeName. "(class: " . $class . "): ";

    		$title = $element->nodeValue;
			$url = $element->getAttribute('href');
			//print "<p><strong>$title</strong> ($url)</p>";
			print "<p><strong>$title</strong></p>";
			$addToArray[$url] = $title;
			
			mineBookPage(loadPage($url), $title);
			/*
			$nodes = $element->childNodes;
   			foreach ($nodes as $node) {
      			$title = $node->nodeValue;
	 			$url = $node->getAttribute('href');
	  			print "$title ($url)\n";
   		 	}
			*/
  		}
	}
	
	return $nextPageURL;	
}

function mineBookPage($domPage, $titelShort){
	$xpath = new DOMXpath($domPage);
	
	$bok["titel"] = $titelShort;	
	$bok["upplaga"] = "";
	$bok["forf_efternamn"] = "";		
	$bok["forf_fornamn"] = "";	
	$bok["isbn"] = "";	
	$bok["antal"] = "";	
	$bok["undertitel"] = "";	
	$bok["forlag"] = "";
	
	$bok["upplaga"] = mineArenaDetailValue($xpath, "edition");
	$bok["forlag"] =  mineArenaDetailValue($xpath, "publisher", "record");
	$namn = mineArenaDetailValue($xpath, "author");
	$namn = explode(",", $namn);
	$bok["forf_efternamn"] = $namn[0];
	$bok["forf_fornamn"] = $namn[1];
	
	print "<p>upplaga: $upplaga</p>";
	print "<p>forlag: $forlag</p>";
}

function mineArenaDetailValue($xpath, $classExtension, $type = "detail"){
	$element = $xpath->query("//div[@class='arena-".$type."-".$classExtension."']/span[@class='arena-value']");
	if (!is_null($element)) {
  		$span = $element->item(0);
		return $span->nodeValue;
	} else {
		return "";	
	}
}
	
function xQuery($xPathObject, $tag, $tagClass="", $tagId=""){
	if($tagClass == ""){
		$class = "";	
	} else {
		$class = " class=\"$tagClass\"";	
	}
	if($tagClass == ""){
		$class = "";	
	} else {
		$class = " class=\"$tagClass\"";	
	}
	
	// http://www.php.net/manual/en/function.get-meta-tags.php#49559
	$start = "<$tag>";
	$end = "<\/$tag>";
	$preg_str = "/".$start."(.*)".$end."/s";
	$preg_str = "/<a>(.*)<\/a>/s";
	print "<h2>dump:</h2>";
	var_dump($preg_str);
	print "<h2>dump:</h2>";
	//var_dump($html);
	//print "<pre><code>$html</code></pre>";
	print "<h2>slut</h2>";
	preg_match($preg_str , $html, $tags );
	
	return $tags;
}

?>
