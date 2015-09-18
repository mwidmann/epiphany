<?php
class EpiCache_Memcache extends EpiCache {

	private static $connected = false;
	private $memcache = null;
	private $host = null;
	private $port = null;
	private $expiry   = null;

	public function __construct($params = array()) {
		$this->host = !empty($params[0]) ? $params[0] : 'localhost';
		$this->port = !empty($params[1]) ? $params[1] : 11211;;
		$this->expiry   = !empty($params[2]) ? $params[2] : 3600;
	}

	public function delete($key, $timeout = 0) {
		if(!$this->connect() || empty($key))
			return false;

		return $this->memcache->delete($key);
	}

	public function get($key, $useCache = true) {
		if(!$this->connect() || empty($key)) {
			return null;
		} else if($useCache && $getEpiCache = $this->getEpiCache($key)) {
			return $getEpiCache;
		} else {
			$value = $this->memcache->get($key);
			$this->setEpiCache($key, $value);
			return $value;
		}
	}

	public function set($key = null, $value = null, $ttl = null) {
		if(!$this->connect() || empty($key) || $value === null)
			return false;

		$expiry = $ttl === null ? $this->expiry : $ttl;
		$this->memcache->set($key, $value, false, $expiry);
		$this->setEpiCache($key, $value);
		return true;
	}

	private function connect() {
		if(self::$connected === true)
			return true;

		if(class_exists('Memcache')) {
			$this->memcache = new Memcache();
			
			if($this->memcache->addServer($this->host, $this->port)) {
				$this->memcache->setCompressThreshold( 20000, 0.2 );
				return self::$connected = true;
			} else {
				EpiException::raise(new EpiCacheMemcacheConnectException('Could not connect to memcache server'));
			}
		}

		EpiException::raise(new EpiCacheMemcacheClientDneException('No memcache client exists'));
	}
}
