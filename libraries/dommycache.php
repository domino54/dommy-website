<?php
/**
 *	DommyCache - extremely simple caching for PHP.
 *
 * @copyright   Copyright (c) 2016 domino54
 * @license     https://www.gnu.org/licenses/gpl-3.0.html
 * @author      domino54
 * @version     07-09-2016
 */

namespace DommyCache;

/**
 *	The cache handler.
 */
class Cache {
	const CACHE_FILENAME = './dommycache.json';

	/**
	 *	Get the contents of the cache file.
	 *
	 *	@return	array[] Array of cache entries.
	 */
	private function getCache() {
		if (!file_exists(self::CACHE_FILENAME)) file_put_contents(self::CACHE_FILENAME, '');
		$cacheFileContents = json_decode(file_get_contents(self::CACHE_FILENAME), true);
		if (is_array($cacheFileContents)) return $cacheFileContents;
		return array();
	}

	/**
	 *	Get the cache entry under a specific name.
	 *
	 *	@param string $name Name of the entry to get.
	 *	@return array[] Array of entry data.
	 */
	function getEntry($name) {
		$cacheContents = $this->getCache();
		if (!is_string($name) || !array_key_exists($name, $cacheContents)) return array();
		return $cacheContents[$name];
	}

	/**
	 *	Get the creation time of an entry.
	 *
	 *	@param string $name Name of the entry to get creation time.
	 *	@return int Creation time of the entry.
	 */
	function getCreationTime($name) {
		if (!is_string($name)) return -1;
		$cacheEntry = $this->getEntry($name);
		if (!is_array($cacheEntry) || !array_key_exists('time', $cacheEntry)) return -1;
		return $cacheEntry['time'];
	}

	/**
	 *	Get for how long does the entry exist in cache.
	 *
	 *	@param string $name Name of the entry to get life duration.
	 *	@return int Life duration of the entry.
	 */
	function getLifeDuration($name) {
		if (!is_string($name)) return -1;
		$creationTime = $this->getCreationTime($name);
		if ($creationTime < 0) return -1;
		return time() - $creationTime;
	}

	/**
	 *	Get the contents of an entry.
	 *
	 *	@param string $name Name of the entry to get contents.
	 *	@return mixed Contents of the entry.
	 */
	function getContent($name) {
		if (!is_string($name)) return;
		$cacheEntry = $this->getEntry($name);
		if (!is_array($cacheEntry) || !array_key_exists('content', $cacheEntry)) return;
		return unserialize($cacheEntry['content']);
	}

	/**
	 *	Check if the entry has expired.
	 *
	 *	@param string $name Name of the entry to check if expired.
	 *	@param int $lifetime Maximum time the entry can be stored in cache.
	 *	@return bool True, if the entry has expired.
	 */
	function hasExpired($name, $lifetime) {
		if (!is_string($name) || !is_int($lifetime)) return true;
		$creationTime = $this->getCreationTime($name);
		if ($creationTime < 0) return true;
		return time() > $creationTime + $lifetime;
	}

	/**
	 *	Store an entry in the cache file.
	 *
	 *	@param string $name Name og the entry to store.
	 *	@param mixed $content The content of the entry to store.
	 */
	function setEntry($name, $content) {
		if (!is_string($name) || !$content) return;
		$cacheContents = $this->getCache();

		$cacheContents[$name] = array(
			'time' => time(),
			'content' => serialize($content)
		);

		file_put_contents(self::CACHE_FILENAME, json_encode($cacheContents));
	}
}

?>