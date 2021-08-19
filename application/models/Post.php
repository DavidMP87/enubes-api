<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Post extends CI_Model
{
    public function get_posts()
    {
        $result = $this->db->select('*')->get('posts')->result();
        return ['posts' => $result];
    }

    public function get_posts_id($data)
    {
        $id = $data['id'];
        $result = $this->db->select('*')->where('id', $id)->get('posts')->result();
        return ['post' => $result];
    }

    public function set_post($data)
    {
        $id_user = $data['user_id'];
        $title = $data['title'];
        $content = $data['content'];
        $state = $data['state'];
        $this->db->insert('posts', ['user_id' => $id_user, 'title' => trim($title), 'content' => trim($content), 'state' => $state]);
        return ['post' => 1];
    }

    public function update_post($data)
    {
        $id = $data['id'];
        $id_user = $data['user_id'];
        $title = $data['title'];
        $content = $data['content'];
        $state = $data['state'];
        $this->db->where('id', $id)->update('posts', ['user_id' => $id_user, 'title' => trim($title), 'content' => trim($content), 'state' => $state]);
        return ['post' => 1];
    }

    public function delete_posts($data)
    {
        $id = $data['id'];
        $this->db->where('id', $id)->delete('posts');
        return ['post' => 1];
    }

    public function get_data()
    {
        $responsePosts = $this->db->select('id ,visits')->get('posts')->result();
        $responseUsers = $this->db->select('*')->get('users')->result();
        return ['posts' => $responsePosts, 'users' => $responseUsers];
    }

    public function increment_visits($data)
    {
        $id = $data['id'];
        $result = $this->db->select('visits')->where('id', $id)->get('posts')->result();
        $visitas = $result[0]->visits + 1;
        $this->db->where('id', $id)->update('posts', ['visits' => $visitas]);
        return ['post' => 1];
    }
}
