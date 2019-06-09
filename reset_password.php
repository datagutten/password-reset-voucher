<?php
/**
 * Created by PhpStorm.
 * User: Anders
 * Date: 29.04.2019
 * Time: 17.34
 */
class reset_password
{
    public $logger;
    public $adtools;
    public $messages;
    function __construct()
    {
        $this->logger=new logger('password-voucher');
        try {
            $this->adtools = new adtools('reset');
        } catch (Exception $e) {
            $this->logger->writelog(array('Error initializing adtools', $e->getMessage()));
        }
        $this->messages = require 'messages.php';
    }

    /**
     * @param $user_dn
     * @param $voucher
     * @return string The generated password
     * @throws Exception
     */
    function reset_password($user_dn, $voucher)
    {
        $password = generate_password();
        $voucher_file = check_voucher($voucher);
        if ($voucher_file === false)
            throw new Exception($this->messages['invalid_voucher']);
        else
            $this->logger->writelog($voucher_file);

        try {
            $this->adtools->change_password($user_dn, $password);
            $this->logger->writelog(array('Password reset', $user_dn, $voucher));
            return $password;
        } catch (LdapException $e) {
            $this->logger->writelog(array('Error from adtools', $e->getMessage(), $user_dn, $voucher));
            throw new Exception($this->messages['error']);
        }
    }

    /**
     * @param string $mobile Mobile number
     * @param string $remove_prefix Prefix to be removed from number
     * @return array User information from AD
     * @throws Exception
     */
    function find_user_mobile($mobile, $remove_prefix = '+47')
    {
        $remove_len = strlen($remove_prefix);
        if(!empty($remove_prefix) && substr($mobile, 0, $remove_len)==$remove_prefix)
            $mobile = substr($mobile, $remove_len);

        $options = array('attributes'=>array('displayname', 'sAMAccountName'));
        try {
            $user = $this->adtools->ldap_query(sprintf('(mobile=%s)', $mobile), $options);
        }
        catch (NoHitsException $e) {
            $this->logger->writelog(array($e->getMessage()));
            throw new Exception($this->messages['not_found']);
        }
        catch (MultipleHitsException $e) {
            $this->logger->writelog(array($e->getMessage()));
            throw new Exception($this->messages['multiple_users']);
        }
        catch (LdapException $e) {
            $this->logger->writelog(array($e->getMessage(), $e->getTraceAsString()));
            throw new Exception($this->messages['error']);
        }
        catch (Exception $e) {
            $this->logger->writelog(array($e->getMessage(), $e->getTraceAsString()));
            throw new Exception($this->messages['error']);
        }

        return $user;
    }

    /**
     * @return string Message to be returned to user
     */
    function reset_password_sms()
    {
        if (empty($_POST))
            return null;

        $this->logger->writelog(array('SMS received') + $_POST);

        try {
            $user = $this->find_user_mobile($_POST['sender']);
        }
        catch (Exception $e)
        {
            return $e->getMessage();
        }

        if(is_string($user))
            return $user;
        else
        {
            try {
                $password = $this->reset_password($user['dn'], $_POST['argument']);
                return sprintf($this->messages['reset'], $user['displayname'][0], $password);
            }
            catch (Exception $e)
            {
                $this->logger->writelog($e->getMessage());
                return $e->getMessage();
            }
        }
    }
}