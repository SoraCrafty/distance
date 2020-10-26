<?php

namespace distance;

use pocketmine\plugin\PluginBase;
use pocketmine\event\Listener;

use pocketmine\Player;
use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\math\Vector3;

use pocketmine\scheduler\ClosureTask;
use pocketmine\scheduler\Task;

class Main extends PluginBase implements Listener {

	/*Task管理用
	[$playername => $taskinstnace]
	*/
	private $tasks = [];

	public function onEnable() {
		$this->getServer()->getPluginManager()->registerEvents($this,$this);
	}

	public function onCommand(CommandSender $sender, Command $cmd, string $label, array $args): bool {

		if(!isset($args[0])) return true;
		$target = $this->getServer()->getPlayer($args[0]);
		$name = $sender->getName();

		if(!$target = $this->getServer()->getPlayer($args[0])) {
			$sender->sendMessage("§c".$args[0]."さんはオフラインです");
			return true;
		}
		$task = new MessageTask($sender,$target,$args[0]);

		if(array_key_exists($name, $this->tasks)) {
			$this->getScheduler()->cancelTask($this->tasks[$name]->getTaskId());
			unset($this->tasks[$name]); //要素の削除
			$this->getScheduler()->scheduleRepeatingTask($task, 1);
			$this->tasks[$name] = $task; //配列への追加
			array_values($this->tasks); //配列内のindex詰め

		}else{

			$this->getScheduler()->scheduleRepeatingTask($task, 1);
			$this->tasks[$name] = $task; //配列への追加
			$sender->sendMessage(">>§a".$args[0]."§fさんとの距離を表示しました");
		}

		return true; 
	}
}

class MessageTask extends Task {
    public function __construct($player, $target, string $targetname){
        $this->sender = $player;
        $this->target = $target;
        $this->targetname = $targetname;
    }
    public function onRun(int $tick){
    	/*player's Position*/
		$p1 = $this->target->getX();
		$p2 = $this->target->getY();
		$p3 = $this->target->getZ();

		$p4 = $this->sender->getX();
		$p5 = $this->sender->getY();
		$p6 = $this->sender->getZ();

		$distance = round(sqrt(pow($p1 - $p4 , 2) + pow($p2 - $p5 , 2) + pow($p3 - $p6 , 2)) , 1);
		if($distance < 0) {
			$distance * -1;
			return true;
		}
        $this->sender->sendPopup($this->targetname."さんとの距離は§a".$distance."m§fです");
    }
}
