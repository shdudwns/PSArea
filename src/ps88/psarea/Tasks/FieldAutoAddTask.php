<?php
    namespace ps88\psarea\Tasks;

    use pocketmine\math\Vector2;
    use pocketmine\math\Vector3;
    use pocketmine\scheduler\PluginTask;
    use ps88\psarea\Loaders\Field\FieldArea;
    use ps88\psarea\Loaders\Field\FieldLoader;
    use ps88\psarea\PSAreaMain;

    class FieldAutoAddTask extends PluginTask {

        /** @var PSAreaMain */
        protected $owner;

        public function __construct(PSAreaMain $owner) {
            parent::__construct($owner);
        }

        /**
         * Actions to execute when run
         *
         * @param int $currentTick
         *
         * @return void
         */
        public function onRun(int $currentTick) {
            $xz = 8 + FieldLoader::$diagonalcount * 37;
            $x = $xz;
            $z = $xz;
            while(\true) {
                $this->addArea($x, $xz);
                $x = $x - 37;
                if($x <= 8) break;
            }
            while(\true) {
                $this->addArea($xz, $z);
                $z = $z - 37;
                if($z <= 8) break;
            }
            FieldLoader::$diagonalcount++;
        }

        public function addArea($x, $z) {
            if (($x - 8) % 37 == 0 and ($z - 8) % 37 == 0) {
                if ($this->owner->fieldloader->getAreaByVector3(new Vector3($x, 0, $z))) return;
                $this->owner->fieldloader->addArea(new FieldArea(FieldLoader::$landcount++, new Vector2($x, $z), new Vector2($x + 29, $z + 29)));
            }
        }
    }