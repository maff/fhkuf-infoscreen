<?php
class InfoScreen_Setup
{
    /* @var Zend_Config */
    protected $_config;

    public function  __construct(Zend_Config $config)
    {
        $this->_config = $config;
    }

    public function run()
    {
        $this->_initDirectories();
    }

   protected function _initDirectories()
    {
        /* @var $config Zend_Config */
        $config = $this->_config;

        if(isset($config->resources->log)) {
            foreach($config->resources->log as $log) {
                if($log->writerName == 'Stream') {
                    if(isset($log->writerParams->stream)) {
                        $path = dirname($log->writerParams->stream);
                        if(!file_exists($path)) {
                            mkdir($path, 0755, true);
                        }
                    }
                }
            }
        }

        if(isset($config->resources->cachemanager)) {
            foreach($config->resources->cachemanager as $cache) {
                if($cache->backend->name == 'File') {
                    if(isset($cache->backend->options->cache_dir)) {
                        $path = $cache->backend->options->cache_dir;
                        if(!file_exists($path)) {
                            mkdir($path, 0755, true);
                        }
                    }
                }
            }
        }

        if(isset($config->resources->session->save_path)) {
            $path = $config->resources->session->save_path;
            if(!file_exists($config->resources->session->save_path)) {
                mkdir($config->resources->session->save_path, 0755, true);
            }
        }
    }
}