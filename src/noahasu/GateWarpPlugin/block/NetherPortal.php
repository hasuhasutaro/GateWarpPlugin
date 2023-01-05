<?php
namespace noahasu\GateWarpPlugin\block;

use Exception;
use noahasu\GateWarpPlugin\block\tile\NetherPortal as TileNetherPortal;
use noahasu\GateWarpPlugin\form\CreatePortalForm;
use pocketmine\block\Block;
use pocketmine\block\BlockBreakInfo;
use pocketmine\block\BlockIdentifier;
use pocketmine\block\MonsterSpawner;
use pocketmine\entity\Entity;
use pocketmine\block\NetherPortal as VanillaPortal;
use pocketmine\item\Item;
use pocketmine\math\Vector3;
use pocketmine\player\Player;
use pocketmine\Server;
use pocketmine\world\BlockTransaction;

class NetherPortal extends VanillaPortal {

    public function onScheduledUpdate(): void
    {
        $world = $this -> position -> getWorld();
        $tile = $world -> getTile($this -> position);
        if($tile -> isClosed()) return;
        if(!$tile instanceof TileNetherPortal) return;
        $world -> scheduleDelayedBlockUpdate($this->position, 20 * 1);
    }

    public function onInteract(Item $item, int $face, Vector3 $clickVector, ?Player $player = null): bool
    {
        if($player === null) return true;
        $tile = $this -> position -> getWorld() -> getTile($this -> position);
        if(!$tile instanceof TileNetherPortal) return true;
        
        if($player -> getServer() -> isOp($player -> getName())) {
            if(!$player -> isSneaking())
                $player -> sendForm(new CreatePortalForm($tile));
        }
        $player -> sendTip("§5あなたを別のところへ移動させるゲートです！§7\n". $tile ?-> getTeleportLocation() ?-> __toString());
        return true;
    }

    public function hasEntityCollision() : bool{
		return true;
	}

    public function onEntityInside(Entity $player) : bool{
        if(!$player instanceof Player) return true;

        $tile = $this -> position -> getWorld() -> getTile($this -> position);
        if(!$tile instanceof TileNetherPortal) return true;
        $loc = $tile ?-> getTeleportLocation();

        if($loc === null) return true;
        $player -> teleport($loc);
		return true;
	}
}