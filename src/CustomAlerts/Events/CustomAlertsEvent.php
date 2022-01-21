<?php

/*
 * CustomAlerts v2.0 by EvolSoft
 * Developer: Flavius12
 * Website: https://www.evolsoft.tk
 * Copyright (C) 2014-2018 EvolSoft
 * Licensed under MIT (https://github.com/EvolSoft/CustomAlerts/blob/master/LICENSE)
 */

namespace CustomAlerts\Events;

use pocketmine\event\plugin\PluginEvent;
use pocketmine\plugin\Plugin;

abstract class CustomAlertsEvent extends PluginEvent{

	/** @var string */
	private $message;

	public function __construct(Plugin $plugin){
		parent::__construct($plugin);
	}

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