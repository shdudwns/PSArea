<?php
    namespace ps88\psarea\Loaders\Skyland;

    use pocketmine\level\generator\Generator;
    use pocketmine\math\Vector3;
    use pocketmine\Server;
    use ps88\psarea\Generator\SkylandGenerator;
    use ps88\psarea\Loaders\base\BaseArea;
    use ps88\psarea\Loaders\base\BaseLoader;

    class SkylandLoader extends BaseLoader{
        /** @var SkylandArea[]  */
        public $areas = [];

        /**
         * @param string $name
         * @return SkylandArea[]
         */
        public function getAreasByOwner(string $name): array{
            return parent::getAreasByOwner($name);
        }

        /**
         * @param string $name
         * @return SkylandArea[]
         */
        public function getAreasSharedAndOwned(string $name): array{
            return parent::getAreasSharedAndOwned($name);
        }

        /**
         * @param int $id
         * @return null|SkylandArea|BaseArea
         */
        public function getAreaById(int $id): ?BaseArea{
            return (($a = parent::getAreaById($id)) instanceof SkylandArea)? $a : \null;
        }

        /**
         * @param BaseArea|SkylandArea $area
         * @return bool
         */
        public function addArea(BaseArea $area): bool{
            if(! $area instanceof SkylandArea) return \false;
            return parent::addArea($area);
        }

        /**
         * @param Vector3 $vec
         * @return null|BaseArea|SkylandArea
         */
        public function getAreaByVector3(Vector3 $vec): ?BaseArea {
            return (($a = parent::getAreaByVector3($vec)) instanceof SkylandArea)? $a : \null;
        }

        /**
         * @return SkylandArea[]
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
            Generator::addGenerator(SkylandGenerator::class, 'skyland');
            $g = Generator::getGenerator("skyland");
            if(! Server::getInstance()->loadLevel("skyland")){
                @mkdir(Server::getInstance()->getDataPath() . "/" . "worlds" . "/" . "skyland");
                Server::getInstance()->generateLevel("skyland", \null, $g, []);
            }
        }
    }