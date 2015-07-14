<?php

namespace CustomAlertsExample;

use pocketmine\event\Listener;
use pocketmine\Player;
use pocketmine\plugin\PluginBase;
use pocketmine\utils\TextFormat;

//CustomAlerts API Call
use CustomAlerts\CustomAlerts;
use CustomAlerts\Events\CustomAlertsJoinEvent;

class Example extends PluginBase implements Listener {
	
	/* 
	 * This is an Example Plugin with CustomAlerts API implementation
	 * This plugin will show an example join message using CustomAlerts API and Events
	 */
    
    public function onEnable(){
    	if(CustomAlerts::getAPI()->getAPIVersion() == "1.2"){ //Checking API version. Important for API Functions Calls
    		$this->getLogger()->info(TextFormat::GREEN . "Example Plugin using CustomAlerts (API v1.2)");
    		$this->getServer()->getPluginManager()->registerEvents($this, $this);
    	}else{
    		$this->getLogger()->alert(TextFormat::RED . "Plugin disabled. Please use CustomAlerts (API v1.2)");
    		$this->getPluginLoader()->disablePlugin($this);
    	}
    }
    
    public function onCAJoinEvent(CustomAlertsJoinEvent $event){
    	CustomAlerts::getAPI()->setJoinMessage("Example Join Message: " . $event->getPlayer()->getName());
    }
    
}
?>
