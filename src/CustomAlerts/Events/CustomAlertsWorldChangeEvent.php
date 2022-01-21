<?php

/*
 * CustomAlerts v2.0 by EvolSoft
 * Developer: Flavius12
 * Website: https://www.evolsoft.tk
 * Copyright (C) 2014-2018 EvolSoft
 * Licensed under MIT (https://github.com/EvolSoft/CustomAlerts/blob/master/LICENSE)
 */

namespace CustomAlerts\Events;

use pocketmine\player\Player;
use pocketmine\plugin\Plugin;
use pocketmine\world\World;

class CustomAlertsWorldChangeEvent extends CustomAlertsEvent{

	public static $handlerList = null;

	/** @var Player $player */
	private $player;

	/** @var World $origin */
	private $origin;

	/** @var World $target */
	private $target;

	/**
	 * @param Player $player
	 * @param World  $origin
	 * @param World  $target
	 */
	public function __construct(Plugin $plugin, Player $player, World $origin, World $target){
		parent::__construct($plugin);
		$this->player = $player;
		$this->origin = $origin;
		$this->target = $target;
	}

	/**
	 * Get world change event player
	 *
	 * @return Player
	 */
	public function getPlayer() : Player{
		return $this->player;
	}

	/**
	 * Get origin level
	 *
	 * @return World
	 */
	public function getOrigin() : World{
		return $this->origin;
	}

	/**
	 * Get target level
	 *
	 * @return World
	 */
	public function getTarget() : World{
		return $this->target;
	}
}