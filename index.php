<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
ini_set('error_reporting', E_ALL);

class WowTest {

    const servername = "localhost";
    const username = "wowtest";
    const password = "12345";
    const database = "wowtest";

    public $db;

    public function __construct() {
        $this->dbConnect();
    }

    public function dbConnect () {

        $this->db = new mysqli(self::servername, self::username, self::password, self::database);

        if ($this->db->connect_error) {
            die("Connection failed: " . $this->db->connect_error);
        }
    }

    public function display() { ?>
        <html>
        <head>
            <title>WOWVendor Test Task</title>
            <script type="text/javascript" src="script.js"></script>
        </head>

        <body>
            <?php
            $userRole = [
                0 => [
                    'name' => 'roleName',
                    'type' => 'text',
                ]
            ];
            $user = [
                0 => [
                    'name' => 'userName',
                    'type' => 'text',
                ],
                1 => [
                    'name' => 'roleName',
                    'type' => 'select',
                ],
            ];
            $this->formDisplay('usrRole', $userRole);
            $this->formDisplay('usr', $user);
            $this->usersDisplay(); ?>
        </body>

        </html>
    <?php }

    public function formDisplay($formName, $fields) { ?>
        <fieldset>
            <legend><?php echo $formName; ?></legend>
            <form id="<?php echo $formName; ?>" action="index.php" method="post">
                <input type="hidden" name="<?php echo $formName; ?>" value="1">
                <?php foreach ($fields as $field) {
                    switch ($field['type']) {
                        case 'select' :
                            $roles = [];
                            $sql = "SELECT * FROM user_role WHERE 1";
                            $rows = $this->db->query($sql);

                            while ($row = mysqli_fetch_array($rows, MYSQLI_ASSOC)) {
                                $roles[$row['id']] = $row['rolename'];
                            } ?>

                            <select name="<?php echo $field['name']; ?>">
                                <?php foreach ($roles as $value => $label ) { ?>
                                     <option label="<?php echo $label ?>" value="<?php echo $value ?>"><?php echo $label ?></option>
                                <?php } ?>
                            </select>

                            <?php break;

                        default: ?>
                            <input id="<?php echo $field['name']; ?>" name="<?php echo $field['name']; ?>" type="<?php echo $field['type']; ?>" />
                            <?php break;
                    }
                } ?>

                <input id="<?php echo $formName; ?>Submit" type="submit" />
            </form>
        </fieldset>
    <?php }

    public function usersDisplay () { ?>
        <button onclick="showUsers()">Показать пользователей</button>
        <div id="usersList">Пользователи будут показаны здесь</div>
    <?php }

    public function roleAdd () {
        $sql = "INSERT INTO user_role (rolename) VALUES ('" . $_POST['roleName'] . "')";
        $this->db->query($sql);
    }

    public function userAdd () {
        $sql = "INSERT INTO user (username, role_id) VALUES ('" . $_POST['userName'] . "', '" . $_POST['roleName'] . "')";
        $this->db->query($sql);
    }

    public function showUsers () {
        echo 'works';
        die;

        $result = [];
        $sql = "SELECT * FROM user
                INNER JOIN user_role ON (user.role_id = user_role.id)";
        $rows = $this->db->query($sql);

        while ($row = mysqli_fetch_array($rows, MYSQLI_ASSOC)) {
            print_r($row);
//          $result[][] = $row['rolename'];
        }

        return $result;
    }

    public function index () {
        if (isset($_POST['usrRole'])) {
            $this->roleAdd();
        }

        if (isset($_POST['usr'])) {
            $this->userAdd();
        }

        $this->display();
//        unset($_POST);
    }
}

$main = new WowTest();

$main->index();
?>