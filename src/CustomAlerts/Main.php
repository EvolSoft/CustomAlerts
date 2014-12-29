<?php

/*
 * CustomAlerts (v1.1) by EvolSoft
 * Developer: EvolSoft (Flavius12)
 * Website: http://www.evolsoft.tk
 * Date: 29/12/2014 09:28 AM (UTC)
 * Copyright & License: (C) 2014 EvolSoft
 * Licensed under MIT (https://github.com/EvolSoft/CustomAlerts/blob/master/LICENSE)
 */

namespace CustomAlerts;

use pocketmine\Player;
use pocketmine\command\CommandExecutor;
use pocketmine\command\CommandSender;
use pocketmine\level\Level;
use pocketmine\plugin\PluginBase;
use pocketmine\utils\Config;
use pocketmine\utils\TextFormat;

class Main extends PluginBase{
    
	//About Plugin Const
	const PRODUCER = "EvolSoft";
	const VERSION = "1.1";
	const MAIN_WEBSITE = "http://www.evolsoft.tk";
	//Other Const
	//Prefix
	const PREFIX = "&b[&aCustom&cAlerts&b] ";
	
	public $cfg;
	
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
    
    public function registerFirstJoin(Player $player){
    	$tmp = new Config($this->getDataFolder() . "data/" . strtolower($player->getName() . ".dat"));
    	$tmp->save();
    }
    
    public function hasJoinedFirstTime(Player $player){
    	if(file_exists($this->getDataFolder() . "data/" . strtolower($player->getName() . ".dat"))){
    		return false;
    	}else{
    		return true;
    	}
    }
    
    public function isFirstJoinEnabled(){
    	$tmp = $this->getConfig()->getAll();
    	return $tmp["FirstJoin"]["enable"];
    }
    
    public function getFirstJoinMessage(Player $player){
    	$tmp = $this->getConfig()->getAll();
    	$format = $tmp["FirstJoin"]["message"];
    	$format = str_replace("{PLAYER}", $player->getName(), $format);
    	$format = str_replace("{TIME}", date($tmp["datetime-format"]), $format);
    	return $this->translateColors("&", $format);
    }
    
    public function isJoinCustom(){
    	$tmp = $this->getConfig()->getAll();
    	return $tmp["Join"]["custom"];
    }
    
    public function isJoinHidden(){
    	$tmp = $this->getConfig()->getAll();
    	return $tmp["Join"]["hide"];
    }
    
    public function getJoinMessage(Player $player){
    	$tmp = $this->getConfig()->getAll();
    	$format = $tmp["Join"]["message"];
    	$format = str_replace("{PLAYER}", $player->getName(), $format);
    	$format = str_replace("{TIME}", date($tmp["datetime-format"]), $format);
    	return $this->translateColors("&", $format);
    }
    
    public function isQuitCustom(){
    	$tmp = $this->getConfig()->getAll();
    	return $tmp["Quit"]["custom"];
    }
    
    public function isQuitHidden(){
    	$tmp = $this->getConfig()->getAll();
    	return $tmp["Quit"]["hide"];
    }
    
    public function getQuitMessage(Player $player){
    	$tmp = $this->getConfig()->getAll();
    	$format = $tmp["Quit"]["message"];
    	$format = str_replace("{PLAYER}", $player->getName(), $format);
    	$format = str_replace("{TIME}", date($tmp["datetime-format"]), $format);
    	return $this->translateColors("&", $format);
    }
    
    public function isDeathCustom(){
    	$tmp = $this->getConfig()->getAll();
    	return $tmp["Death"]["custom"];
    }
    
    public function isDeathHidden(){
    	$tmp = $this->getConfig()->getAll();
    	return $tmp["Death"]["hide"];
    }
    
    public function getKillMessage(Player $player, Player $killer){
    	$tmp = $this->getConfig()->getAll();
    	$format = $tmp["Death"]["kill-message"];
    	$format = str_replace("{PLAYER}", $player->getName(), $format);
    	$format = str_replace("{KILLER}", $killer->getName(), $format);
    	$format = str_replace("{TIME}", date($tmp["datetime-format"]), $format);
    	return $this->translateColors("&", $format);;
    }
    
    public function getDeathMessage(Player $player, $cause = null){
    	$tmp = $this->getConfig()->getAll();
    	//Causes
    	if($cause == 3){ //Suffocation
    		$format = $tmp["Death"]["death-suffocation-message"];
    	}elseif($cause == 4){ //Fall
    		$format = $tmp["Death"]["death-fall-message"];
    	}elseif($cause == 5){ //Fire
    		$format = $tmp["Death"]["death-fire-message"];
    	}elseif($cause == 7){ //Lava
    		$format = $tmp["Death"]["death-lava-message"];
    	}elseif($cause == 8){ //Drowning
    		$format = $tmp["Death"]["death-drowning-message"];
    	}elseif($cause == 9){ //Explosion
    		$format = $tmp["Death"]["death-explosion-message"];
    	}else{ //Default message
    		$format = $tmp["Death"]["death-default-message"];
    	}
    	$format = str_replace("{PLAYER}", $player->getName(), $format);
    	$format = str_replace("{TIME}", date($tmp["datetime-format"]), $format);
    	return $this->translateColors("&", $format);
    }
    
    public function isWorldChangeEnabled(){
    	$tmp = $this->getConfig()->getAll();
    	return $tmp["WorldChange"]["enable"];
    }
    
    public function getWorldChangeMessage(Player $player, Level $origin, Level $target){
    	$tmp = $this->getConfig()->getAll();
    	$format = $tmp["WorldChange"]["message"];
    	$format = str_replace("{ORIGIN}", $origin->getName(), $format);
    	$format = str_replace("{TARGET}", $target->getName(), $format);
    	$format = str_replace("{PLAYER}", $player->getName(), $format);
    	$format = str_replace("{TIME}", date($tmp["datetime-format"]), $format);
    	return $this->translateColors("&", $format);
    }

}
?>
