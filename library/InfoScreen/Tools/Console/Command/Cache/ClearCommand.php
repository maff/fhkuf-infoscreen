<?php
namespace InfoScreen\Tools\Console\Command\Cache;

use Symfony\Component\Console\Input\InputArgument,
    Symfony\Component\Console\Input\InputOption,
    Symfony\Component\Console;

/**
 * Command to clear cache
 */
class ClearCommand extends Console\Command\Command
{
    /**
     * @var Zend_Cache_Manager
     */
    protected $_cacheManager;

    /**
     * Get the cache manager
     *
     * @return Zend_Cache_Manager
     */
    protected function _getCacheManager()
    {
        if($this->_cacheManager === null) {
            $this->_cacheManager = \Zend_Registry::get('cachemanager');
            if(!$this->_cacheManager instanceof \Zend_Cache_Manager) {
                throw new Exception('Cache manager not initialized.');
            }
        }

        return $this->_cacheManager;
    }

    protected function _getAvailableCacheInstanceString()
    {
        $caches = $this->_getCacheManager()->getCaches();

        $i = 1;
        $string = '  * all (all caches)' . "\n";
        foreach($caches as $key => $cache) {
            $string .= '  * ' . $key;

            if($i != count($caches)) {
                $string .= PHP_EOL;
            }
            ++$i;
        }

        return $string;
    }

    /**
     * @see Console\Command\Command
     */
    protected function configure()
    {
        $cacheInstances = $this->_getAvailableCacheInstanceString();

        $this->setName('cache:clear')
            ->setDescription('Clear the cache')
            ->setDefinition(array(
                new InputArgument(
                    'instance', InputArgument::REQUIRED,
                    'Cache instance'
                )
            ))
            ->addOption('outdated', null, InputOption::VALUE_NONE, 'Clean only outdated records')
            ->setHelp(<<<EOT
Clear the cache. Instance can be one of the following:

{$cacheInstances}
EOT
            );
    }

    /**
     * @see Console\Command\Command
     */
    protected function execute(Console\Input\InputInterface $input, Console\Output\OutputInterface $output)
    {
        $instances = array();
        $instanceName = $input->getArgument('instance');

        if($instanceName == 'all') {
            foreach($this->_getCacheManager()->getCaches() as $key => $cache) {
                $instances[$key] = $cache;
            }
        } else {
            $cache = $this->_getCacheManager()->getCache($instanceName);
            if($cache === null) {
                $output->write(sprintf('<error>Cache instance %s does not exist.</error>', $instanceName) . PHP_EOL);
                die();
            } else {
                $instances[$instanceName] = $cache;
            }
        }

        if(count($instances) > 0) {
            foreach($instances as $name => $cache) {
                try {
                    if($input->getOption('outdated')) {
                        $cache->clean(\Zend_Cache::CLEANING_MODE_OLD);
                        $output->write(sprintf('Cache <info>%s</info> purged from outdated records', $name) . PHP_EOL);
                    } else {
                        $cache->clean(\Zend_Cache::CLEANING_MODE_ALL);
                        $output->write(sprintf('Cache <info>%s</info> cleared', $name) . PHP_EOL);
                    }
                } catch(Exception $e) {
                    $output->write('<error>' . $e->getMessage() . '</error>' . PHP_EOL);
                }
            }
        }
    }
}