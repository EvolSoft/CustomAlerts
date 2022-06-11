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
use pocketmine\network\mcpe\NetworkSession;

class CustomAlertsOutdatedServerKickEvent extends CustomAlertsEvent {
	
	public static $handlerList = null;
	
	/** @var NetworkSession $origin */
	private $origin;
	
	/**
	 * @param NetworkSession $origin
	 */
	public function __construct(?NetworkSession $origin){
		$this->origin = $origin;
	}

	/**
	 * Get outdated server kick event player
	 * 
	 * @return NetworkSession
	 */
	public function getOrigin() : ?NetworkSession {
		return $this->origin;
	}
}
