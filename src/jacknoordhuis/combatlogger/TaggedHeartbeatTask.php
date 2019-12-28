<?php

/**
 * CombatLogger plugin for PocketMine-MP
 * Copyright (C) 2017 JackNoordhuis
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU Lesser General Public License as published by
 * the Free Software Foundation, either version 2 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 */

namespace jacknoordhuis\combatlogger;

use pocketmine\Player;
use pocketmine\scheduler\Task;
use Scoreboards\Scoreboards;

class TaggedHeartbeatTask extends Task {

	private $plugin;
	
	public function __construct(CombatLogger $plugin) {
		$this->plugin = $plugin;
		return;
	}

	
	public function onRun(int $currentTick) {
		
		foreach($this->plugin->taggedPlayers as $name => $time) {
			$sender = $this->plugin->getPlayer(strtolower($player->getName()));
			$api = Scoreboards::getInstance();
			$api->new($player, "COMBAT", TextFormat::RED . TextFormat::BOLD . "COMBAT LOGGER");
			$api->setLine($player, 1, TextFormat::GOLD . "");
			$api->setLine($player, 2, TextFormat::GRAY . "§eYou are in combat");
			$api->setLine($player, 3, TextFormat::GRAY . "§aTime remaining:" . " " . $time);
			$api->setLine($player, 4, TextFormat::BLUE . "");
			$api->getObjectiveName($player);			
			$time--;
			if($time <= 0) {
				$this->plugin->setTagged($name, false);
				$player = $this->plugin->getServer()->getPlayerExact($name);
				if($player instanceof Player) $player->sendMessage($this->plugin->getMessageManager()->getMessage("player-tagged-timeout"));
				return;
			}
			$this->plugin->taggedPlayers[$name]--;
		}
	}

}
