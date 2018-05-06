<?php
    namespace ps88\psarea\Loaders\Island;

    use pocketmine\level\generator\Generator;
    use pocketmine\math\Vector3;
    use pocketmine\Server;
    use ps88\psarea\Generator\IslandGenerator;
    use ps88\psarea\Loaders\base\BaseArea;
    use ps88\psarea\Loaders\base\BaseLoader;

    class IslandLoader extends BaseLoader{
        /** @var IslandArea[]  */
        public $areas = [];

        /**
         * @param string $name
         * @return IslandArea[]
         */
        public function getAreasByOwner(string $name): array{
            return parent::getAreasByOwner($name);
        }

        /**
         * @param string $name
         * @return IslandArea[]
         */
        public function getAreasSharedAndOwned(string $name): array{
            return parent::getAreasSharedAndOwned($name);
        }

        /**
         * @param int $id
         * @return null|IslandArea|BaseArea
         */
        public function getAreaById(int $id): ?BaseArea{
            return (($a = parent::getAreaById($id)) instanceof IslandArea)? $a : \null;
        }

        /**
         * @param BaseArea|IslandArea $area
         * @return bool
         */
        public function addArea(BaseArea $area): bool{
            if(! $area instanceof IslandArea) return \false;
            return parent::addArea($area);
        }

        /**
         * @param Vector3 $vec
         * @return null|BaseArea|IslandArea
         */
        public function getAreaByVector3(Vector3 $vec): ?BaseArea {
            return (($a = parent::getAreaByVector3($vec)) instanceof IslandArea)? $a : \null;
        }

        /**
         * @return IslandArea[]
         */
        public function getAreas(): array{
            return $this->areas;
        }

        /**
         * @return bool
         */
        public function saveAll(): bool {
            return \false;
        }

        public function loadLevel(): void {
            Generator::addGenerator(IslandGenerator::class, 'island');
            $g = Generator::getGenerator("island");
            if(! Server::getInstance()->loadLevel("island")){
                @mkdir(Server::getInstance()->getDataPath() . "/" . "worlds" . "/" . "island");
                Server::getInstance()->generateLevel("island", \null, $g, []);
            }
        }
    }