<?php
/**
 * User: sascha.bast
 * Date: 12/1/18
 * Time: 10:12 PM
 */

class CfgService
{

    private $cfgFilePath;
    private $cfg = array();
    private $log;

    public function __construct(Psr\Log\LoggerInterface $log)
    {
        $this->log = $log;
    }

    public function loadCfgFile(string $cfgFilePath): bool
    {
        if (file_exists($cfgFilePath)) {
            $string = file_get_contents($cfgFilePath);
            $json = json_decode($string, true);
            if ($json == null) {
                throw new \splitbrain\phpcli\Exception("could not parse config, probably syntax error");
            }
            foreach ($json as $key => $value) {
                if (!is_array($value) && !is_object($value)) {
                    $this->cfg[$key] = $value;
                } else if (is_object($value)) {
                    $this->cfg[$key] = $value;
                } else {
                    foreach ($value as $val) {
                        $this->cfg[$key] = $value;
                    }
                }
            }
            $this->log->debug("-> successfully loaded cfg:[" . json_encode($this->cfg) . "]");
            return true;
        } else {
            throw new \splitbrain\phpcli\Exception("Could not read config file [" . $this->cfgFilePath . "]");
        }
    }

    public function getValue(string $key): string
    {
        if (isset($this->cfg[$key])) {
            return $this->cfg[$key];
        }
        $msg = "value ['" . $key . "'] not found";
        return $msg;
        $this->log->debug($msg);
    }

    public function getCfg(): array
    {
        return $this->cfg;
    }
}