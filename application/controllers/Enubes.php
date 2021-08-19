<?php
defined('BASEPATH') or exit('No direct script access allowed');

header('Access-Control-Allow-Origin: *');
header("Access-Control-Allow-Methods: GET,POST, OPTIONS");


class Enubes extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->library('encryption');
        $this->load->library('email');
        $this->load->helper('url');
        $this->load->model('user');
        $this->load->model('post');
    }

    /********************************************
     *
     *                USERS
     *
     *********************************************/

    public function set_user()
    {
        $post_data = $this->input->post();
        $response = $this->user->set_user($post_data);
        header('Content-Type: application/json');
        echo json_encode($response);
    }

    public function get_user()
    {
        $post_data = $this->input->post();
        $response = $this->user->get_user($post_data);
        header('Content-Type: application/json');
        echo json_encode($response);
    }

    public function get_users()
    {
        $response = $this->user->get_users();
        header('Content-Type: application/json');
        echo json_encode($response);
    }

    public function confirmed($id_user, $sha)
    {
        $this->user->confirmed_user($id_user, $sha);
        header('Location: http://localhost:3000/confirmed');
    }

    public function get_user_recovery()
    {
        $post_data = $this->input->post();
        $response = $this->user->recovery($post_data);
        header('Content-Type: application/json');
        echo json_encode($response);
    }

    public function update_rol()
    {
        $post_data = $this->input->post();
        $response = $this->user->update_rol($post_data);
        header('Content-Type: application/json');
        echo json_encode($response);
    }

    /********************************************
     *
     *                POSTS
     *
     *********************************************/

    public function get_posts()
    {
        $response = $this->post->get_posts();
        header('Content-Type: application/json');
        echo json_encode($response);
    }

    public function get_posts_id()
    {
        $post_data = $this->input->post();
        $response = $this->post->get_posts_id($post_data);
        header('Content-Type: application/json');
        echo json_encode($response);
    }

    public function set_posts()
    {
        $post_data = $this->input->post();
        $response = $this->post->set_post($post_data);
        header('Content-Type: application/json');
        echo json_encode($response);
    }

    public function update_post()
    {
        $post_data = $this->input->post();
        $response = $this->post->update_post($post_data);
        header('Content-Type: application/json');
        echo json_encode($response);
    }

    public function delete_posts()
    {
        $post_data = $this->input->post();
        $response = $this->post->delete_posts($post_data);
        header('Content-Type: application/json');
        echo json_encode($response);
    }

    public function get_data()
    {
        $response = $this->post->get_data();
        header('Content-Type: application/json');
        echo json_encode($response);
    }

    public function increment_visit()
    {
        $post_data = $this->input->post();
        $response = $this->post->increment_visits($post_data);
        header('Content-Type: application/json');
        echo json_encode($response);
    }
}
