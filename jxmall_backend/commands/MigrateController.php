<?php
/**
 * @link:http://www.dujxmall.com/
 * @copyright: Copyright (c) 2020 广州动力宇宙信息科技
 * Created by PhpStorm
 * Author: ganxiaohao
 * Date: 2020-09-10
 * Time: 16:10
 */
namespace app\commands;
use yii\base\Exception;
use yii\base\InvalidConfigException;
use yii\console\controllers\BaseMigrateController;
use yii\db\Connection;
use yii\db\Query;
use yii\di\Instance;
use yii\helpers\ArrayHelper;
use yii\helpers\Console;
use yii\helpers\FileHelper;


/**
 *  Manages application migrations.
 * @package app\commands
 */
class MigrateController extends BaseMigrateController
{

    public $migrationPath = '@app/migrations';

    /**
     * @var Connection|array|string the DB connection object or the application component ID of the DB connection to use
     * when applying migrations. Starting from version 2.0.3, this can also be a configuration array
     * for creating the object.
     */
    public $db = 'db';

    /**
     * migrate table name
     * @var string
     */
    public $migrationTable = 'migration';

    /**
     * @var boolean indicates whether the table names generated should consider
     * the `tablePrefix` setting of the DB connection. For example, if the table
     * name is `post` the generator wil return `{{%post}}`.
     * @since 2.0.8
     */
    public $useTablePrefix = true;

    /**
     * @var string
     */
    public $dbName = '';


    public $dbHost = 'localhost';

    public $dbUser = 'root';

    public $dbPassword = '';

    /**
     * @inheritdoc
     */
    public $templateFile = '@common/views/migration.php';

    /**
     * @var array a set of template paths for generating migration code automatically.
     *
     * The key is the template type, the value is a path or the alias. Supported types are:
     * - `create_table`: table creating template
     * - `drop_table`: table dropping template
     * - `add_column`: adding new column template
     * - `drop_column`: dropping column template
     * - `create_junction`: create junction template
     *
     * @since 2.0.7
     */
    public $generatorTemplateFiles = [
        'create_table' => '@yii/views/createTableMigration.php',
        'drop_table' => '@yii/views/dropTableMigration.php',
        'add_column' => '@yii/views/addColumnMigration.php',
        'drop_column' => '@yii/views/dropColumnMigration.php',
        'create_junction' => '@yii/views/createTableMigration.php',
    ];

    /**
     * @var array column definition strings used for creating migration code.
     *
     * The format of each definition is `COLUMN_NAME:COLUMN_TYPE:COLUMN_DECORATOR`. Delimiter is `,`.
     * For example, `--fields="name:string(12):notNull:unique"`
     * produces a string column of size 12 which is not null and unique values.
     *
     * Note: primary key is added automatically and is named id by default.
     * If you want to use another name you may specify it explicitly like
     * `--fields="id_key:primaryKey,name:string(12):notNull:unique"`
     * @since 2.0.7
     */
    public $fields = [];


    /**
     * never confirm
     * @var bool
     */
    public $force = false;


    /**
     * @var string
     * the user account
     * Note: this is define who do this
     * `--user=smile`
     */
    public $user = '';

    /**
     * @var string
     * the user password
     * @example
     * `--password=pwd`
     */
    public $password = '';

    /**
     * dev person account file
     * @var string
     */

    public $migrateLogFile = '';

    public function init()
    {
        parent::init();
        if (\Yii::$app->db->tablePrefix) {
            $this->migrationTable = \Yii::$app->db->tablePrefix . $this->migrationTable;
        }
        if (empty($this->migrateLogFile)) {
            $this->migrateLogFile = \Yii::$app->runtimePath . '/logs/migrate.log';
            $dirname = pathinfo($this->migrateLogFile, PATHINFO_DIRNAME);
            if (!is_dir($dirname)) {
                FileHelper::createDirectory($dirname);
            }
        }
    }

    /**
     * @inheritdoc
     */
    public function options($actionID)
    {
        return array_merge(
            parent::options($actionID),
            ['migrationTable', 'db',],          // global for all actions
            $actionID === 'create'
                ? ['templateFile', 'fields', 'useTablePrefix']
                : [],
            in_array($actionID, ['down', 'up', 'up-tables']) ? ['force', 'user', 'password'] : [],
            in_array($actionID, ['mark', 'fetch']) ? ['user', 'password'] : [],
            in_array($actionID, ['merge']) ? ['dbName', 'dbHost', 'dbUser', 'dbPassword'] : []
        );
    }

    /**
     * @inheritdoc
     */
    public function optionAliases()
    {
        return array_merge(parent::optionAliases(), [
            'f' => 'fields',
            'p' => 'migrationPath',
            't' => 'migrationTable',
            'F' => 'templateFile',
            'P' => 'useTablePrefix',
        ]);
    }


    /**
     * @inheritdoc
     */
    public function beforeAction($action)
    {
        if (empty($this->migrationNamespaces) && empty($this->migrationPath)) {
            throw new InvalidConfigException('At least one of `migrationPath` or `migrationNamespaces` should be specified.');
        }
        foreach ($this->migrationNamespaces as $key => $value) {
            $this->migrationNamespaces[$key] = trim($value, '\\');
        }
        if (is_array($this->migrationPath)) {
            foreach ($this->migrationPath as $i => $path) {
                $this->migrationPath[$i] = \Yii::getAlias($path);
            }
        } elseif ($this->migrationPath !== null) {
            $path = \Yii::getAlias($this->migrationPath);
            if (!is_dir($path)) {
                if ($action->id !== 'create') {
                    throw new InvalidConfigException("Migration failed. Directory specified in migrationPath doesn't exist: {$this->migrationPath}");
                }
                FileHelper::createDirectory($path);
            }
            $this->migrationPath = $path;
        }
        if ($action->id !== 'up-tables') {
            $version = \Yii::getVersion();
            $this->stdout("Yii Migration Tool (based on Yii v{$version})\n\n");
        }
        if ($action->id !== 'create') {
            $this->db = Instance::ensure($this->db, Connection::className());
        }
        return true;
    }

    protected function includeMigrationFile($class)
    {
        $class = trim($class, '\\');
        if (strpos($class, '\\') === false) {
            if (is_array($this->migrationPath)) {
                foreach ($this->migrationPath as $path) {
                    $file = $path . DIRECTORY_SEPARATOR . $class . '.php';
                    if (is_file($file)) {
                        require_once($file);
                        break;
                    }
                }
            } else {
                $file = $this->migrationPath . DIRECTORY_SEPARATOR . $class . '.php';
                require_once($file);
            }
        }
    }

    /**
     * Creates a new migration instance.
     * @param string $class the migration class name
     * @return \yii\db\Migration the migration instance
     */
    protected function createMigration($class)
    {
        $this->includeMigrationFile($class);
        return new $class(['db' => $this->db]);
    }

    /**
     * @inheritdoc
     */
    protected function createMigrationHistoryTable()
    {
        $tableName = $this->db->schema->getRawTableName($this->migrationTable);
        $this->stdout("Creating migration history table \"$tableName\"...", Console::FG_YELLOW);
        $this->db->createCommand()->createTable($this->migrationTable, [
            'version' => 'varchar(180) NOT NULL PRIMARY KEY',
            'apply_time' => 'integer',
            'user' => 'varchar(36) NOT NULL DEFAULT \'\'',
            'path' => 'varchar(255) NOT NULL DEFAULT \'\''
        ])->execute();
        $this->db->createCommand()->insert($this->migrationTable, [
            'version' => self::BASE_MIGRATION,
            'apply_time' => time(),
            'user' => 'init'
        ])->execute();
        $this->stdout("Done.\n", Console::FG_GREEN);
    }

    /**
     * @inheritdoc
     */
    protected function getMigrationHistory($limit)
    {
        if ($this->db->schema->getTableSchema($this->migrationTable, true) === null) {
            $this->createMigrationHistoryTable();
        }
        $query = new Query();
        $rows = $query->select(['version', 'apply_time', 'user'])
            ->from($this->migrationTable)
            ->orderBy('apply_time DESC, version DESC')
            ->limit($limit)
            ->createCommand($this->db)
            ->queryAll();
        $history = ArrayHelper::map($rows, 'version', 'apply_time');
        unset($history[self::BASE_MIGRATION]);
        return $history;
    }

    /**
     * @inheritdoc
     */
    protected function addMigrationHistory($version)
    {
        $command = $this->db->createCommand();
        $command->insert($this->migrationTable, [
            'version' => $version,
            'apply_time' => time(),
            'user' => $this->user,
            'path' => $this->migrationPath
        ])->execute();
        file_put_contents($this->migrateLogFile, sprintf('%s|%s|%s|%s' . PHP_EOL, date('Y-m-d H:i:s'), 'ADD', $version, $this->user), FILE_APPEND | LOCK_EX);
    }

    /**
     * @inheritdoc
     */
    protected function removeMigrationHistory($version)
    {
        $command = $this->db->createCommand();
        $command->delete($this->migrationTable, [
            'version' => $version,
        ])->execute();
        file_put_contents($this->migrateLogFile, sprintf('%s|%s|%s|%s' . PHP_EOL, date('Y-m-d H:i:s'), 'REMOVE', $version, $this->user), FILE_APPEND | LOCK_EX);
    }



    /**
     * Parse the command line migration fields
     * @return array parse result with following fields:
     *
     * - fields: array, parsed fields
     * - foreignKeys: array, detected foreign keys
     *
     * @since 2.0.7
     */
    protected function parseFields()
    {
        $fields = [];
        $foreignKeys = [];
        foreach ($this->fields as $index => $field) {
            $chunks = preg_split('/\s?:\s?/', $field, null);
            $property = array_shift($chunks);

            foreach ($chunks as $i => &$chunk) {
                if (strpos($chunk, 'foreignKey') === 0) {
                    preg_match('/foreignKey\((\w*)\)/', $chunk, $matches);
                    $foreignKeys[$property] = isset($matches[1])
                        ? $matches[1]
                        : preg_replace('/_id$/', '', $property);

                    unset($chunks[$i]);
                    continue;
                }

                if (!preg_match('/^(.+?)\(([^(]+)\)$/', $chunk)) {
                    $chunk .= '()';
                }
            }
            $fields[] = [
                'property' => $property,
                'decorators' => implode('->', $chunks),
            ];
        }

        return [
            'fields' => $fields,
            'foreignKeys' => $foreignKeys,
        ];
    }

    /**
     * Adds default primary key to fields list if there's no primary key specified
     * @param array $fields parsed fields
     * @since 2.0.7
     */
    protected function addDefaultPrimaryKey(&$fields)
    {
        foreach ($fields as $field) {
            if ($field['decorators'] === 'primaryKey()' || $field['decorators'] === 'bigPrimaryKey()') {
                return;
            }
        }
        array_unshift($fields, ['property' => 'id', 'decorators' => 'primaryKey()']);
    }


    protected function getCustomMigrationHistory($limit)
    {
        if ($this->db->schema->getTableSchema($this->migrationTable, true) === null) {
            $this->createMigrationHistoryTable();
        }
        $query = new Query();
        $rows = $query->select(['version', 'apply_time', 'user'])
            ->from($this->migrationTable)
            ->orderBy('apply_time DESC, version DESC')
            ->limit($limit)
            ->createCommand($this->db)
            ->queryAll();
        return $rows;
    }

    /**
     * Displays the migration history.
     *
     * This command will show the list of migrations that have been applied
     * so far. For example,
     *
     * ```
     * yii migrate/history     # showing the last 10 migrations
     * yii migrate/history 5   # showing the last 5 migrations
     * yii migrate/history all # showing the whole history
     * ```
     * @param int $limit limit
     * @throws Exception
     */
    public function actionHistory($limit = 10)
    {
        if ($limit === 'all') {
            $limit = null;
        } else {
            $limit = (int)$limit;
            if ($limit < 1) {
                throw new Exception('The limit must be greater than 0.');
            }
        }
        $migrations = $this->getCustomMigrationHistory($limit);
        if (empty($migrations)) {
            $this->stdout("No migration has been done before.\n", Console::FG_YELLOW);
        } else {
            $n = count($migrations);
            if ($limit > 0) {
                $this->stdout("Showing the last $n applied " . ($n === 1 ? 'migration' : 'migrations') . ":\n", Console::FG_YELLOW);
            } else {
                $this->stdout("Total $n " . ($n === 1 ? 'migration has' : 'migrations have') . " been applied before:\n", Console::FG_YELLOW);
            }
            foreach ($migrations as $row) {
                $this->stdout($row['version'] . PHP_EOL, Console::FG_GREEN);
                $this->stdout(sprintf("%10s : %s \n", 'Date', date('Y-m-d H:i:s', $row['apply_time'])));
                $this->stdout(sprintf("%10s : %s\n\n", 'Author', $row['user']));
            }
        }
    }

    /**
     * fetch migrate script save to @app/migrations
     * @param string $path fetch path
     */
    public function actionFetch($path)
    {
        $path = \Yii::getAlias($path);
        if (!is_dir($path)) {
            $this->stdout('Invalid path :' . $path);
            return;
        }
        $count = 0;
        $handle = opendir($path);
        while (($file = readdir($handle)) !== false) {
            if ($file === '.' || $file === '..') {
                continue;
            }
            $filename = $path . DIRECTORY_SEPARATOR . $file;
            if (preg_match('/^(m(\d{6}_?\d{6})\D.*?)\.php$/is', $file, $matches) && is_file($filename)) {
                $count++;
                @copy($filename, $this->migrationPath . DIRECTORY_SEPARATOR . $file);
                $this->stdout(sprintf('fetch : %s successful ' . PHP_EOL, $matches[0]), Console::FG_YELLOW);
            }
        }
        closedir($handle);
        if ($count > 0 && $this->confirm('fetch successful, do you want\'t migrate now?')) {
            $this->actionUp($count);
        }
    }


    /**
     * Downgrades the application by reverting old migrations.
     * For example,
     *
     * ```
     * yii migrate/down     # revert the last migration
     * yii migrate/down 3   # revert the last 3 migrations
     * yii migrate/down all # revert all migrations
     * ```
     *
     * @param integer $limit the number of migrations to be reverted. Defaults to 1,
     * meaning the last applied migration will be reverted.
     * @throws Exception if the number of the steps specified is less than 1.
     *
     * @return integer the status of the action execution. 0 means normal, other values mean abnormal.
     */
    public function actionDown($limit = 1)
    {
        if ($limit === 'all') {
            $limit = null;
        } else {
            $limit = (int)$limit;
            if ($limit < 1) {
                throw new Exception('The step argument must be greater than 0.');
            }
        }

        $migrations = $this->getMigrationHistory($limit);

        if (empty($migrations)) {
            $this->stdout("No migration has been done before.\n", Console::FG_YELLOW);

            return self::EXIT_CODE_NORMAL;
        }

        $migrations = array_keys($migrations);

        //remove not exist migration file
        foreach ($migrations as $k => $class) {
            $file = $this->migrationPath . DIRECTORY_SEPARATOR . $class . '.php';
            if (!is_file($file)) {
                unset($migrations[$k]);
            }
        }

        $n = count($migrations);
        $this->stdout("Total $n " . ($n === 1 ? 'migration' : 'migrations') . " to be reverted:\n", Console::FG_YELLOW);
        foreach ($migrations as $migration) {
            $this->stdout("\t$migration\n");
        }
        $this->stdout("\n");

        $reverted = 0;
        if ($this->force || $this->confirm('Revert the above ' . ($n === 1 ? 'migration' : 'migrations') . '?')) {
            foreach ($migrations as $migration) {
                if (!$this->migrateDown($migration)) {
                    $this->stdout("\n$reverted from $n " . ($reverted === 1 ? 'migration was' : 'migrations were') . " reverted.\n", Console::FG_RED);
                    $this->stdout("\nMigration failed. The rest of the migrations are canceled.\n", Console::FG_RED);

                    return self::EXIT_CODE_ERROR;
                }
                $reverted++;
            }
            $this->stdout("\n$n " . ($n === 1 ? 'migration was' : 'migrations were') . " reverted.\n", Console::FG_GREEN);
            $this->stdout("\nMigrated down successfully.\n", Console::FG_GREEN);
        }
    }


    /**
     * Upgrades the application by applying new migrations.
     * For example,
     *
     * ```
     * yii migrate     # apply all new migrations
     * yii migrate 3   # apply the first 3 new migrations
     * ```
     *
     * @param integer $limit the number of new migrations to be applied. If 0, it means
     * applying all available new migrations.
     *
     * @return integer the status of the action execution. 0 means normal, other values mean abnormal.
     */
    public function actionUp($limit = 0)
    {
        $migrations = $this->getNewMigrations();
        if (empty($migrations)) {
            $this->stdout("No new migrations found. Your system is up-to-date.\n", Console::FG_GREEN);

            return self::EXIT_CODE_NORMAL;
        }

        $total = count($migrations);
        $limit = (int)$limit;
        if ($limit > 0) {
            $migrations = array_slice($migrations, 0, $limit);
        }

        $n = count($migrations);
        if ($n === $total) {
            $this->stdout("Total $n new " . ($n === 1 ? 'migration' : 'migrations') . " to be applied:\n", Console::FG_YELLOW);
        } else {
            $this->stdout("Total $n out of $total new " . ($total === 1 ? 'migration' : 'migrations') . " to be applied:\n", Console::FG_YELLOW);
        }

        foreach ($migrations as $migration) {
            $this->stdout("\t$migration\n");
        }
        $this->stdout("\n");

        $applied = 0;
        if ($this->force || $this->confirm('Apply the above ' . ($n === 1 ? 'migration' : 'migrations') . '?')) {
            foreach ($migrations as $migration) {
                if (!$this->migrateUp($migration)) {
                    $this->stdout("\n$applied from $n " . ($applied === 1 ? 'migration was' : 'migrations were') . " applied.\n", Console::FG_RED);
                    $this->stdout("\nMigration failed. The rest of the migrations are canceled.\n", Console::FG_RED);
                    return self::EXIT_CODE_ERROR;
                }
                $applied++;
            }
            $this->stdout("\n$n " . ($n === 1 ? 'migration was' : 'migrations were') . " applied.\n", Console::FG_GREEN);
            $this->stdout("\nMigrated up successfully.\n", Console::FG_GREEN);
        }
    }

    /**
     * @param int $limit
     */
    public function actionAuto($limit = 0)
    {

        //要考虑未安装的插件是否需要执行迁移文件
        $this->force = true;
        $this->db = \Yii::$app->db;
        $this->stdout("migrate up " . $this->getDatabaseName($this->db) . " database ..." . PHP_EOL, Console::FG_GREEN);
        $paths = [\Yii::getAlias('@app/migrations')];
        $plugin_list = \Yii::$app->plugin->scanPluginList();
        foreach ($plugin_list as $item) {
            $name = $item->getName();
            $migrationsPath = \Yii::getAlias("@app/plugins/{$name}/migrations");
            if (is_dir($migrationsPath)) {
                $paths[] = $migrationsPath;
            }
        }
        foreach ($paths as $path) {
            $this->stdout("migrate up " . $path . " table ..." . PHP_EOL, Console::FG_GREEN);
            $this->migrationPath = $path;
            $this->actionUp($limit);
        }
    }


    public function migrateUp($class)
    {
        if ($class === self::BASE_MIGRATION) {
            return true;
        }

        $this->stdout("*** applying $class\n", Console::FG_YELLOW);
        $start = microtime(true);
        $migration = $this->createMigration($class);

//        if (!method_exists($migration, 'upgrade')) {
//            throw new \Exception(\Yii::t('app', '{class} 缺少方法 upgrade', [
//                'class' => $class
//            ]));
//        }

        if ($migration->up() !== false) {
            $this->addMigrationHistory($class);
            $time = microtime(true) - $start;
            $this->stdout("*** applied $class (time: " . sprintf('%.3f', $time) . "s)\n\n", Console::FG_GREEN);
            return true;
        } else {
            $time = microtime(true) - $start;
            $this->stdout("*** failed to apply $class (time: " . sprintf('%.3f', $time) . "s)\n\n", Console::FG_RED);

            return false;
        }
    }

    protected function getAllMigrations()
    {
        $applied = [];
        $migrationPaths = [];
        if (is_array($this->migrationPath)) {
            foreach ($this->migrationPath as $path) {
                $migrationPaths[] = [$path, ''];
            }
        } elseif (!empty($this->migrationPath)) {
            $migrationPaths[] = [$this->migrationPath, ''];
        }
        foreach ($this->migrationNamespaces as $namespace) {
            $migrationPaths[] = [str_replace('/', DIRECTORY_SEPARATOR, \Yii::getAlias('@' . str_replace('\\', '/', $namespace))), $namespace];
        }

        $migrations = [];
        foreach ($migrationPaths as $item) {
            list($migrationPath, $namespace) = $item;
            if (!file_exists($migrationPath)) {
                continue;
            }
            $handle = opendir($migrationPath);
            while (($file = readdir($handle)) !== false) {
                if ($file === '.' || $file === '..') {
                    continue;
                }
                $path = $migrationPath . DIRECTORY_SEPARATOR . $file;
                if (preg_match('/^(m(\d{6}_?\d{6})\D.*?)\.php$/is', $file, $matches) && is_file($path)) {
                    $class = $matches[1];
                    if (!empty($namespace)) {
                        $class = $namespace . '\\' . $class;
                    }
                    $time = str_replace('_', '', $matches[2]);
                    if (!isset($applied[$class])) {
                        $migrations[$time . '\\' . $class] = $class;
                    }
                }
            }
            closedir($handle);
        }
        ksort($migrations);
        return array_values($migrations);
    }


    protected function getDatabaseName($db)
    {
        /** @var $db Connection */
        $lines = explode(';', $db->dsn);
        foreach ($lines as $line) {
            if (($pos = strpos($line, 'dbname=')) !== false) {
                return substr($line, $pos + strlen('dbname='));
            }
        }
        return '';
    }

    protected function hasTable($db, $tabName)
    {
        /** @var $db Connection */
        return !empty($db->getTableSchema($tabName));
    }
}
