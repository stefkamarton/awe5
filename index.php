<?php

function HTTP_AUTH() {
    $AUTH_USER = 'uzletembermagazin';
    $AUTH_PASS = '123456';
    header('Cache-Control: no-cache, must-revalidate, max-age=0');
    $has_supplied_credentials = !(empty($_SERVER['PHP_AUTH_USER']) && empty($_SERVER['PHP_AUTH_PW']));
    $is_not_authenticated = (
            !$has_supplied_credentials ||
            $_SERVER['PHP_AUTH_USER'] != $AUTH_USER ||
            $_SERVER['PHP_AUTH_PW'] != $AUTH_PASS
            );
    if ($is_not_authenticated) {
        header('HTTP/1.1 401 Authorization Required');
        header('WWW-Authenticate: Basic realm="Access denied"');
        die("Nincs hozzáférés");
        exit;
    }
}

HTTP_AUTH();

session_start();

require_once 'core/autoload.php';
global $awe;
/**
 * @name $awe
 * @global class $GLOBALS
 * @var string $GLOBALS AWE
 * @see Example::getDemoData()
 */
$awe = new AWE(array());
/* Core osztályok hívása */
$awe->coreInit(array());
//$awe->Translator->Viewer(array());
//var_dump($GLOBALS['awe']->LoadComponents(array("name"=>"adm_url", "params"=>array("type"=>"edit"))));


/*
  $A = array(
  "table" => "core_url",
  /* "limit"=>"3",
  "offset"=>"5", */
/* "orderby"=>array(
  "url_id" => "ASC",
  "url_pos" => "DESC"),
  "groupby" => array(
  "url_pos"),
  "having" => array(
  "OR" => array(
  "url_pos" => "1",
  "AND" => array(
  "." => ">",
  "url_pos" => "2"))),
  "distinct" => false,
  "projection" => array(),
  "where" => array(
  "OR" => array(
  "."=>">",
  "url_pos" => "0",
  "AND" => array(
  "." => ">",
  "url_pos" => "2"))),
  "joins" => array(
  0 => array(
  "type" => "INNER",
  "table" => "core_url_template",
  "ON" => "core_url.url_url = core_url_template.url_url")
  )
  ); */
/* Template betöltő hívása */

$awe->Template->Load(array());
?>
<script>
    $(document).ready(function () {
        $('.expanded-btn').on("click", function () {
            var element = $(this).closest(".expanded-directory").next(".expanded");
            if (element.is(":hidden")) {
                $(this).css({'transform': 'rotate(90deg)'});
                element.show(500);
            } else {
                element.hide(500);
                $(this).css({'transform': 'rotate(0deg)'});
                element.find(".expanded").hide(500);
                element.find(".expanded-btn").css({'transform': 'rotate(0deg)'});
            }

        });
    });</script>
<style>
    li{
        margin-left:30px;
        list-style-type: none;
    }
    li.alone{
        margin-left:45px;
    }
    ul.expanded{
        margin-left:30px;
        display:none;
    }
    .expanded-btn{
        display:inline-block;
        width:10px;
        text-align: center;
    }
</style>
