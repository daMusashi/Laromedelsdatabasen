<?php

require_once("class_datalager.php");

if($_SESSION["datalagerDataChanged"]){
        print "
            <script>
                $(document).ready(function(){
                    $.get(\"ajax.php?".Config::PARAM_AJAX."=update-datalager\");
                });
            </script>
        ";
}

