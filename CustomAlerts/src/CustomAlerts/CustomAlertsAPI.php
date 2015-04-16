<?php

/*
 * CustomAlerts (v1.2) by EvolSoft
 * Developer: EvolSoft (Flavius12)
 * Website: http://www.evolsoft.tk
 * Date: 16/04/2015 04:04 PM (UTC)
 * Copyright & License: (C) 2014-2015 EvolSoft
 * Licensed under MIT (https://github.com/EvolSoft/CustomAlerts/blob/master/LICENSE)
 */

namespace CustomAlerts;

use pocketmine\command\CommandExecutor;
use pocketmine\command\CommandSender;
use pocketmine\event\entity\EntityDamageByBlockEvent;
use pocketmine\event\entity\EntityDamageEvent;
use pocketmine\level\Level;
use pocketmine\Player;
use pocketmine\plugin\PluginBase;
use pocketmine\utils\Config;
use pocketmine\utils\TextFormat;

class CustomAlertsAPI extends PluginBase {
    
	//About Plugin Const
	
	/** @var string PRODUCER Plugin producer */
	const PRODUCER = "EvolSoft";
	
	/** @var string VERSION Plugin version */
	const VERSION = "1.2";
	
	/** @var string MAIN_WEBSITE Plugin producer website */
	const MAIN_WEBSITE = "http://www.evolsoft.tk";
	
	//Other Const
	
	/** @var string PREFIX Plugin prefix */
	const PREFIX = "&b[&aCustom&cAlerts&b] ";
	
	/** @var array $extensions_highest CustomAlerts highest priority extensions */
	private $extensions_highest = array();
	
	/** @var array $extensions_high CustomAlerts high priority extensions */
	private $extensions_high = array();
	
	/** @var array $extensions_normal CustomAlerts normal priority extensions */
	private $extensions_normal = array();
	
	/** @var array $extensions_low CustomAlerts low priority extensions */
	private $extensions_low = array();
	
	/** @var array $extensions_lowest CustomAlerts lowest priority extensions */
	private $extensions_lowest = array();
	
	//Messages
	
	/** @var string $message_join The current join message */
	private $message_join;
	
	/** @var string $message_quit The current quit message */
	private $message_quit;
	
	/** @var string $message_world_change The current world change message */
	private $message_world_change;
	
	/** @var string $message_death The current death message */
	private $message_death;
	
	/** @var CustomAlerts $api Plugin instance */
	private static $instance = null;
	
	/**
	 * Get CustomAlerts API
	 *
	 * @return CustomAlerts CustomAlerts API
	 */
	public static function getAPI(){
		return self::$instance;
	}
	
	public function onLoad(){
		if(!self::$instance instanceof CustomAlerts){
			self::$instance = $this;
		}
	}
	
	/**
	 * Translate Minecraft colors
	 *
	 * @param char $symbol Color symbol
	 * @param string $message The message to be translated
	 *
	 * @return string The translated message
	 */
	public function translateColors($symbol, $message){
	
		$message = str_replace($symbol."0", TextFormat::BLACK, $message);
		$message = str_replace($symbol."1", TextFormat::DARK_BLUE, $message);
		$message = str_replace($symbol."2", TextFormat::DARK_GREEN, $message);
		$message = str_replace($symbol."3", TextFormat::DARK_AQUA, $message);
		$message = str_replace($symbol."4", TextFormat::DARK_RED, $message);
		$message = str_replace($symbol."5", TextFormat::DARK_PURPLE, $message);
		$message = str_replace($symbol."6", TextFormat::GOLD, $message);
		$message = str_replace($symbol."7", TextFormat::GRAY, $message);
		$message = str_replace($symbol."8", TextFormat::DARK_GRAY, $message);
		$message = str_replace($symbol."9", TextFormat::BLUE, $message);
		$message = str_replace($symbol."a", TextFormat::GREEN, $message);
		$message = str_replace($symbol."b", TextFormat::AQUA, $message);
		$message = str_replace($symbol."c", TextFormat::RED, $message);
		$message = str_replace($symbol."d", TextFormat::LIGHT_PURPLE, $message);
		$message = str_replace($symbol."e", TextFormat::YELLOW, $message);
		$message = str_replace($symbol."f", TextFormat::WHITE, $message);
	
		$message = str_replace($symbol."k", TextFormat::OBFUSCATED, $message);
		$message = str_replace($symbol."l", TextFormat::BOLD, $message);
		$message = str_replace($symbol."m", TextFormat::STRIKETHROUGH, $message);
		$message = str_replace($symbol."n", TextFormat::UNDERLINE, $message);
		$message = str_replace($symbol."o", TextFormat::ITALIC, $message);
		$message = str_replace($symbol."r", TextFormat::RESET, $message);
	
		return $message;
	}
	
    public function onEnable(){
    	@mkdir($this->getDataFolder());
    	@mkdir($this->getDataFolder() . "data/");
    	$this->saveDefaultConfig();
    	$this->cfg = $this->getConfig()->getAll();
    	$this->getCommand("customalerts")->setExecutor(new Commands\Commands($this));
    	$this->getServer()->getPluginManager()->registerEvents(new EventListener($this), $this);
    }
    
    //API Functions
    
    /** @var string API_VERSION CustomAlerts API version */
    const API_VERSION = "1.0";
    
    /** @var int PRIORITY_HIGHEST */
    const PRIORITY_HIGHEST = 5;
    
    /** @var int PRIORITY_HIGH */
    const PRIORITY_HIGH = 4;
    
    /** @var int PRIORITY_NORMAL */
    const PRIORITY_NORMAL = 3;
    
    /** @var int PRIORITY_LOW */
    const PRIORITY_LOW = 2;
    
    /** @var int PRIORITY_LOWEST */
    const PRIORITY_LOWEST = 1;
    
    /**
     * Get CustomAlerts version
     *
     * @return string CustomAlerts version
     */
    public function getVersion(){
    	return CustomAlertsAPI::VERSION;
    }
    
    /**
     * Get CustomAlerts API version
     *
     * @return string CustomAlerts API version
     */
    public function getAPIVersion(){
    	return CustomAlertsAPI::API_VERSION;
    }
    
    /**
     * Register Plugin as CustomAlerts Extension
     * 
     * @param PluginBase $extension The Plugin to register as extension
     * @param int $priority (optional)
     */
    public function registerExtension(PluginBase $extension, $priority = null){
    	if($priority == CustomAlertsAPI::PRIORITY_HIGHEST){
    		array_push($this->extensions_highest, $extension);
    	}elseif($priority == CustomAlertsAPI::PRIORITY_HIGH){
    		array_push($this->extensions_high, $extension);
    	}elseif($priority == CustomAlertsAPI::PRIORITY_NORMAL){
    		array_push($this->extensions_normal, $extension);
    	}elseif($priority == CustomAlertsAPI::PRIORITY_LOW){
    		array_push($this->extensions_low, $extension);
    	}elseif($priority == CustomAlertsAPI::PRIORITY_LOWEST){
    		array_push($this->extensions_lowest, $extension);
    	}else{
    	    array_push($this->extensions_normal, $extension);
    	}
    }
    
    /**
     * Get all CustomAlerts loaded extensions
     * 
     * @param int $priority (optional)
     * 
     * @return array All CustomAlerts loaded extensions if no priority specified, otherwise returns all extesions with the specified priority
     */
    public function getAllExtensions($priority = null){
    	if($priority == CustomAlertsAPI::PRIORITY_HIGHEST){
    		return $this->extensions_highest;
    	}elseif($priority == CustomAlertsAPI::PRIORITY_HIGH){
    		return $this->extensions_high;
    	}elseif($priority == CustomAlertsAPI::PRIORITY_NORMAL){
    		return $this->extensions_normal;
    	}elseif($priority == CustomAlertsAPI::PRIORITY_LOW){
    		return $this->extensions_low;
    	}elseif($priority == CustomAlertsAPI::PRIORITY_LOWEST){
    		return $this->extensions_lowest;
    	}else{
    		return array_merge($this->extensions_highest, $this->extensions_high, $this->extensions_normal, $this->extensions_low, $this->extensions_lowest);
    	}
    }
    
    /**
     * Get if default first join message is enabled
     * 
     * @return boolean
     */
    public function isDefaultFirstJoinMessageEnabled(){
    	$cfg = $this->getConfig()->getAll();
    	return $cfg["FirstJoin"]["enable"];
    }

    /**
     * Get default first join message
     * 
     * @param Player $player
     * 
     * @return string The default first join message
     */
    public function getDefaultFirstJoinMessage(Player $player){
    	$cfg = $this->getConfig()->getAll();
    	$message = $cfg["FirstJoin"]["message"];
    	$message = str_replace("{PLAYER}", $player->getName(), $message);
    	$message = str_replace("{MAXPLAYERS}", $this->getServer()->getMaxPlayers(), $message);
    	$message = str_replace("{TOTALPLAYERS}", count($this->getServer()->getOnlinePlayers()), $message);
    	$message = str_replace("{TIME}", date($cfg["datetime-format"]), $message);
    	return $this->translateColors("&", $message);
    }
    
    /**
     * Register the first join of a player (don't use this function)
     * @param Player $player
     */
    public function registerFirstJoin(Player $player){
    	$cfg = new Config($this->getDataFolder() . "data/" . strtolower($player->getName() . ".dat"));
    	$cfg->save();
    }
    
    /**
     * Check if a player has joined for the first time
     * 
     * @param Player $player
     * 
     * @return boolean
     */
    public function hasJoinedFirstTime(Player $player){
    	if(file_exists($this->getDataFolder() . "data/" . strtolower($player->getName() . ".dat"))){
    		return false;
    	}else{
    		return true;
    	}
    }
    
    /**
     * Check if default join message is custom
     * 
     * @return boolean
     */
    public function isDefaultJoinMessageCustom(){
    	$cfg = $this->getConfig()->getAll();
    	return $cfg["Join"]["custom"];
    }
    
    /**
     * Check if default join message is hidden
     *
     * @return boolean
     */
    public function isDefaultJoinMessageHidden(){
    	$tmp = $this->getConfig()->getAll();
    	return $tmp["Join"]["hide"];
    }
    
    /**
     * Get default join message
     * 
     * @param Player $player
     * 
     * @return string The default join message
     */
    public function getDefaultJoinMessage(Player $player){
    	$cfg = $this->getConfig()->getAll();
    	$message = $cfg["Join"]["message"];
    	$message = str_replace("{PLAYER}", $player->getName(), $message);
    	$message = str_replace("{MAXPLAYERS}", $this->getServer()->getMaxPlayers(), $message);
    	$message = str_replace("{TOTALPLAYERS}", count($this->getServer()->getOnlinePlayers()), $message);
    	$message = str_replace("{TIME}", date($cfg["datetime-format"]), $message);
    	return $this->translateColors("&", $message);
    }
    
    /**
     * Get current join message
     * 
     * @return string The current join message
     */
    public function getJoinMessage(){
    	return $this->message_join;
    }
    
    /**
     * Set current join message
     * 
     * @param string $message The message
     */
    public function setJoinMessage($message){
    	$this->message_join = $message;
    }
    
    /**
     * Check if default quit message is custom
     *
     * @return boolean
     */
    public function isQuitCustom(){
    	$cfg = $this->getConfig()->getAll();
    	return $cfg["Quit"]["custom"];
    }
    
    /**
     * Check if default quit message is hidden
     *
     * @return boolean
     */
    public function isQuitHidden(){
    	$cfg = $this->getConfig()->getAll();
    	return $cfg["Quit"]["hide"];
    }
    
    /**
     * Get default quit message
     * 
     * @param Player $player
     * 
     * @return string The default quit message
     */
    public function getDefaultQuitMessage(Player $player){
    	$cfg = $this->getConfig()->getAll();
    	$message = $cfg["Quit"]["message"];
    	$message = str_replace("{PLAYER}", $player->getName(), $message);
    	$message = str_replace("{MAXPLAYERS}", $this->getServer()->getMaxPlayers(), $message);
    	$message = str_replace("{TOTALPLAYERS}", count($this->getServer()->getOnlinePlayers()), $message);
    	$message = str_replace("{TIME}", date($cfg["datetime-format"]), $message);
    	return $this->translateColors("&", $message);
    }
    
    /**
     * Get current quit message
     * 
     * @return string The current quit message
     */
    public function getQuitMessage(){
    	return $this->message_quit;
    }
    
    /**
     * Set current quit message
     * 
     * @param string $message The message
     */
    public function setQuitMessage($message){
    	$this->message_quit = $message;
    }
    
    /**
     * Get if default world change message is enabled
     *
     * @return boolean
     */
    public function isDefaultWorldChangeMessageEnabled(){
    	$cfg = $this->getConfig()->getAll();
    	return $cfg["WorldChange"]["enable"];
    }
    
    /**
     * Get default quit message
     *
     * @param Player $player
     * @param Level $origin
     * @param Level $target
     *
     * @return string The default world change message
     */
    public function getDefaultWorldChangeMessage(Player $player, Level $origin, Level $target){
    	$cfg = $this->getConfig()->getAll();
    	$message = $cfg["WorldChange"]["message"];
    	$message = str_replace("{ORIGIN}", $origin->getName(), $message);
    	$message = str_replace("{TARGET}", $target->getName(), $message);
    	$message = str_replace("{PLAYER}", $player->getName(), $message);
    	$message = str_replace("{MAXPLAYERS}", $this->getServer()->getMaxPlayers(), $message);
    	$message = str_replace("{TOTALPLAYERS}", count($this->getServer()->getOnlinePlayers()), $message);
    	$message = str_replace("{TIME}", date($tmp["datetime-format"]), $message);
    	return $this->translateColors("&", $message);
    }
    
    /**
     * Get current world change message
     *
     * @return string The current world change message
     */
    public function getWorldChangeMessage(){
    	return $this->message_world_change;
    }
    
    /**
     * Set current world change message
     *
     * @param string $message The message
     */
    public function setWorldChangeMessage($message){
    	$this->message_world_change = $message;
    }
    
    /**
     * Check if death messages are custom
     * 
     * @param EntityDeathEvent $cause Check message by cause
     *
     * @return boolean
     */
    public function isDeathCustom($cause = null){
        $cfg = $this->getConfig()->getAll();
        if($cause instanceof EntityDamageEvent){
        	if($cause->getCause() == EntityDamageEvent::CAUSE_CONTACT){
        		return $cfg["Death"]["death-contact-message"]["custom"];
        	}elseif($cause->getCause() == EntityDamageEvent::CAUSE_ENTITY_ATTACK){
        		return $cfg["Death"]["kill-message"]["custom"];
        	}elseif($cause->getCause() == EntityDamageEvent::CAUSE_PROJECTILE){
        		return $cfg["Death"]["death-projectile-message"]["custom"];
        	}elseif($cause->getCause() == EntityDamageEvent::CAUSE_SUFFOCATION){
        		return $cfg["Death"]["death-suffocation-message"]["custom"];
        	}elseif($cause->getCause() == EntityDamageEvent::CAUSE_FALL){
        		return $cfg["Death"]["death-fall-message"]["custom"];
        	}elseif($cause->getCause() == EntityDamageEvent::CAUSE_FIRE){
        		return $cfg["Death"]["death-fire-message"]["custom"];
        	}elseif($cause->getCause() == EntityDamageEvent::CAUSE_FIRE_TICK){
        		return $cfg["Death"]["death-on-fire-message"]["custom"];
        	}elseif($cause->getCause() == EntityDamageEvent::CAUSE_LAVA){
        		return $cfg["Death"]["death-lava-message"]["custom"];
        	}elseif($cause->getCause() == EntityDamageEvent::CAUSE_DROWNING){
        		return $cfg["Death"]["death-drowning-message"]["custom"];
        	}elseif($cause->getCause() == EntityDamageEvent::CAUSE_ENTITY_EXPLOSION || $cause->getCause() == EntityDamageEvent::CAUSE_BLOCK_EXPLOSION){
        		return $cfg["Death"]["death-explosion-message"]["custom"];
        	}elseif($cause->getCause() == EntityDamageEvent::CAUSE_VOID){
        		return $cfg["Death"]["death-void-message"]["custom"];
        	}elseif($cause->getCause() == EntityDamageEvent::CAUSE_SUICIDE){ 
        		return $cfg["Death"]["death-suicide-message"]["custom"];
        	}elseif($cause->getCause() == EntityDamageEvent::CAUSE_MAGIC){
        		return $cfg["Death"]["death-magic-message"]["custom"];
        	}else{
        		return $cfg["Death"]["custom"];
        	}
    	}else{
    		return $cfg["Death"]["custom"];
    	}
    }
    
    /**
     * Check if death messages are hidden
     * 
     * @param EntityDamageEvent $cause Check message by cause
     *
     * @return boolean
     */
    public function isDeathHidden($cause = null){
    	$cfg = $this->getConfig()->getAll();
        if($cause instanceof EntityDamageEvent){
        	if($cause->getCause() == EntityDamageEvent::CAUSE_CONTACT){
        		return $cfg["Death"]["death-contact-message"]["hide"];
        	}elseif($cause->getCause() == EntityDamageEvent::CAUSE_ENTITY_ATTACK){
        		return $cfg["Death"]["kill-message"]["hide"];
        	}elseif($cause->getCause() == EntityDamageEvent::CAUSE_PROJECTILE){
        		return $cfg["Death"]["death-projectile-message"]["hide"];
        	}elseif($cause->getCause() == EntityDamageEvent::CAUSE_SUFFOCATION){
        		return $cfg["Death"]["death-suffocation-message"]["hide"];
        	}elseif($cause->getCause() == EntityDamageEvent::CAUSE_FALL){
        		return $cfg["Death"]["death-fall-message"]["hide"];
        	}elseif($cause->getCause() == EntityDamageEvent::CAUSE_FIRE){
        		return $cfg["Death"]["death-fire-message"]["hide"];
        	}elseif($cause->getCause() == EntityDamageEvent::CAUSE_FIRE_TICK){
        		return $cfg["Death"]["death-on-fire-message"]["hide"];
        	}elseif($cause->getCause() == EntityDamageEvent::CAUSE_LAVA){
        		return $cfg["Death"]["death-lava-message"]["hide"];
        	}elseif($cause->getCause() == EntityDamageEvent::CAUSE_DROWNING){
        		return $cfg["Death"]["death-drowning-message"]["hide"];
        	}elseif($cause->getCause() == EntityDamageEvent::CAUSE_ENTITY_EXPLOSION || $cause->getCause() == EntityDamageEvent::CAUSE_BLOCK_EXPLOSION){
        		return $cfg["Death"]["death-explosion-message"]["hide"];
        	}elseif($cause->getCause() == EntityDamageEvent::CAUSE_VOID){
        		return $cfg["Death"]["death-void-message"]["hide"];
        	}elseif($cause->getCause() == EntityDamageEvent::CAUSE_SUICIDE){ 
        		return $cfg["Death"]["death-suicide-message"]["hide"];
        	}elseif($cause->getCause() == EntityDamageEvent::CAUSE_MAGIC){
        		return $cfg["Death"]["death-magic-message"]["hide"];
        	}else{
        		return $cfg["Death"]["hide"];
        	}
    	}else{
    		return $cfg["Death"]["hide"];
    	}
    }
    
    /**
     * Get default death message related to the specified cause
     *
     * @param Player $player
     * @param EntityDamageEvent $cause Get message related to the specified cause
     *
     * @return string The default death message related to the specified cause
     */
    public function getDefaultDeathMessage(Player $player, $cause = null){
    	$cfg = $this->getConfig()->getAll();
        if($cause instanceof EntityDamageEvent){
        	if($cause->getCause() == EntityDamageEvent::CAUSE_CONTACT){
        		$message = $cfg["Death"]["death-contact-message"]["message"];
        		if($cause instanceof EntityDamageByBlockEvent){
        			$message = str_replace("{BLOCK}", $cause->getDamager()->getName(), $message);
        		}else{
        			$message = str_replace("{BLOCK}", "Unknown", $message);
        		}
        	}elseif($cause->getCause() == EntityDamageEvent::CAUSE_ENTITY_ATTACK){
        		$message = $cfg["Death"]["kill-message"]["message"];
        	    $killer = $cause->getDamager();
        		if($killer instanceof Living){
        			$message = str_replace("{KILLER}", $killer->getName(), $message);
        		}else{
        			$message = str_replace("{KILLER}", "Unknown", $message);
        		}
        	}elseif($cause->getCause() == EntityDamageEvent::CAUSE_PROJECTILE){
        		$message = $cfg["Death"]["death-projectile-message"]["message"];
        		$killer = $cause->getDamager();
        		if($killer instanceof Living){
        			$message = str_replace("{KILLER}", $killer->getName(), $message);
        		}else{
        			$message = str_replace("{KILLER}", "Unknown", $message);
        		}
        	}elseif($cause->getCause() == EntityDamageEvent::CAUSE_SUFFOCATION){
        		$message = $cfg["Death"]["death-suffocation-message"]["message"];
        	}elseif($cause->getCause() == EntityDamageEvent::CAUSE_FALL){
        		$message = $cfg["Death"]["death-fall-message"]["message"];
        	}elseif($cause->getCause() == EntityDamageEvent::CAUSE_FIRE){
        		$message = $cfg["Death"]["death-fire-message"]["message"];
        	}elseif($cause->getCause() == EntityDamageEvent::CAUSE_FIRE_TICK){
        		$message = $cfg["Death"]["death-on-fire-message"]["message"];
        	}elseif($cause->getCause() == EntityDamageEvent::CAUSE_LAVA){
        		$message = $cfg["Death"]["death-lava-message"]["message"];
        	}elseif($cause->getCause() == EntityDamageEvent::CAUSE_DROWNING){
        		$message = $cfg["Death"]["death-drowning-message"]["message"];
        	}elseif($cause->getCause() == EntityDamageEvent::CAUSE_ENTITY_EXPLOSION || $cause->getCause() == EntityDamageEvent::CAUSE_BLOCK_EXPLOSION){
        		$message = $cfg["Death"]["death-explosion-message"]["message"];
        	}elseif($cause->getCause() == EntityDamageEvent::CAUSE_VOID){
        		$message = $cfg["Death"]["death-void-message"]["message"];
        	}elseif($cause->getCause() == EntityDamageEvent::CAUSE_SUICIDE){
        		$message = $cfg["Death"]["death-suicide-message"]["message"];
        	}elseif($cause->getCause() == EntityDamageEvent::CAUSE_MAGIC){
        		$message = $cfg["Death"]["death-magic-message"]["message"];
        	}else{
        		$message = $cfg["Death"]["message"];
        	}
    	}else{
    		$message = $cfg["Death"]["message"];
    	}
    	$message = str_replace("{PLAYER}", $player->getName(), $message);
    	$message = str_replace("{MAXPLAYERS}", $this->getServer()->getMaxPlayers(), $message);
    	$message = str_replace("{TOTALPLAYERS}", count($this->getServer()->getOnlinePlayers()), $message);
    	$message = str_replace("{TIME}", date($cfg["datetime-format"]), $message);
    	return $this->translateColors("&", $message);
    }
    
    /**
     * Get current death message
     *
     * @return string The current death message
     */
    public function getDeathMessage(){
    	return $this->message_death;
    }
    
    /**
     * Set current death message
     *
     * @param string $message The message
     */
    public function setDeathMessage($message){
    	$this->message_death = $message;
    }

}
?>
