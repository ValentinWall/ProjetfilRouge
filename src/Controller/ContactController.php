<?php

namespace App\Controller;

use App\Form\ContactType;
use Symfony\Component\Mime\Address;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\String\Slugger\SluggerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class ContactController extends AbstractController
{
    #[Route('/contact', name: 'contact')]
    public function index(Request $request, MailerInterface $mailer, SluggerInterface $slugger): Response
    {
        $form = $this->createForm(ContactType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            if (empty($form['honeypot']->getData()))
            {
                $contact = $form->getData();
                $email = (new TemplatedEmail())
                    ->from(new Address($contact['email'], $contact['first_name'] . '' . $contact['last_name']))
                    ->to(new Address('valouh59130@gmail.com', 'Valentin WALLAERT'))
                    ->replyTo(new Address($contact['email'], $contact['first_name'] . '' . $contact['last_name']))
                    ->subject('Facile-eat-la-vie - demande de contact -' . $contact['subject'])
                    ->htmlTemplate('email/contact.html.twig')
                    ->context([
                        'firstName' => $contact['first_name'],
                        'lastName' => $contact['last_name'],
                        'company' => $contact['company'],
                        'emailAddress' => $contact['email'],
                        'subject' => $contact['subject'],
                        'message' => $contact['message']
                    ]);
                    if ($contact['attachment'] !== null) { // vérifie s'il y a un fichier dans le formulaire
                        $originalFileName = pathinfo($contact['attachment']->getClientOriginalName(), PATHINFO_FILENAME); // récupère le nom original du fichier
                        $safeFileName = $slugger->slug($originalFileName); // nécessaire pour inclure le nom du fichier dans une URL
                        $newFileName = $safeFileName . '.' . $contact['attachment']->guessExtension(); // renomme le fichier pour lui ajouter une extension de fichier
                        $email->attachFromPath($contact['attachment']->getPathName(), $newFileName); // attache la pièce-jointe au corps du mail
                    }
                    $mailer->send($email);
                    $this->addFlash('success', 'Votre message a bien été envoyé. Nous vous répondrons dans les plus brefs délais.');
                    return $this->redirectToRoute('contact');
            } else {
                $this->addFlash('danger', 'Ne seriez-vous pas un robot ?');
                return $this->redirectToRoute('app_home');
            }
        }

        return $this->render('contact/index.html.twig', [
            'contactForm' => $form->createView()
        ]);
    }
}
