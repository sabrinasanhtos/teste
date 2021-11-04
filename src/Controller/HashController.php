<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpKernel\Exception\TooManyRequestsHttpException;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\String\CodePointString;
use Symfony\Component\String\UnicodeString;
use Symfony\Component\RateLimiter\Limiter;
use Symfony\Component\RateLimiter\RateLimiterFactory;
use Symfony\Component\String\ByteString;
use App\Entity\Hash;
use App\Repository\HashRepository;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\Serializer\SerializerInterface;

class HashController extends AbstractController
{
    public $hash;
    public $chave;

    /**
     * @Route(
     *  "/create/{input}",
     *  name="createHash", methods={"GET"},
     *  requirements={"inputString"="\d+"},
     * )
    */
    public function create(
        string $input, 
        Request $request,
        RateLimiterFactory $anonymousApiLimiter
    ): Response
    { 
        $limiter = $anonymousApiLimiter->create($request->getClientIp());

        if (false === $limiter->consume(1)->isAccepted()) {
            return $this->json(["menssage" => "Too ManyAttempts"], Response::HTTP_TOO_MANY_REQUESTS);
        }

        for ($tentativas = 1; ; $tentativas++){
            $this->chave = ByteString::fromRandom(8);
            $this->hash = md5($input.$this->chave) ;

            if(substr($this->hash, 0, 4) === '0000')
                break;
        }

        $obj = new Hash();
        $obj->setString($input);
        $obj->setChave($this->chave);
        $obj->setHash($this->hash);
        $obj->setTentativas($tentativas);
        $obj->setbatch(new \DateTime("now", new \DateTimeZone("America/Sao_Paulo")));
        $doctrine = $this->getDoctrine()->getManager();
        $doctrine->persist($obj);
        $doctrine->flush();

        return $this->json([
            'hash' => $this->hash,
            'key' => $this->chave,
            'tentativas' => $tentativas
        ]);
    }

    /**
     * @Route(
     *  "/list/{filterTentativas}/{page}",
     *  name="listHashs", methods={"GET"},
     *  requirements={"filterTentativas"="\d+", "page": "\w+"},
     * )
    */
    public function list(
        Request $request,
        int $filterTentativas, 
        HashRepository $hashRepository, 
        PaginatorInterface $paginator,
        SerializerInterface $serializer
    )
    {
        $result = $hashRepository->findHashTentativas($filterTentativas);
        if ($result === null) {
            return $this->json(["menssage" => "there are no hash exist"], Response::HTTP_NOT_FOUND);
        }
        
        $result =  $paginator->paginate($result, $request->query->getInt('page', 1), 10);
        $person = [
            'pageCount' => $result->getPaginationData()['pageCount'],
            'totalCount' => $result->getPaginationData()['totalCount'],
            'current' => $result->getPaginationData()['current'],
            'numItemsPerPage' => $result->getPaginationData()['numItemsPerPage'],
            'data' => $result->getItems()
        ];

        $serializer->serialize($person, 'json');
        return $this->json($person, Response::HTTP_OK);
    }
}
