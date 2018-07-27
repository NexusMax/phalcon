<?php
namespace App\Library;

use Phalcon\Mvc\User\Component;
use App\Models\Users;

/**
 * Vokuro\Auth\Auth
 * Manages Authentication/Identity Management in Vokuro
 */
class Auth extends Component
{

    /**
     * Creates the remember me environment settings the related cookies and generating tokens
     *
     * @param \Vokuro\Models\Users $user
     */
    public function createRememberEnvironment(Users $user)
    {
        $token = md5($user->email . $user->password . $this->request->getUserAgent());

        $user->remember_token = $token;

        if ($user->save() != false) {
            $expire = time() + 86400 * 8;
            $this->cookies->set('RMU', $user->id, $expire);
            $this->cookies->set('RMT', $token, $expire);
        }
    }

    /**
     * Check if the session has a remember me cookie
     *
     * @return boolean
     */
    public function hasRememberMe()
    {
        return $this->cookies->has('RMU');
    }

    /**
     * Logs on using the information in the cookies
     *
     * @return \Phalcon\Http\Response
     */
    public function loginWithRememberMe()
    {
        $userId = $this->cookies->get('RMU')->getValue();
        $cookieToken = $this->cookies->get('RMT')->getValue();

        $user = Users::findFirstById($userId);
        if ($user) {

            $token = md5($user->email . $user->password . $this->request->getUserAgent());

            if ($cookieToken == $token && $cookieToken == $user->remember_token) {

                $this->session->set('auth', [
                    'id' => $user->id,
                    'name' => $user->name,
                    'profile' => $this->acl->roles[$user->role_id]
                ]);

                return true;
            }
        }

        $this->cookies->get('RMU')->delete();
        $this->cookies->get('RMT')->delete();

        return $this->response->redirect('admin/session/login');
    }



    /**
     * Returns the current identity
     *
     * @return array
     */
    public function getIdentity()
    {
        return $this->session->get('auth');
    }

    /**
     * Returns the current identity
     *
     * @return string
     */
    public function getName()
    {
        return $this->getIdentity()['name'];
    }

    /**
     * Removes the user identity information from session
     */
    public function remove()
    {
        if ($this->cookies->has('RMU')) {
            $this->cookies->get('RMU')->delete();
        }
        if ($this->cookies->has('RMT')) {
            $token = $this->cookies->get('RMT')->getValue();

            $user = $this->findFirstByToken($token);
            $this->deleteToken($user);
            
            $this->cookies->get('RMT')->delete();
        }

        $this->session->remove('auth');
    }

    /**
     * Auths the user by his/her id
     *
     * @param int $id
     * @throws Exception
     */
    public function authUserById($id)
    {
        $user = Users::findFirstById($id);
        if ($user == false) {
            throw new Exception('The user does not exist');
        }

        $this->session->set('auth', [
            'id' => $user->id,
            'name' => $user->name,
            'profile' => $user->profile->name
        ]);
    }

    /**
     * Get the entity related to user in the active identity
     *
     * @return \Vokuro\Models\Users
     * @throws Exception
     */
    public function getUser()
    {
        $identity = $this->getIdentity();
        if (isset($identity['id'])) {

            $user = Users::findFirstById($identity['id']);
            if ($user == false) {
                throw new Exception('The user does not exist');
            }

            return $user;
        }

        return false;
    }
    
    /**
     * Returns the current token user
     *
     * @param string $token
     * @return boolean
     */
    public function findFirstByToken($token)
    {
        $user = Users::findFirst([
            'conditions' => 'remember_token = :token:',
            'bind'       => [
                'token' => $token,
            ],
        ]);
        
        $user = ($user) ? $user : false; 
        return $user;
    }


    /**
     * Delete the current user token in session
     */
    public function deleteToken($user) 
    {
        if ($user) {
            $user->remember_token = null;
            $user->save();
        }
    }


    public function login($credentials)
    {
        $user = Users::findFirst([
            "(email = :email: OR second_email = :email:) AND active = '1' AND banned = '0'",
            'bind' => [
                'email' => $credentials['email']
            ]
        ]);

        if ($user != false && $this->security->checkHash($credentials['password'], $user->password)) {

            if(isset($credentials['remember'])){
                $this->createRememberEnvironment($user);
            }
            $this->session->set('auth', [
                'id' => $user->id,
                'name' => $user->name,
                'profile' => $this->acl->roles[$user->role_id]
            ]);
            $this->flash->success('Welcome ' . $user->name);

            return true;
        }

        $this->flash->error('Wrong email/password');

        return false;
    }
}
