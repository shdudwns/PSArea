<?php
    namespace ps88\psarea\Events;

    use pocketmine\event\Cancellable;
    use pocketmine\event\Event;
    use pocketmine\Player;
    use ps88\psarea\Loaders\base\BaseArea;

    class LandAddShareEvent extends Event implements Cancellable {
        /** @var BaseArea  */
        private $area;

        /** @var Player  */
        private $share;

        public function __construct(BaseArea $area, Player $share) {
            $this->area = $area;
            $this->share = $share;
        }

        /**
         * @return BaseArea
         */
        public function getArea(): BaseArea {
            return $this->area;
        }

        /**
         * @param BaseArea $area
         */
        public function setArea(BaseArea $area): void {
            $this->area = $area;
        }

        /**
         * @return Player
         */
        public function getShare(): Player {
            return $this->share;
        }
    }