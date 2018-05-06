<?php
    namespace ps88\psarea;


    use pocketmine\{
            event\Listener, level\generator\Generator, level\generator\normal\Normal, plugin\PluginBase
    };
    use ps88\psarea\Commands\Island\IslandAddShareCommand;
    use ps88\psarea\Commands\Island\IslandBuyCommand;
    use ps88\psarea\Commands\Island\IslandGiveCommand;
    use ps88\psarea\Commands\Island\IslandInfoCommand;
    use ps88\psarea\Generator\{
            IslandGenerator, FieldGenerator, SkylandGenerator
    };
    use ps88\psarea\Loaders\base\BaseLoader;
    use ps88\psarea\Loaders\Field\FieldLoader;
    use ps88\psarea\Loaders\Island\IslandLoader;
    use ps88\psarea\Loaders\Skyland\SkylandLoader;
    use ps88\psarea\Tasks\AreaAddTask;

    class PSAreaMain extends PluginBase implements Listener {

        /** @var FieldLoader */
        public $fieldloader;

        /** @var IslandLoader */
        public $islandloader;

        /** @var SkylandLoader */
        public $skylandloader;

        public function onEnable(){
            $this->fieldloader = new FieldLoader();
            $this->islandloader = new IslandLoader();
            $this->skylandloader = new SkylandLoader();
            $this->getServer()->getPluginManager()->registerEvents($this, $this);
            $this->getServer()->getScheduler()->scheduleRepeatingTask(new AreaAddTask($this), 3);
            $this->loadLevels();
            $this->registerCommands();
        }

        public function loadLevels(): void{
            /** @var BaseLoader[] $loaders */
            $loaders = [
                    $this->fieldloader,
                    $this->skylandloader,
                    $this->islandloader
            ];
            foreach ($loaders as $item) {
                $item->loadLevel();
            }
        }

        public function registerCommands(): void{
                $this->getServer()->getCommandMap()->registerAll('PSArea', [
                        new IslandAddShareCommand($this),
                        new IslandBuyCommand($this),
                        new IslandGiveCommand($this),
                        new IslandInfoCommand($this)
                ]);
        }

        /**
         * @return FieldLoader
         */
        public function getFieldloader(): FieldLoader {
            return $this->fieldloader;
        }

        /**
         * @return IslandLoader
         */
        public function getIslandloader(): IslandLoader {
            return $this->islandloader;
        }

        /**
         * @return SkylandLoader
         */
        public function getSkylandloader(): SkylandLoader {
            return $this->skylandloader;
        }
    }