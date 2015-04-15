<?php

namespace CustomAlertsExample;

use pocketmine\plugin\PluginBase;
use pocketmine\utils\TextFormat;
use pocketmine\Player;

//CustomAlerts API Call
use CustomAlerts\CustomAlertsAPI;
use CustomAlerts\Events\CustomAlertsJoinEvent;

class Example extends PluginBase{
	
	/* 
	 * This is an Example Plugin with CustomAlertsAPI implementation
	 * This plugin will show an example join message using CustomAlerts API and Events
	 */
    
    public function onEnable(){
    	if(CustomAlertsAPI::getAPI()->getAPIVersion() == "1.0"){ //Checking API version. Important for API Functions Calls
    		$this->getLogger()->info(TextFormat::GREEN . "Example Plugin using CustomAlerts (API v1.0)");
    	}else{
    		$this->getLogger()->alert(TextFormat::RED . "Plugin disabled. Please use CustomAlerts (API v1.0)");
    		$this->getPluginLoader()->disablePlugin($this);
    	}
        CustomAlertsAPI::getAPI()->registerExtension($this);
    }
    
    public function onCustomAlertsJoinEvent(CustomAlertsJoinEvent $event){
    	CustomAlertsAPI::getAPI()->setJoinMessage("Example Join Message: " . $event->getPlayer()->getName());
    }
    
}
?>
