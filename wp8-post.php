<?php

$api_post = new Api_post();
add_shortcode('wp8_api_post', [$api_post, 'fomPage']);

class Api_post
{

    function html_form_code()
    {

        echo '<form action="' . esc_url($_SERVER['REQUEST_URI']) . '" method="post">';
        echo '<p>';
        echo 'Title  <br/>';
        echo '<input type="text" name="input-title" value="' . (isset($_POST["input-title"]) ? esc_attr($_POST["input-title"]) : '') . '" size="40" />';
        echo '</p>';

        echo '<p>';
        echo 'Content <br/>';
        echo '<input type="text" name="input-content" value="' . (isset($_POST["input-content"]) ? esc_attr($_POST["input-content"]) : '') . '" size="40" />';
        echo '</p>';


        echo '<p><input type="submit" name="submit" value="Send"></p>';
        echo '</form>';

        // wp_redirect( site_url('/') ); // <-- here goes address of site that user should be redirected after submitting that form
        // die;

    }

    function post_data()
    {
        $url = wp_remote_post('http://localhost/wordpress/wp-json/wp/v2/posts');

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
                        'Content-type: application/x-www-form-urlencoded',
                    ),
                    'body' => array('title' => $title, 'content' => $content),
                    'cookies' => array()
                )
            );

            if (is_wp_error($response)) {
                $error_message = $response->get_error_message();
                echo "Something went wrong: $error_message";
            } else {
                echo 'Response:<pre>';
                print_r($response);
                echo '</pre>';
            }
        }
    }

    function fomPage()
    {

        ob_start();
        $this->html_form_code();
        $this->post_data();
        return ob_get_clean();
    }
}
