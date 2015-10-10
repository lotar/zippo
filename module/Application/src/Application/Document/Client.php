<?php

namespace Application\Document;

use Doctrine\ODM\MongoDB\Mapping\Annotations as ODM;

/**
 * @ODM\Document(collection="Clients" ,slaveOkay=false, repositoryClass="Application\Repository\Client")
 *
 * Class Client
 * @package Application\Document
 */
class Client extends Base
{

}
