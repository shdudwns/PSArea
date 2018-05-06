<?php
    namespace ps88\psarea\Loaders\Island;

    use pocketmine\math\Vector2;
    use pocketmine\Player;
    use ps88\psarea\Loaders\base\BaseArea;

    class IslandArea extends BaseArea{
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
            return $this->center;
        }
    }