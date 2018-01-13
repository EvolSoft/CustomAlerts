<?php

/*
 * This is an Example Plugin with CustomAlerts API implementation
 * This plugin will show an example join message using CustomAlerts API and Events
 */

namespace CustomAlertsExample;

use pocketmine\event\Listener;
use pocketmine\plugin\PluginBase;
use pocketmine\utils\TextFormat;

//CustomAlerts API Call
use CustomAlerts\CustomAlerts;
use CustomAlerts\Events\CustomAlertsJoinEvent;

class CustomAlertsExample extends PluginBase implements Listener {
    
    public function onEnable(){
    	if(CustomAlerts::getAPI()->getAPIVersion() == "2.0"){ //Checking API version. Important for API Functions Calls
    		$this->getLogger()->info(TextFormat::GREEN . "Example Plugin using CustomAlerts (API v2.0)");
    		$this->getServer()->getPluginManager()->registerEvents($this, $this);
    	}else{
    		$this->getLogger()->alert(TextFormat::RED . "Plugin disabled. Please use CustomAlerts (API v2.0)");
    		$this->getPluginLoader()->disablePlugin($this);
    	}
    }
    
    public function onCAJoinEvent(CustomAlertsJoinEvent $event){
        $event->setMessage("Example Join Message: " . $event->getPlayer()->getName());
    }
}