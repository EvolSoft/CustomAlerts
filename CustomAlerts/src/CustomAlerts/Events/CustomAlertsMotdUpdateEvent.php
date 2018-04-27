<?php

/*
 * CustomAlerts (v1.9) by EvolSoft
 * Developer: EvolSoft (Flavius12)
 * Website: https://www.evolsoft.tk
 * Date: 13/01/2018 02:02 PM (UTC)
 * Copyright & License: (C) 2014-2018 EvolSoft
 * Licensed under MIT (https://github.com/EvolSoft/CustomAlerts/blob/master/LICENSE)
 */

namespace CustomAlerts\Events;

class CustomAlertsMotdUpdateEvent extends CustomAlertsEvent {
	
	public static $handlerList = null;
	
	/** @var string $pocketminemotd The default PocketMine motd message */
	private $pocketminemessage;	

	public function __construct(){}
}