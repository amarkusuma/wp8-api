<?php

/*
Plugin Name: Rest Api 
Description: Rest Api Wordpress
Author: Ammar
*/

require_once('wp8-post.php');

$api = new Api();
add_shortcode('wp8_api', [$api, 'fomPage']);

class Api
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

    function show_data($data)
    {
?>
        <table border="1" cellpadding="0" cellspacing="0">
            <tr>
                <th>Id</th>
                <th>Title</th>
                <th>Name</th>
            </tr>
            <?php

            $display_data = $this->get_data();
            // echo "<p>" . var_dump($display_data) . "</p>";
            for ($i = 0; $i < $data; $i++) {
            ?>
                <tr>
                    <td><?php echo $display_data[$i]->id; ?></td>
                    <td width="25%"><?php echo $display_data[$i]->slug; ?></td>
                    <td width="65%"><?php echo $display_data[$i]->content->rendered; ?></td>
                </tr>
            <?php
            }

            ?>
        </table>
<?php
    }

    function fomPage($atts)
    {
        ob_start();
        $this->get_data();
        $value = shortcode_atts([
            'data' => '',
        ], $atts);
        $this->show_data($value['data']);
        return ob_get_clean();
    }
}
