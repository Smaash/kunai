#Kunai 0.2
Sometimes there is a need to obtain ip address of specific person or perform client-side attacks via user browser. This is what you need in such situations.

Kunai is a simple script which collects many informations about a visitor and saves output to file; furthermore, you may try to perform attacks on user browser, using beef or metasploit. 

In order to grab as many informations as possible, script detects whenever javascript is enabled to obtain more details about a visitor. For example, you can include this script in iframe, or perform redirects, to avoid detection of suspicious activities. Script can notify you via email about user that visit your script. Whenever someone will visit your hook (kunai), output fille will be updated.

#Functions
- Stores informations about users in elegant output
- Website spoofing
- Redirects
- BeEF & Metasploit compatibility
- Email notification
- Diffrent reaction for javascript disabled browser
- One file composition

#Example configs
- Website spoofing (more stable & better for autopwn & beef):
- Redirect (better for quick ip catching):
```
goo.gl/urlink -> evilhost/x.php -> site.com/kitty.png
```
- Cross Site Scripting (inclusion)

#Screens
- http://i.imgur.com/cScbarL.png
- http://i.imgur.com/WOM3uyi.png



