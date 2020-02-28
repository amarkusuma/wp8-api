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
    }


    function fomPage()
    {

        ob_start();
        $this->html_form_code();
        $this->api = new Api();
        $this->api->post_data();
        return ob_get_clean();
    }
}
