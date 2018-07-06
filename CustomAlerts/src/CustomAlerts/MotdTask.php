<?php

/*
 * CustomAlerts (v1.9) by EvolSoft
 * Developer: EvolSoft (Flavius12)
 * Website: https://www.evolsoft.tk
 * Date: 13/01/2018 02:01 PM (UTC)
 * Copyright & License: (C) 2014-2018 EvolSoft
 * Licensed under MIT (https://github.com/EvolSoft/CustomAlerts/blob/master/LICENSE)
 */

namespace CustomAlerts;

use pocketmine\scheduler\Task;
use pocketmine\plugin\PluginBase;
use pocketmine\plugin\Plugin;

class MotdTask extends Task{
   private $plugin;
	
    public function __construct(CustomAlerts $plugin){
      $this->plugin = $plugin;
    }
    
    public function onRun($tick){
        CustomAlerts::getAPI()->updateMotd();
    }

    public function getPlugin(){
        return $this->plugin;
    }
}
