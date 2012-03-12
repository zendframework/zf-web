<?php
/**
 * Generates a set of dummy posts to test view script generation.
 */

for ($i = 1; $i <= 100; $i++) {
    $post = include(__DIR__ . '/../posts/2011-08-08-Welcome.php');

    $post->title .= '-v' . $i;
    $post->author = (array) $post->author;

    $date = new DateTime($post->date, new DateTimezone('America/Los_Angeles'));
    $date->add(new DateInterval('P' . $i . 'D'));
    $post->date = $date->format('Y-m-d H:i');

    $content = "<?php\n\$post = (object) " . var_export((array) $post, 1) . ";\nreturn \$post;";
    $script  = __DIR__ . '/../posts/' . $date->format('Y-m-d') . '-Welcome-v' . $i . '.php';
    file_put_contents($script, $content);
}
