<?php
    namespace ps88\psarea\Loaders\Land;

    use pocketmine\level\generator\Generator;
    use pocketmine\math\Vector2;
    use pocketmine\math\Vector3;
    use pocketmine\Server;
    use pocketmine\utils\Config;
    use ps88\psarea\Generator\FieldGenerator;
    use ps88\psarea\Loaders\base\BaseArea;
    use ps88\psarea\Loaders\base\BaseLoader;
    use ps88\psarea\Loaders\Land\LandArea;

    class LandLoader extends BaseLoader {
        /** @var LandArea[] */
        public $areas = [];

        public static $landcount = 0;

        /**
         * @param string $name
         * @return LandArea[]
         */
        public function getAreasByOwner(string $name): array {
            return parent::getAreasByOwner($name);
        }

        /**
         * @param string $name
         * @return LandArea[]
         */
        public function getAreasSharedAndOwned(string $name): array {
            return parent::getAreasSharedAndOwned($name);
        }

        /**
         * @param int $id
         * @return null|LandArea|BaseArea
         */
        public function getAreaById(int $id): ?BaseArea {
            return (($a = parent::getAreaById($id)) instanceof LandArea) ? $a : \null;
        }

        /**
         * @param BaseArea|LandArea $area
         * @return bool
         */
        public function addArea(BaseArea $area): bool {
            if (!$area instanceof LandArea) return \false;
            return parent::addArea($area);
        }

        /**
         * @param Vector3 $vec
         * @return null|BaseArea|LandArea
         */
        public function getAreaByVector3(Vector3 $vec): ?BaseArea {
            return (($a = parent::getAreaByVector3($vec)) instanceof LandArea) ? $a : \null;
        }

        /**
         * @return LandArea[]
         */
        public function getAreas(): array {
            return $this->areas;
        }

        /**
         * @return bool
         */
        public function saveAll(): bool {
            $c = new Config(Server::getInstance()->getDataPath() . "/" . "NormalLand" . "/" . "data.json", Config::JSON);
            $c->setAll([]);
            foreach ($this->getAreas() as $area) {
                $o = ($area->owner == \null) ? \null : $area->owner->getName();
                $s = [];
                foreach ($area->getShares() as $share) {
                    array_push($s, $share->getName());
                }
                $c->set($area->getLandnum(), [
                        'levelid' => $area->getLevel()->getId(),
                        'minv' => [$area->getMinVector()->x, $area->getMinVector()->y],
                        'maxv' => [$area->getMaxVector()->x, $area->getMaxVector()->y],
                        'owner' => $o,
                        'shares' => $s
                ]);
            }
            $c->save();
            return \true;
        }

        public function loadLevel(): void {
            @mkdir(Server::getInstance()->getDataPath() . "/" . "NormalLand");
            $c = new Config(Server::getInstance()->getDataPath() . "/" . "NormalLand" . "/" . "data.json", Config::JSON);
            foreach ($c->getAll() as $key => $value) {
                $s = [];
                foreach ($value['shares'] as $share) {
                    array_push($s, Server::getInstance()->getOfflinePlayer($share));
                }
                $o = ($value['owner'] == \null) ? \null : Server::getInstance()->getOfflinePlayer($value['owner']);
                $this->addArea(new LandArea($key, Server::getInstance()->getLevel($value['levelid']), new Vector2($value['minv'][0], $value['minv'][1]), new Vector2($value['maxv'][0], $value['maxv'][1]), $o, $s));
                if (self::$landcount < $key) self::$landcount = $key;
            }
        }
    }