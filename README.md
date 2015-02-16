![start](https://cloud.githubusercontent.com/assets/10303538/6211293/b184e7d6-b5d8-11e4-937a-b1da3fde854d.png)

# CustomAlerts

Customize or hide alerts (join/leave messages, etc...) plugin for PocketMine-MP
## Category

PocketMine-MP plugins

## Requirements

PocketMine-MP Alpha_1.4 API 1.9.0

## Overview

**CustomAlerts** allows you to customize or hide alerts (join/leave messages, etc...)

**EvolSoft Website:** http://www.evolsoft.tk

***This Plugin uses the New API. You can't install it on old versions of PocketMine.***

With CustomAlerts you can customize or hide join/leave messages, first join messages, death messages, world change messages... (read documentation)

**Commands:**

***/customalerts*** *- CustomAlerts commands*

**To-Do:**

<dd><i>- Bug fix (if bugs will be found)</i></dd>
<dd><i>- Killed by mob message</i></dd>

## Documentation

**Colors:**

Black ("&0");<br>
Dark Blue ("&1");<br>
Dark Green ("&2");<br>
Dark Aqua ("&3");<br>
Dark Red ("&4");<br>
Dark Purple ("&5");<br>
Gold ("&6");<br>
Gray ("&7");<br>
Dark Gray ("&8");<br>
Blue ("&9");<br>
Green ("&a");<br>
Aqua ("&b");<br>
Red ("&c");<br>
Light Purple ("&d");<br>
Yellow ("&e");<br>
White ("&f");<br>

**Special:**

Obfuscated ("&k");<br>
Bold ("&l");<br>
Strikethrough ("&m");<br>
Underline ("&n");<br>
Italic ("&o");<br>
Reset ("&r");<br>

**Configuration (config.yml):**

```yaml
---
#Date\Time format (replaced in {TIME}). For format codes read http://php.net/manual/en/datetime.formats.php
datetime-format: "H:i:s"
#First Join message settings
FirstJoin:
#Enable First Join message
enable: true
#First Join message
#Available Tags:
# - {PLAYER}: Show player name
# - {TIME}: Show current time
message: "&2[{TIME}] &a{PLAYER}&d joined the game for the first time."
#Join message settings
Join:
#Hide Join message
hide: false
#Enable custom join message 
custom: true
#Join message
#Available Tags:
# - {PLAYER}: Show player name
# - {TIME}: Show current time
message: "&2[{TIME}] &a{PLAYER}&e joined the game."
#Quit message settings
Quit:
#Hide Quit message
hide: true
#Enable custom quit message 
custom: false
#Quit message
#Available Tags:
# - {PLAYER}: Show player name
# - {TIME}: Show current time
message: "&4[{TIME}] &c{PLAYER}&e has left the game"
#Death message settings
Death:
#Hide Quit message
hide: false
#Enable custom quit message
custom: true
#Killed by player message
#Available Tags:
# - {PLAYER}: Show player name
# - {KILLER}: Show killer name
# - {TIME}: Show current time
kill-message: "&9{PLAYER} &ewas killed by &c{KILLER}"
#Death default message
#Available Tags:
# - {PLAYER}: Show player name
# - {TIME}: Show current time
death-default-message: "&c{PLAYER} died"
#Death suffocation message
#Available Tags:
# - {PLAYER}: Show player name
# - {TIME}: Show current time
death-suffocation-message: "&c{PLAYER} suffocated"
#Death fall message
#Available Tags:
# - {PLAYER}: Show player name
# - {TIME}: Show current time
death-fall-message: "&c{PLAYER} fell from a high place"
#Death fire message
#Available Tags:
# - {PLAYER}: Show player name
# - {TIME}: Show current time
death-fire-message: "&c{PLAYER} went up in flames"
#Death lava message
#Available Tags:
# - {PLAYER}: Show player name
# - {TIME}: Show current time
death-lava-message: "&c{PLAYER} tried to swim in lava"
#Death drowning message
#Available Tags:
# - {PLAYER}: Show player name
# - {TIME}: Show current time
death-drowning-message: "&c{PLAYER} drowned"
#Death explosion message
#Available Tags:
# - {PLAYER}: Show player name
# - {TIME}: Show current time
death-explosion-message: "&c{PLAYER} exploded"
#World Change message settings
WorldChange:
#Enable World Change message
enable: true
#World Change message
#Available Tags:
# - {ORIGIN}: Show origin world name
# - {PLAYER}: Show player name
# - {TARGET}: Show target world name
# - {TIME}: Show current time
message: "&2[{TIME}] &a{PLAYER}&e moved from &c{ORIGIN}&e to &a{TARGET}"
```

**Commands:**

***/customalerts*** *- CustomAlerts commands*
<br><br>
**Permissions:**
<br>
- <dd><i><b>customalerts.*</b> - CustomAlertspermissions.</i></dd>
- <dd><i><b>customalerts.help</b> - CustomAlerts command Help permission.</i></dd>
- <dd><i><b>customalerts.info</b> - CustomAlerts command Info permission.</i></dd>
- <dd><i><b>customalerts.reload</b> - CustomAlerts command Reload permission.</i></dd>
