<?php

namespace nacos\failover;

use SplFileInfo;
use nacos\NacosConfig;

/**
 * Class LocalConfigInfoProcessor
 * @package nacos\failover
 */
class LocalConfigInfoProcessor extends Processor
{
    const DS = DIRECTORY_SEPARATOR;

    /**
     * Undocumented function
     *
     * @param [type] $serverName
     * @param [type] $dataId
     * @param [type] $group
     * @param [type] $tenant
     *
     * @return void
     */
    public static function getFailover($serverName, $dataId, $group, $tenant)
    {
        $failoverFile = self::getFailoverFile($serverName, $dataId, $group, $tenant);
        if (!is_file($failoverFile)) {
            return null;
        }
        return file_get_contents($failoverFile);
    }

    /**
     * Undocumented function
     *
     * @param [type] $serverName
     * @param [type] $dataId
     * @param [type] $group
     * @param [type] $tenant
     *
     * @return void
     */
    public static function getFailoverFile($serverName, $dataId, $group, $tenant)
    {
        $failoverFile = NacosConfig::getSnapshotPath() . self::DS . $serverName . "_nacos" . self::DS;
        if ($tenant) {
            $failoverFile .= "config-data-tenant" . self::DS . $tenant . self::DS;
        } else {
            $failoverFile .= "config-data" . self::DS;
        }
        return $failoverFile . $dataId;
    }

    /**
     * 获取本地缓存文件内容。NULL表示没有本地文件或抛出异常。
     *
     * @param [type] $name
     * @param [type] $dataId
     * @param [type] $group
     * @param [type] $tenant
     *
     * @return void
     */
    public static function getSnapshot($name, $dataId, $group, $tenant)
    {
        $snapshotFile = self::getSnapshotFile($name, $dataId, $group, $tenant);
        if (!is_file($snapshotFile)) {
            return null;
        }
        return file_get_contents($snapshotFile);
    }

    /**
     * Undocumented function
     *
     * @param [type] $envName
     * @param [type] $dataId
     * @param [type] $group
     * @param [type] $tenant
     *
     * @return void
     */
    public static function getSnapshotFile($envName, $dataId, $group, $tenant)
    {
        $snapshotFile = NacosConfig::getSnapshotPath() . self::DS . $envName . "_nacos" . self::DS;
        if ($tenant) {
            $snapshotFile .= "snapshot-tenant" . self::DS . $tenant . self::DS;
        } else {
            $snapshotFile .= "snapshot" . self::DS;
        }
        return $snapshotFile .= $dataId;
    }

    /**
     * save snapshot
     *
     * @param [type] $envName
     * @param [type] $dataId
     * @param [type] $group
     * @param [type] $tenant
     * @param [type] $config
     * @param string $cacheSnapshotFile
     *
     * @return void
     */
    public static function saveSnapshot($envName, $dataId, $group, $tenant, $config, $cacheSnapshotFile = '')
    {
        if (!empty($cacheSnapshotFile)) {
            @unlink($cacheSnapshotFile);
        }

        $snapshotFile = self::getSnapshotFile($envName, $dataId, $group, $tenant);
        $file = new SplFileInfo($snapshotFile);
        if (!is_dir($file->getPath())) {
            mkdir($file->getPath(), 0777, true);
        }
        file_put_contents($snapshotFile, $config);
    }
}