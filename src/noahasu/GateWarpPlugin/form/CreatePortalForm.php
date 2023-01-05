<?php
namespace noahasu\GateWarpPlugin\form;

use noahasu\GateWarpPlugin\block\tile\NetherPortal;
use pocketmine\entity\Location;
use pocketmine\form\Form;
use pocketmine\player\Player;
use pocketmine\Server;
use pocketmine\world\World;

class CreatePortalForm implements Form {
    private NetherPortal $tile;
    public function __construct(NetherPortal $tile)
    {
        $this -> tile = $tile;
    }

    public function handleResponse(Player $player, $data): void
    {
        if($data === null) return;

        if(!(is_numeric($data[1]) && is_numeric($data[2]) && is_numeric($data[3]))) return;

        $worldManager = Server::getInstance() -> getWorldManager();
        $world = $worldManager -> getWorldByName($data[0]);
        if($world == null) {
            $worldManager -> loadWorld($data[0]);
            $world = $worldManager -> getWorldByName($data[0]);
        }

        if(!$world instanceof World) {
            $player -> sendTip($data[0].' §cは存在しません！');
        }
        $this -> tile -> setTeleportLocation(new Location($data[1], $data[2], $data[3],$world, 0, 0));
    }

    public function jsonSerialize(): mixed
    {
        return [
            'type' => 'custom_form',
            'title' => 'CREATE PORTAL FORM',
            'content' => [
                [
                    'type' => 'input',
                    'text' => 'テレポート先のワールド名'
                ],
                [
                    'type' => 'input',
                    'text' => 'x座標(文字不可)'
                ],
                [
                    'type' => 'input',
                    'text' => 'y座標(文字不可)'
                ],
                [
                    'type' => 'input',
                    'text' => 'z座標(文字不可)'
                ]
            ]
        ];
    }
}