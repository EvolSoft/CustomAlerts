<?php

/*
 * CustomAlerts v2.0 by EvolSoft
 * Developer: Flavius12
 * Website: https://www.evolsoft.tk
 * Copyright (C) 2014-2018 EvolSoft
 * Licensed under MIT (https://github.com/EvolSoft/CustomAlerts/blob/master/LICENSE)
 */

namespace CustomAlerts\Events;

use pocketmine\event\entity\EntityDamageEvent;
use pocketmine\player\Player;

class CustomAlertsDeathEvent extends CustomAlertsEvent {
	
	public static $handlerList = null;
	
	/** @var Player $player */
	private $player;
	
	/** @var EntityDamageEvent|null $cause */
	private $cause;
	
	/**
	 * @param Player $player
	 * @param EntityDamageEvent $cause
	 */
	public function __construct(Player $player, EntityDamageEvent $cause = null){
		$this->player = $player;
		$this->cause = $cause;
	}
	
	/**
	 * Get death event player
	 *
	 * @return Player
	 */
	public function getPlayer() : Player {
		return $this->player;
	}
	
	/**
	 * Get death event cause
	 *
	 * @return EntityDamageEvent|null
	 */
	public function getCause() : ?EntityDamageEvent {
		return $this->cause;
	}
	
}
