<?php
    namespace ps88\psarea\Events;

    use pocketmine\event\Cancellable;
    use pocketmine\event\Event;
    use pocketmine\Player;
    use ps88\psarea\Loaders\base\BaseArea;

    class LandBuyEvent extends Event implements Cancellable {
        /** @var BaseArea */
        private $area;

        /** @var Player */
        private $buyer;

        public function __construct(BaseArea $area, Player $buyer) {
            $this->area = $area;
            $this->buyer = $buyer;
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
        public function getBuyer(): Player {
            return $this->buyer;
        }
    }