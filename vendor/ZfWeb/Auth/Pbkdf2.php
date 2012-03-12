<?php

/**
 * Specialized authentication adapter for Crowd
 * 
 * Extends DbTable auth adapter to add ability to introspect stored credential 
 * in order to compare it to the passed credential, utilizing a PBKDF2 algorithm.
 */
class ZfWeb_Auth_Pbkdf2 extends Zend_Auth_Adapter_DbTable
{
    public function authenticate()
    {
        $dbSelect = clone $this->getDbSelect();
        $dbSelect->from($this->_tableName, array('*'))
                 ->where($this->_zendDb->quoteIdentifier($this->_identityColumn, true) . ' = ?', $this->_identity);
        $resultIdentities = $this->_authenticateQuerySelect($dbSelect);

        if (1 > count($resultIdentities)) {
            return new Zend_Auth_Result(
                Zend_Auth_Result::FAILURE_IDENTITY_NOT_FOUND,
                $this->_identity,
                array('A record with the supplied identity could not be found.')
            );
        }
        if (1 < count($resultIdentities)) {
            return new Zend_Auth_Result(
                Zend_Auth_Result::FAILURE_IDENTITY_AMBIGUOUS,
                $this->_identity,
                array('More than one record matches the supplied identity.')
            );
        }

        $result        = array_shift($resultIdentities);
        $encryptedPass = $result['credential'];
        $metadata      = $this->getHashAndSaltFromEncryptedPassword($encryptedPass);
        $credential    = $this->strHashPbkdf2($this->_credential, $metadata['salt'], 10000, 32, 'sha1');
        if ($credential !== $metadata['hash']) {
            return new Zend_Auth_Result(
                Zend_Auth_Result::FAILURE_CREDENTIAL_INVALID,
                $this->_identity,
                array('Supplied credential is invalid.')
            );
        }

        $this->_resultRow = $result;
        return new Zend_Auth_Result(
            Zend_Auth_Result::SUCCESS,
            $this->_identity,
            array('Authentication successful.')
        );
    }

    /**
     * PBKDF2 Implementation (described in RFC 2898)
     *
     * @param   string  p   password
     * @param   string  s   salt
     * @param   int     c   iteration count (use 1000 or higher)
     * @param   int     kl  derived key length
     * @param   string  a   hash algorithm
     * @param   int     st  start position of result
     *
     * @return  string  derived key
     */
    protected function strHashPbkdf2($password, $salt, $count, $keyLen, $alg = 'sha256', $start = 0)
    {
        $keyBlock   = $start + $keyLen; // Key blocks to compute
        $keyDerived = '';               // Derived key

        // Create key
        for ($block = 1; $block <= $keyBlock; $block++) {
            // Initial hash for this block
            $iterBlock = $hash = hash_hmac($alg, $salt . pack('N', $block), $password, true);

            // Perform block iterations
            for ($i = 1; $i < $count; $i++) {
                // XOR each iterate
                $iterBlock ^= ($hash = hash_hmac($alg, $hash, $password, true));
            }

            $keyDerived .= $iterBlock; // Append iterated block
        }

        // Return derived key of correct length
        return substr($keyDerived, $start, $keyLen);
    }

    protected function getHashAndSaltFromEncryptedPassword($encrypted)
    {
        $base64 = substr($encrypted,9);
        $plain  = base64_decode($base64);
        $salt   = substr($plain,0,16);
        $hash   = substr($plain,16);
        return array(
            'salt' => $salt,
            'hash' => $hash,
        );
    }
}
