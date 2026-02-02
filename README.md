### AceADA

## Technologies
- [Laravel](https://laravel.com/)
- [Vue3](https://vuejs.org/)
- [Inertia](https://inertiajs.com/)
- [Vite](https://laravel.com/docs/9.x/vite)
- [TailwindCss](https://tailwindcss.com/)
- [DaisyUI](https://daisyui.com/)
- [Pinia](https://pinia.vuejs.org/)

## How to Setup

```
// install dependencies
composer install && npm install && php artisan key:generate

// migrate and seed
php artisan migrate --seed

// on local development
npm run dev
npm run icon:dev
php artisan serve
``` 

## Stripe Webhook Events
Set up Stripe Webhooks and include these events to send to our app. For local development, we can use [Stripe CLI](https://stripe.com/docs/stripe-cli).

- customer.subscription.updated
- charge.captured
- charge.expired
- charge.failed
- charge.pending
- charge.succeeded
- charge.updated
- invoice.payment_failed
- invoice.payment_succeeded
- invoice.created
- invoice.deleted
- invoice.finalized
- invoice.finalization_failed
- invoice.marked_uncollectible
- invoice.paid
- invoice.payment_action_required
- invoice.payment_failed
- invoice.payment_succeeded
- invoice.sent
- invoice.upcoming
- invoice.updated
- invoice.voided


## Admin Credentials

- **Email**: admin@aceada.com
- **Password**: password


## Server Details

### Testing Environments
- **Ubuntu**: Ubuntu 22.04.1 LTS
- **Apache**: Apache/2.4.52 

### Production Environment
- **Amazon Linux**: 2023
- **Httpd**: Apache/2.4.56 (Amazon Linux) 
