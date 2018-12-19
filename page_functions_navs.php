<?php

require_once("class_termin.php");

function getBockerAjaxCharTab($ajaxNav, $ajaxTarget){
	$bocker = Bok::getAll();

	$chars = [];
	foreach($bocker as $bok){
		$char = $bok->fullTitel[0];

		if(!in_array($char, $chars)){
			array_push($chars, strtolower($char));
		}
	}

	array_push($chars, "*");

	$html = "<ul id=\"bocker-char-tab\" class=\"nav nav-tabs welled\">";
	foreach($chars as $char){
			
		$active = "";
		if($char == $_SESSION["bok-urval"]){
			$active = " class=\"active\"";
		}

		if($char == "*"){
			$desc = "ALLA";
		} else {
			$desc = strtoupper($char);
		}

		$a = "<a data-bok-urval=\"".strtolower($char)."\" href=\"#\" role=\"button\">".$desc."</a>";

		$tabs[$char] = "<li role=\"presentation\"$active>$a</li>";
			//print "<p>$a</p>";

	}

	ksort($tabs);

	foreach($tabs as $tab){
		$html .=  $tab;
	}

	$html .=  "</ul>";

	$html .= "<script>
				$(document).ready(function(){
					$('#bocker-char-tab a').click(function(e){
						e.preventDefault();
						var urval = $(this).attr('data-bok-urval');
						//alert(id);
						$('#".$ajaxTarget."').html('".Config::LOADING_HTML."');
						$('#bocker-char-tab li').removeClass('active');
						$(this).parent().addClass('active');
						$.get('ajax.php?".Config::PARAM_AJAX."=".$ajaxNav."&".Config::PARAM_ID."='+urval, function(data){
							$('#".$ajaxTarget."').html(data);
						});
					});
				});
			</script>
			";

	return $html;
}

function _getTerminNavHTML($htmlId, $nav_value, $typClass, $activeTerminId, $useLasar = false, $useAjax = false, $ajaxNav = "", $ajaxTarget = "", $ajaxRef = ""){

	$terminer = Termin::getAll();

	$navs = [];
	$usedDescs = [];

	$html = "<ul id=\"$htmlId\" class=\"$typClass\" aria-labelledby=\"".$htmlId."-button\">";
	foreach($terminer as $termin){
		
		$id = $termin->id;
		$desc = $termin->descLong;

		if($useLasar){
			//$id = $termin->lasar->id;
			$desc = $termin->lasar->desc;
		}


		if(!in_array($desc, $usedDescs)){

			$active = "";
			if($id == $activeTerminId){
				$active = " class=\"active\"";
			}

			if($useAjax){
				$a = "<a data-termin-id=\"".$id."\" data-termin-ref=\"".$ajaxRef."\" href=\"#\">".$desc."</a>";
			} else {
				$a = "<a href=\"?".Config::PARAM_NAV."=$nav_value&".Config::PARAM_ID."=".$id."\">".$desc."</a>";
			}

			$navs[$id] = "<li role=\"presentation\"$active>$a</li>";
			array_push($usedDescs, $desc);
		} 
	}

	ksort($navs);

	foreach($navs as $nav){
		$html .=  $nav;
	}

	$html .=  "</ul>";

	
	if($useAjax){
		$html .= "<script>
			$(document).ready(function(){
				$('#".$htmlId." a').click(function(e){
					e.preventDefault();
					var id = $(this).attr('data-termin-id');
					var ref = $(this).attr('data-termin-ref');
					//alert(id);

					$('#".$ajaxTarget."').html('".Config::LOADING_HTML."');

					$('#".$htmlId." li').removeClass('active');
					$(this).parent().addClass('active');
					$('#".$htmlId."-button .current').text($(this).text());

					$.get('ajax.php?".Config::PARAM_AJAX."=".$ajaxNav."&".Config::PARAM_ID."='+id+'&".Config::PARAM_REF_ID."='+ref, function(data){
						$('#".$ajaxTarget."').html(data);
					});

				});
			});
		</script>
		";
	}

	return $html;
}

function getTerminSelectWidget($htmlId, $nav_value, $terminObj, $useAjax = false, $ajaxNav = "", $ajaxTarget = "", $ajaxRef = null){
	//dropdownTerminer
	$html = "<button class=\"btn btn-primary\" type=\"button\" id=\"".$htmlId."-button\" data-toggle=\"dropdown\" aria-expanded=\"true\">
			<span class=\"current\">".$terminObj->descLong."</span>
			<span class=\"glyphicon glyphicon-chevron-down\"></span>
			</button>
	";
	$html .= _getTerminNavHTML($htmlId, $nav_value, "dropdown-menu", $terminObj->id, false, $useAjax, $ajaxNav, $ajaxTarget, $ajaxRef);

	/*$html .= "<script>
				$('#".$htmlId." a').click(function(e){
					$('#".$htmlId."-button .current').text($(this).text());
				});
			</script>
	";*/

	return $html;
}

function getChangeTerminInputHTML($htmlId){

	$terminer = Termin::getAll();

	$navs = [];
	$usedDescs = [];

	$html = "<select id=\"".$htmlId."\">";

	foreach($terminer as $termin){
		
		$id = $termin->id;
		$desc = $termin->descLong;


		if(!in_array($desc, $usedDescs)){

			$navs[$id] = "<option value=\"$id\">$desc</option>";
			array_push($usedDescs, $desc);
		} 
	}

	ksort($navs);

	// lägger till tre terminer till för att kunna byta ännu icke existerande
	$ids = array_keys($navs);
	$last_id = $ids[count($navs)-1];

	$termin = new Termin();
	$termin->setFromId($last_id);
	
	$nextTermin = $termin->getNextTermin();
	$nextNextTermin = $nextTermin->getNextTermin();
	$nextNextNextTermin = $nextNextTermin->getNextTermin();

	$navs[$nextTermin->id] = "<option value=\"".$nextTermin->id."\">".$nextTermin->descLong."</option>";
	$navs[$nextNextTermin->id] = "<option value=\"".$nextNextTermin->id."\">".$nextNextTermin->descLong."</option>";
	$navs[$nextNextNextTermin->id] = "<option value=\"".$nextNextNextTermin->id."\">".$nextNextNextTermin->descLong."</option>";


	foreach($navs as $nav){
		$html .=  $nav;
	}

	$html .=  "</select>";

	return $html;
}

function getTabsHTML($htmlId, $nav_value, $activeTidId, $useLasar = true){
   	return _getTerminNavHTML($htmlId, $nav_value, "nav nav-tabs welled", $activeTidId, $useLasar);
}

function getTabsAjaxHTML($htmlId, $nav_value, $activeTidId, $ajaxNav, $ajaxTarget, $useLasar = true, $ajaxRef = ""){
   	return _getTerminNavHTML($htmlId, $nav_value, "nav nav-tabs welled", $activeTidId, $useLasar, true, $ajaxNav, $ajaxTarget, $ajaxRef);
}

function getBokareNavHTML($htmlId, $ajaxNav, $ajaxTarget){

	$alla = "- alla -";
	if($_SESSION["bokning-bokare"] == "*"){
		$label = $alla;
	} else {
		$label = $_SESSION["bokning-bokare"];
	}
	

	$bokningar = Bokning::getAll();

	$bokare = [];
	foreach($bokningar as $bokning){
		if(!in_array($bokning->bokare, $bokare)){
			array_push($bokare, $bokning->bokare);
		}
	}
	sort($bokare);

	$html = "<button class=\"btn btn-primary\" type=\"button\" id=\"".$htmlId."-button\" data-toggle=\"dropdown\" aria-expanded=\"true\">
			<span class=\"current\">".$label."</span>
			<span class=\"glyphicon glyphicon-chevron-down\"></span>
			</button>
	";

	$navs = [];
	$navs["*"]  = "<li role=\"presentation\"><a class=\"active\" data-termin-id=\"*\" href=\"#\">".$alla."</a></li>";

	foreach($bokare as $larare){
		$a = "<a data-termin-id=\"".$larare."\" href=\"#\">".$larare."</a>";
		$navs[$larare] = "<li role=\"presentation\">$a</li>";
	}

	$html .= "<ul id=\"$htmlId\" class=\"dropdown-menu\" aria-labelledby=\"".$htmlId."-button\">";
	foreach($navs as $nav){
		$html .=  $nav;
	}
	$html .=  "</ul>";

	$html .= "<script>
		$(document).ready(function(){
			$('#".$htmlId." a').click(function(e){
				e.preventDefault();
				var id = $(this).attr('data-termin-id');

				$('#".$ajaxTarget."').html('".Config::LOADING_HTML."');

				$('#".$htmlId." li').removeClass('active');
				$(this).parent().addClass('active');
				$('#".$htmlId."-button .current').text($(this).text());

				$.get('ajax.php?".Config::PARAM_AJAX."=".$ajaxNav."&".Config::PARAM_ID."='+id, function(data){
					$('#".$ajaxTarget."').html(data);
				});

			});
		});
	</script>
	";


	return $html;
}