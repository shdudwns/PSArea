<?php
    namespace ps88\psarea\Loaders\Field;

    use pocketmine\level\generator\Generator;
    use pocketmine\math\Vector3;
    use pocketmine\Server;
    use ps88\psarea\Generator\FieldGenerator;
    use ps88\psarea\Loaders\base\BaseArea;
    use ps88\psarea\Loaders\base\BaseLoader;

    class FieldLoader extends BaseLoader {
        /** @var FieldArea[] */
        public $areas = [];

        public static $landcount = 0;

        /**
         * @param string $name
         * @return FieldArea[]
         */
        public function getAreasByOwner(string $name): array {
            return parent::getAreasByOwner($name);
        }

        /**
         * @param string $name
         * @return FieldArea[]
         */
        public function getAreasSharedAndOwned(string $name): array {
            return parent::getAreasSharedAndOwned($name);
        }

        /**
         * @param int $id
         * @return null|FieldArea|BaseArea
         */
        public function getAreaById(int $id): ?BaseArea {
            return (($a = parent::getAreaById($id)) instanceof FieldArea) ? $a : \null;
        }

        /**
         * @param BaseArea|FieldArea $area
         * @return bool
         */
        public function addArea(BaseArea $area): bool {
            if (!$area instanceof FieldArea) return \false;
            return parent::addArea($area);
        }

        /**
         * @param Vector3 $vec
         * @return null|BaseArea|FieldArea
         */
        public function getAreaByVector3(Vector3 $vec): ?BaseArea {
            return (($a = parent::getAreaByVector3($vec)) instanceof FieldArea) ? $a : \null;
        }

        /**
         * @return FieldArea[]
         */
        public function getAreas(): array {
            return $this->areas;
        }

        /**
         * @return bool
         */
        public function saveAll(): bool {
            return \false;
        }

        public function loadLevel(): void {
            Generator::addGenerator(FieldGenerator::class, 'field');
            $g = Generator::getGenerator("field");
            if (!Server::getInstance()->loadLevel("field")) {
                @mkdir(Server::getInstance()->getDataPath() . "/" . "worlds" . "/" . "field");
                Server::getInstance()->generateLevel("field", \null, $g, []);
            }
        }
    }