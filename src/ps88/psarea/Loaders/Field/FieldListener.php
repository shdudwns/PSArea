<?php
    namespace ps88\psarea\Loaders\Field;

    use pocketmine\event\level\ChunkLoadEvent;
    use pocketmine\event\level\ChunkPopulateEvent;
    use pocketmine\event\Listener;
    use pocketmine\event\player\PlayerInteractEvent;
    use pocketmine\event\player\PlayerMoveEvent;
    use pocketmine\event\server\DataPacketSendEvent;
    use pocketmine\math\Vector2;
    use pocketmine\math\Vector3;
    use ps88\psarea\PSAreaMain;

    class FieldListener implements Listener {

        /** @var PSAreaMain */
        public $main;

        public function __construct(PSAreaMain $main) {
            $this->main = $main;
        }


        public function get(PlayerMoveEvent $ev) {
            $pl = $ev->getTo();
            $x = (int) $pl->getX();
            $z = (int) $pl->getZ();
            if (($x - 7) % 37 == 0 and ($z - 7) % 37 == 0) {
                if ($this->main->fieldloader->getAreaByVector3(new Vector3($x, 0, $z))) return;
                $this->main->fieldloader->addArea(new FieldArea(FieldLoader::$landcount++, new Vector2($x, $z), new Vector2($x + 29, $z + 29)));
            }
        }
    }