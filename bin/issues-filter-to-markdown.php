<?php

require_once '/Users/ralphschindler/Projects/GitHubAPI/autoload.php';


function jira_filter_to_markdown($content) {
    $content = preg_replace('#({code.*})(\n+<\?php)#m', '```php$2', $content);
    $content = preg_replace('#{code.*}#', '```', $content);
    $content = preg_replace('#{/code}#', '```', $content);
    $content = preg_replace('#{noformat.*}#', '```', $content);
    return $content;
}

function jira_username_to_github_name($name, $displayName) {
    global $githubNames;
    static $usermap = array();
    
    if ($usermap === array()) {
        $usermap = include __DIR__ . '/data/users/map.php';
    }
    
    if (isset($usermap[$name])) {
        if (!in_array('@' . $usermap[$name], $githubNames)) {
            $githubNames[] = '@' . $usermap[$name];
        }
        return $usermap[$name];
    }
    
    return ($displayName != '') ? $displayName : $name;
}

function jira_issue_to_github_issue(array $ji, array $components, $ghOwner, $ghRepo, array $ghApiCreds) {
    global $githubNames;
    
    $githubNames = array();
    
    ob_start();
    ?>

#### Jira Information

<table>
    <tr>
        <td>Original Issue:</td><td><a href="http://framework.zend.com/issues/browse/<?php echo $ji['key'] ?>"><?= $ji['key'] ?></a></td>
    </tr>
    <tr>
        <td>Issue Type:</td><td><?php echo $ji['fields']['issuetype']['value']['name']?></td>
    </tr>
    <tr>
        <td>Reporter:</td><td><?php
            echo jira_username_to_github_name(
                $ji['fields']['reporter']['value']['name'],
                $ji['fields']['reporter']['value']['displayName']
            ); 
        ?></td>
    </tr>
    <tr>
        <td>Created:</td><td><?php echo date('Y-m-d', strtotime($ji['fields']['created']['value'])) ?></td>
    </tr>
    <tr>
        <td>Assignee:</td><td><?php
            echo jira_username_to_github_name(
                $ji['fields']['assignee']['value']['name'],
                $ji['fields']['assignee']['value']['displayName']
            );
        ?></td>
    </tr>
    <tr>
        <td>Components:</td><td><?php 
        foreach ($ji['fields']['components']['value'] as $component) { 
            echo $component['name'] . ' '; 
        } 
        ?></td>
    </tr>

</table>


#### Description

<?php echo jira_filter_to_markdown($ji['fields']['description']['value']) ?>


<?php

    $markdown = ob_get_clean();


    $issueApi = new GitHubAPI\Issue\IssueAPI($ghApiCreds);
    $commentApi = new GitHubAPI\Issue\CommentAPI($ghApiCreds);

    $issue = new GitHubAPI\Issue\Issue;
    $issue->setState('open');
    $issue->setTitle($ji['fields']['summary']['value']);


    // create labels for components
    $labels = array();
    foreach ($components as $component) {
        $labels[] = $component;
    }

    switch ($ji['fields']['issuetype']['value']['name']) {
        case 'Bug':
            $labels[] = 'bug';
            break;
        case 'Improvement':
            $lables[] = 'enhancement';
            break;
    }

    if (count($labels) > 0) {
        $issue->setLabels($labels);        
    }

    $issue->setBody($markdown);

    try {
        $issueApi->createIssueWithEntity($ghOwner, $ghRepo, $issue);
    
        $issueNum = $issue->getNumber();
        foreach ($ji['fields']['comment']['value'] as $comment) {
            $commentMd = '(Originally posted by: ' . jira_username_to_github_name($comment['author']['name'], $comment['author']['displayName'])
                . ' on ' . date('m/d/y', strtotime($comment['created'])) . ')' . "\n\n"
                . jira_filter_to_markdown($comment['body']);
            $commentApi->createComment($ghOwner, $ghRepo, $issueNum, $commentMd);
        }
    
        $ghNamesString = implode(', ', $githubNames);
        $jiraIssueNum = $ji['key'];
        $commentApi->createComment($ghOwner, $ghRepo, $issueNum, <<<EOS
This issue was ported from the ZF2 Jira Issue Tracker at
http://framework.zend.com/issues/browse/{$jiraIssueNum}

Known GitHub users mentioned in the original message or comment:
{$ghNamesString}
EOS
);
    
    } catch (Exception $e) {
        echo 'Exception has occurred ' . $e->getMessage();
        var_dump($e->getPrevious());
    }
    
}
