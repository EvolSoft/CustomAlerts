![start2](https://cloud.githubusercontent.com/assets/10303538/6315586/9463fa5c-ba06-11e4-8f30-ce7d8219c27d.png)

# CustomAlerts

Customize or hide alerts (join/leave messages, etc...) plugin for PocketMine-MP
## Category

PocketMine-MP plugins

## Requirements

PocketMine-MP Alpha_1.4 API 1.11.0

## Overview

**CustomAlerts** allows you to customize or hide all PocketMine alerts (join/leave messages, etc...)

**EvolSoft Website:** http://www.evolsoft.tk

***This Plugin uses the New API. You can't install it on old versions of PocketMine.***

With CustomAlerts you can customize or hide join/leave messages, first join messages, death messages, world change messages... (read documentation)

**What is included?**

In the ZIP file you will find:<br>
*- CustomAlerts_v1.2.phar : CustomAlerts Plugin + API*<br>
*- CustomAlertsExample_v1.phar : CustomAlerts API implementation example*<br>
*- CustomAlertsExample : Example Plugin source code*<br>

**Commands:**

***/customalerts*** *- CustomAlerts commands*

**To-Do:**

<dd><i>- Bug fix (if bugs will be found)</i></dd>

## Documentation

#### For Users:

**Text format (Available on PocketMine console and on MCPE v0.11.0 and later):**

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
#REMEMBER THAT IF YOU USE CustomAlerts EXTENSIONS, MESSAGES MAY NOT FOLLOW THE DEFAULT CONFIG
#Date/Time format (replaced in {TIME}). For format codes read http://php.net/manual/en/datetime.formats.php
datetime-format: "H:i:s"
#First Join message settings
FirstJoin:
  #Enable First Join message
  enable: true
  #First Join message
  #Available Tags:
  # - {MAXPLAYERS}: Show the maximum number of players supported by the server
  # - {PLAYER}: Show player name
  # - {TIME}: Show current time
  # - {TOTALPLAYERS}: Show the number of all online players
  message: "&2[{TIME}] &a{PLAYER}&d joined the game for the first time."
#Join message settings
Join:
  #Hide Join message
  hide: false
  #Show custom Join message 
  custom: true
  #Join message
  #Available Tags:
  # - {MAXPLAYERS}: Show the maximum number of players supported by the server
  # - {PLAYER}: Show player name
  # - {TIME}: Show current time
  # - {TOTALPLAYERS}: Show the number of all online players
  message: "&2[{TIME}] &a{PLAYER}&e joined the game."
#Quit message settings
Quit:
  #Hide Quit message
  hide: true
  #Show custom Quit message 
  custom: false
  #Quit message
  #Available Tags:
  # - {MAXPLAYERS}: Show the maximum number of players supported by the server
  # - {PLAYER}: Show player name
  # - {TIME}: Show current time
  # - {TOTALPLAYERS}: Show the number of all online players
  message: "&4[{TIME}] &c{PLAYER}&e has left the game"
#World Change message settings
WorldChange:
  #Enable World Change message
  enable: true
  #World Change message
  #Available Tags:
  # - {MAXPLAYERS}: Show the maximum number of players supported by the server
  # - {ORIGIN}: Show origin world name
  # - {PLAYER}: Show player name
  # - {TARGET}: Show target world name
  # - {TIME}: Show current time
  # - {TOTALPLAYERS}: Show the number of all online players
  message: "&2[{TIME}] &a{PLAYER}&e moved from &c{ORIGIN}&e to &a{TARGET}"
#Death message settings
Death:
  #Hide deafult Death message
  hide: false
  #Show custom default Death message
  custom: true
  #Default Death message
  #Available Tags:
  # - {MAXPLAYERS}: Show the maximum number of players supported by the server
  # - {PLAYER}: Show player name
  # - {TIME}: Show current time
  # - {TOTALPLAYERS}: Show the number of all online players
  message: "&c{PLAYER} died"
  #Death by contact message
  death-contact-message:
    #Hide Death by contact message
    hide: false
    #Show custom Death by contact message
    custom: true
    #Death by contact message
    #Available Tags:
    # - {BLOCK}: The name of the block which killed the player
    # - {MAXPLAYERS}: Show the maximum number of players supported by the server
    # - {PLAYER}: Show player name
    # - {TIME}: Show current time
    # - {TOTALPLAYERS}: Show the number of all online players
    message: "&cOops! {PLAYER} was killed by {BLOCK}"
  #Death by entity message (players and mobs)
  kill-message:
    #Hide Death by entity message
    hide: false
    #Show custom Death by entity message
    custom: true
    #Death by entity message
    # - {KILLER}: The name of the killer entity
    # - {MAXPLAYERS}: Show the maximum number of players supported by the server
    # - {PLAYER}: Show player name
    # - {TIME}: Show current time
    # - {TOTALPLAYERS}: Show the number of all online players
    message: "&9{PLAYER} &ewas killed by &c{KILLER}"
  #Death by projectile message
  death-projectile-message:
    #Hide Death by projectile message
    hide: false
    #Show custom Death by projectile message
    custom: true
    #Death by projectile message
    # - {KILLER}: The name of the killer entity
    # - {MAXPLAYERS}: Show the maximum number of players supported by the server
    # - {PLAYER}: Show player name
    # - {TIME}: Show current time
    # - {TOTALPLAYERS}: Show the number of all online players
    message: "&c{PLAYER} was killed by {KILLER} by arrow"
  #Death by suffocation message
  death-suffocation-message:
    #Hide Death by suffocation message
    hide: false
    #Show custom Death by suffocation message
    custom: true
    #Death by suffocation message
    # - {MAXPLAYERS}: Show the maximum number of players supported by the server
    # - {PLAYER}: Show player name
    # - {TIME}: Show current time
    # - {TOTALPLAYERS}: Show the number of all online players
    message: "&c{PLAYER} suffocated"
  #Death by fall message
  death-fall-message:
    #Hide Death by fall message
    hide: false
    #Show custom Death by fall message
    custom: true
    #Death by fall message
    # - {MAXPLAYERS}: Show the maximum number of players supported by the server
    # - {PLAYER}: Show player name
    # - {TIME}: Show current time
    # - {TOTALPLAYERS}: Show the number of all online players
    message: "&c{PLAYER} fell from a high place"
  #Death by fire message
  death-fire-message:
    #Hide Death by fire message
    hide: false
    #Show custom Death by fire message
    custom: true
    #Death by fire message
    # - {MAXPLAYERS}: Show the maximum number of players supported by the server
    # - {PLAYER}: Show player name
    # - {TIME}: Show current time
    # - {TOTALPLAYERS}: Show the number of all online players
    message: "&c{PLAYER} went up in flames"
  #Death on fire message
  death-on-fire-message:
    #Hide Death on fire message
    hide: false
    #Show custom Death on fire message
    custom: true
    #Death on fire message
    # - {MAXPLAYERS}: Show the maximum number of players supported by the server
    # - {PLAYER}: Show player name
    # - {TIME}: Show current time
    # - {TOTALPLAYERS}: Show the number of all online players
    message: "&c{PLAYER} burned"
  #Death by lava message
  death-lava-message:
    #Hide Death by lava message
    hide: false
    #Show custom Death by lava message
    custom: true
    #Death by lava message
    # - {MAXPLAYERS}: Show the maximum number of players supported by the server
    # - {PLAYER}: Show player name
    # - {TIME}: Show current time
    # - {TOTALPLAYERS}: Show the number of all online players
    message: "&c{PLAYER} tried to swim in lava"
  #Death by drowning message
  death-drowning-message:
    #Hide Death by drowning message
    hide: false
    #Show custom Death by drowning message
    custom: true
    #Death by drowning message
    # - {MAXPLAYERS}: Show the maximum number of players supported by the server
    # - {PLAYER}: Show player name
    # - {TIME}: Show current time
    # - {TOTALPLAYERS}: Show the number of all online players
    message: "&c{PLAYER} drowned"
  #Death by explosion message
  death-explosion-message:
    #Hide Death by explosion message
    hide: false
    #Show custom Death by explosion message
    custom: true
    #Death by explosion message
    # - {MAXPLAYERS}: Show the maximum number of players supported by the server
    # - {PLAYER}: Show player name
    # - {TIME}: Show current time
    # - {TOTALPLAYERS}: Show the number of all online players
    message: "&c{PLAYER} exploded"
  #Death by void message
  death-void-message:
    #Hide Death by void message
    hide: false
    #Show custom Death by void message
    custom: true
    #Death by void message
    # - {MAXPLAYERS}: Show the maximum number of players supported by the server
    # - {PLAYER}: Show player name
    # - {TIME}: Show current time
    # - {TOTALPLAYERS}: Show the number of all online players
    message: "&c{PLAYER} fell into the void"
  #Death by suicide message
  death-suicide-message:
    #Hide Death by suicide message
    hide: false
    #Show custom Death by suicide message
    custom: true
    #Death by suicide message
    # - {MAXPLAYERS}: Show the maximum number of players supported by the server
    # - {PLAYER}: Show player name
    # - {TIME}: Show current time
    # - {TOTALPLAYERS}: Show the number of all online players
    message: "&c{PLAYER} committed suicide"
  #Death magic message
  death-magic-message:
    #Hide Death magic message
    hide: false
    #Show custom Death magic message
    custom: true
    #Death magic message
    # - {MAXPLAYERS}: Show the maximum number of players supported by the server
    # - {PLAYER}: Show player name
    # - {TIME}: Show current time
    # - {TOTALPLAYERS}: Show the number of all online players
    message: "&c{PLAYER} was killed by a spell"
```

**Commands:**

***/customalerts*** *- CustomAlerts commands*
<br><br>
**Permissions:**
<br>
- <dd><i><b>customalerts.*</b> - CustomAlertspermissions.</i></dd>
- <dd><i><b>customalerts.extensions</b> - CustomAlerts command Extensions permission.</i></dd>
- <dd><i><b>customalerts.help</b> - CustomAlerts command Help permission.</i></dd>
- <dd><i><b>customalerts.info</b> - CustomAlerts command Info permission.</i></dd>
- <dd><i><b>customalerts.reload</b> - CustomAlerts command Reload permission.</i></dd>

#### For Developers

**Basic Tutorial:**

*1. Define the plugin dependency in plugin.yml (you can check if CustomAlerts is installed in different ways):*
```yaml
depend: [CustomAlerts]
```
*2. Include CustomAlerts API and CustomAlerts Events in your php code:*
```php
//CustomAlerts API
use CustomAlerts\CustomAlertsAPI;
//CustomAlerts Events
use CustomAlerts\Events\CustomAlertsJoinEvent;
```
*3. Create the class (don't forget to add 'extends PluginBase'):*
```php
class Example extends PluginBase {
}
```
*4. Check if CustomAlerts API is compatible and register it as CustomAlerts extension (insert this code in onEnable() function)*
```php
if(CustomAlertsAPI::getAPI()->getAPIVersion() == "(used API version)"){
            //API compatible
            //Now register this plugin as CustomAlerts Extension
            CustomAlertsAPI::getAPI()->registerExtension($this);
        }else{
            //API not compatible
            $this->getPluginLoader()->disablePlugin($this);
        }
```
*5. Handle a CustomAlerts event (in this tutorial we will handle the CustomAlertsJoinEvent):*
```php
//REMEMBER THAT THE FUNCTION NAME IN THIS CASE MUST BE onCustomAlertsJoinEvent. IF YOU CHANGE THE FUNCTION NAME, THE FUNCTION WON'T BE EXECUTED
public function onCustomAlertsJoinEvent(CustomAlertsJoinEvent $event){
  CustomAlertsAPI::getAPI()->setJoinMessage("Example Join message: " . $event->getPlayer()->getName());
}
```
*6. Call the API function:*
```php
CustomAlertsAPI::getAPI()->api_function();
```
***A full plugin example using CustomAlerts API and CustomAlerts Events is included in the ZIP file.***

**CustomAlerts API Events:**

###### CustomAlertsJoinEvent:

This event is handled when a player joins. It must be declared:
```php
public function onCustomAlertsJoinEvent(CustomAlertsJoinEvent $event){
}
```
Remember that the function name must be *onCustomAlertsJoinEvent*

Event functions are:

###### Get Player:

```php
Player getPlayer()
```
**Description:**<br>
Get join event player.<br>
**Return:**<br>
The join event player (instance of pocketmine\Player)

###### Get default PocketMine join message:

```php
string getPocketMineJoinMessage()
```
**Description:**<br>
Get default PocketMine join message.<br>
**Return:**<br>
The default PocketMine join message

###### CustomAlertsQuitEvent:

This event is handled when a player quits. It must be declared:
```php
public function onCustomAlertsQuitEvent(CustomAlertsQuitEvent $event){
}
```
Remember that the function name must be *onCustomAlertsQuitEvent*

Event functions are:

###### Get Player:

```php
Player getPlayer()
```
**Description:**<br>
Get quit event player.<br>
**Return:**<br>
The quit event player (instance of pocketmine\Player)

###### Get default PocketMine quit message:

```php
string getPocketMineQuitMessage()
```
**Description:**<br>
Get default PocketMine quit message.<br>
**Return:**<br>
The default PocketMine quit message

###### CustomAlertsWorldChangeEvent:

This event is handled when a player changes world. It must be declared:
```php
public function onCustomAlertsWorldChangeEvent(CustomAlertsWorldChangeEvent $event){
}
```
Remember that the function name must be *onCustomAlertsWorldChangeEvent*

Event functions are:

###### Get Player:

```php
Player getPlayer()
```
**Description:**<br>
Get world change event player.<br>
**Return:**<br>
The world change event player (instance of pocketmine\Player)

###### Get Origin Level:

```php
Level getOrigin()
```
**Description:**<br>
Get origin level.<br>
**Return:**<br>
The origin level (instance of pocketmine\Level)

###### Get Target Level:

```php
Level getTarget()
```
**Description:**<br>
Get target level.<br>
**Return:**<br>
The target level (instance of pocketmine\Level)

###### CustomAlertsDeathEvent:

This event is handled when a player dies. It must be declared:
```php
public function onCustomAlertsDeathEvent(CustomAlertsDeathEvent $event){
}
```
Remember that the function name must be *onCustomAlertsDeathEvent*

Event functions are:

###### Get Player:

```php
Player getPlayer()
```
**Description:**<br>
Get death event player.<br>
**Return:**<br>
The death event player

```php
EntityDamageEvent|null getCause()
```
**Description:**<br>
Get death event cause.<br>
**Return:**<br>
The death event cause

**CustomAlerts API Functions:**

###### Get Version:
```php
string getVersion()
```
**Description:**<br>
Get CustomAlerts plugin version<br>
**Return:**<br>
plugin version

###### Get API Version:
```php
string getAPIVersion()
```
**Description:**<br>
Get the CustomAlerts API version.<br>
**Return:**<br>
plugin API version

###### Register a CustomAlerts extension:
```php
function registerExtension(PluginBase $extension, $priority = null)
```
**Description:**<br>
Register a CustomAlerts extension.<br>
**Parameters:**<br>
*$extension* the extension to register<br>
*$priority* extension priority<br>
*Available priorities are:*<br>
***PRIORITY_HIGHEST***<br>
***PRIORITY_HIGH***<br>
***PRIORITY_NORMAL***<br>
***PRIORITY_LOW***<br>
***PRIORITY_LOWEST***

###### Get all CustomAlerts loaded extensions:
```php
array getAllExtensions($priority = null)
```
**Description:**<br>
Get all CustomAlerts loaded extensions.<br>
**Parameters:**<br>
*$priority* extension priority (if it's not specified, the function will return an array with all loaded extensions)<br>
**Return:**<br>
*array* of loaded extensions

###### Check if default first join message is enabled:
```php
boolean isDefaultFirstJoinMessageEnabled()
```
**Description:**<br>
Check if default first join message is enabled.<br>
**Return:**<br>
*boolean*

###### Get default first join message:
```php
string getDefaultFirstJoinMessage(Player $player)
```
**Description:**<br>
Get default first join message.<br>
**Parameters:**<br>
*$player* the current player<br>
**Return:**<br>
*string* the default first join message

###### Check if a player has joined for the first time:
```php
boolean hasJoinedFirstTime(Player $player)
```
**Description:**<br>
Check if a player has joined for the first time.<br>
**Parameters:**<br>
*$player*<br>
**Return:**<br>
*boolean*

###### Check if default join message is custom:
```php
boolean isDefaultJoinMessageCustom()
```
**Description:**<br>
Check if default join message is custom.<br>
**Return:**<br>
*boolean*

###### Check if default join message is hidden:
```php
boolean isDefaultJoinMessageHidden()
```
**Description:**<br>
Check if default join message is hidden.<br>
**Return:**<br>
*boolean*

###### Get default join message:
```php
string getDefaultJoinMessage(Player $player)
```
**Description:**<br>
Get default join message.<br>
**Parameters:**<br>
*$player* the current player<br>
**Return:**<br>
*string* the default join message

###### Get current join message:
```php
string getJoinMessage()
```
**Description:**<br>
Get current join message.<br>
**Return:**<br>
*string* the current join message

###### Set current join message:
```php
function setJoinMessage($message)
```
**Description:**<br>
Set current join message.<br>
**Parameters:**<br>
*$message*

###### Check if default quit message is custom:
```php
boolean isDefaultQuitMessageCustom()
```
**Description:**<br>
Check if default quit message is custom.<br>
**Return:**<br>
*boolean*

###### Check if default quit message is hidden:
```php
boolean isDefaultQuitMessageHidden()
```
**Description:**<br>
Check if default quit message is hidden.<br>
**Return:**<br>
*boolean*

###### Get default quit message:
```php
string getDefaultQuitMessage(Player $player)
```
**Description:**<br>
Get default quit message.<br>
**Parameters:**<br>
*$player* the current player<br>
**Return:**<br>
*string* the default quit message

###### Get current quit message:
```php
string getQuitMessage()
```
**Description:**<br>
Get current quit message.<br>
**Return:**<br>
*string* the current quit message

###### Set current quit message:
```php
function setQuitMessage($message)
```
**Description:**<br>
Set current quit message.<br>
**Parameters:**<br>
*$message*

###### Check if default world change message is enabled:
```php
boolean isDefaultWorldChangeMessageEnabled()
```
**Description:**<br>
Check if default world change message is enabled.<br>
**Return:**<br>
*boolean*

###### Get default world change message:
```php
string getDefaultWorldChangeMessage(Player $player, Level $origin, Level $target)
```
**Description:**<br>
Get default world change message.<br>
**Parameters:**<br>
*$player* the current player<br>
*$origin* the origin level<br>
*$target* the target level<br>
**Return:**<br>
*string* the default world change message

###### Get current world change message:
```php
string getWorldChangeMessage()
```
**Description:**<br>
Get current world change message.<br>
**Return:**<br>
*string* the current world change message

###### Set current world change message:
```php
function setWorldChangeMessage($message)
```
**Description:**<br>
Set current world change message.<br>
**Parameters:**<br>
*$message*

###### Check if default death messages are custom:
```php
boolean isDefaultDeathMessageCustom($cause = null)
```
**Description:**<br>
Check if default death messages are custom.<br>
**Parameters:**<br>
*$cause* Check message by cause<br>
**Return:**<br>
*boolean*

###### Check if default death messages are hidden:
```php
boolean isDefaultDeathMessageHidden($cause = null)
```
**Description:**<br>
Check if default death messages are hidden<br>
**Parameters:**<br>
*$cause* Check message by cause<br>
**Return:**<br>
*boolean*

###### Get default death message related to the specified cause:
```php
string getDefaultDeathMessage(Player $player, $cause = null)
```
**Description:**<br>
Get default death message related to the specified cause.<br>
**Parameters:**<br>
*$player* the current player<br>
*$cause* the cause of death (instanceof EntityDamageEvent). If it's null, the function will return the default death message
**Return:**<br>
*string* the default death message related to the specified cause

###### Get current death message:
```php
string getDeathMessage()
```
**Description:**<br>
Get current death message.<br>
**Return:**<br>
*string* the current death message

###### Set current death message:
```php
function setDeathMessage($message)
```
**Description:**<br>
Set current death message.<br>
**Parameters:**<br>
*$message*
