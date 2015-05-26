<?php

/*
 * CustomAlerts (v1.4) by EvolSoft
 * Developer: EvolSoft (Flavius12)
 * Website: http://www.evolsoft.tk
 * Date: 09/05/2015 04:00 PM (UTC)
 * Copyright & License: (C) 2014-2015 EvolSoft
 * Licensed under MIT (https://github.com/EvolSoft/CustomAlerts/blob/master/LICENSE)
 */

namespace CustomAlerts;

use pocketmine\scheduler\PluginTask;
use pocketmine\utils\TextFormat;

use CustomAlerts\Events\CustomAlertsMotdUpdateEvent;

class MotdTask extends PluginTask {
	
    public function __construct(CustomAlerts $plugin){
    	parent::__construct($plugin);
        $this->plugin = $plugin;
        $this->plugin = $this->getOwner();
        $this->counter = 0;
    }
    
    public function onRun($tick){
    	$cfg = $this->plugin->getConfig()->getAll();
    	$this->counter += 1;
    	if($this->counter >= $cfg["Motd"]["update-timeout"]){
    		//Check if Motd message is custom
    		if(CustomAlerts::getAPI()->isMotdCustom()){
    			CustomAlerts::getAPI()->setMotdMessage(CustomAlerts::getAPI()->getDefaultMotdMessage());
    		}else{
    			CustomAlerts::getAPI()->setMotdMessage($this->plugin->translateColors("&", $this->plugin->getServer()->getMotd()));
    		}
    		$this->plugin->getServer()->getPluginManager()->callEvent(new CustomAlertsMotdUpdateEvent($this->plugin->getServer()->getMotd()));
    		$this->plugin->getServer()->getNetwork()->setName(CustomAlerts::getAPI()->getMotdMessage());
    		$this->counter = 0;
    	}
    }
}
?>
