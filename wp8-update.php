<?php

$wp8_update = new Wp8_update();
add_action('admin_menu', [$wp8_update, 'my_plugin_menu']);

class Wp8_update
{

    function my_plugin_menu()
    {
        // add_menu_page('Rest Api Options', 'Rest Api ', 'manage_options', 'my-menu', array($this, 'form_update'));

        add_submenu_page('my-menu', 'Form Update', 'Form Update', 'manage_options', 'update', array($this, 'form_update'));
    }


    function form_update()
    {
        if (isset($_POST['submit'])) {
            $id = $_GET['id'];
            $this->api = new Api();
            $this->api->update($id);
        }
        if (isset($_GET['id'])) {
            $id = $_GET['id'];
            $this->api = new Api();
            $data = $this->api->get_data_id($id);


            echo '<h3>Update Data Posts</h3>';
            echo '<form action="' . esc_url($_SERVER['REQUEST_URI'])  . '" method="post">';
            echo '<p>';
            echo 'Title  <br/>';
            echo '<input type="text" name="input-title" value="' . (isset($_POST["input-title"]) ? esc_attr($_POST["input-title"]) : $data->slug) . '" size="40" />';
            echo '</p>';
            // echo "<p>" . var_dump($data) . "</p>";
            echo '<p>';
            echo 'Content <br/>';
            echo '<textarea rows="5" cols="45" name="input-content">' . (isset($_POST["input-content"]) ? esc_attr($_POST["input-content"]) : $data->content->rendered) . '</textarea>';
            echo '</p>';


            echo '<p><input type="submit" name="submit" value="Send"></p>';
            echo '</form>';
        }
    }
}
