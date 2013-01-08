<?php

require_once __DIR__ . '/../vendor/autoload.php';
require_once __DIR__ . '/../lib/password.php';
require_once __DIR__ . '/../lib/user.php';

$users = require_once __DIR__ . '/../data/users.php';
$posts = require_once __DIR__ . '/../data/posts.php';

$app = new Silex\Application;
$app['debug'] = true;

$app->register(new Silex\Provider\TwigServiceProvider, array(
    'twig.path' => __DIR__ . '/../views',
));


/**
 * The basic views
 *
 * There isn't much of our concern here, all the interesting stuff happens later
 */
$app->get('/', function() use ($app, $posts) {
    $message = '';
    if (!empty($_GET['message'])) {
        if ($_GET['message'] == 'login') {
            $message = 'You have been logged in!';
        } elseif ($_GET['message'] == 'register') {
            $message = 'You have been registered!';
        }
    }
    return $app['twig']->render('list.twig', array( 
        'message' => $message,
        'title' => 'All Posts',
        'posts' => $posts 
    ));
});

$app->get('/posts/{id}', function(Silex\Application $app, $id) use ($posts) {
    if (!isset($posts[$id])) {
        $app->abort(404, "Post $id does not exist");
    }
    $post = $posts[$id];

    return $app['twig']->render('post.twig', array( 'post' => $post ));
});

/**
 * A dummy route that simulates a successful SQL injection
 *
 * This route simulates the following Injection:
 *
 * ' UNION SELECT name as title, password as body FROM users
 *
 * Since we're not using an SQL database here, the SQLI would be impossible
 * But for the sake of the demo, this is the next result.
 *
 * It shows what could happen with a paginated home page that had a vulnerability
 */
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
        'message' => 'Injected!!!',
        'error' => true,
        'posts' => $tmp 
    ));
});

/**
 * The authentication routes!
 *
 * The GET login route renders the form
 * The POST login route verifies the form submission
 *
 * We're cheating here, because we're not using sessions. All it does is show messages
 *
 * Also note that the password "verification" happens in a function
 * see /lib/password.php for the current definitions of those functions
 */
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
    if (login($username, $password)) {
        $value = true;
    }
    foreach ($users as $user) {
        if ($user['name'] == $username) {
            if (verifyPassword($user['password'], $password)) {
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

/**
 * The registration routes!
 *
 * The GET route renders the form
 * The POST route handles registration
 *
 */
$app->get('/register', function() use ($app) {
    $msg = empty($_GET['message']) ? '' : $_GET['message'];
    $message = '';
    switch ($msg) {
        case 'taken':
            $message = 'Username is taken';
            break;
    }
    return $app['twig']->render('register.twig', array('message' => $message, 'error' => true));
});

$app->post('/register', function() use ($app, $users) {
    $username = empty($_POST['username']) ? '' : $_POST['username'];
    $password = empty($_POST['password']) ? '' : $_POST['password'];
    $password2 = empty($_POST['password2']) ? '' : $_POST['password2'];
    if ($password != $password2) {
        return $app->redirect('/register?message=match');
    }
    if (findUserByName($username)) {
        return $app->redirect('/register?message=taken');
    }
    $user = array(
        'name' => $username,
        'password' => createPassword($password),
    );
    writeUser($user);
    saveUsers();
    return $app->redirect('/?message=register');
});

$app->run();