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

use pocketmine\Player;

class CustomAlertsOutdatedClientKickEvent extends CustomAlertsEvent {
	
	public static $handlerList = null;
	
	/** @var Player $player */
	private $player;
	
	/**
	 * @param Player $player
	 */
	public function __construct(Player $player){
		$this->player = $player;
	}

	/**
	 * Get outdated client kick event player
	 * 
	 * @return Player
	 */
	public function getPlayer() : Player {
		return $this->player;
	}
}