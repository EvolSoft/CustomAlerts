<?php

/*
 * CustomAlerts (v1.6) by EvolSoft
 * Developer: EvolSoft (Flavius12)
 * Website: http://www.evolsoft.tk
 * Date: 17/06/2018 01:04 PM (UTC)
 * Copyright & License: (C) 2014-2018 EvolSoft
 * Licensed under MIT (https://github.com/EvolSoft/CustomAlerts/blob/master/LICENSE)
 */

namespace CustomAlerts;

use pocketmine\scheduler\Task;
use pocketmine\utils\TextFormat;

use CustomAlerts\Events\CustomAlertsMotdUpdateEvent;

class MotdTask extends Task{

	private $plugin;
	private $counter;

	public function __construct(CustomAlerts $plugin){

		$this->plugin = $plugin;
		$this->counter = 0;
                     }

	public function onRun(int $tick){
		$cfg = $this->plugin->getConfig()->getAll();
		$this->counter += 1;
		if($this->counter >= $cfg["Motd"]["update-timeout"]){
			//Check if Motd message is custom
			if(CustomAlerts::getAPI()->isMotdCustom()){
				CustomAlerts::getAPI()->setMotdMessage(CustomAlerts::getAPI()->getDefaultMotdMessage());
			}
			$this->plugin->getServer()->getPluginManager()->callEvent(new CustomAlertsMotdUpdateEvent($this->plugin->getServer()->getMotd()));
			$this->counter = 0;
		}
	}
}
