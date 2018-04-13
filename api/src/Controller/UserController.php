<?php

namespace App\Controller;

use App\Entity\User;
use Lexik\Bundle\JWTAuthenticationBundle\Encoder\JWTEncoderInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Core\Exception\BadCredentialsException;

class UserController extends Controller
{
    /**
     * Function d'enregistrement
     * @Route("/register", name="register")
     */
    public function register(Request $request, UserPasswordEncoderInterface $encoder)
    {
        $em = $this->getDoctrine()->getManager();
        $user = new User();
        $data = (object) $request->request->all();
        if ($request->files->count() == 1) {
            $file = $request->files->get('picture');
            $fileName = $this->generateUniqueFileName().'.'.$file->guessExtension();
            $file->move(
                $this->getParameter('profile_directory'),
                $fileName
            );
            $user->setPicture($fileName);
        }

        $user->setUsername($data->username);
        $user->setPassword($encoder->encodePassword($user, $data->password));
        $user->setEmail($data->email);


        $em->persist($user);
        $em->flush();

        return  new JsonResponse(array('message' => 'Utilisateur cree avec succes'));
    }

    /**
     * Function d'authentification
     * @Route("/login", name="login")
     */
    public function login(Request $request, UserPasswordEncoderInterface $encoder, JWTEncoderInterface $jwtEncoder )
    {
        $data = \GuzzleHttp\json_decode($request->getContent());

        $user = $this->getDoctrine()
            ->getRepository(User::class)
            ->loadUserByUsername($data->username);

        if (!$user) {
           throw $this->createNotFoundException();
        }

        $isValid = $encoder->isPasswordValid($user, $data->password);

        if (!$isValid) {
            throw new BadCredentialsException();
        }

        $token = $jwtEncoder->encode(['username' => $user->getUsername()]);

        return new JsonResponse(['token' => $token]);

    }

    /**
     * @return string
     */
    private function generateUniqueFileName()
    {
        return md5(uniqid());
    }


}
