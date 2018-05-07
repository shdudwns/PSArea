<?php
    namespace ps88\psarea\Loaders\Land;

    use pocketmine\IPlayer;
    use pocketmine\level\Level;
    use pocketmine\math\Vector2;
    use ps88\psarea\Loaders\base\BaseArea;

    class LandArea extends BaseArea {
        public const LandType = self::Land;

        /** @var Level */
        private $level;

        public function __construct(int $landnum, Level $level, Vector2 $minvec, Vector2 $maxvec, ?IPlayer $owner = \null, array $shares = []) {
            parent::__construct($landnum, $minvec, $maxvec, $owner, $shares);
            $this->level = $level;
        }

        /**
         * @return Level
         */
        public function getLevel(): Level {
            return $this->level;
        }

        /**
         * @param Level $level
         */
        public function setLevel(Level $level): void {
            $this->level = $level;
        }
    }