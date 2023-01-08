<?php
namespace noahasu\GateWarpPlugin\block\tile;

use pocketmine\block\tile\Tile;
use pocketmine\entity\Location;
use pocketmine\nbt\tag\CompoundTag;
use pocketmine\Server;
use pocketmine\world\World;

class NetherPortal extends Tile {
    private const TAG_TELEPORT_X = 'teleportX';
    private const TAG_TELEPORT_Y = 'teleportY';
    private const TAG_TELEPORT_Z = 'teleportZ';
    private const TAG_TELEPORT_WORLD = 'teleportWorld';
    private ?Location $loc = null;

    private ?int $locx = null;
    private ?int $locy = null;
    private ?int $locz = null;
    private ?string $worldname = null;

    public function setTeleportLocation(Location $loc) : void {
        $this -> loc = $loc;
        $this -> locx = $loc -> x;
        $this -> locy = $loc -> y;
        $this -> locz = $loc -> z;
        $this -> worldname = $loc -> getWorld() -> getFolderName();
    }

    public function getTeleportLocation() : ?Location {
        return $this -> loc;
    }

    public function readSaveData(CompoundTag $nbt): void
    {
        $default = Server::getInstance() -> getWorldManager() -> getDefaultWorld();
        $defaultSpawn = $default->getSpawnLocation();
        $x = $nbt -> getFloat(self::TAG_TELEPORT_X, $defaultSpawn -> x);
        $y = $nbt -> getFloat(self::TAG_TELEPORT_Y, $defaultSpawn -> y);
        $z = $nbt -> getFloat(self::TAG_TELEPORT_Z, $defaultSpawn -> z);
        $worldname = $nbt -> getString(self::TAG_TELEPORT_WORLD, $default -> getFolderName());

        $worldManager = Server::getInstance() -> getWorldManager();
        $world = $worldManager -> getWorldByName($worldname);
        if($world == null) {
            $worldManager -> loadWorld($worldname);
            $world = $worldManager -> getWorldByName($worldname);
        }

        if($world instanceof World)
            $this -> setTeleportLocation(new Location($x, $y, $z, $world, 0, 0));
    }

    protected function writeSaveData(CompoundTag $nbt): void
    {
        if($this -> loc === null) return;
        $nbt -> setFloat(self::TAG_TELEPORT_X, $this -> locx);
        $nbt -> setFloat(self::TAG_TELEPORT_Y, $this -> locy);
        $nbt -> setFloat(self::TAG_TELEPORT_Z, $this -> locz);
        $nbt -> setString(self::TAG_TELEPORT_WORLD, $this -> worldname);
    }
}