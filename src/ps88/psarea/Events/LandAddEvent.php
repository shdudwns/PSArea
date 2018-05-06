<?php
    namespace ps88\psarea\Events;

    use pocketmine\event\Cancellable;
    use pocketmine\event\Event;
    use ps88\psarea\Loaders\base\BaseArea;

    class LandAddEvent extends Event implements Cancellable {
        /** @var BaseArea  */
        private $area;

        public function __construct(BaseArea $area) {
            $this->area = $area;
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
    }