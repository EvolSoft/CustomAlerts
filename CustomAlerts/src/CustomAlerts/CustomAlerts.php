<?php

/*
 * CustomAlerts (v1.9) by EvolSoft
 * Developer: EvolSoft (Flavius12)
 * Website: https://www.evolsoft.tk
 * Date: 13/01/2018 02:01 PM (UTC)
 * Copyright & License: (C) 2014-2018 EvolSoft
 * Licensed under MIT (https://github.com/EvolSoft/CustomAlerts/blob/master/LICENSE)
 */

namespace CustomAlerts;

use pocketmine\entity\Living;
use pocketmine\event\entity\EntityDamageByBlockEvent;
use pocketmine\event\entity\EntityDamageEvent;
use pocketmine\event\entity\EntityDeathEvent;
use pocketmine\level\Level;
use pocketmine\Player;
use pocketmine\plugin\PluginBase;
use pocketmine\utils\TextFormat;
use pocketmine\scheduler\Task;

use CustomAlerts\Events\CustomAlertsMotdUpdateEvent;

class CustomAlerts extends PluginBase {
    
	/** @var string */
	const PREFIX = "&b[&aCustom&cAlerts&b] ";
	
	/** @var string */
	const API_VERSION = "2.0";
	
	private $cfg;
	
	/** @var CustomAlerts $instance */
	private static $instance = null;
	
	/**
	 * Translate Minecraft colors
	 * 
	 * @param string $symbol
	 * @param string $message
	 * 
	 * @return string
	 */
	public function translateColors($symbol, $message){
	    $message = str_replace($symbol . "0", TextFormat::BLACK, $message);
	    $message = str_replace($symbol . "1", TextFormat::DARK_BLUE, $message);
	    $message = str_replace($symbol . "2", TextFormat::DARK_GREEN, $message);
	    $message = str_replace($symbol . "3", TextFormat::DARK_AQUA, $message);
	    $message = str_replace($symbol . "4", TextFormat::DARK_RED, $message);
	    $message = str_replace($symbol . "5", TextFormat::DARK_PURPLE, $message);
	    $message = str_replace($symbol . "6", TextFormat::GOLD, $message);
	    $message = str_replace($symbol . "7", TextFormat::GRAY, $message);
	    $message = str_replace($symbol . "8", TextFormat::DARK_GRAY, $message);
	    $message = str_replace($symbol . "9", TextFormat::BLUE, $message);
	    $message = str_replace($symbol . "a", TextFormat::GREEN, $message);
	    $message = str_replace($symbol . "b", TextFormat::AQUA, $message);
	    $message = str_replace($symbol . "c", TextFormat::RED, $message);
	    $message = str_replace($symbol . "d", TextFormat::LIGHT_PURPLE, $message);
	    $message = str_replace($symbol . "e", TextFormat::YELLOW, $message);
	    $message = str_replace($symbol . "f", TextFormat::WHITE, $message);
	    
	    $message = str_replace($symbol . "k", TextFormat::OBFUSCATED, $message);
	    $message = str_replace($symbol . "l", TextFormat::BOLD, $message);
	    $message = str_replace($symbol . "m", TextFormat::STRIKETHROUGH, $message);
	    $message = str_replace($symbol . "n", TextFormat::UNDERLINE, $message);
	    $message = str_replace($symbol . "o", TextFormat::ITALIC, $message);
	    $message = str_replace($symbol . "r", TextFormat::RESET, $message);
	    return $message;
	}
	
	public function onLoad(){
	    if(!self::$instance instanceof CustomAlerts){
	        self::$instance = $this;
	    }
	}
	
    public function onEnable(){
    	@mkdir($this->getDataFolder());
    	$this->saveDefaultConfig();
    	$this->cfg = $this->getConfig()->getAll();
    	$this->getCommand("customalerts")->setExecutor(new Commands\Commands($this));
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
        return $this->translateColors("&", $this->replaceVars($this->cfg["Motd"]["message"], array(
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
        $this->getServer()->getPluginManager()->callEvent($cevent);
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
     * @param Player
     *
     * @return string
     */
    public function getOutdatedClientMessage(Player $player){
        return $this->translateColors("&", $this->replaceVars($this->cfg["OutdatedClient"]["message"], array(
            "PLAYER" => $player->getName(),
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
     * @param Player
     *
     * @return string
     */
    public function getOutdatedServerMessage(Player $player){
        return $this->translateColors("&", $this->replaceVars($this->cfg["OutdatedServer"]["message"], array(
            "PLAYER" => $player->getName(),
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
     * @param Player
     *
     * @return string
     */
    public function getWhitelistMessage(Player $player){
        return $this->translateColors("&", $this->replaceVars($this->cfg["WhitelistedServer"]["message"], array(
            "PLAYER" => $player->getName(),
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
     * @param Player $player
     *
     * @return string
     */
    public function getFullServerMessage(Player $player){
        return $this->translateColors("&", $this->replaceVars($this->cfg["FullServer"]["message"], array(
            "PLAYER" => $player->getName(),
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

    /**
     * Get first join message
     * 
     * @param Player $player
     * 
     * @return string
     */
    public function getFirstJoinMessage(Player $player){
        return $this->translateColors("&", $this->replaceVars($this->cfg["FirstJoin"]["message"], array(
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
        return $this->translateColors("&", $this->replaceVars($this->cfg["Join"]["message"], array(
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
        return $this->translateColors("&", $this->replaceVars($this->cfg["Quit"]["message"], array(
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
     * @param Level $origin
     * @param Level $target
     *
     * @return string
     */
    public function getWorldChangeMessage(Player $player, Level $origin, Level $target){
    	return $this->translateColors("&", $this->replaceVars($this->cfg["WorldChange"]["message"], array(
    	    "ORIGIN" => $origin->getName(),
    	    "TARGET" => $target->getName(),
    	    "PLAYER" => $player->getName(),
    	    "MAXPLAYERS" => $this->getServer()->getMaxPlayers(),
    	    "TOTALPLAYERS" => count($this->getServer()->getOnlinePlayers()),
    	    "TIME" => date($this->cfg["datetime-format"]))));
    }
    
    /**
     * Check if death messages are custom
     * 
     * @param EntityDeathEvent $cause
     *
     * @return bool
     */
    public function isDeathMessageCustom(EntityDamageEvent $cause = null){
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
        return $this->translateColors("&", $this->replaceVars($message, $array));
    }
}
