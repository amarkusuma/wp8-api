<?php

/*
Plugin Name: Rest Api 
Description: Rest Api Wordpress
Author: Ammar
*/

require_once('wp8-post.php');
require_once('wp8-form.php');
require_once('wp8-update.php');

$api = new Api();
add_shortcode('wp8_api', [$api, 'fomPage']);

class Api
{

    function get_data()
    {
        $url = site_url() . '/wp-json/wp/v2/posts';
        $response = wp_remote_get($url);

        if (is_array($response)) {
            // $header = $response['headers'];
            $body = wp_remote_retrieve_body($response);
            $data = json_decode($body);
        }
        return $data;
    }

    function get_data_id($id)
    {
        $url = site_url() . '/wp-json/wp/v2/posts/' . $id;
        $response = wp_remote_get($url);

        if (is_array($response)) {
            // $header = $response['headers'];
            $body = wp_remote_retrieve_body($response);
            $data = json_decode($body);
        }
        return $data;
    }


    function delete_data($id)
    {
        $url = site_url() . '/wp-json/wp/v2/posts/' . $id;

        $args = array(
            'method' => 'DELETE',
            'headers'  => array(

                'Authorization' => 'Basic ' . base64_encode('admin: admin'),
            ),

        );
        $response =  wp_remote_request($url, $args);

        return $response;
    }

    function post_data()
    {
        $url = site_url() . '/wp-json/wp/v2/posts';

        if (isset($_POST['submit'])) {
            $title = isset($_POST['input-title']) ? sanitize_text_field($_POST['input-title']) : '';
            $content = isset($_POST['input-content']) ? sanitize_text_field($_POST['input-content']) : '';

            $response = wp_remote_post(
                $url,
                array(
                    'method' => 'POST',
                    'timeout'     => 45,
                    'redirection' => 5,
                    'httpversion' => '1.0',
                    'blocking'    => true,
                    'headers'  => array(

                        'Authorization' => 'Basic ' . base64_encode('admin: admin'),
                    ),
                    'body' => array('title' => $title, 'content' => $content, 'status' => 'publish'),
                    'cookies' => array()
                )
            );

            if (is_wp_error($response)) {
                $error_message = $response->get_error_message();
                echo "Something went wrong: $error_message";
            } else {
                echo 'Success <pre>';
                // print_r($response);
                echo '</pre>';
            }
        }
    }

    function update($id)
    {
        $url = site_url() . '/wp-json/wp/v2/posts/' . $id;


        if (isset($_GET['id'])) {
            $title = isset($_POST['input-title']) ? sanitize_text_field($_POST['input-title']) : '';
            $content = isset($_POST['input-content']) ? sanitize_text_field($_POST['input-content']) : '';

            $response = wp_remote_post(
                $url,
                array(
                    'method' => 'POST',
                    'timeout'     => 45,
                    'redirection' => 5,
                    'httpversion' => '1.0',
                    'blocking'    => true,
                    'headers'  => array(

                        'Authorization' => 'Basic ' . base64_encode('admin: admin'),
                    ),
                    'body' => array('slug' => $title, 'content' => $content, 'status' => 'publish'),
                    'cookies' => array()
                )
            );

            if (is_wp_error($response)) {
                $error_message = $response->get_error_message();
                echo "Something went wrong: $error_message";
            } else {
                echo 'Success <pre>';
                // print_r($response);
                echo '</pre>';
            }
            return $response;
        }
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
