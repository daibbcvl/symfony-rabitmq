<?php



namespace App\Cache;

/**
 * Memcached based session storage handler based on the Memcached class
 * provided by the PHP memcached extension.
 *
 * @see http://php.net/memcached
 *
 * @author Drak <drak@zikula.org>
 */
class MemcachedHandler
{
    private $memcached;

    /**
     * @var int Time to live in seconds
     */
    private $ttl;

    /**
     * @var string Key prefix for shared environments
     */
    private $prefix;

    /**
     * Constructor.
     *
     * List of available options:
     *  * prefix: The prefix to use for the memcached keys in order to avoid collision
     *  * expiretime: The time to live in seconds.
     *
     * @param \Memcached $memcached A \Memcached instance
     * @param array      $options   An associative array of Memcached options
     *
     * @throws \InvalidArgumentException When unsupported options are passed
     */
    public function __construct(\Memcached $memcached, array $options = [])
    {
        $this->memcached = $memcached;

        if ($diff = array_diff(array_keys($options), ['prefix', 'expiretime'])) {
            throw new \InvalidArgumentException(sprintf('The following options are not supported "%s"', implode(', ', $diff)));
        }

        $this->ttl = isset($options['expiretime']) ? (int) $options['expiretime'] : 86400;
        $this->prefix = isset($options['prefix']) ? $options['prefix'] : 'sf2s';
    }

    /**
     * {@inheritdoc}
     */
    public function close()
    {
        return $this->memcached->quit();
    }

    /**
     * {@inheritdoc}
     */
    public function doRead($key)
    {
        return $this->memcached->get($this->prefix.$key) ?: null;
    }

    /**
     * {@inheritdoc}
     */
    public function updateTimestamp($key)
    {
        $this->memcached->touch($this->prefix.$key, time() + $this->ttl);

        return true;
    }

    /**
     * {@inheritdoc}
     */
    public function doWrite($key, $data)
    {
        return $this->memcached->set($this->prefix.$key, $data, time() + $this->ttl);
    }

    /**
     * {@inheritdoc}
     */
    protected function doDestroy($key)
    {
        $result = $this->memcached->delete($this->prefix.$key);

        return $result || \Memcached::RES_NOTFOUND == $this->memcached->getResultCode();
    }
}
