<?php
    namespace ps88\psarea;

    use mpm\Generator\FieldGenerator;
    use mpm\Generator\IslandGenerator;
    use mpm\Generator\SkylandGenerator;
    use pocketmine\event\Listener;
    use pocketmine\level\generator\Generator;
    use pocketmine\plugin\PluginBase;

    class PSAreaMain extends PluginBase implements Listener {
        public function onEnable(){
            $this->getServer()->getPluginManager()->registerEvents($this, $this);
            foreach([
                    'field' => FieldGenerator::class,
                'island' => IslandGenerator::class,
                'skyland' => SkylandGenerator::class
                    ] as $name => $class){
                Generator::addGenerator($class, $name);
                $g = Generator::getGenerator($name);
                if(! $this->getServer()->loadLevel($name)){
                    @mkdir($this->getServer()->getDataPath() . "/" . "worlds" . "/" . $name);
                    $this->getServer()->generateLevel($name, 0, $g, []);
                }
            }
        }
    }