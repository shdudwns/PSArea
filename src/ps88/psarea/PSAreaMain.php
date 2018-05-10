<?php
    namespace ps88\psarea;


    use pocketmine\{
            event\Listener,
            lang\BaseLang,
            lang\TextContainer,
            plugin\PluginBase,
            utils\Color,
            utils\Config,
            utils\TextFormat
    };
    use ps88\psarea\Commands\Field\{
            FieldAddShareCommand,
            FieldBuyCommand,
            FieldDelShareCommand,
            FieldGiveCommand,
            FieldInfoCommand,
            FieldRegisteredList,
            FieldWarpCommand
    };
    use ps88\psarea\Commands\Island\{
            IslandAddShareCommand,
            IslandBuyCommand,
            IslandDelShareCommand,
            IslandGiveCommand,
            IslandInfoCommand,
            IslandWarpCommand
    };
    use ps88\psarea\Commands\Land\{
            LandAddShareCommand,
            LandBuyCommand,
            LandDelShareCommand,
            LandGiveCommand,
            LandInfoCommand,
            LandMakeCommand,
            LandWarpCommand
    };
    use ps88\psarea\Commands\Skyland\{
            SkylandAddShareCommand,
            SkylandBuyCommand,
            SkylandDelShareCommand,
            SkylandGiveCommand,
            SkylandInfoCommand,
            SkylandWarpCommand
    };
    use ps88\psarea\Loaders\{
            base\BaseLoader,
            Field\FieldLoader,
            Land\LandListener,
            Land\LandLoader,
            Island\IslandLoader,
            Skyland\SkylandLoader
    };
    use ps88\psarea\MoneyTranslate\MoneyTranslator;
    use ps88\psarea\ProtectWorld\ProtectWorld;
    use ps88\psarea\Commands\ProtectWorld\setProtectWorldCommand;
    use ps88\psarea\Tasks\AreaAddTask;
    use ps88\psarea\Tasks\FieldAutoAddTask;

    class PSAreaMain extends PluginBase implements Listener {

        /** @var FieldLoader */
        public $fieldloader;

        /** @var IslandLoader */
        public $islandloader;

        /** @var SkylandLoader */
        public $skylandloader;

        /** @var LandLoader */
        public $landloader;

        /** @var MoneyTranslator */
        public $moneytranslator;

        /** @var ProtectWorld */
        public $protectworld;

        /** @var Config */
        public static $langcf;

        /** @var Config */
        public $setting;

        public function onLoad() {
            $this->fieldloader = new FieldLoader();
            $this->islandloader = new IslandLoader();
            $this->skylandloader = new SkylandLoader();
            $this->landloader = new LandLoader();
        }

        public function onEnable() {
            $this->protectworld = new ProtectWorld($this);
            $this->getServer()->getPluginManager()->registerEvents($this, $this);
            $this->getServer()->getPluginManager()->registerEvents(new LandListener($this), $this);
            $this->getServer()->getScheduler()->scheduleRepeatingTask(new AreaAddTask($this), 3);
            $this->getServer()->getScheduler()->scheduleRepeatingTask(new FieldAutoAddTask($this), 20);
            @mkdir($this->getDataFolder());
            $this->setting = new Config($this->getDataFolder()."setting.yml", Config::YAML, [
                    "lang" => "eng"
            ]);
            if(!file_exists($this->getDataFolder()."lang.yml")) {
                file_put_contents($this->getDataFolder() . "lang.yml", stream_get_contents($this->getResource("lang.yml")));
            }
            self::$langcf = new Config($this->getDataFolder(). "lang.yml", Config::YAML);
            $this->loadLevels();
            $this->registerCommands();
            if ($this->getServer()->getPluginManager()->getPlugin('StormCore') !== \null) {
                $this->moneytranslator = new MoneyTranslator($this->getServer()->getPluginManager()->getPlugin('StormCore'));
            } elseif ($this->getServer()->getPluginManager()->getPlugin('EconomyAPI') !== \null) {
                $this->moneytranslator = new MoneyTranslator($this->getServer()->getPluginManager()->getPlugin('EconomyAPI'));
            } else {
                $this->getLogger()->emergency("No Money(EconomyAPI etc..) Plugin");
                $this->getServer()->getPluginManager()->disablePlugin($this);
            }
        }

        public function onDisable() {
            $this->islandloader->saveAll();
            $this->skylandloader->saveAll();
            $this->fieldloader->saveAll();
            $this->landloader->saveAll();
        }

        public function loadLevels(): void {
            /** @var BaseLoader[] $loaders */
            $loaders = [
                    $this->fieldloader,
                    $this->skylandloader,
                    $this->islandloader,
                    $this->landloader
            ];
            foreach ($loaders as $item) {
                $item->loadLevel();
            }
        }

        public function registerCommands(): void {
            $this->getServer()->getCommandMap()->registerAll('PSArea', [
                    new IslandAddShareCommand($this),
                    new IslandBuyCommand($this),
                    new IslandGiveCommand($this),
                    new IslandInfoCommand($this),
                    new IslandWarpCommand($this),
                    new SkylandAddShareCommand($this),
                    new SkylandBuyCommand($this),
                    new SkylandGiveCommand($this),
                    new SkylandInfoCommand($this),
                    new SkylandWarpCommand($this),
                    new setProtectWorldCommand($this),
                    new LandAddShareCommand($this),
                    new LandBuyCommand($this),
                    new LandGiveCommand($this),
                    new LandWarpCommand($this),
                    new LandInfoCommand($this),
                    new LandMakeCommand($this),
                    new FieldAddShareCommand($this),
                    new FieldBuyCommand($this),
                    new FieldGiveCommand($this),
                    new FieldInfoCommand($this),
                    new FieldWarpCommand($this),
                    new FieldRegisteredList($this),
                    new IslandDelShareCommand($this),
                    new LandDelShareCommand($this),
                    new SkylandDelShareCommand($this),
                    new FieldDelShareCommand($this),
            ]);
        }

        /**
         * @param string $key
         * @param array ...$args
         * @return null|string
         */
        public static function get(string $key, bool $prefix = \true, array... $args): ?string{
            if(! self::$langcf->exists("message-".$key)) return \null;
            $st = self::$langcf->get("message-".$key);
            /** @var array $arg */
            foreach ($args as $arg) {
                $st = str_replace($arg[0], $arg[1], $st);
            }
            $pr = ($prefix)? TextFormat::BOLD . self::$langcf->get("Prefix") : "";
            return $pr.TextFormat::RESET.$st;
        }

        /**
         * @param string $key
         * @return mixed|null
         */
        public static function getCommands(string $key){
            if(! self::$langcf->exists("message-".$key)) return \null;
            return self::$langcf->get("commands-".$key);
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