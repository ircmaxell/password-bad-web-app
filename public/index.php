<?php

require_once __DIR__ . '/../vendor/autoload.php';

$users = require_once __DIR__ . '/../data/users.php';
$posts = require_once __DIR__ . '/../data/posts.php';

$app = new Silex\Application;
$app['debug'] = true;

$app->register(new Silex\Provider\TwigServiceProvider, array(
    'twig.path' => __DIR__ . '/../views',
));

$app->get('/', function() use ($app, $posts) {
    $message = '';
    if (!empty($_GET['message']) && $_GET['message'] == 'login') {
        $message = 'You have been logged in!';
    }
    return $app['twig']->render('list.twig', array( 
        'message' => $message,
        'title' => 'All Posts',
        'posts' => $posts 
    ));
});

$app->get('/sqli', function() use ($app, $posts, $users) {
    $tmp = $posts;
    foreach ($users as $user) {
        $tmp[] = array(
            'id' => $user['id'],
            'title' => $user['name'],
            'body' => $user['password'],
        );
    }
    return $app['twig']->render('list.twig', array( 
        'title' => 'Injected!!!',
        'posts' => $tmp 
    ));
});

$app->get('/posts/{id}', function(Silex\Application $app, $id) use ($posts) {
    if (!isset($posts[$id])) {
        $app->abort(404, "Post $id does not exist");
    }
    $post = $posts[$id];

    return $app['twig']->render('post.twig', array( 'post' => $post ));
});

$app->get('/login', function() use ($app) {
    $args = array();
    if (isset($_GET['message']) && $_GET['message'] == 'error') {
        $args['message'] = 'Invalid username or login provided';
        $args['error'] = true;
    }
    return $app['twig']->render('login.twig', $args);
});

$app->post('/login', function() use ($app, $users) {
    $username = empty($_POST['username']) ? '' : $_POST['username'];
    $password = empty($_POST['password']) ? '' : $_POST['password'];
    
    $valid = false;
    foreach ($users as $user) {
        if ($user['name'] == $username) {
            if ($user['password'] == $password) {
                $valid = true;
            }
            break;
        }
    }
    if ($valid) {
        return $app->redirect('/?message=login');
    } else {
        return $app->redirect('/login?message=error');
    }
});

$app->run();