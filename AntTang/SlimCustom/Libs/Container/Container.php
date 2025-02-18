<?php
/**
 * @package     Container.php
 * @author      Jing <tangjing3321@gmail.com>
 * @link        http://www.slimphp.net
 * @version     1.0
 * @copyright   Copyright (c) SlimCustom.
 * @date        2017年5月10日
 */

namespace SlimCustom\Libs\Container;

use Interop\Container\ContainerInterface;
use Interop\Container\Exception\ContainerException;
use Pimple\Container as PimpleContainer;
use Slim\Exception\ContainerValueNotFoundException;
use Slim\Exception\ContainerException as SlimContainerException;
use SlimCustom\Libs\DefaultServicesProvider;
use Slim\Collection;
use SlimCustom\Libs\App;

/**
 * 容器
 * 
 * @author Jing Tang <tangjing3321@gmail.com>
 */
class Container extends PimpleContainer implements ContainerInterface
{
    /**
     * Default settings
     *
     * @var array
     */
    private $defaultSettings = [
        'httpVersion' => '1.1',
        'responseChunkSize' => 4096,
        'outputBuffering' => 'append',
        'determineRouteBeforeAppMiddleware' => false,
        'displayErrorDetails' => false,
        'addContentLengthHeader' => true,
        'routerCacheFile' => false,
    ];
    
    /**
     * Create new container
     *
     * @param array $values The parameters or objects.
     */
    public function __construct(array $values = [])
    {
        parent::__construct($values);
    
        $userSettings = isset($values['settings']) ? $values['settings'] : [];
        $this->registerDefaultServices($userSettings);
    }
    
    /**
     * This function registers the default services that Slim needs to work.
     *
     * All services are shared - that is, they are registered such that the
     * same instance is returned on subsequent calls.
     *
     * @param array $userSettings Associative array of application settings
     *
     * @return void
     */
    private function registerDefaultServices($userSettings)
    {
        $defaultSettings = $this->defaultSettings;
    
        /**
         * This service MUST return an array or an
         * instance of \ArrayAccess.
         *
         * @return array|\ArrayAccess
         */
        $this['settings'] = function () use ($userSettings, $defaultSettings) {
            return new Collection(array_merge($defaultSettings, $userSettings));
        };
        
        $container = $this;
//         $defaultProvider = new DefaultServicesProvider();
//         $defaultProvider->register($this);
    }
    
    /********************************************************************************
     * Methods to satisfy Interop\Container\ContainerInterface
     *******************************************************************************/
    
    /**
     * Finds an entry of the container by its identifier and returns it.
     *
     * @param string $id Identifier of the entry to look for.
     *
     * @throws ContainerValueNotFoundException  No entry was found for this identifier.
     * @throws ContainerException               Error while retrieving the entry.
     *
     * @return mixed Entry.
     */
    public function get($id)
    {
        if (!$this->offsetExists($id)) {
            throw new ContainerValueNotFoundException(sprintf('Identifier "%s" is not defined.', $id));
        }
        try {
            return $this->offsetGet($id);
        } catch (\InvalidArgumentException $exception) {
            if ($this->exceptionThrownByContainer($exception)) {
                throw new SlimContainerException(
                    sprintf('Container error while retrieving "%s"', $id),
                    null,
                    $exception
                    );
            } else {
                throw $exception;
            }
        }
    }
    
    /**
     * Tests whether an exception needs to be recast for compliance with Container-Interop.  This will be if the
     * exception was thrown by Pimple.
     *
     * @param \InvalidArgumentException $exception
     *
     * @return bool
     */
    private function exceptionThrownByContainer(\InvalidArgumentException $exception)
    {
        $trace = $exception->getTrace()[0];
    
        return $trace['class'] === PimpleContainer::class && $trace['function'] === 'offsetGet';
    }
    
    /**
     * Returns true if the container can return an entry for the given identifier.
     * Returns false otherwise.
     *
     * @param string $id Identifier of the entry to look for.
     *
     * @return boolean
     */
    public function has($id)
    {
        return $this->offsetExists($id);
    }
    
    /**
     * 依赖处理
     * @param string $class
     * @return object
     */
    public function parseDependencies($class)
    {
        $reflector = new \ReflectionClass($class);
        $constructor = $reflector->getConstructor();
        $alias = config('alias', []);
        $agrs = [];
        if ($constructor) {
            $dependencies = $constructor->getParameters();
            if (! empty($dependencies)) {
                foreach ($dependencies as $key => $value) {
                    if ($value->isDefaultValueAvailable()) {
                        $agrs[] = $value->getDefaultValue();
                    }
                    else {
                        $dependenciesClass = $value->getClass()->getName();
                        $dependenciesClass = isset($alias[$dependenciesClass]) ? $alias[$dependenciesClass] : $dependenciesClass;
                        if ($this->has($dependenciesClass)) {
                            $agrs[] = $this->get($dependenciesClass);
                        }
                        else {
                            $dependenciesObj = (new \ReflectionClass($dependenciesClass))->newInstanceWithoutConstructor();
                            if ($dependenciesObj instanceof \SlimCustom\Libs\Model\Model) {
                                $dependenciesObj = $this->parseDependencies(get_class($dependenciesObj));
                            }
                            else {
                                $dependenciesObj = new $dependenciesClass();
                            }
                            $this->$dependenciesClass = $dependenciesObj;
                            $agrs[] = $dependenciesObj;
                        }
                    }
                }
            }
        }
        return $reflector->newInstanceArgs($agrs);
    }
    
    /**
     * 魔术方法 __get
     * 
     * @param string $name
     * @return mixed|unknown
     */
    public function __get($name)
    {
        return $this->get($name);
    }
    
    /**
     * 魔术方法 __isset
     * 
     * @param string $name
     * @return boolean
     */
    public function __isset($name)
    {
        return $this->has($name);
    }
}