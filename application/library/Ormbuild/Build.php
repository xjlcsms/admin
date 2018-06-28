<?php
namespace Ormbuild;

final class Build {

    protected $_db     = null;
    protected $_params = array();
    protected $_dbname = null;
    protected $_state  = false;

    public function __construct($argv) {
        $params       = \Ormbuild\Lib\Params::getInstance();
        $params->setParams($argv)->parse();
        $this->_state = $params->getState();
    }

    /**
     * 流程处理前执行
     */
    public function before() {
        if ($this->_state === false && \Ormbuild\Lib\Params::getInstance()->showHelp() === false) {
            \Ormbuild\Lib\State::error('Invalid process');
        }
    }

    /**
     * 流程处理后执行
     */
    public function after() {
        $this->_state = ($this->_state === true ? false : true);
    }

    /**
     * 流程处理
     *
     * @return boolean
     * @throws \Ormbuild\Lib\Exception
     */
    public function process() {
        if ($this->_state === false) {
            if (\Ormbuild\Lib\Params::getInstance()->showHelp() === true) {
                $this->getHelp();
            }

            return false;
        }

        $db = $this->getDbResponse();
        $op = \Ormbuild\Lib\Options::getInstance();

        if (empty($this->_dbname)) {
            \Ormbuild\Lib\State::error('The database is not specified');
        }

        \Ormbuild\Lib\State::notice('Scanning the database table...');
        $tables = $op->getTable();

        if (empty($tables)) {
            $tables = $db->findTables();
        } else {
            foreach ($tables as $table) {
                if ($db->isExistTable($table) === false) {
                    \Ormbuild\Lib\State::warning('Unkown table \'' . $table . '\'');
                }
            }
        }

        if (empty($tables)) {
            \Ormbuild\Lib\State::warning('Not found any tables');
        }

        \Ormbuild\Lib\State::notice('Found ' . sizeof($tables) . ' table(s)');
        $modelFile     = \Ormbuild\Model\File::getInstance();
        $modelContents = \Ormbuild\Model\Content::getInstance();

        foreach ($tables as $table) {
            $tableName = ($op->getUnderline() === false) ? str_replace('_', '', $table) : $table;

            if (preg_match('/^[0-9]+/', $tableName)) {
                $tableName = ltrim(preg_replace('/^[0-9]+/', '', $tableName), '_');
            }

            \Ormbuild\Lib\State::notice('-----------------');
            \Ormbuild\Lib\State::notice('Processing [' . $table . ']');
            $modelContents->setTableInfo($db->findTableInfo($table));
            $modelContents->setTableName($tableName)->setColumns($db->findCols($table))->build();
            \Ormbuild\Lib\State::notice('Done');

            $modelFile->setTableName($tableName)->build();
            $modelContents->reset();
            $modelFile->reset();
        }

        return true;
    }

    /**
     * 获取 DB 资源
     *
     * @return Db
     */
    protected function getDbResponse() {
        if (!$this->_db instanceof \Ormbuild\Lib\Db) {
            $options  = \Ormbuild\Lib\Options::getInstance();
            $dbConfig = \Ormbuild\Lib\DbConfig::getInstance();
            $userName = $options->getUsername();
            $passwd   = $options->getPasswd();
            $confName = (empty($userName) || empty($passwd)) ? $options->getDbConfig() : false;
            //根据模型路径拼接项目数据库文件的路径
            $fileRoot = APPLICATION_PATH.'/conf/database.ini';
            //如果项目数据库配置文件存在，将自动读取数据库default文件
            if(empty($userName) && empty($passwd) && file_exists($fileRoot)){
                $handle = @fopen($fileRoot, "r");
                if ($handle) {
                    $dbConfigini = array();
                    while (!feof($handle)) {
                        $buffer = fgets($handle, 4096);
                        if(!empty($buffer) && stripos($buffer,'default')){
                            $arr = strpos($buffer, '=')?explode('=', $buffer):array(0=>'',1=>'');
                            $dbConfigini[trim($arr[0])] = trim(trim($arr[1]),'"');
                        } 
                    }
                    fclose($handle);
                    $dbConfig->setHost(isset($dbConfigini['resources.database.multi.default.hostname'])?$dbConfigini['resources.database.multi.default.hostname']:'');
                    $dbConfig->setPort(isset($dbConfigini['resources.database.multi.default.port'])?$dbConfigini['resources.database.multi.default.port']:'');
                    $dbConfig->setDbname(isset($dbConfigini['resources.database.multi.default.database'])?$dbConfigini['resources.database.multi.default.database']:'');
                    $dbConfig->setOptions(isset($dbConfigini['resources.database.multi.default.driver_options.1002'])?$dbConfigini['resources.database.multi.default.driver_options.1002']:'');
                    $dbConfig->setUsername(isset($dbConfigini['resources.database.multi.default.username'])?$dbConfigini['resources.database.multi.default.username']:'');
                    $dbConfig->setPasswd(isset($dbConfigini['resources.database.multi.default.password'])?$dbConfigini['resources.database.multi.default.password']:'');
                } else{
                    \Ormbuild\Lib\State::error('database.ini read fail');
                }
            }
            $params   = array('host', 'dbname', 'port', 'options');
            if (!empty($confName)) {
                $predefined = '\\Config\\' . ucfirst(strtolower($confName));
                $preConfig  = new $predefined();

                if ($preConfig instanceof \Config\ConfigAbstract) {
                    $dbConfig->setHost($preConfig->get('host'));
                    $dbConfig->setPort($preConfig->get('port'));
                    $dbConfig->setDbname($preConfig->get('dbname'));
                    $dbConfig->setOptions($preConfig->get('options'));
                    $dbConfig->setUsername($preConfig->get('username'));
                    $dbConfig->setPasswd($preConfig->get('passwd'));
                }
            } else {
                array_push($params, 'username');
                array_push($params, 'passwd');
            }

            foreach ($params as $name) {
                $get = 'get' . ucfirst(strtolower($name));

                if (method_exists($options, $get)) {
                    $val = $options->{$get}();
                    $set = 'set' . ucfirst(strtolower($name));

                    if (!empty($val) && method_exists($dbConfig, $set)) {
                        $dbConfig->{$set}($val);
                    }
                }
            }

            $this->_db     = new \Ormbuild\Lib\Db($dbConfig);
            $this->_dbname = $dbConfig->getDbname();
        }

        return $this->_db;
    }

    /**
     * Get Help info
     *
     * @return string
     */
    protected function getHelp() {
        $this->_isHelp = true;
        $item          = array();
        $item[] = ' ######生成全部表模型，请输入任意参数##########';
        $item[] = ' H  显示帮助';
        $item[] = ' t  指定Build的表名，多个时用 \',\' 分隔';
        $item[] = 'f  Model Class保存路径, 默认保存在work.php相应目录下的BuildResult文件夹下';
        $item[] = ' e  Model Class父类 (未开启命名空间，\'\\\' 以 \'_\' 代替)';
        $item[] = ' i  Model Class类所需接口类 (未开启命名空间，\'\\\' 以 \'_\' 代替)';
        $item[] = ' x  Model Class文件后缀名, 默认 php';
        $item[] = ' l  Model Class文件名/类名是否保留下划线, 默认 false';
        $item[] = ' L  Model Class方法名是否保留下划线, 默认 true';
        $item[] = ' m  Model Class命名类型, 默认 1，1. %sModel  2. Model%s  3.%s_Model  4. Model_%s';
        $item[] = ' N  Model Class的命名空间，默认 \\';
        $item[] = ' F  Model Class能支持写 final 关键字, 默认 false';
        $item[] = ' o  是否开启命名空间， 默认 true';
        $item[] = ' d  从Config中读取的数据库配置，默认 false';
        $item[] = ' T  设置N个空格替代一个TAB，为0时将以TAB出现不替换, 默认 4';
        $item[] = ' u  连接mysql用户名，使用此项 +d 将失效';
        $item[] = ' p  连接mysql密码，使用此项 +d 将失效, 不建议直接在命令行输入密码';
        $item[] = ' h  连接mysql主机, 默认 127.0.0.1';
        $item[] = ' P  连接mysql主机端口, 默认 3306';
        $item[] = ' n  连接mysql数据库名';
        $item[] = ' O  数据库驱动选项处理, 多个时用 \',\' 分隔';
        

        \Ormbuild\Lib\State::notice(implode("\n", $item));
    }

}
