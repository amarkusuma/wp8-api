<?php

$form_api = new Form_api();
add_action('admin_menu', [$form_api, 'my_plugin_menu']);

class Form_api
{


    function get_data()
    {
        $response = wp_remote_get('http://localhost/wordpress/wp-json/wp/v2/posts');

        if (is_array($response)) {
            // $header = $response['headers'];
            $body = $response['body'];
            $data = json_decode($body);
        }
        return $data;
    }

    function delete_data($id)
    {
        $url = 'http://localhost/wordpress/wp-json/wp/v2/posts/' . $id;
        $args = array(
            'method' => 'DELETE',
            'headers'  => array(

                'Authorization' => 'Basic ' . base64_encode('admin: admin'),
            ),

        );
        $response =  wp_remote_request($url, $args);

        return $response;
    }



    function my_plugin_menu()
    {
        add_menu_page('Rest Api Options', 'Form Api ', 'manage_options', 'my-menu', array($this, 'my_plugin_options'));
    }

    function my_plugin_options()
    {
        if (!current_user_can('manage_options')) {
            wp_die(__('You do not have sufficient permissions to access this page.'));
        }


        if (isset($_GET['id'])) {
            $id = $_GET['id'];
            $this->delete_data($id);
        }
?>
        <br><br>
        <p>
        </p>
        <table border="1" cellpadding="0" cellspacing="0">
            <tr>

                <th>Id</th>
                <th>Title</th>
                <th>Content</th>
                <th>Action</th>
            </tr>

            <?php
            $rest_api = $this->get_data();
            foreach ($rest_api as $data) {
            ?>
                <tr>
                    <td width="7%">
                        <center><?php echo $data->id; ?> </center>
                    </td>
                    <td width="20%"><?php echo $data->slug; ?></td>
                    <td width="45%"><?php echo $data->content->rendered; ?></td>

                    <td width="20%">
                        <center>
                            <a href="<?php echo admin_url('admin.php?page=update') . '&id=' . $data->id ?>">Update</a> |
                            <!-- <a href="<?php echo 'http://localhost/wordpress/wp-json/wp/v2/posts/' . $data->id ?>">delete</a> -->
                            <a href="<?php echo admin_url('admin.php?page=my-menu') . '&id=' . $data->id ?>">delete</a>
                        </center>
                    </td>

                </tr>
            <?php
            }
            ?>
        </table>
<?php
    }
}
?>