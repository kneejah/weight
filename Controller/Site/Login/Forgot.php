<?php

class Controller_Site_Login_Forgot extends Abstract_Controller
{

    public function GET()
    {
        $policy = new Policy_LoggedOut($this->app);
        $policy->ensure();
    }

    public function POST()
    {
        $policy = new Policy_LoggedOut($this->app);
        $policy->ensure();

        $app = Config::get('app');
        $request = $this->app->request();

        $email = trim($request->post('email'));

        if (!$email) {
            $this->_error("Email address is a required field.");
        }

        $user_mapper = new Mapper_User();
        $user = $user_mapper->getUserByEmail($email);
        if (!$user) {
            $this->_error("No user with that email address exists.");
        }

        $newPass = substr(md5(mt_rand() . microtime(true)), 0, 10);

        $subject = "Your {$app->name} password has been reset";
        $body    = "Hey {$user['username']},<br /><br />"
        . "Your password has successfuly been reset.<br />"
        . 'It is now: "' . $newPass . '".' . "<br />"
        . 'You should <a href="http://www.trackly.me/account">log in</a>, and change it.' . "<br /><br />"
        . "Thanks,<br />"
        . "{$app->name}";

        $this->_email($email, $subject, $body);

        $user_mapper->updatePasswordForUser($user['id'], $newPass);

        $this->_success();
    }

    private function _email($email, $subject, $body)
    {
        $headers = 'From: Trackly <no-reply@trackly.me>' . "\r\n" .
                       'Reply-To: Trackly <no-reply@trackly.me>' . "\r\n" .
                       'X-Mailer: PHP/' . phpversion() . "\r\n" .
                       'MIME-Version: 1.0' . "\r\n" .
                       'Content-type: text/html; charset=iso-8859-1' . "\r\n";
        mail($email, $subject, $body, $headers);
    }

    private function _error($string)
    {
        Helper_Message::setError($this->app, $string);
        $this->app->redirect('/login/forgot');
        die();
    }

    private function _success()
    {
        Helper_Message::setSuccess($this->app, "Password successfuly reset. Check your email.");
        $this->app->redirect('/login/forgot');
        die();
    }

}
