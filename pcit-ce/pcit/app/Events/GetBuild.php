<?php

declare(strict_types=1);

namespace App\Events;

use App\Build;
use App\Repo;
use App\Setting;
use PCIT\Exception\PCITException;
use PCIT\Framework\Support\JSON;
use PCIT\Runner\BuildData;
use PCIT\Support\CI;

/**
 * 获取 build 数据.
 */
class GetBuild extends BuildData
{
    /**
     * @throws PCITException
     *
     * @return null|self
     */
    public function handle(int $buildId = 0)
    {
        $result = \App\Build::getData($buildId);

        $result = array_values($result);

        list($build_key_id,
            $this->git_type,
            $rid,
            $this->commit_id,
            $this->commit_message,
            $this->branch,
            $this->event_type,
            $pull_request_number,
            $this->tag,
            $this->config, $this->internal, $this->private) = $result;

        if (!$this->config or !json_decode($this->config)) {
            Build::updateBuildStatus((int) $build_key_id, 'skipped');

            throw new PCITException(CI::GITHUB_CHECK_SUITE_CONCLUSION_SUCCESS);
        }

        $this->build_key_id = (int) $build_key_id;
        $this->rid = (int) $rid;
        $this->pull_request_number = (int) $pull_request_number;

        $this->unique_id = session_create_id();

        $this->getRepoConfig();

        if ('0' === $this->build_pull_requests
        and CI::BUILD_EVENT_PR === $this->event_type) {
            // don't build pr
            \Log::info("Build #$build_key_id is skipped, because repo settings [build_pull_requests]");

            Build::updateBuildStatus((int) $build_key_id, 'skipped');

            return;
        }

        if ('0' === $this->build_pushes
        and CI::BUILD_EVENT_PUSH === $this->event_type
        and 0 === $buildId) {
            // don't build push

            \Log::info("Build #$build_key_id is skipped, because repo settings [build_pushes]");

            Build::updateBuildStatus((int) $build_key_id, 'skipped');

            return;
        }

        $this->config = JSON::beautiful($this->config);

        \Log::emergency('====== 🚩Get Build '.$this->build_key_id.' Data Start ======');

        $this->getEnv('0' === $this->internal);

        $this->repo_full_name = Repo::getRepoFullName((int) $this->rid, $this->git_type);

        return $this;
    }

    /**
     * @throws \Exception
     */
    private function getRepoConfig(): void
    {
        $result = Setting::list($this->rid, $this->git_type);

        $this->build_pushes = $result['build_pushes'] ?? '1';
        $this->build_pull_requests = $result['build_pull_requests'] ?? '1';
        $this->maximum_number_of_builds = $result['maximum_number_of_builds'] ?? '1';
        $this->auto_cancel_branch_builds = $result['auto_cancel_branch_builds'] ?? '0';
        $this->auto_cancel_pull_request_builds = $result['auto_cancel_pull_request_builds'] ?? '0';
    }

    /**
     * get user set build env. ['k=v'].
     *
     * public true:           只获取公开的 secret
     *        false(default): 获取所有的 secret
     *
     * @throws \Exception
     */
    public function getEnv(bool $public = false): void
    {
        $env = [];

        $env_array = \App\Env::list($this->rid, $this->git_type);

        foreach ($env_array as $k) {
            if ($public) {
                if ('0' === $k['public']) {
                    continue;
                }
            }
            $name = $k['name'];
            $value = $k['value'];

            $env[] = $name.'='.$value;
        }

        $this->env = $env;
    }
}
