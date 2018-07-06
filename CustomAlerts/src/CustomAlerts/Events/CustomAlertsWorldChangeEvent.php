<?php

/*
 * CustomAlerts v2.0 by EvolSoft
 * Developer: Flavius12
 * Website: https://www.evolsoft.tk
 * Copyright (C) 2014-2018 EvolSoft
 * Licensed under MIT (https://github.com/EvolSoft/CustomAlerts/blob/master/LICENSE)
 */

namespace CustomAlerts\Events;

use pocketmine\level\Level;
use pocketmine\Player;

class CustomAlertsWorldChangeEvent extends CustomAlertsEvent {
	
	public static $handlerList = null;
	
	/** @var Player $player */
	private $player;
	
	/** @var Level $origin */
	private $origin;
	
	/** @var Level $target */
	private $target;
	
	/**
	 * @param Player $player
	 * @param Level $origin
	 * @param Level $target
	 */
	public function __construct(Player $player, Level $origin, Level $target){
		$this->player = $player;
		$this->origin = $origin;
		$this->target = $target;
	}
	
	/**
	 * Get world change event player
	 *
	 * @return Player
	 */
	public function getPlayer() : Player {
		return $this->player;
	}
	
	/**
	 * Get origin level
	 *
	 * @return Level
	 */
	public function getOrigin() : Level {
		return $this->origin;
	}
	
	/**
	 * Get target level
	 *
	 * @return Level
	 */
	public function getTarget() : Level {
		return $this->target;
	}
}