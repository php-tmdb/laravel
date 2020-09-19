<?php
/**
 * @author Serafim <nesk@xakep.ru>
 * @date 22.03.2016 20:24
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Tmdb\Laravel\Cache;

use Doctrine\Common\Cache\Cache;
use Illuminate\Contracts\Cache\Repository;

/**
 * Class DoctrineCacheBridge
 *
 * @see https://gist.github.com/SerafimArts/af64ac4edae86a2285fa
 */
class DoctrineCacheBridge implements Cache
{
    /**
     * @var int
     */
    private $hitsCount = 0;

    /**
     * @var int
     */
    private $missesCount = 0;

    /**
     * @var int
     */
    private $upTime;

    /**
     * @var Repository
     */
    private $driver;

    /**
     * DoctrineCacheBridge constructor.
     * @param Repository $repository
     */
    public function __construct(Repository $repository)
    {
        $this->upTime = time();
        $this->driver = $repository;
    }

    /**
     * @param string $id
     * @return mixed
     */
    public function fetch($id)
    {
        $result = $this->driver->get($id, false);

        if (!$result) {
            $this->missesCount++;
        }

        $this->hitsCount++;

        return $result;
    }

    /**
     * @param string $id
     * @return bool
     */
    public function contains($id)
    {
        return $this->driver->has($id);
    }

    /**
     * @param string $id
     * @param mixed $data
     * @param int $lifeTime
     * @return bool
     */
    public function save($id, $data, $lifeTime = 0)
    {
        return $this->driver->add($id, $data, $lifeTime / 60);
    }

    /**
     * @param string $id
     * @return bool
     */
    public function delete($id)
    {
        return $this->driver->forget($id);
    }

    /**
     * @return array
     */
    public function getStats()
    {
        return [
            Cache::STATS_HITS             => $this->hitsCount,
            Cache::STATS_MISSES           => $this->missesCount,
            Cache::STATS_UPTIME           => $this->upTime,
            Cache::STATS_MEMORY_USAGE     => null,
            Cache::STATS_MEMORY_AVAILABLE => null,
        ];
    }
}
