<?php
declare(strict_types=1);

/*
 * CustomAlerts v2.0 by EvolSoft
 * Developer: Flavius12
 * Website: https://www.evolsoft.tk
 * Copyright (C) 2014-2018 EvolSoft
 * Licensed under MIT (https://github.com/EvolSoft/CustomAlerts/blob/master/LICENSE)
 */

namespace CustomAlerts;

use CustomAlerts\Events\CustomAlertsDeathEvent;
use CustomAlerts\Events\CustomAlertsFullServerKickEvent;
use CustomAlerts\Events\CustomAlertsJoinEvent;
use CustomAlerts\Events\CustomAlertsOutdatedClientKickEvent;
use CustomAlerts\Events\CustomAlertsOutdatedServerKickEvent;
use CustomAlerts\Events\CustomAlertsQuitEvent;
use CustomAlerts\Events\CustomAlertsWhitelistKickEvent;
use CustomAlerts\Events\CustomAlertsWorldChangeEvent;
use pocketmine\event\entity\EntityTeleportEvent;
use pocketmine\event\Listener;
use pocketmine\event\player\PlayerDeathEvent;
use pocketmine\event\player\PlayerJoinEvent;
use pocketmine\event\player\PlayerPreLoginEvent;
use pocketmine\event\player\PlayerQuitEvent;
use pocketmine\event\server\DataPacketReceiveEvent;
use pocketmine\network\mcpe\protocol\LoginPacket;
use pocketmine\network\mcpe\protocol\ProtocolInfo;
use pocketmine\player\Player;
use pocketmine\Server;

class EventListener implements Listener{

	private CustomAlerts $plugin;

	public function __construct(CustomAlerts $plugin){
		$this->plugin = $plugin;
	}

	/**
	 * @param DataPacketReceiveEvent $event
	 *
	 * @priority HIGHEST
	 */
	public function onReceivePacket(DataPacketReceiveEvent $event){
		$origin = $event->getOrigin();
		$player = $origin->getPlayer();
		$packet = $event->getPacket();
		if($packet instanceof LoginPacket){
			if($packet->protocol < ProtocolInfo::CURRENT_PROTOCOL){
				//Outdated Client message
				$cevent = new CustomAlertsOutdatedClientKickEvent($this->plugin, $player);
				if($this->plugin->isOutdatedClientMessageCustom()){
					$cevent->setMessage($this->plugin->getOutdatedClientMessage($player));
				}
				$cevent->call();
				if($cevent->getMessage() != ""){
					$origin->disconnect($cevent->getMessage());
					$event->cancel();
				}
			}elseif($packet->protocol > ProtocolInfo::CURRENT_PROTOCOL){
				//Outdated Server message
				$cevent = new CustomAlertsOutdatedServerKickEvent($this->plugin, $player);
				if($this->plugin->isOutdatedServerMessageCustom()){
					$cevent->setMessage($this->plugin->getOutdatedServerMessage($player));
				}
				$cevent->call();
				if($cevent->getMessage() != ""){
					$origin->disconnect($cevent->getMessage());
					$event->cancel();
				}
			}
		}
	}

	/**
	 * @param PlayerPreLoginEvent $event
	 *
	 * @priority HIGHEST
	 */
	public function onPlayerPreLogin(PlayerPreLoginEvent $event){
		$player = $event->getPlayerInfo();
		if(count($this->plugin->getServer()->getOnlinePlayers()) - 1 < $this->plugin->getServer()->getMaxPlayers()){
			//Whitelist Message
			if(!$this->plugin->getServer()->isWhitelisted($player->getUsername())){
				$cevent = new CustomAlertsWhitelistKickEvent($this->plugin, $player);
				if($this->plugin->isWhitelistMessageCustom()){
					$cevent->setMessage($this->plugin->getWhitelistMessage($player));
				}
				$cevent->call();
				if($cevent->getMessage() != ""){
					$event->setKickReason(0, $cevent->getMessage());
				}
			}
		}else{
			//Full Server Message
			$cevent = new CustomAlertsFullServerKickEvent($this->plugin, $player);
			if($this->plugin->isFullServerMessageCustom()){
				$cevent->setMessage($this->plugin->getFullServerMessage($player));
			}
			$cevent->call();
			if($cevent->getMessage() != ""){
				$event->setKickReason(0, $cevent->getMessage());
			}
		}
	}

	/**
	 * @param PlayerJoinEvent $event
	 *
	 * @priority HIGHEST
	 */
	public function onPlayerJoin(PlayerJoinEvent $event){
		$player = $event->getPlayer();
		//Motd Update
		$this->plugin->updateMotd();
		//Join Message
		$cevent = new CustomAlertsJoinEvent($this->plugin, $player);
		if(!$player->hasPlayedBefore() && $this->plugin->isFirstJoinMessageEnabled()){
			$cevent->setMessage($this->plugin->getFirstJoinMessage($player));
		}elseif($this->plugin->isJoinMessageHidden()){
			$cevent->setMessage("");
		}elseif($this->plugin->isJoinMessageCustom()){
			$cevent->setMessage($this->plugin->getJoinMessage($player));
		}else{
			$cevent->setMessage($event->getJoinMessage());
		}
		$cevent->call();
		$event->setJoinMessage($cevent->getMessage());
	}

	/**
	 * @param PlayerQuitEvent $event
	 *
	 * @priority HIGHEST
	 */
	public function onPlayerQuit(PlayerQuitEvent $event){
		$player = $event->getPlayer();
		//Motd Update
		$this->plugin->updateMotd();
		//Quit Message
		$cevent = new CustomAlertsQuitEvent($this->plugin, $player);
		if($this->plugin->isQuitMessageHidden()){
			$cevent->setMessage("");
		}elseif($this->plugin->isQuitMessageCustom()){
			$cevent->setMessage($this->plugin->getQuitMessage($player));
		}else{
			$cevent->setMessage($event->getQuitMessage());
		}
		$cevent->call();
		$event->setQuitMessage($cevent->getMessage());
	}

	/**
	 * @param EntityTeleportEvent $event
	 *
	 * @priority HIGHEST
	 */
	public function onWorldChange(EntityTeleportEvent $event){
		if($event->getFrom()->getWorld() === $event->getTo()->getWorld()){
			return;
		}
		$entity = $event->getEntity();
		//Check if the Entity is a Player
		if($entity instanceof Player){
			$player = $entity;
			$origin = $event->getFrom()->getWorld();
			$target = $event->getTo()->getWorld();
			$cevent = new CustomAlertsWorldChangeEvent($this->plugin, $player, $origin, $target);
			if($this->plugin->isWorldChangeMessageEnabled()){
				$cevent->setMessage($this->plugin->getWorldChangeMessage($player, $origin, $target));
			}else{
				$cevent->setMessage("");
			}
			$cevent->call();
			if($cevent->getMessage() != ""){
				Server::getInstance()->broadcastMessage($cevent->getMessage());
			}
		}
	}


	/**
	 * @param PlayerDeathEvent $event
	 *
	 * @priority HIGHEST
	 */
	public function onPlayerDeath(PlayerDeathEvent $event){
		$player = $event->getEntity();
		if($player instanceof Player){
			$cause = $player->getLastDamageCause();
			$cevent = new CustomAlertsDeathEvent($this->plugin, $player, $cause);
			if($this->plugin->isDeathMessageHidden($cause)){
				$cevent->setMessage("");
			}elseif($this->plugin->isDeathMessageCustom($cause)){
				$cevent->setMessage($this->plugin->getDeathMessage($player, $cause));
			}else{
				$cevent->setMessage($event->getDeathMessage());
			}
			$cevent->call();
			$event->setDeathMessage($cevent->getMessage());
		}
	}
}