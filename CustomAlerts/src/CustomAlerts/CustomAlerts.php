<?php

/*
 * CustomAlerts v2.0 by EvolSoft
 * Developer: Flavius12
 * Website: https://www.evolsoft.tk
 * Copyright (C) 2014-2018 EvolSoft
 * Licensed under MIT (https://github.com/EvolSoft/CustomAlerts/blob/master/LICENSE)
 */

namespace CustomAlerts;

use pocketmine\player\Player;
use pocketmine\entity\Living;
use pocketmine\event\entity\EntityDamageByBlockEvent;
use pocketmine\event\entity\EntityDamageEvent;
use pocketmine\level\Level;
use pocketmine\plugin\PluginBase;
use pocketmine\utils\TextFormat;

use CustomAlerts\Commands\Commands;
use CustomAlerts\Events\CustomAlertsMotdUpdateEvent;
use pocketmine\command\PluginCommand;
use pocketmine\entity\projectile\Projectile;
use pocketmine\event\entity\EntityDamageByEntityEvent;
use pocketmine\event\entity\ProjectileHitEntityEvent;
use pocketmine\player\Player as PlayerPlayer;
use pocketmine\player\PlayerInfo;
use pocketmine\utils\Config;
use pocketmine\world\World;

class CustomAlerts extends PluginBase {
    
	/** @var string */
	const PREFIX = "&b[&aCustom&cAlerts&b] ";
	
	/** @var string */
	const API_VERSION = "2.0";
	
	protected Config $cfg;
	
	/** @var CustomAlerts $instance */
	private static $instance = null;
	
	public function onLoad():void{
	    if(!self::$instance instanceof CustomAlerts){
	        self::$instance = $this;
	    }
	}
	
    public function onEnable():void{
    	@mkdir($this->getDataFolder());
    	$this->saveDefaultConfig();
    	$this->cfg = $this->getConfig()->getAll();
        $this->getServer()->getCommandMap()->register("customalerts", new Commands($this));
    	$this->getServer()->getPluginManager()->registerEvents(new EventListener($this), $this);
    	$this->getScheduler()->scheduleRepeatingTask(new MotdTask($this), 20);
    }
    
    /**
     * Replace variables inside a string
     * 
     * @param string $str
     * @param array $vars
     * 
     * @return string
     */
    public function replaceVars($str, array $vars){
        foreach($vars as $key => $value){
            $str = str_replace("{" . $key . "}", $value, $str);
        }
        return $str;
    }
    
    //API Functions
    
    /**
     * Get CustomAlerts API
     *
     * @return CustomAlerts
     */
    public static function getAPI(){
        return self::$instance;
    }
    
    /**
     * Get CustomAlerts version
     * 
     * @return string
     */
    public function getVersion(){
    	return $this->getVersion();
    }
    
    /**
     * Get CustomAlerts API version
     *
     * @return string
     */
    public function getAPIVersion(){
    	return self::API_VERSION;
    }
    
    /**
     * Check if motd is custom
     * 
     * @return bool
     */
    public function isMotdCustom() : bool {
    	return $this->cfg["Motd"]["custom"];
    }
    
    /**
     * Get motd message
     *
     * @return string
     */
    public function getMotdMessage(){
        return TextFormat::colorize($this->replaceVars($this->cfg["Motd"]["message"], array(
            "MAXPLAYERS" => $this->getServer()->getMaxPlayers(),
            "TOTALPLAYERS" => count($this->getServer()->getOnlinePlayers()),
            "TIME" => date($this->cfg["datetime-format"]))));
    }
    
    public function updateMotd(){
        $cevent = new CustomAlertsMotdUpdateEvent();
        if($this->isMotdCustom()){
            $cevent->setMessage($this->getMotdMessage());
        }else{
            $cevent->setMessage($this->getServer()->getMotd());
        }
        $cevent->call();
        $this->getServer()->getNetwork()->setName($cevent->getMessage());
    }
    
    /**
     * Check if outdated client message is custom
     *
     * @return bool
     */
    public function isOutdatedClientMessageCustom() : bool {
    	return $this->cfg["OutdatedClient"]["custom"];
    }
    
    /**
     * Get outdated client message
     *
     * @return string
     */
    public function getOutdatedClientMessage(){
        return TextFormat::colorize($this->replaceVars($this->cfg["OutdatedClient"]["message"], array(
            "MAXPLAYERS" => $this->getServer()->getMaxPlayers(),
            "TOTALPLAYERS" => count($this->getServer()->getOnlinePlayers()),
            "TIME" => date($this->cfg["datetime-format"]))));
    }
    
    /**
     * Check if outdated server message is custom
     *
     * @return bool
     */
    public function isOutdatedServerMessageCustom() : bool {
    	return $this->cfg["OutdatedServer"]["custom"];
    }
    
    /**
     * Get outdated server message
     * 
     *
     * @return string
     */
    public function getOutdatedServerMessage(){
        return TextFormat::colorize($this->replaceVars($this->cfg["OutdatedServer"]["message"], array(
            "MAXPLAYERS" => $this->getServer()->getMaxPlayers(),
            "TOTALPLAYERS" => count($this->getServer()->getOnlinePlayers()),
            "TIME" => date($this->cfg["datetime-format"]))));
    }
    
    /**
     * Check if whitelist message is custom
     *
     * @return bool
     */
    public function isWhitelistMessageCustom() : bool {
    	return $this->cfg["WhitelistedServer"]["custom"];
    }
    
    /**
     * Get whitelist message
     *
     * @return string
     */
    public function getWhitelistMessage(){
        return TextFormat::colorize($this->replaceVars($this->cfg["WhitelistedServer"]["message"], array(
            "MAXPLAYERS" => $this->getServer()->getMaxPlayers(),
            "TOTALPLAYERS" => count($this->getServer()->getOnlinePlayers()),
            "TIME" => date($this->cfg["datetime-format"]))));
    }
    
    /**
     * Check if full server message is custom
     *
     * @return bool
     */
    public function isFullServerMessageCustom() : bool {
    	$cfg = $this->getConfig()->getAll();
    	return $cfg["FullServer"]["custom"];
    }
    
    /**
     * Get full server message
     * 
     *
     * @return string
     */
    public function getFullServerMessage(){
        return TextFormat::colorize($this->replaceVars($this->cfg["FullServer"]["message"], array(
            "MAXPLAYERS" => $this->getServer()->getMaxPlayers(),
            "TOTALPLAYERS" => count($this->getServer()->getOnlinePlayers()),
            "TIME" => date($this->cfg["datetime-format"]))));
    }
    
    /**
     * Check if first join message is enabled
     * 
     * @return bool
     */
    public function isFirstJoinMessageEnabled() : bool {
    	return $this->cfg["FirstJoin"]["enable"];
    }

    public function reloadCfg(){
        $this->cfg = $this->getConfig()->getAll();
    }

    /**
     * Get first join message
     * 
     * @param Player $player
     * 
     * @return string
     */
    public function getFirstJoinMessage(Player $player){
        return TextFormat::colorize($this->replaceVars($this->cfg["FirstJoin"]["message"], array(
            "PLAYER" => $player->getName(),
            "MAXPLAYERS" => $this->getServer()->getMaxPlayers(),
            "TOTALPLAYERS" => count($this->getServer()->getOnlinePlayers()),
            "TIME" => date($this->cfg["datetime-format"]))));
    }
    
    /**
     * Check if join message is custom
     * 
     * @return bool
     */
    public function isJoinMessageCustom() : bool {
    	return $this->cfg["Join"]["custom"];
    }
    
    /**
     * Check if join message is hidden
     *
     * @return bool
     */
    public function isJoinMessageHidden() : bool {
    	return $this->cfg["Join"]["hide"];
    }
    
    /**
     * Get join message
     * 
     * @param Player $player
     * 
     * @return string
     */
    public function getJoinMessage(Player $player){
        return TextFormat::colorize($this->replaceVars($this->cfg["Join"]["message"], array(
            "PLAYER" => $player->getName(),
            "MAXPLAYERS" => $this->getServer()->getMaxPlayers(),
            "TOTALPLAYERS" => count($this->getServer()->getOnlinePlayers()),
            "TIME" => date($this->cfg["datetime-format"]))));
    }
    
    /**
     * Check if quit message is custom
     *
     * @return bool
     */
    public function isQuitMessageCustom(){
    	return $this->cfg["Quit"]["custom"];
    }
    
    /**
     * Check if quit message is hidden
     *
     * @return bool
     */
    public function isQuitMessageHidden(){
    	return $this->cfg["Quit"]["hide"];
    }
    
    /**
     * Get default quit message
     * 
     * @param Player $player
     * 
     * @return string
     */
    public function getQuitMessage(Player $player){
        return TextFormat::colorize($this->replaceVars($this->cfg["Quit"]["message"], array(
            "PLAYER" => $player->getName(),
            "MAXPLAYERS" => $this->getServer()->getMaxPlayers(),
            "TOTALPLAYERS" => count($this->getServer()->getOnlinePlayers()),
            "TIME" => date($this->cfg["datetime-format"]))));
    }
    
    /**
     * Check if world change message is enabled
     *
     * @return bool
     */
    public function isWorldChangeMessageEnabled(){
    	return $this->cfg["WorldChange"]["enable"];
    }
    
    /**
     * Get world change message
     *
     * @param Player $player
     * @param World $origin
     * @param World $target
     *
     * @return string
     */
    public function getWorldChangeMessage(Player $player, World $origin, World $target){
        return TextFormat::colorize($this->replaceVars($this->cfg["WorldChange"]["message"], array(
    	    "ORIGIN" => $origin->getDisplayName(),
    	    "TARGET" => $target->getDisplayName(),
    	    "PLAYER" => $player->getName(),
    	    "MAXPLAYERS" => $this->getServer()->getMaxPlayers(),
    	    "TOTALPLAYERS" => count($this->getServer()->getOnlinePlayers()),
    	    "TIME" => date($this->cfg["datetime-format"]))));
    }
    
    /**
     * Check if death messages are custom
     * 
     *
     * @return bool
     */
    public function isDeathMessageCustom(?EntityDamageEvent $cause = null){
        if(!$cause){
            return $this->cfg["Death"]["custom"];
        }
        switch($cause->getCause()){
            case EntityDamageEvent::CAUSE_CONTACT:
                return $this->cfg["Death"]["death-contact-message"]["custom"];
            case EntityDamageEvent::CAUSE_ENTITY_ATTACK:
                return $this->cfg["Death"]["kill-message"]["custom"];
            case EntityDamageEvent::CAUSE_PROJECTILE:
                return $this->cfg["Death"]["death-projectile-message"]["custom"];
            case EntityDamageEvent::CAUSE_SUFFOCATION:
                return $this->cfg["Death"]["death-suffocation-message"]["custom"];
            case EntityDamageEvent::CAUSE_FALL:
                return $this->cfg["Death"]["death-fall-message"]["custom"];
            case EntityDamageEvent::CAUSE_FIRE:
                return $this->cfg["Death"]["death-fire-message"]["custom"];
            case EntityDamageEvent::CAUSE_FIRE_TICK:
                return $this->cfg["Death"]["death-on-fire-message"]["custom"];
            case EntityDamageEvent::CAUSE_LAVA:
                return $this->cfg["Death"]["death-lava-message"]["custom"];
            case EntityDamageEvent::CAUSE_DROWNING:
                return $this->cfg["Death"]["death-drowning-message"]["custom"];
            case EntityDamageEvent::CAUSE_ENTITY_EXPLOSION:
            case EntityDamageEvent::CAUSE_BLOCK_EXPLOSION:
                return $this->cfg["Death"]["death-explosion-message"]["custom"];
            case EntityDamageEvent::CAUSE_VOID:
                return $this->cfg["Death"]["death-void-message"]["custom"];
            case EntityDamageEvent::CAUSE_SUICIDE:
                return $this->cfg["Death"]["death-suicide-message"]["custom"];
            case EntityDamageEvent::CAUSE_MAGIC:
                return $this->cfg["Death"]["death-magic-message"]["custom"];
            default:
                return $this->cfg["Death"]["custom"];
        }
    }
    
    /**
     * Check if death messages are hidden
     * 
     * @param EntityDamageEvent $cause
     *
     * @return bool
     */
    public function isDeathMessageHidden(EntityDamageEvent $cause = null){
        if(!$cause){
            return $this->cfg["Death"]["hide"];
        }
        switch($cause->getCause()){
            case EntityDamageEvent::CAUSE_CONTACT:
                return $this->cfg["Death"]["death-contact-message"]["hide"];
            case EntityDamageEvent::CAUSE_ENTITY_ATTACK:
                return $this->cfg["Death"]["kill-message"]["hide"];
            case EntityDamageEvent::CAUSE_PROJECTILE:
                return $this->cfg["Death"]["death-projectile-message"]["hide"];
            case EntityDamageEvent::CAUSE_SUFFOCATION:
                return $this->cfg["Death"]["death-suffocation-message"]["hide"];
            case EntityDamageEvent::CAUSE_FALL:
                return $this->cfg["Death"]["death-fall-message"]["hide"];
            case EntityDamageEvent::CAUSE_FIRE:
                return $this->cfg["Death"]["death-fire-message"]["hide"];
            case EntityDamageEvent::CAUSE_FIRE_TICK:
                return $this->cfg["Death"]["death-on-fire-message"]["hide"];
            case EntityDamageEvent::CAUSE_LAVA:
                return $this->cfg["Death"]["death-lava-message"]["hide"];
            case EntityDamageEvent::CAUSE_DROWNING:
                return $this->cfg["Death"]["death-drowning-message"]["hide"];
            case EntityDamageEvent::CAUSE_ENTITY_EXPLOSION:
            case EntityDamageEvent::CAUSE_BLOCK_EXPLOSION:
                return $this->cfg["Death"]["death-explosion-message"]["hide"];
            case EntityDamageEvent::CAUSE_VOID:
                return $this->cfg["Death"]["death-void-message"]["hide"];
            case EntityDamageEvent::CAUSE_SUICIDE:
                return $this->cfg["Death"]["death-suicide-message"]["hide"];
            case EntityDamageEvent::CAUSE_MAGIC:
                return $this->cfg["Death"]["death-magic-message"]["hide"];
            default:
                return $this->cfg["Death"]["hide"];
        }
    }
    
    /**
     * Get death message related to the specified cause
     *
     * @param Player $player
     * @param EntityDamageEvent $cause
     *
     * @return string
     */
    public function getDeathMessage(Player $player, EntityDamageEvent $cause = null){
        $array = array(
            "PLAYER" => $player->getName(),
            "MAXPLAYERS" => $this->getServer()->getMaxPlayers(),
            "TOTALPLAYERS" => count($this->getServer()->getOnlinePlayers()),
            "TIME" => date($this->cfg["datetime-format"]));
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
                    if($cause instanceof EntityDamageByEntityEvent){
                        $killer = $cause->getDamager();
                        if($killer instanceof Living){
                            $array["KILLER"] = $killer->getName();
                            break;
                        }
                    }
                    $array["KILLER"] = "Unknown";
                    break;
                case EntityDamageEvent::CAUSE_PROJECTILE:
                    $message = $this->cfg["Death"]["death-projectile-message"]["message"];
                    if($cause instanceof ProjectileHitEntityEvent){
                        $projectile = $cause->getEntity();
                        if($projectile instanceof Projectile){
                            $killer = $projectile->getOwningEntity();
                            if($killer instanceof Living){
                                $array["KILLER"] = $killer->getName();
                                break;
                            }
                        }
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
