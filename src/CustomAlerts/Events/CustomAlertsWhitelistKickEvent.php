<?php

/*
 * CustomAlerts v2.0 by EvolSoft
 * Developer: Flavius12
 * Website: https://www.evolsoft.tk
 * Copyright (C) 2014-2018 EvolSoft
 * Licensed under MIT (https://github.com/EvolSoft/CustomAlerts/blob/master/LICENSE)
 */

namespace CustomAlerts\Events;

use pocketmine\player\PlayerInfo;
use pocketmine\plugin\Plugin;

class CustomAlertsWhitelistKickEvent extends CustomAlertsEvent {
	
	public static $handlerList = null;
	
	/** @var PlayerInfo $player */
	private $player;
	
	/**
	 * @param PlayerInfo $player
	 */
	public function __construct(Plugin $plugin, PlayerInfo $player){
		parent::__construct($plugin);
		$this->player = $player;
	}

	/**
	 * Get whitelist kick event player
	 * 
	 * @return PlayerInfo
	 */
	public function getPlayer() : PlayerInfo {
		return $this->player;
	}
}