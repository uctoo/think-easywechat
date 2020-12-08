<?php
/**
 * This file is part of think-easywechat.
 *
 * Licensed under The MIT License
 * For full copyright and license information, please see the MIT-LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @author    UCToo<contact@uctoo.com>
 * @copyright UCToo<contact@uctoo.com> UCToo [ Universal Convergence Technology ]
 * @link      http://www.uctoo.com/
 * @license   http://www.opensource.org/licenses/mit-license.php MIT License
 */

namespace uctoo\ThinkEasyWeChat\MiniProgram\CloudDb;


use EasyWeChat\Kernel\BaseClient;
use EasyWeChat\Kernel\Exceptions\InvalidConfigException;
use EasyWeChat\Kernel\Support\Collection;
use Psr\Http\Message\ResponseInterface;

class Client extends BaseClient
{
    /**
     * Database migrate export
     *
     * @param string $file_path
     * @param string $query
     * @param int $file_type
     * @return array|Collection|object|ResponseInterface|string
     * @throws InvalidConfigException
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function migrateExport(string $file_path,string $query, int $file_type = 1)
    {
        return $this->httpPostJson('tcb/databasemigrateexport', [
            'env' => $this->app->config->get('env'),
            'file_path' => $file_path,
            'file_type' => $file_type,
            'query' => $query,
        ]);
    }

    /**
     * Database migrate import
     *
     * @param string $collection_name
     * @param string $file_path
     * @param int $file_type
     * @param bool $stop_on_error
     * @param int $conflict_mode
     * @return array|Collection|object|ResponseInterface|string
     * @throws InvalidConfigException
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function migrateImport(string $collection_name, string $file_path, int $file_type = 1, bool $stop_on_error = false, int $conflict_mode = 1)
    {
        return $this->httpPostJson('tcb/databasemigrateimport', [
            'env' => $this->app->config->get('env'),
            'collection_name' => $collection_name,
            'file_path' => $file_path,
            'file_type' => $file_type,
            'stop_on_error' => $stop_on_error,
            'conflict_mode' => $conflict_mode
        ]);
    }

    /**
     * Database migration status query
     *
     * @param int $job_id
     * @return array|Collection|object|ResponseInterface|string
     * @throws InvalidConfigException
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function migrateQueryInfo(int $job_id){
        return $this->httpPostJson('tcb/databasemigratequeryinfo', [
            'env' => $this->app->config->get('env'),
            'job_id' => $job_id,
        ]);
    }

    /**
     * Changing database index
     *
     * @param string $collection_name
     * @param array $create_indexes
     * @param array $drop_indexes
     * @return array|Collection|object|ResponseInterface|string
     * @throws InvalidConfigException
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function updateIndex(string $collection_name, array $create_indexes, array $drop_indexes)
    {
        return $this->httpPostJson('tcb/databasemigrateimport', [
            'env' => $this->app->config->get('env'),
            'collection_name' => $collection_name,
            'create_indexes' => $create_indexes,
            'drop_indexes' => $drop_indexes,
        ]);
    }

    /**
     * Add database collection
     *
     * @param string $collection_name
     * @return array|Collection|object|ResponseInterface|string
     * @throws InvalidConfigException
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function collectionAdd(string $collection_name)
    {
        return $this->httpPostJson('tcb/databasecollectionadd', [
            'env' => $this->app->config->get('env'),
            'collection_name' => $collection_name,
        ]);
    }

    /**
     * Delete database collection
     *
     * @param string $collection_name
     * @return array|Collection|object|ResponseInterface|string
     * @throws InvalidConfigException
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function collectionDelete(string $collection_name)
    {
        return $this->httpPostJson('tcb/databasecollectiondelete', [
            'env' => $this->app->config->get('env'),
            'collection_name' => $collection_name,
        ]);
    }

    /**
     * Get database collection
     *
     * @param int $limit
     * @param int $offset
     * @return array|Collection|object|ResponseInterface|string
     * @throws InvalidConfigException
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function collectionGet(int $limit = 10, int $offset = 0)
    {
        return $this->httpPostJson('tcb/databasecollectionget', [
            'env' => $this->app->config->get('env'),
            'limit' => $limit,
            'offset' => $offset
        ]);
    }

    /**
     * Database insert records
     *
     * @param string $query
     * @return array|Collection|object|ResponseInterface|string
     * @throws InvalidConfigException
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function add(string $query)
    {
        return $this->httpPostJson('tcb/databaseadd', [
            'env' => $this->app->config->get('env'),
            'query' => $query,
        ]);
    }

    /**
     * Database delete records
     *
     * @param string $query
     * @return array|Collection|object|ResponseInterface|string
     * @throws InvalidConfigException
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function delete(string $query)
    {
        return $this->httpPostJson('tcb/databaseadd', [
            'env' => $this->app->config->get('env'),
            'query' => $query,
        ]);
    }

    /**
     * Database update records
     *
     * @param string $query
     * @return array|Collection|object|ResponseInterface|string
     * @throws InvalidConfigException
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function update(string $query)
    {
        return $this->httpPostJson('tcb/databaseupdate', [
            'env' => $this->app->config->get('env'),
            'query' => $query,
        ]);
    }

    /**
     * Database aggregate query
     *
     * @param string $query
     * @return array|Collection|object|ResponseInterface|string
     * @throws InvalidConfigException
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function aggregate(string $query)
    {
        return $this->httpPostJson('tcb/databaseaggregate', [
            'env' => $this->app->config->get('env'),
            'query' => $query,
        ]);
    }

    /**
     * Database count query
     *
     * @param string $query
     * @return array|Collection|object|ResponseInterface|string
     * @throws InvalidConfigException
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function count(string $query)
    {
        return $this->httpPostJson('tcb/databasecount', [
            'env' => $this->app->config->get('env'),
            'query' => $query,
        ]);
    }




}