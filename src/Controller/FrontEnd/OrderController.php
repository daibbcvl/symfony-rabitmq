<?php

namespace App\Controller\FrontEnd;

use App\Entity\Category;
use App\Entity\Comment;
use App\Entity\Post;
use App\Entity\Tag;
use App\Entity\User;
use App\Form\Site\CommentType;
use App\Repository\CategoryRepository;
use App\Repository\CommentRepository;
use App\Repository\PostRepository;
use App\Repository\TagRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class OrderController extends AbstractController
{


    /**
     * @Route("/checkout", name="order_checkout")
     *
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     * @throws \Stripe\Exception\ApiErrorException
     */
    public function index(Request $request)
    {
        $products = [];
        for($i =0; $i<2; $i++){

            $product = new \stdClass();
            $product->name =  'ABC'. rand(200,200);
            $product->price =  20;

            $products[] = $product;
        }

        if ($request->isMethod('POST')) {
            $token = $request->request->get('stripeToken');
            $privateKey = $this->getParameter('stripe_secret_key');
            \Stripe\Stripe::setApiKey($privateKey);
            $charge = \Stripe\Charge::create([
                'amount' => 40 *100,
                'currency' => 'USD',
                'description' => 'Shop 2 product',
                'source' => $token,
                'receipt_email' => $request->request->get('stripeEmail'),
            ]);
        }

        $cart = new \stdClass();
        $cart->total = 40;
        return $this->render('front/order/checkout.html.twig', array(
            'products' => $products,
            'cart' => $cart,
            'stripe_public_key' => $this->getParameter('stripe_public_key')
        ));
    }

    /**
     * @Route("/checkout-customer", name="order_customer_checkout")
     *
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     * @throws \Stripe\Exception\ApiErrorException
     */
    public function checkoutWithCustomerId(Request $request)
    {
        //dd($this->getUser());
        $products = [];
        for($i =0; $i<2; $i++){

            $product = new \stdClass();
            $product->name =  'ABC'. rand(200,200);
            $product->price =  20;

            $products[] = $product;
        }

        if ($request->isMethod('POST')) {
            $token = $request->request->get('stripeToken');
            $privateKey = $this->getParameter('stripe_secret_key');
            \Stripe\Stripe::setApiKey($privateKey);

            /** @var User $user */
            $user = $this->getUser();
            if (!$user->getStripeCustomerId()) {
                $customer = \Stripe\Customer::create([
                    'email' => $user->getEmail(),
                    'source' => $token,
                    "description" => "First test charge!"
                ]);
                $user->setStripeCustomerId($customer->id);
                $em = $this->getDoctrine()->getManager();
                $em->persist($user);
                $em->flush();
            } else {
                $customer = \Stripe\Customer::retrieve($user->getStripeCustomerId());
                $customer->source = $token;
                $customer->save();
            }

            $charge = \Stripe\Charge::create([
                'amount' => 40 *100,
                'currency' => 'usd',
                'description' => 'Shop 2 product by Customer Id',
                'customer' => $user->getStripeCustomerId(),
            ]);

        }

        $cart = new \stdClass();
        $cart->total = 40;
        return $this->render('front/order/checkout.html.twig', array(
            'products' => $products,
            'cart' => $cart,
            'stripe_public_key' => $this->getParameter('stripe_public_key')
        ));
    }

    /**
     * @Route("/charge/list", name="charge_list")
     *
     * @param Request $request
     * @throws \Stripe\Exception\ApiErrorException
     */
    public function chargeList(Request $request)
    {
        $privateKey = $this->getParameter('stripe_secret_key');
        \Stripe\Stripe::setApiKey($privateKey);
        $charges  = \Stripe\Charge::all(['limit' => 3]);
        //dd($charges['data'][0]->id);
        //retrieve a change
        $charge = \Stripe\Charge::retrieve($charges['data'][0]->id);

        dd($charge);
    }


    /**
     * @Route("/invoice", name="charge_invoice")
     *
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     * @throws \Stripe\Exception\ApiErrorException
     */
    public function invoice(Request $request)
    {
        $products = [];
        $total =0;
        for($i =0; $i<2; $i++){

            $product = new \stdClass();
            $product->name =  'ABC'. rand(1,200);
            $product->price =  rand(20,99);
            $total += $product->price;
            $products[] = $product;
        }

        if ($request->isMethod('POST')) {
            $token = $request->request->get('stripeToken');
            \Stripe\Stripe::setApiKey($this->getParameter('stripe_secret_key'));
            /** @var User $user */
            $user = $this->getUser();
            if (!$user->getStripeCustomerId()) {
                $customer = \Stripe\Customer::create([
                    'email' => $user->getEmail(),
                    'source' => $token
                ]);
                $user->setStripeCustomerId($customer->id);
                $em = $this->getDoctrine()->getManager();
                $em->persist($user);
                $em->flush();
            } else {
                $customer = \Stripe\Customer::retrieve($user->getStripeCustomerId());
                $customer->source = $token;
                $customer->save();
            }
            foreach ($products as $product) {
                \Stripe\InvoiceItem::create(array(
                    "amount" => $product->price * 100,
                    "currency" => "usd",
                    "customer" => $user->getStripeCustomerId(),
                    "description" => $product->name
                ));
            }
            $invoice = \Stripe\Invoice::create(array(
                "customer" => $user->getStripeCustomerId()
            ));
            // guarantee it charges *right* now
            $invoice->pay();
            $this->addFlash('success', 'Order Complete! Yay!');
            return $this->redirectToRoute('charge_invoice');

        }

        $cart = new \stdClass();
        $cart->total = $total;
        return $this->render('front/order/checkout.html.twig', array(
            'products' => $products,
            'cart' => $cart,
            'stripe_public_key' => $this->getParameter('stripe_public_key')
        ));
    }
}
