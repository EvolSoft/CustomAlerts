<?php

/*
 * CustomAlerts (v1.8) by EvolSoft
 * Developer: EvolSoft (Flavius12)
 * Website: https://www.evolsoft.tk
 * Date: 13/01/2018 02:02 PM (UTC)
 * Copyright & License: (C) 2014-2018 EvolSoft
 * Licensed under MIT (https://github.com/EvolSoft/CustomAlerts/blob/master/LICENSE)
 */

namespace CustomAlerts\Events;

use pocketmine\event\plugin\PluginEvent;

abstract class CustomAlertsEvent extends PluginEvent {
    
    /** @var string */
    private $message;
    
    /**
     * Get event message
     * 
     * @return string
     */
    public function getMessage(){
        return $this->message;
    }
    
    /**
     * Set event message
     * 
     * @param string $message
     */
    public function setMessage($message){
        $this->message = $message;
    }
}