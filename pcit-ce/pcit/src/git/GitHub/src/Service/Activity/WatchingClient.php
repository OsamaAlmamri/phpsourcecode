<?php

declare(strict_types=1);

namespace PCIT\GitHub\Service\Activity;

use PCIT\GPI\ServiceClientCommon;

class WatchingClient
{
    use ServiceClientCommon;

    /**
     * List watchers.
     *
     * @throws \Exception
     *
     * @return mixed
     */
    public function list(string $repo_full_name)
    {
        return $this->curl->get($this->api_url.'/repos/'.$repo_full_name.'/subscribers');
    }

    /**
     * List repositories being watched.
     *
     * @throws \Exception
     *
     * @return mixed
     */
    public function listRepositoryBeingWatched(string $username = null)
    {
        if ($username) {
            return $this->curl->get($this->api_url.'/users/'.$username.'/subscriptions');
        }

        return $this->curl->get($this->api_url.'/user/subscriptions');
    }

    /**
     * Get a Repository Subscription.
     *
     * 检查是否 watching repo
     *
     * @throws \Exception
     *
     * @return mixed
     */
    public function getRepositorySubscription(string $repo_full_name)
    {
        return $this->curl->get($this->api_url.'/repos/'.$repo_full_name.'/subscription');
    }

    /**
     * Set a Repository Subscription.
     *
     * @throws \Exception
     *
     * @return mixed
     */
    public function setRepositorySubscription(string $repo_full_name, bool $subscribed, bool $ignored)
    {
        return $this->curl->put(
            $this->api_url.'/repos/'.$repo_full_name.'/subscription?'.http_build_query(
                [
                    'subscribed' => $subscribed,
                    'ignored' => $ignored,
                ]
            )
        );
    }

    /**
     * Delete a Repository Subscription.
     *
     * 204
     *
     * @throws \Exception
     */
    public function deleteRepositorySubscription(string $repo_full_name): void
    {
        $this->curl->delete($this->api_url.'/repos/'.$repo_full_name.'/subscription');
    }
}
