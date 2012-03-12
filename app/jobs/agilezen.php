<?php
/**
 * Read the agilezen project data
 * 
 * Usage: agilezen.php [projectId] [output]\n
 * 
 * @author Enrico Zimuel (enrico@zend.com)
 */

if (count($argv)!==3) {
   echo "Usage: agilezen.php [projectId] [output]\n";
   exit;
}

$projectId = $argv[1];
$output = $argv[2];

require_once '/var/www/staging.framework.zend.com/vendor/Zf2/Zend/Loader/StandardAutoloader.php';

/** DURING INSTANTIATION **/
$loader = new \Zend\Loader\StandardAutoloader(array(
    // absolute directory
    'Zend' => '/var/www/staging.framework.zend.com/vendor/Zf2/Zend',
));
$loader->register(); 

use Zend\Service\AgileZen\AgileZen;


$apiKey='882636b35b4742edbe2b9c6261e2770d';

$agileZen = new AgileZen($apiKey);

$project = $agileZen->getProject($projectId);
if (!$agileZen->isSuccessful()) {
    die ('Error: ' . $agileZen->getErrorMsg());
}

$projectName = $project->getName();

// Get phases
$phases = $agileZen->getPhases($projectId);

if (!$agileZen->isSuccessful()) {
    die ('Error: ' . $agileZen->getErrorMsg());
}

$data = array();
foreach ($phases as $phase) {
    $params = array (
        'with' => 'details,tags'
    );
    $stories = $phase->getStories($params);
    $dataStory = array();
    if (!empty($stories)) {
        foreach ($stories as $story) {
            $tasks = $story->getTasks();
            $dataTasks = array();
            $completedTasks = 0;
            $totTasks = 0;
            if (!empty($tasks)) {
                $totTasks = count($tasks);
                foreach ($tasks as $task) {
                    $dataTasks[] = array (
                        'text'       => $task->getText(),
                        'status'     => $task->getStatus(),
                        'createTime' => $task->getCreateTime()
                    );
                    if (strtolower($task->getStatus()==='complete')) {
                        $completedTasks++;
                    }
                }    
            }
            $comments = $story->getComments();
            $dataComments = array();
            if (!empty($comments)) {
                foreach ($comments as $comment) {
                    $dataComments[] = array (
                        'text'   => $comment->getText(),
                        'author' => $comment->getAuthor()->getName()
                    );
                }   
            }
            $owner = $story->getOwner();
            $assignee = '';
            if (!empty($owner)) {
                $assignee = $owner->getName();
            }
            $rate = 0;
            if ($totTasks>0) {
                $rate = round(($completedTasks / $totTasks) * 100);
            }
            $tags = $story->getTags();
            $tag = array();
            foreach ($tags as $t) {
                $tag[] = $t->getName();
            }
            $dataStory[] = array (
                'text'           => $story->getText(),
                'details'        => $story->getDetails(),
                'id'             => $story->getId(),
                'assignee'       => $assignee,
                'tasks'          => $dataTasks,
                'totTasks'       => $totTasks,
                'completedTasks' => $completedTasks,
                'rateTasks'      => $rate,
                'comments'       => $dataComments,
                'tags'           => $tag
            );
        }
    }    
    $data[] = array (
        'phase'   => $phase->getName(),
        'stories' => $dataStory
    );
}

$data = array(
    'id'     => $projectId,
    'name'   => $projectName,
    'phases' => $data
);

if (false!==@file_put_contents($output, serialize($data))) {
    echo "OK\n";
} else {
    echo "ERROR\n";
}
