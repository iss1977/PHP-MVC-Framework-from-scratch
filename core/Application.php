<?php 

namespace app\core;



class Application
{
    // we define a root directory for our app
    public static string $ROOT_DIR;
    // we configure the user class in index.php. It should be not hardcoded for re-usability.
    public string $userClass;
    // the router will be stored in Application
    public Router $router;
    public Request $request;
    public Response $response;
    public Database $db;
    public ?DbModel $user; // ? = might be null if it's a guest

    public static Application $app;

    public Controller $controller;
    public Session $session;

    /**
     * @return Controller
     */
    public function getController(): Controller
    {
        return $this->controller;
    }

    /**
     * @param Controller $controller
     */
    public function setController(Controller $controller): void
    {
        $this->controller = $controller;
    }

    public function __construct($rootPath, array $config)
    {
        $this->userClass = $config['userClass']; // user class will not be hardcoded.

        self::$ROOT_DIR = $rootPath; // so we use always this directory in our project
        self::$app = $this; // ...so we can access the application from the router like this: Application::$app
        

        $this->request = new Request();
        $this->response = new Response();
        $this->session = new Session();
        $this->router = new Router($this->request, $this->response);

        $this->db = new Database($config['db']); // subarray "dn" will be only send

        $primaryValue = $this->session->get('user'); // will return the user ID saved in the session, or null if guest.
        if ($primaryValue) {
            $primaryKey = $this->userClass::primaryKey(); // will return 'id' generally
            $this->user =  $this->userClass::findOne([$primaryKey=>$primaryValue]); // we load the complete User object in the Application
        } else { // to prevent problems with accessing $user before init
            $this->user = null;
        }
    }

    /** Start the application. Will be called from index.php */
    public function run()
    {
        echo $this->router->resolve();
    }

    /** Login the user, set session key with user ID and save complete user data to Application
     * @param DbModel $user
     * @return bool
     */
    public function login(DbModel $user): bool
    {
        // we have to save the user in the session. we Will save the user ID, and on each load of a webpage we will
        $this->user = $user;
        $primaryKey = $user->primaryKey();
        $primaryKeyValue = $user->{$primaryKey};  // if the primary key of users is 'id', then $user object will contain a property named 'id'.
        $this->session->set('user', $primaryKeyValue);
        return true;
    }

    /** Logout the current user. */
    public function logout()
    {
        $this->user = null;
        $this->session->remove('user');
    }

    /** Used in menu to display register + login / user + logout */
    public static function isGuest(): bool
    {
        return !self::$app->user;
    }
}