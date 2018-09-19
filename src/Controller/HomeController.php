<?php

namespace App\Controller;

use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

#can instantiate the entity
use App\Entity\User;

#can use entity's form
use App\Form\UserMyaccountType;
use App\Form\UserMyaccountEnrollType;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class HomeController extends Controller
{

    # the following is created to encode the password
    private $encoder;
    public function __construct(UserPasswordEncoderInterface $encoder)
    {
      $this->encoder = $encoder;
    }

    /**
     * @Route("/", name="index")
     */
    public function index()
    {
        if ($this->getUser()->getUsertype() === 'ROLE_PARENT') {
          return $this->redirectToRoute("myaccount");
        } else {
        return $this->render('home/index.html.twig', [
            //'controller_name' => 'HomeController',
          ]);
        }
    }

    /**
     * @Route("/account", name="myaccount")
     */
    public function index_parent()
    {
        if ($this->getUser()->getUsertype() === 'ROLE_ADMIN') {
          return $this->redirectToRoute("index");
        } else {
          $kids = $this->getUser()->getGuardianacc()->getChildren();
          $accounts = array();
          foreach ($kids as $kid) {
            $theStudent = $kid->getChildLatestEnroll()->getStudent();
            if ($theStudent != null) { array_push($accounts, $theStudent->getLatestMonthAccount()); }
          }
          if (count($accounts) > 0) {
            $invoices = array();
            foreach ($accounts as $account) {
              if ($account != null) {
                $accInvoices=$account->getAccountInvoices();
                foreach ($accInvoices as $invoice) {
                  if ($invoice != null) {
                    array_push($invoices, $invoice);
                  }
                }
              }
            }
            if (count($invoices) > 0) {
              $latestInvoice = false;
              foreach ($invoices as $invoice) {
                if ($invoice != null && ($latestInvoice == false || $latestInvoice->getId() < $invoice->getId())) {
                  $latestInvoice = $invoice;
                }
              }
            } else {
              $latestInvoice = false;
            }
          } else {
            $latestInvoice = false;
          }
          return $this->render('home/index.parent.html.twig', [
              'latest_invoice' => $latestInvoice,
          ]);
        }
    }

    /**
     * @Route("/account/settings", name="myaccount_settings")
     */
    public function myaccount_settings(Request $request)
    {
        if ($this->getUser()->getUsertype() === 'ROLE_ADMIN') {
          return $this->redirectToRoute("index");
        } else {
          $user = new User();
          $user = $this->getUser();

          $originalPassword = $user->getPassword();

          $form = $this->CreateForm(UserMyaccountType::Class, $user);

          $form->handleRequest($request);

          if($form->isSubmitted() && $form->isValid()) {

            if(!empty($user->getPassword())){
              $user->setPassword(
                $this->encoder->encodePassword($user, $user->getPassword())
              );
            } else {
              $user->setPassword($originalPassword);
            }
            //NOTE: no need to persist when editing
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->flush();

            $this->get('session')->getFlashBag()->add(
                'notice',
                'Informațiile dumneavoastră au fost salvate cu succes!'
            );

            return $this->redirectToRoute('myaccount_settings');
          }

          return $this->render('home/myaccount.settings.html.twig', [
              'form' => $form->createView(),
          ]);
        }
    }

    /**
     * @Route("/account/optionals", name="myaccount_optionals")
     */
    public function myaccount_optionals(Request $request)
    {
        if ($this->getUser()->getUsertype() === 'ROLE_ADMIN') {
          return $this->redirectToRoute("index");
        } else {

          $kids = $this->getUser()->getGuardianacc()->getChildren();

          foreach ($kids as $kid) {
            $student = $kid->getChildLatestEnroll()->getStudent();
              if (!empty($student)) {
              $optionals = $student->getSchoolUnit()->getClassOptionals();

              $form = $this->createForm(UserMyaccountEnrollType::Class, $student, array(
                'optionals' => $optionals,
              ));
              $forms[] = $form;
              $views[] = $form->createView();
            }
          }

          if ($request->isMethod('POST')) {

              foreach ($forms as $form) {
                $form->handleRequest($request);
              }

              foreach ($forms as $form) {
                if ($form->isSubmitted()) {
                  $student = $form->getData();

                  foreach ($optionals as $optional) {
                    if ($student->getClassOptionals()->contains($optional)) {
                      $optional->addStudent($student);
                    } else {
                      $optional->removeStudent($student);
                    }
                    $entityManager = $this->getDoctrine()->getManager();
                    $entityManager->flush();
                  }

                  $this->get('session')->getFlashBag()->add(
                      'notice',
                      'Informația a fost salvată cu succes!'
                  );

                  return $this->redirectToRoute('myaccount_optionals');

                }
              }

          }

        return $this->render('home/myaccount.optionals.html.twig', [
            'forms' => $views,
          ]);
        }
    }
}
