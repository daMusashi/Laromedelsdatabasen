<?php

final class TEXT {
	
	const TITEL = "Älvkullens läromedelsbokning";


	public static $SITE_TITEL = "";
}

TEXT::$SITE_TITEL = TEXT::TITEL . "(v.". Config::VERSION . ")";