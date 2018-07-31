<?php

namespace Nateev\Fastmag;

use Curl;

/**
 * Fastmag EDI class
 *
 * EDI Documentation: http://docs.fastmag.fr/EdiWebSRV%20-%20Interface%20EDI%20entre%20Fastmag%20et%20un%20site%20web%20ecommerce.pdf
 * Class Documentation: https://github.com/nateev/Fastmag-PHP-EDI
 *
 * @author Mickael Fradin (Nateev)
 * @author Julien Perez (Nateev)
 * @author Loic Bertholet (Nateev)
 * @since 20.07.2017
 * @copyright Nateev -  2017
 * @version 1.0
 * @license GNU https://www.gnu.org/licenses/licenses.fr.html
 */

class Fastmag {
    /**
     * The Fastmag connector for EDI transaction
     *
     * @var string
     */
    private $ediWebConnector;

    /**
     * The Fastmag connector for EDI query
     *
     * @var string
     */
    private $ediQuery;
    /**
     * The Fastmag connector for EDI photo
     *
     * @var string
     */
    private $ediPhoto;

    /**
     * The Fastmag ftp directory
     *
     * @var string
     */
    private $directory;

    /**
     * The Fastmag Client's business name
     *
     * @var string
     */
    private $businessName;

    /**
     * The Fastmag Client's Store
     *
     * @var string
     */
    private $store;

    /**
     * The Fastmag Client's account
     *
     * @var string
     */
    private $account;

    /**
     * The Fastmag Client's password
     *
     * @var string
     */
    private $password;

    /**
     * The Fastmag Edi Data
     *
     * @var array
     */
    private $data;

    /**
     * @return string
     */
    public function getEdiWebConnector()
    {
        return $this->ediWebConnector;
    }

    /**
     * @param string $ediWebConnector
     */
    public function setEdiWebConnector($ediWebConnector)
    {
        $this->ediWebConnector = $ediWebConnector;
    }

    /**
     * @return string
     */
    public function getEdiQuery()
    {
        return $this->ediQuery;
    }

    /**
     * @param string $ediQuery
     */
    public function setEdiQuery($ediQuery)
    {
        $this->ediQuery = $ediQuery;
    }
    /**
     * @return string
     */
    public function getEdiPhoto()
    {
        return $this->ediPhoto;
    }

    /**
     * @param string $ediQuery
     */
    public function setEdiPhoto($ediPhoto)
    {
        $this->ediPhoto = $ediPhoto;
    }

    /**
     * @return string
     */
    public function getDirectory()
    {
        return $this->directory;
    }

    /**
     * @param string $directory
     */
    public function setDirectory($directory)
    {
        $this->directory = $directory;
    }

    /**
     * @return string
     */
    public function getBusinessName()
    {
        return $this->businessName;
    }

    /**
     * @param string $businessName
     */
    public function setBusinessName($businessName)
    {
        $this->businessName = $businessName;
    }

    /**
     * @return string
     */
    public function getStore()
    {
        return $this->store;
    }

    /**
     * @param string $store
     */
    public function setStore($store)
    {
        $this->store = $store;
    }

    /**
     * @return string
     */
    public function getAccount()
    {
        return $this->account;
    }

    /**
     * @param string $account
     */
    public function setAccount($account)
    {
        $this->account = $account;
    }

    /**
     * @return string
     */
    public function getPassword()
    {
        return $this->password;
    }

    /**
     * @param string $password
     */
    public function setPassword($password)
    {
        $this->password = $password;
    }

    /**
     * @return string
     */
    public function getData()
    {
        return $this->data;
    }

    /**
     * @param string $data with this query or transaction information :
     */
    public function setData($data)
    {
        $this->data = $data;
    }

    /**
    *
    * @param string $businessName
    * @param string $store
    * @param string $account
    * @param string $password
    *
    **/
    public function __construct($businessName, $store, $account, $password, $ediQueryConnector, $ediWebConnector, $ediPhotoConnector)
    {
        $this->setBusinessName($businessName);
        $this->setStore($store);
        $this->setAccount($account);
        $this->setPassword($password);
        $this->setEdiWebConnector($ediWebConnector);
        $this->setEdiQuery($ediQueryConnector);
        $this->setEdiPhoto($ediPhotoConnector);

    }

    /**
    * Function to create a curl request with curl librairie
    *
    * @param string $connector
    * @param string $type
    */
    public function curlCreateRequest($data, $connector=false) {
        $params = [
            'enseigne' => $this->getBusinessName(),
            'magasin' =>$this->getStore(),
            'compte' => $this->getAccount(),
            'motpasse' => $this->getPassword(),
            'data' => $data
        ];
        $curl = new Curl\Curl();
        $curl->setOpt(CURLOPT_SSL_VERIFYPEER, FALSE);
        $curl->setOpt(CURLOPT_SSL_VERIFYHOST, FALSE);
        $curl->setHeader('charset', 'UTF-8');
        $curl->setHeader('Content-type', 'text/html');
        switch ($connector) {
            case 'ediwebsrv':
                $url = $this->getEdiWebConnector();
                break;
            case 'ediquery':
                $url = $this->getEdiQuery();
                break;
        }
        $result = $curl->post($url, $params);
        if ($result->error) {
            error_log($result->error_message);
        }
        return $result;
    }

    /**
     * @param array $data
     *
     * */
    public function curlCreateRequestForPicture($data) {
        $curl = new Curl\Curl();
        $curl->setOpt(CURLOPT_HEADER, 0);
        $curl->setOpt(CURLOPT_RETURNTRANSFER, 1);
        $curl->setOpt(CURLOPT_BINARYTRANSFER, 1);
        $curl->setHeader('Content-type', 'image/jpeg');
        $url = $this->getEdiPhoto().$data['barcode'].'&couleur=&Numero='.$data['index'];
        $result = $curl->post($url);
        print_r($result);die;
    }
}
