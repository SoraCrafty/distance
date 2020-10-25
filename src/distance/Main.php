<?php

namespace distance;

use pocketmine\plugin\PluginBase;
use pocketmine\event\Listener;

use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\math\Vector3;

use pocketmine\scheduler\ClosureTask;
use pocketmine\scheduler\Task;

class Main extends PluginBase implements Listener {

	public function onEnable() {
		$this->getServer()->getPluginManager()->registerEvents($this,$this);
	}

	public function onCommand(CommandSender $sender, Command $cmd, string $label, array $args): bool {

		if(!isset($args[0])) return true;
		$target = $this->getServer()->getPlayer($args[0]);
		/*player's Position*/
		$p1 = $target->getX();
		$p2 = $target->getY();
		$p3 = $target->getZ();

		$p4 = $sender->getX();
		$p5 = $sender->getY();
		$p6 = $sender->getZ();

		$distance = round(sqrt(pow($p1 - $p4 , 2) + pow($p2 - $p5 , 2) + pow($p3 - $p6 , 2)) , 1);
		if($distance < 0) {
			$distance * -1;
			return true;
		}

		$sender->sendMessage($args[0]."との距離は".$distance."mです");

		return true; 
	}
}
