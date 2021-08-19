<?php
defined('BASEPATH') or exit('No direct script access allowed');

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

class User extends CI_Model
{
    private function exist_user($email)
    {
        // Outputs: This is a plain-text message!
        //echo $this->encryption->decrypt($ciphertext);
        $result = $this->db->select('*')->where('email', $email)->get('users')->result();
        if (empty($result)) {
            return false;
        } else {
            return true;
        }
    }

    public function get_user($data)
    {
        $email = $data['email'];
        $pass = base64_encode($data['password']);
        $result = $this->db->select('u.*,ue.confirmed,ur.r_admin,ur.r_create,ur.r_update,ur.r_delete,ur.r_change_state,ur.r_edit_roles')
            ->join('user_roles ur', 'ur.id_user = u.id')
            ->join('user_email ue', 'ue.id_user = u.id')
            ->where('u.email', $email)
            ->where('u.password', $pass)
            ->get('users u')->result();

        if (empty($result)) {
            return ['user' => 0];
        } elseif ($result[0]->confirmed == 0) {
            return ['user' => 1];
        } else {
            return ['user' => $result];
        }
    }

    public function get_users()
    {
        $result = $this->db->select('u.*,ue.confirmed,ur.r_admin,ur.r_create,ur.r_update,ur.r_delete,ur.r_change_state,ur.r_edit_roles')
            ->join('user_roles ur', 'ur.id_user = u.id')
            ->join('user_email ue', 'ue.id_user = u.id')
            ->get('users u')->result();

        return ['users' => $result];
    }

    public function set_user($data)
    {
        $name = $data['name'];
        $lastname = $data['lastname'];
        $email = $data['email'];
        $pass = base64_encode($data['password']);

        if ($this->exist_user($email)) {
            return ['user' => 0];
        } else {
            //Guardo los datos del usuario
            $this->db->insert('users', ['name' => trim($name), 'last_name' => trim($lastname), 'email' => trim($email), 'password' => $pass]);
            //Obtengo el id del usuario
            $insert_id = $this->db->insert_id();
            //Aplico los permisos
            $this->db->insert('user_roles', ['id_user' => $insert_id, 'r_admin' => 0, 'r_create' => 0, 'r_update' => 0, 'r_delete' => 0, 'r_change_state' => 0, 'r_edit_roles' => 0]);
            //Genero la contraseña para la verificacion del correo
            $sha = rand(10000, 99999);
            $this->db->insert('user_email', ['id_user' => trim($insert_id), 'sha256' => $sha, 'confirmed' => 0]);
            //Envio el correo
            $this->send_email($name, $email, $sha, $insert_id);
            return ['user' => $data];
        }
    }

    private function send_email($name, $email, $sha, $user_id)
    {
        try {
            $mail = new phpmailer();
            $mail->isSMTP();
            $mail->Host = 'smtp.office365.com';
            $mail->Port = 587;
            $mail->SMTPSecure = 'tls';
            $mail->SMTPAuth = true;
            $mail->Username = 'enubestest@outlook.es';
            $mail->Password = 'Enubes13';
            $mail->SetFrom('enubestest@outlook.es', 'Enubes');
            $mail->addAddress($email, 'ToEmail');
            $mail->IsHTML(true);
            $mail->Subject = 'Gracias por registrarte en eNubes.';
            $url = site_url() . "enubes/confirmed/" . $user_id . '/' . $sha;
            $msg = "<b>Hola $name,</b><br>";
            $msg .= "<b>Gracias por registrarte en eNubes.</b><br>";
            $msg .= "<b>Por favor confirma tu cuenta haciendo click en el siguiente enlace <a href='$url'>Confirmar mi cuenta</a>.</b><br>";
            $mail->Body    = $msg;
            $mail->send();
        } catch (Exception $ex) {
            //Error
        }
        return ['user' => 1];
    }

    public function confirmed_user($user_id, $sha)
    {
        $result = $this->db->select('*')->where('id_user', $user_id)->where('sha256', $sha)->get('user_email')->result();
        if (!empty($result)) {
            $this->db->update('user_email', ['confirmed' => 1]);
        }
    }


    public function recovery($data)
    {
        $email = $data['email'];
        try {
            $mail = new phpmailer();
            $mail->isSMTP();
            $mail->Host = 'smtp.office365.com';
            $mail->Port = 587;
            $mail->SMTPSecure = 'tls';
            $mail->SMTPAuth = true;
            $mail->Username = 'enubestest@outlook.es';
            $mail->Password = 'Enubes13';
            $mail->SetFrom('enubestest@outlook.es', 'Enubes');
            $mail->addAddress($email, 'ToEmail');
            $mail->IsHTML(true);
            $mail->Subject = 'Recuperar contraseña eNubes.';
            $url = site_url() . "enubes/confirmed/" . $user_id . '/' . $sha;
            $msg = "<b>Hola,</b><br>";
            $msg .= "<b>Se a enviado este correo electronico por que se a solicitado la recuperación de la contraseña.</b><br>";
            $msg .= "<b>Si no has sido tu, simplemente ignora este mensaje.</b><br>";
            //$msg .= "<b>Por favor confirma tu cuenta haciendo click en el siguiente enlace <a href='$url'>Confirmar mi cuenta</a>.</b><br>";
            $mail->Body    = $msg;
            $mail->send();
        } catch (Exception $ex) {
            //Error
        }
        return ['user' => 1];
    }

    public function update_rol($data)
    {
        $id_user = $data['id_user'];
        $key_ = "";
        $value_ = "";
        foreach ($data as $key => $value) {
            if ($key == 'id_user') {
                continue;
            }
            $key_ = $key;
            $value_ = $value;
        }

        $this->db->where('id_user', $id_user)->update('user_roles', ["$key_" => "$value_"]);
        return ['user' => 1];
    }

    public function update_user()
    {
        return 'test model';
    }

    public function delete_user()
    {
        return 'test model';
    }
}
