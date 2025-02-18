<?php

declare(strict_types=1);

namespace PCIT\GitHub\Service\Issue;

use PCIT\GPI\ServiceClientCommon;

/**
 * Class Labels.
 *
 * @see https://developer.github.com/v3/issues/labels/
 */
class LabelsClient
{
    use ServiceClientCommon;

    /**
     * List all labels for this repository.
     *
     * @throws \Exception
     *
     * @return mixed
     */
    public function all(string $repo_full_name)
    {
        $url = $this->api_url.'/repos/'.$repo_full_name.'/labels';

        return $this->curl->get($url);
    }

    /**
     * Get a single label.
     *
     * @throws \Exception
     *
     * @return mixed
     */
    public function find(string $repo_full_name, string $label_name)
    {
        $url = $this->api_url.'/repos/'.$repo_full_name.'/labels/'.$label_name;

        return $this->curl->get($url);
    }

    /**
     * Create a label.
     *
     * post 201
     *
     * @param string $name label name
     *
     * @throws \Exception
     *
     * @return mixed
     */
    public function create(string $repo_full_name, string $name, string $color, string $description)
    {
        $url = $this->api_url.'/repos/'.$repo_full_name.'/labels';

        return $this->curl->post($url, compact('name', 'color', 'description'));
    }

    /**
     * Update a label.
     *
     * patch
     *
     * @param string $label_name label name
     *
     * @throws \Exception
     *
     * @return mixed
     */
    public function update(
        string $repo_full_name,
        string $label_current_name,
        string $name,
        string $color,
        string $description
    ) {
        $url = $this->api_url.'/repos/'.$repo_full_name.'/labels/'.$label_current_name;

        return $this->curl->post($url, json_encode(compact('name', 'color', 'description')));
    }

    /**
     * Delete a label.
     *
     * 204
     *
     * @throws \Exception
     *
     * @return mixed
     */
    public function delete(string $repo_full_name, string $label_name)
    {
        $url = $this->api_url.'/repos/'.$repo_full_name.'/labels/'.$label_name;

        return $this->curl->delete($url);
    }

    /**
     * List labels on an issue.
     *
     * @throws \Exception
     *
     * @return mixed
     */
    public function listLabelsOnIssue(string $repo_full_name, int $issue_number)
    {
        $url = $this->api_url.'/repos/'.$repo_full_name.'/issues/'.$issue_number.'/labels';

        return $this->curl->get($url);
    }

    /**
     * Add labels to an issue.
     *
     * @throws \Exception
     *
     * @return mixed
     */
    public function addLabelsOnIssue(string $repo_full_name, int $issue_number, array $label)
    {
        $url = $this->api_url.'/repos/'.$repo_full_name.'/issues/'.$issue_number.'/labels';

        return $this->curl->post($url, json_encode($label));
    }

    /**
     * Remove a label from an issue.
     *
     * @throws \Exception
     *
     * @return mixed
     */
    public function removeLabelOnIssue(string $repo_full_name, int $issue_number, string $label_name)
    {
        $url = $this->api_url.'/repos/'.$repo_full_name.'/issues/'.$issue_number.'/labels/'.$label_name;

        return $this->curl->delete($url);
    }

    /**
     * Replace all labels for an issue.
     *
     * @throws \Exception
     *
     * @return mixed
     */
    public function replaceAllLabelsForIssue(string $repo_full_name, int $issue_number, array $labels = [])
    {
        $url = $this->api_url.'/repos/'.$repo_full_name.'/issues/'.$issue_number.'/labels';

        return $this->curl->put($url, json_encode($labels));
    }

    /**
     * Remove all labels from an issue.
     *
     * 204
     *
     * @throws \Exception
     *
     * @return mixed
     */
    public function removeAllLabelsFromIssue(string $repo_full_name, int $issue_number)
    {
        $url = $this->api_url.'/repos/'.$repo_full_name.'/issues/'.$issue_number.'/labels';

        return $this->curl->delete($url);
    }

    /**
     * Get labels for every issue in a milestone.
     *
     * @throws \Exception
     *
     * @return mixed
     */
    public function getLabelsForEveryIssueInMilestone(string $repo_full_name, int $milestones_number)
    {
        $url = $this->api_url.'/repos/'.$repo_full_name.'/milestones/'.$milestones_number.'/labels';

        return $this->curl->get($url);
    }
}
