<?php

/*
 * CustomAlerts v2.0 by EvolSoft
 * Developer: Flavius12
 * Website: https://www.evolsoft.tk
 * Copyright (C) 2014-2018 EvolSoft
 * Licensed under MIT (https://github.com/EvolSoft/CustomAlerts/blob/master/LICENSE)
 */

namespace CustomAlerts\Events;

use pocketmine\plugin\Plugin;

class CustomAlertsMotdUpdateEvent extends CustomAlertsEvent {
	
	public static $handlerList = null;

	public function __construct(Plugin $plugin){
		parent::__construct($plugin);
	}
}