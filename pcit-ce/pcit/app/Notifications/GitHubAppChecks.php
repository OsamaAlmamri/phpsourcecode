<?php

declare(strict_types=1);

namespace App\Notifications;

use App\Build;
use App\GetAccessToken;
use App\Job;
use App\Notifications\GitHubChecksConclusion\Queued;
use App\Repo;
use PCIT\Framework\Support\JSON;
use PCIT\GitHub\Service\Checks\RunData;
use PCIT\PCIT;
use PCIT\Support\CI;

class GitHubAppChecks
{
    /**
     * @param string     $status
     * @param int        $started_at
     * @param int        $completed_at
     * @param string     $conclusion
     * @param string     $summary
     * @param string     $text
     * @param null|array $annotations  [$annotation, $annotation2]
     * @param null|array $images       [$image, $image2]
     * @param null|array $actions      [$action]
     * @param bool       $force_create 默认情况下若 check_run_id 已存在，则更新此 check_run_id
     *                                 若设为 true 则新建一个 check_run ,适用于第三方服务完成状态展示
     *                                 或是没有过程，直接完成的构建
     *
     * @throws \Exception
     */
    public static function send(
        int $job_key_id,
        string $name = null,
        string $status = null,
        int $started_at = null,
        int $completed_at = null,
        string $conclusion = null,
        string $title = null,
        string $summary = null,
        string $text = null,
        array $annotations = null,
        array $images = null,
        array $actions = null,
        bool $force_create = false
    ): void {
        \Log::info('Create GitHub App Check Run', ['job_key_id' => $job_key_id]);

        $job_env_vars = Job::getEnv($job_key_id);
        $job_env = null;
        if ($job_env_vars) {
            $job_env_values = array_values($job_env_vars);

            $job_env = '('.implode(', ', $job_env_values).')';
        }

        $rid = Job::getRid((int) $job_key_id);

        $build_key_id = (int) Job::getBuildKeyID($job_key_id);

        $repo_full_name = Repo::getRepoFullName((int) $rid);

        $access_token = GetAccessToken::getGitHubAppAccessToken($rid);

        /** @var \PCIT\GPI\GPI */
        $pcit = app(PCIT::class)->git('github', $access_token);

        $output_array = Build::find((int) $build_key_id);

        $commit_id = $output_array['commit_id'];
        $event_type = $output_array['event_type'];

        $details_url = config('app.host').'/github/'.$repo_full_name.'/jobs/'.$job_key_id;

        $config = JSON::beautiful(Build::getConfig((int) $build_key_id));

        $status = $status ?? CI::GITHUB_CHECK_SUITE_STATUS_QUEUED;

        if ($conclusion) {
            $title_prefix = 'Build '.ucfirst($conclusion);
        } else {
            $title_prefix = ucfirst($status);

            if (CI::GITHUB_CHECK_SUITE_STATUS_IN_PROGRESS === $status) {
                $title_prefix = 'In progress';
            }
        }

        $title_prefix = str_replace('_', ' ', $title_prefix);

        $name = $name ?? (config('git.github.check_run.prefix').' / '.$event_type.' '.$job_env);

        $title = $title ?? $title_prefix.' #'.$build_key_id.'-'.$job_key_id;

        $summary = $summary ??
            'This Repository CI/CD Powered By [PCIT](https://github.com/pcit-ce/pcit)';

        $text = $text ??
            (new Queued($build_key_id, $config, null, 'PHP', PHP_OS))
                ->markdown();

        $run_data = new RunData(
            $repo_full_name,
            $name,
            $commit_id,
            $details_url,
            (string) $job_key_id,
            $status,
            $started_at,
            $completed_at,
            $conclusion,
            $title,
            $summary,
            $text,
            $annotations,
            $images,
            $actions
        );

        $result = $pcit->check_run->create($run_data);

        $check_run_id = json_decode($result)->id ?? null;

        $log_message = 'Create GitHub App Check run error';

        if ($check_run_id) {
            $log_message = 'Create GitHub App Check Run success';

            $result = $check_run_id;
        }

        \Log::info($log_message, [
            'job_key_id' => $job_key_id,
            'build_key_id' => $build_key_id,
            'result' => $result,
            'status' => $status,
            'conclusion' => $conclusion,
            'commit_id' => $commit_id,
        ]);

        // 更新 PCIT / EVENT_TYPE 状态
        // eg: PCIT / push
        // 获取 build 状态

        $build_status = Build::getBuildStatusByBuildKeyId($build_key_id);
        $conclusion = self::buildStatus2conclusion($build_status);

        $run_data->name = config('git.github.check_run.prefix').' / '.$event_type;
        $run_data->details_url = config('app.host').'/github/'.$repo_full_name.'/builds/'.$build_key_id;
        $run_data->external_id = (string) $build_key_id;
        $conclusion = $run_data->conclusion = $conclusion;
        $run_data->started_at = (int) Build::getStartAt($build_key_id);
        $run_data->completed_at = $conclusion ? (int) Build::getStopAt($build_key_id) : null;
        $status = $run_data->status = \in_array($build_status, [
            'pending', 'queued',
        ])
        ? CI::GITHUB_CHECK_SUITE_STATUS_IN_PROGRESS :
        CI::GITHUB_CHECK_SUITE_STATUS_COMPLETED;
        $run_data->title = 'Build #'.$build_key_id;
        // $run_data->summary = $summary;
        $run_data->text = 'This is summary check run.';

        $result = $pcit->check_run->create($run_data);
        // var_dump($result);
        $check_run_id = json_decode($result)->id ?? null;

        \Log::info('Create GitHub App Check Run, build status summary', compact(
            'build_key_id',
            'build_status',
            'conclusion',
            'status',
            'check_run_id',
            'commit_id'
        ));
    }

    public static function buildStatus2conclusion($status)
    {
        if (\in_array($status, ['queued', 'skip', 'misconfigured', 'skipped'])) {
            return;
        }

        return $status;
    }
}
