<?php

/*
 * CustomAlerts v2.0 by EvolSoft
 * Developer: Flavius12
 * Website: https://www.evolsoft.tk
 * Copyright (C) 2014-2018 EvolSoft
 * Licensed under MIT (https://github.com/EvolSoft/CustomAlerts/blob/master/LICENSE)
 */

namespace CustomAlerts;

use pocketmine\scheduler\Task;

class MotdTask extends Task {
    
    /** @var CustomAlerts */
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
