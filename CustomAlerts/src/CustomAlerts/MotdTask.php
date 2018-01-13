<?php

/*
 * CustomAlerts (v1.8) by EvolSoft
 * Developer: EvolSoft (Flavius12)
 * Website: https://www.evolsoft.tk
 * Date: 13/01/2018 02:01 PM (UTC)
 * Copyright & License: (C) 2014-2018 EvolSoft
 * Licensed under MIT (https://github.com/EvolSoft/CustomAlerts/blob/master/LICENSE)
 */

namespace CustomAlerts;

use pocketmine\scheduler\PluginTask;

class MotdTask extends PluginTask {
	
    public function __construct(CustomAlerts $plugin){
    	parent::__construct($plugin);
    }
    
    public function onRun($tick){
        $plugin = $this->getOwner();
        CustomAlerts::getAPI()->updateMotd();
    }
}