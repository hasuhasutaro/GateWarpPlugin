<?php
namespace noahasu\GateWarpPlugin;

use pocketmine\block\BlockBreakInfo;
use pocketmine\block\BlockFactory;
use pocketmine\block\BlockIdentifier;
use pocketmine\block\BlockLegacyIds;
use pocketmine\block\tile\TileFactory;

use pocketmine\event\Listener;
use pocketmine\plugin\PluginBase;

use noahasu\GateWarpPlugin\block\NetherPortal;
use noahasu\GateWarpPlugin\block\tile\NetherPortal as TileNetherPortal;

class Main extends PluginBase implements Listener {
    public function onEnable() : void {
        $this -> getServer() -> getPluginManager() -> registerEvents($this, $this);
        BlockFactory::getInstance() -> register(
            new NetherPortal(new BlockIdentifier(BlockLegacyIds::PORTAL, 0, null, TileNetherPortal::class), "Nether Portal", BlockBreakInfo::indestructible(0.0)),
            true
        );
        TileFactory::getInstance() -> register(TileNetherPortal::class, ["NetherPortal", "minecraft:nether_portal"]);
    }
}