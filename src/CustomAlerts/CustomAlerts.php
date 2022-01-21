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

use CustomAlerts\Commands\Commands;
use CustomAlerts\Events\CustomAlertsMotdUpdateEvent;
use pocketmine\entity\Living;
use pocketmine\event\entity\EntityDamageByBlockEvent;
use pocketmine\event\entity\EntityDamageEvent;
use pocketmine\player\Player;
use pocketmine\player\PlayerInfo;
use pocketmine\plugin\PluginBase;
use pocketmine\utils\TextFormat;
use pocketmine\world\World;

class CustomAlerts extends PluginBase{

	/** @var string */
	const PREFIX = "&b[&aCustom&cAlerts&b] ";

	/** @var string */
	const API_VERSION = "4.0";
	/** @var null|CustomAlerts $instance */
	private static ?CustomAlerts $instance = null;
	public array $cfg;

	/**
	 * Get CustomAlerts API
	 *
	 * @return CustomAlerts|null
	 */
	public static function getAPI() : ?CustomAlerts{
		return self::$instance;
	}

	public function onLoad() : void{
		if(!self::$instance instanceof CustomAlerts){
			self::$instance = $this;
		}
	}

	public function onEnable() : void{
		@mkdir($this->getDataFolder());
		$this->saveDefaultConfig();
		$this->cfg = $this->getConfig()->getAll();
		$this->getServer()->getCommandMap()->register("customalerts", new Commands($this));
		$this->getServer()->getPluginManager()->registerEvents(new EventListener($this), $this);
		$this->getScheduler()->scheduleRepeatingTask(new MotdTask($this), 20);
	}

	//API Functions

	/**
	 * Get CustomAlerts version
	 *
	 * @return string
	 */
	public function getVersion() : string{
		return $this->getVersion();
	}

	/**
	 * Get CustomAlerts API version
	 *
	 * @return string
	 */
	public function getAPIVersion() : string{
		return self::API_VERSION;
	}

	public function updateMotd(){
		$cevent = new CustomAlertsMotdUpdateEvent($this);
		if($this->isMotdCustom()){
			$cevent->setMessage($this->getMotdMessage());
		}else{
			$cevent->setMessage($this->getServer()->getMotd());
		}
		$cevent->call();
		$this->getServer()->getNetwork()->setName($cevent->getMessage());
	}

	/**
	 * Check if motd is custom
	 *
	 * @return bool
	 */
	public function isMotdCustom() : bool{
		return $this->cfg["Motd"]["custom"];
	}

	/**
	 * Get motd message
	 *
	 * @return string
	 */
	public function getMotdMessage() : string{
		return TextFormat::colorize($this->replaceVars($this->cfg["Motd"]["message"], [
			"MAXPLAYERS" => $this->getServer()->getMaxPlayers(),
			"TOTALPLAYERS" => count($this->getServer()->getOnlinePlayers()),
			"TIME" => date($this->cfg["datetime-format"])]));
	}

	/**
	 * Replace variables inside a string
	 *
	 * @param string $str
	 * @param array  $vars
	 *
	 * @return string
	 */
	public function replaceVars(string $str, array $vars) : string{
		foreach($vars as $key => $value){
			$str = str_replace("{" . $key . "}", (string)$value, $str);
		}
		return $str;
	}

	/**
	 * Check if outdated client message is custom
	 *
	 * @return bool
	 */
	public function isOutdatedClientMessageCustom() : bool{
		return $this->cfg["OutdatedClient"]["custom"];
	}

	/**
	 * Get outdated client message
	 *
	 * @param Player|null $player
	 *
	 * @return string
	 */
	public function getOutdatedClientMessage(?Player $player) : string{
		return TextFormat::colorize($this->replaceVars($this->cfg["OutdatedClient"]["message"], [
			"PLAYER" => $player?$player->getName():"",
			"MAXPLAYERS" => $this->getServer()->getMaxPlayers(),
			"TOTALPLAYERS" => count($this->getServer()->getOnlinePlayers()),
			"TIME" => date($this->cfg["datetime-format"])]));
	}

	/**
	 * Check if outdated server message is custom
	 *
	 * @return bool
	 */
	public function isOutdatedServerMessageCustom() : bool{
		return $this->cfg["OutdatedServer"]["custom"];
	}

	/**
	 * Get outdated server message
	 *
	 * @param Player|null $player
	 *
	 * @return string
	 */
	public function getOutdatedServerMessage(?Player $player) : string{
		return TextFormat::colorize($this->replaceVars($this->cfg["OutdatedServer"]["message"], [
			"PLAYER" => $player?$player->getName():"",
			"MAXPLAYERS" => $this->getServer()->getMaxPlayers(),
			"TOTALPLAYERS" => count($this->getServer()->getOnlinePlayers()),
			"TIME" => date($this->cfg["datetime-format"])]));
	}

	/**
	 * Check if whitelist message is custom
	 *
	 * @return bool
	 */
	public function isWhitelistMessageCustom() : bool{
		return $this->cfg["WhitelistedServer"]["custom"];
	}

	/**
	 * Get whitelist message
	 *
	 * @param PlayerInfo $player
	 *
	 * @return string
	 */
	public function getWhitelistMessage(PlayerInfo $player) : string{
		return TextFormat::colorize($this->replaceVars($this->cfg["WhitelistedServer"]["message"], [
			"PLAYER" => $player->getUsername(),
			"MAXPLAYERS" => $this->getServer()->getMaxPlayers(),
			"TOTALPLAYERS" => count($this->getServer()->getOnlinePlayers()),
			"TIME" => date($this->cfg["datetime-format"])]));
	}

	/**
	 * Check if full server message is custom
	 *
	 * @return bool
	 */
	public function isFullServerMessageCustom() : bool{
		$cfg = $this->getConfig()->getAll();
		return $cfg["FullServer"]["custom"];
	}

	/**
	 * Get full server message
	 *
	 * @param PlayerInfo $player
	 *
	 * @return string
	 */
	public function getFullServerMessage(PlayerInfo $player) : string{
		return TextFormat::colorize($this->replaceVars($this->cfg["FullServer"]["message"], [
			"PLAYER" => $player->getUsername(),
			"MAXPLAYERS" => $this->getServer()->getMaxPlayers(),
			"TOTALPLAYERS" => count($this->getServer()->getOnlinePlayers()),
			"TIME" => date($this->cfg["datetime-format"])]));
	}

	/**
	 * Check if first join message is enabled
	 *
	 * @return bool
	 */
	public function isFirstJoinMessageEnabled() : bool{
		return $this->cfg["FirstJoin"]["enable"];
	}

	/**
	 * Get first join message
	 *
	 * @param Player $player
	 *
	 * @return string
	 */
	public function getFirstJoinMessage(Player $player) : string{
		return TextFormat::colorize($this->replaceVars($this->cfg["FirstJoin"]["message"], [
			"PLAYER" => $player->getName(),
			"MAXPLAYERS" => $this->getServer()->getMaxPlayers(),
			"TOTALPLAYERS" => count($this->getServer()->getOnlinePlayers()),
			"TIME" => date($this->cfg["datetime-format"])]));
	}

	/**
	 * Check if join message is custom
	 *
	 * @return bool
	 */
	public function isJoinMessageCustom() : bool{
		return $this->cfg["Join"]["custom"];
	}

	/**
	 * Check if join message is hidden
	 *
	 * @return bool
	 */
	public function isJoinMessageHidden() : bool{
		return $this->cfg["Join"]["hide"];
	}

	/**
	 * Get join message
	 *
	 * @param Player $player
	 *
	 * @return string
	 */
	public function getJoinMessage(Player $player) : string{
		return TextFormat::colorize($this->replaceVars($this->cfg["Join"]["message"], [
			"PLAYER" => $player->getName(),
			"MAXPLAYERS" => $this->getServer()->getMaxPlayers(),
			"TOTALPLAYERS" => count($this->getServer()->getOnlinePlayers()),
			"TIME" => date($this->cfg["datetime-format"])]));
	}

	/**
	 * Check if quit message is custom
	 *
	 * @return bool
	 */
	public function isQuitMessageCustom() : bool{
		return $this->cfg["Quit"]["custom"];
	}

	/**
	 * Check if quit message is hidden
	 *
	 * @return bool
	 */
	public function isQuitMessageHidden() : bool{
		return $this->cfg["Quit"]["hide"];
	}

	/**
	 * Get default quit message
	 *
	 * @param Player $player
	 *
	 * @return string
	 */
	public function getQuitMessage(Player $player) : string{
		return TextFormat::colorize($this->replaceVars($this->cfg["Quit"]["message"], [
			"PLAYER" => $player->getName(),
			"MAXPLAYERS" => $this->getServer()->getMaxPlayers(),
			"TOTALPLAYERS" => count($this->getServer()->getOnlinePlayers()),
			"TIME" => date($this->cfg["datetime-format"])]));
	}

	/**
	 * Check if world change message is enabled
	 *
	 * @return bool
	 */
	public function isWorldChangeMessageEnabled() : bool{
		return $this->cfg["WorldChange"]["enable"];
	}

	/**
	 * Get world change message
	 *
	 * @param Player $player
	 * @param World  $origin
	 * @param World  $target
	 *
	 * @return string
	 */
	public function getWorldChangeMessage(Player $player, World $origin, World $target) : string{
		return TextFormat::colorize($this->replaceVars($this->cfg["WorldChange"]["message"], [
			"ORIGIN" => $origin->getDisplayName(),
			"TARGET" => $target->getDisplayName(),
			"PLAYER" => $player->getName(),
			"MAXPLAYERS" => $this->getServer()->getMaxPlayers(),
			"TOTALPLAYERS" => count($this->getServer()->getOnlinePlayers()),
			"TIME" => date($this->cfg["datetime-format"])]));
	}

	/**
	 * Check if death messages are custom
	 *
	 * @param EntityDamageEvent|null $cause
	 *
	 * @return bool
	 */
	public function isDeathMessageCustom(EntityDamageEvent $cause = null) : bool{
		if(!$cause){
			return $this->cfg["Death"]["custom"];
		}
		return match ($cause->getCause()) {
			EntityDamageEvent::CAUSE_CONTACT => $this->cfg["Death"]["death-contact-message"]["custom"],
			EntityDamageEvent::CAUSE_ENTITY_ATTACK => $this->cfg["Death"]["kill-message"]["custom"],
			EntityDamageEvent::CAUSE_PROJECTILE => $this->cfg["Death"]["death-projectile-message"]["custom"],
			EntityDamageEvent::CAUSE_SUFFOCATION => $this->cfg["Death"]["death-suffocation-message"]["custom"],
			EntityDamageEvent::CAUSE_FALL => $this->cfg["Death"]["death-fall-message"]["custom"],
			EntityDamageEvent::CAUSE_FIRE => $this->cfg["Death"]["death-fire-message"]["custom"],
			EntityDamageEvent::CAUSE_FIRE_TICK => $this->cfg["Death"]["death-on-fire-message"]["custom"],
			EntityDamageEvent::CAUSE_LAVA => $this->cfg["Death"]["death-lava-message"]["custom"],
			EntityDamageEvent::CAUSE_DROWNING => $this->cfg["Death"]["death-drowning-message"]["custom"],
			EntityDamageEvent::CAUSE_ENTITY_EXPLOSION, EntityDamageEvent::CAUSE_BLOCK_EXPLOSION => $this->cfg["Death"]["death-explosion-message"]["custom"],
			EntityDamageEvent::CAUSE_VOID => $this->cfg["Death"]["death-void-message"]["custom"],
			EntityDamageEvent::CAUSE_SUICIDE => $this->cfg["Death"]["death-suicide-message"]["custom"],
			EntityDamageEvent::CAUSE_MAGIC => $this->cfg["Death"]["death-magic-message"]["custom"],
			default => $this->cfg["Death"]["custom"],
		};
	}

	/**
	 * Check if death messages are hidden
	 *
	 * @param EntityDamageEvent|null $cause
	 *
	 * @return bool
	 */
	public function isDeathMessageHidden(EntityDamageEvent $cause = null) : bool{
		if(!$cause){
			return $this->cfg["Death"]["hide"];
		}
		return match ($cause->getCause()) {
			EntityDamageEvent::CAUSE_CONTACT => $this->cfg["Death"]["death-contact-message"]["hide"],
			EntityDamageEvent::CAUSE_ENTITY_ATTACK => $this->cfg["Death"]["kill-message"]["hide"],
			EntityDamageEvent::CAUSE_PROJECTILE => $this->cfg["Death"]["death-projectile-message"]["hide"],
			EntityDamageEvent::CAUSE_SUFFOCATION => $this->cfg["Death"]["death-suffocation-message"]["hide"],
			EntityDamageEvent::CAUSE_FALL => $this->cfg["Death"]["death-fall-message"]["hide"],
			EntityDamageEvent::CAUSE_FIRE => $this->cfg["Death"]["death-fire-message"]["hide"],
			EntityDamageEvent::CAUSE_FIRE_TICK => $this->cfg["Death"]["death-on-fire-message"]["hide"],
			EntityDamageEvent::CAUSE_LAVA => $this->cfg["Death"]["death-lava-message"]["hide"],
			EntityDamageEvent::CAUSE_DROWNING => $this->cfg["Death"]["death-drowning-message"]["hide"],
			EntityDamageEvent::CAUSE_ENTITY_EXPLOSION, EntityDamageEvent::CAUSE_BLOCK_EXPLOSION => $this->cfg["Death"]["death-explosion-message"]["hide"],
			EntityDamageEvent::CAUSE_VOID => $this->cfg["Death"]["death-void-message"]["hide"],
			EntityDamageEvent::CAUSE_SUICIDE => $this->cfg["Death"]["death-suicide-message"]["hide"],
			EntityDamageEvent::CAUSE_MAGIC => $this->cfg["Death"]["death-magic-message"]["hide"],
			default => $this->cfg["Death"]["hide"],
		};
	}

	/**
	 * Get death message related to the specified cause
	 *
	 * @param Player                 $player
	 * @param EntityDamageEvent|null $cause
	 *
	 * @return string
	 */
	public function getDeathMessage(Player $player, EntityDamageEvent $cause = null) : string{
		$array = [
			"PLAYER" => $player->getName(),
			"MAXPLAYERS" => $this->getServer()->getMaxPlayers(),
			"TOTALPLAYERS" => count($this->getServer()->getOnlinePlayers()),
			"TIME" => date($this->cfg["datetime-format"])];
		if(!$cause){
			$message = $this->cfg["Death"]["message"];
		}else{
			switch($cause->getCause()){
				case EntityDamageEvent::CAUSE_CONTACT:
					$message = $this->cfg["Death"]["death-contact-message"]["message"];
					if($cause instanceof EntityDamageByBlockEvent){
						$array["BLOCK"] = $cause->getDamager()->getName();
						break;
					}
					$array["BLOCK"] = "Unknown";
					break;
				case EntityDamageEvent::CAUSE_ENTITY_ATTACK:
					$message = $this->cfg["Death"]["kill-message"]["message"];
					$killer = $cause->getDamager();
					if($killer instanceof Living){
						$array["KILLER"] = $killer->getName();
						break;
					}
					$array["KILLER"] = "Unknown";
					break;
				case EntityDamageEvent::CAUSE_PROJECTILE:
					$message = $this->cfg["Death"]["death-projectile-message"]["message"];
					$killer = $cause->getDamager();
					if($killer instanceof Living){
						$array["KILLER"] = $killer->getName();
						break;
					}
					$array["KILLER"] = "Unknown";
					break;
				case EntityDamageEvent::CAUSE_SUFFOCATION:
					$message = $this->cfg["Death"]["death-suffocation-message"]["message"];
					break;
				case EntityDamageEvent::CAUSE_FALL:
					$message = $this->cfg["Death"]["death-fall-message"]["message"];
					break;
				case EntityDamageEvent::CAUSE_FIRE:
					$message = $this->cfg["Death"]["death-fire-message"]["message"];
					break;
				case EntityDamageEvent::CAUSE_FIRE_TICK:
					$message = $this->cfg["Death"]["death-on-fire-message"]["message"];
					break;
				case EntityDamageEvent::CAUSE_LAVA:
					$message = $this->cfg["Death"]["death-lava-message"]["message"];
					break;
				case EntityDamageEvent::CAUSE_DROWNING:
					$message = $this->cfg["Death"]["death-drowning-message"]["message"];
					break;
				case EntityDamageEvent::CAUSE_ENTITY_EXPLOSION:
				case EntityDamageEvent::CAUSE_BLOCK_EXPLOSION:
					$message = $this->cfg["Death"]["death-explosion-message"]["message"];
					break;
				case EntityDamageEvent::CAUSE_VOID:
					$message = $this->cfg["Death"]["death-void-message"]["message"];
					break;
				case EntityDamageEvent::CAUSE_SUICIDE:
					$message = $this->cfg["Death"]["death-suicide-message"]["message"];
					break;
				case EntityDamageEvent::CAUSE_MAGIC:
					$message = $this->cfg["Death"]["death-magic-message"]["message"];
					break;
				default:
					$message = $this->cfg["Death"]["message"];
					break;
			}
		}
		return TextFormat::colorize($this->replaceVars($message, $array));
	}
}
