<?php
    namespace ps88\psarea\Loaders\Island;

    use pocketmine\level\Position;
    use pocketmine\math\Vector2;
    use pocketmine\Player;
    use pocketmine\Server;
    use ps88\psarea\Events\LandWarpEvent;
    use ps88\psarea\Loaders\base\BaseArea;

    class IslandArea extends BaseArea {
        public const LandType = self::Island;

        /** @var Vector2 */
        public $center;

        public function __construct(int $landnum, Vector2 $center, ?Player $owner = \null, $shares = []) {
            $this->center = $center;
            $minv = new Vector2($center->x - 100, $center->y - 100);
            $maxv = new Vector2($center->x + 100, $center->y + 100);
            parent::__construct($landnum, $minv, $maxv, $owner, $shares);
        }

        /**
         * @return Vector2
         */
        public function getCenter(): Vector2 {
            return $this->center;//103, 295, 503
        }

        public function Warp(Player $pl): bool {
            $v = $this->getCenter();
            Server::getInstance()->getPluginManager()->callEvent($ev = new LandWarpEvent($this, $pl));
            if ($ev->isCancelled()) return \false;
            $x = ($this->getLandnum() % 2 == 0)? $v->x : $v->x - 5;
            $pl->teleport(new Position($x, 14, $v->y, Server::getInstance()->getLevelByName('island')));
            return \true;
        }
    }