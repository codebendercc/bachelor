<?php

namespace Codebender\UtilitiesBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Doctrine\ORM\EntityManager;
use Codebender\UtilitiesBundle\Entity\ReferralCode;

/**
 * @deprecated This can be removed from Bachelor as it is no longer needed
 */
class ReferralCodeController extends Controller
{
	protected $em;
//
//	public function listAction()
//	{
//
//		$codes = $this->em->getRepository('CodebenderUtilitiesBundle:ReferralCode')->findAll();
//
//		foreach($codes as $key => $code)
//		{
//			echo $code->getName();
//		}
//		return new Response("");
//	}
//
	/**
	 * Referral Code User
	 *
	 * @param String $referral_code
	 * @return JSON encoded success or failure
	 */
	public function useCodeAction($referral_code)
	{

		$codes = $this->em->getRepository('CodebenderUtilitiesBundle:ReferralCode')->findAll();

		/** @var ReferralCode $code */
		foreach ($codes as $code)
		{
			if($code->getCode() == $referral_code)
			{
				if($code->getIssued() != null)
				{
					if($code->getAvailable() > 0)
					{
						$code->setAvailable($code->getAvailable()-1);
						$this->em->persist($code);
						$this->em->flush();
						return new Response(json_encode(array("success" => true, "points" => $code->getPoints())));
					}
				}
				else
				{
					$code->setAvailable($code->getAvailable() - 1);
					$this->em->persist($code);
					$this->em->flush();
					return new Response(json_encode(array("success" => true, "points" => $code->getPoints())));
				}
			}
		}
		return new Response(json_encode(array("success"=>false)));
	}

    /**
     * Constructor
     * 
     * @param Doctrine\ORM\EntityManager $entityManager
     */
	public function __construct(EntityManager $entityManager)
	{
	    $this->em = $entityManager;
	}
}
