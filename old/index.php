<?php
/* ini_set('upload_max_filesize',"20M");
  ini_set('post_max_size',"20M");
  var_dump(ini_get('upload_max_filesize'));
 */






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

$awe->DB->
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


<?php
$colors = array('#007AFF', '#FF7000', '#FF7000', '#15E25F', '#CFC700', '#CFC700', '#CF1100', '#CF00BE', '#F00');
$color_pick = array_rand($colors);
?>

<!DOCTYPE html>
<html>
    <head>
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <style type="text/css">
            .chat-wrapper {
                font: bold 11px/normal 'lucida grande', tahoma, verdana, arial, sans-serif;
                background: #00a6bb;
                padding: 20px;
                margin: 20px auto;
                box-shadow: 2px 2px 2px 0px #00000017;
                max-width:700px;
                min-width:500px;
            }
            #message-box {
                width: 97%;
                display: inline-block;
                height: 300px;
                background: #fff;
                box-shadow: inset 0px 0px 2px #00000017;
                overflow: auto;
                padding: 10px;
            }
            .user-panel{
                margin-top: 10px;
            }
            input[type=text]{
                border: none;
                padding: 5px 5px;
                box-shadow: 2px 2px 2px #0000001c;
            }
            input[type=text]#name{
                width:20%;
            }
            input[type=text]#message{
                width:60%;
            }
            button#send-message {
                border: none;
                padding: 5px 15px;
                background: #11e0fb;
                box-shadow: 2px 2px 2px #0000001c;
            }
        </style>
    </head>
    <body>

        <div class="chat-wrapper">
            <div id="message-box"></div>
            <div class="user-panel">
                <input type="text" name="name" id="name" placeholder="Your Name" maxlength="15" />
                <input type="text" name="message" id="message" placeholder="Type your message here..." maxlength="100" />
                <button id="send-message">Send</button>
            </div>
        </div>

        <script language="javascript" type="text/javascript">
            var msgBox = $('#message-box');
            var wsUri22 = "ws://awe5.plugin.hu:9000";
            var websocket;

            /* WebSocket Send Message*/
            function __WebSocketFormSend__(obj, other = {}) {
                var sendJson = {};

                /*Form bekérés*/
                var formData = new FormData($(obj).closest('form')[0]);
                var fDObject = {};
                formData.forEach((value, key) => {
                    if (!Reflect.has(fDObject, key)) {
                        fDObject[key] = value;
                        return;
                    }
                    if (!Array.isArray(fDObject[key])) {
                        fDObject[key] = [fDObject[key]];
                    }
                    fDObject[key].push(value);
                });
                sendJson["__forms__"] = JSON.stringify(fDObject);

                /*System bekérés*/
                sendJson["__system__"] = {};
                sendJson["__system__"]["parameters"] = getUrlParameter("params");
                sendJson["__system__"]["domain"] = window.location.origin;
                sendJson["__system__"]["url"] = window.location.pathname;


                /* Other bekérés */
                sendJson["__other__"] = other;
                websocket.send(JSON.stringify(sendJson));
            }

            /*WebSocket Core*/
            function __WebSocket__(wsUri) {
                websocket = new WebSocket(wsUri);
                websocket.onopen = function (ev) { // connection is open 
                    msgBox.append('<div class="system_msg" style="color:#bbbbbb">Welcome to my "Demo WebSocket Chat box"!</div>'); //notify user
                    __WebSocketFormSend__(".asd", {"status": "CONNECT"});
                }
                // Message received from server
                websocket.onmessage = function (ev) {
                    var response = JSON.parse(ev.data); //PHP sends Json data

                    var res_type = response.type; //message type
                    var user_message = response.message; //message text
                    var user_name = response.name; //user name
                    var user_color = response.color; //color

                    switch (res_type) {
                        case 'usermsg':
                            msgBox.append('<div><span class="user_name" style="color:' + user_color + '">' + user_name + '</span> : <span class="user_message">' + user_message + '</span></div>');
                            break;
                        case 'system':
                            msgBox.append('<div style="color:#bbbbbb">' + user_message + '</div>');
                            break;
                    }
                    msgBox[0].scrollTop = msgBox[0].scrollHeight; //scroll message 

                };

                websocket.onerror = function (ev) {
                    msgBox.append('<div class="system_error">Error Occurred - ' + ev.data + '</div>');
                };
                websocket.onclose = function (ev) {
                    setTimeout(function () {
                        __WebSocket__()
                    }, 5000);
                    msgBox.append('<div class="system_msg">Connection Closed</div>');
                };
            }
            ;
            __WebSocket__(wsUri22);

            //Message send button
            $('#send-message').click(function () {
                send_message();
            });

            //User hits enter key 
            $("#message").on("keydown", function (event) {
                if (event.which == 13) {
                    send_message();
                }
            });

            //Send message
            function send_message() {
                var message_input = $('#message'); //user message text
                var name_input = $('#name'); //user name

                if (message_input.val() == "") { //empty name?
                    alert("Enter your Name please!");
                    return;
                }
                if (message_input.val() == "") { //emtpy message?
                    alert("Enter Some message Please!");
                    return;
                }

                //prepare json data
                var msg = {
                    message: message_input.val(),
                    name: name_input.val(),
                    color: '<?php echo $colors[$color_pick]; ?>'
                };
                //convert and send data to server
                websocket.send(JSON.stringify(msg));
                message_input.val(''); //reset message input
            }
        </script>
    </body>
</html>


?>

