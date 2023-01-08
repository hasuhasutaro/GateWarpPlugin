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

        if($data[0] == '') return;

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
        $loc = new Location((float)$data[1], (float)$data[2], (float)$data[3],$world, 0, 0);
        $this -> tile -> setTeleportLocation($loc);
    }

    public function jsonSerialize(): mixed
    {
        $loc = $this -> tile -> getTeleportLocation();
        if($loc == null)
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
        
        return [
            'type' => 'custom_form',
            'title' => 'CREATE PORTAL FORM',
            'content' => [
                [
                    'type' => 'input',
                    'text' => 'テレポート先のワールド名',
                    'default' => (string)$loc -> getWorld() -> getFolderName()
                ],
                [
                    'type' => 'input',
                    'text' => 'x座標(文字不可)',
                    'default' => (string)$loc -> x
                ],
                [
                    'type' => 'input',
                    'text' => 'y座標(文字不可)',
                    'default' => (string)$loc -> y
                ],
                [
                    'type' => 'input',
                    'text' => 'z座標(文字不可)',
                    'default' => (string)$loc -> z
                ]
            ]
        ];
    }
}