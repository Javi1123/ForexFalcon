<?php

namespace App\controlador;

use \App\modelo\stripeModelo;
use \App\modelo\loginModelo;

class stripeControlador {
  
  public function pago () {
    try {
      $stripe = new stripeModelo(STRIPE_SECRET_KEY);
      $productId = $_POST['product_id'] ?? null;
      $mode = $_GET['mode'];
      $_SESSION['id_servicio'] = $_GET['id_servicio'];

      if (!$productId) {
        header('Location: /forexfalcon');
        exit;
      }

      $producto = $stripe->get('products/' . $productId . '?expand[]=default_price');

      // Stripe Checkout Sessions: la forma más sencilla y segura de
      // aceptar pagos, ya que la propia página de pago la aloja Stripe
      // (cumple PCI-DSS automáticamente, no manejas tarjetas tú mismo).
      $session = $stripe->post('checkout/sessions', [
        'mode'                        => $mode === 'unico' ? 'payment' : 'subscription',
        'success_url'                 => SITE_URL . '/guardar',
        'cancel_url'                  => SITE_URL . '/recursos',
        'customer_email'           => $_SESSION['email'],                                // <-- precarga el email en Checkout
        // Muestra el campo "¿Tienes un código promocional?"
        // en la página de pago de Stripe.

        'allow_promotion_codes'        => 'true',

        // Métodos de pago disponibles. "card" cubre Visa/Mastercard/Amex.
        // Puedes añadir más si están activados en tu cuenta de Stripe,
        // p.ej. 'paypal', 'klarna', 'bancontact', 'sepa_debit'...
        'payment_method_types[0]'     => 'card',

        'line_items[0][price]'        => $producto['default_price']['id'],
        'line_items[0][quantity]'     => 1,

        // Guardamos qué producto era, para poder identificarlo
        // luego en el webhook o en la página de éxito.
        'metadata[product_id]'        => $producto['id'],
      ]);
      // Redirige al comprador a la página de pago alojada por Stripe.
      header('Location: ' . $session['url']);
      exit;
      
    } catch (\Exception $e) {
      header('Location: /forexfalcon/recursos');
      exit;
    }
  }

  public function guardarDatosPago () {
    $id_servicio = $_SESSION['id_servicio'];
    $email = $_SESSION['email'];

    loginModelo::setSuscripcones($email,$id_servicio);

    header('Location: /forexfalcon/recursos');
    exit;

  }
}
