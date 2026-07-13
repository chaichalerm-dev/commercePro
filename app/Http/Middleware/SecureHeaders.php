<?php

declare(strict_types=1);

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\View;
use Symfony\Component\HttpFoundation\Response;

class SecureHeaders
{
    /**
     * Baseline security headers for every response, plus a per-request CSP
     * nonce shared with views so the handful of inline <script> blocks
     * (JSON-LD, dashboard chart data, password-strength labels) can opt into
     * the policy instead of needing 'unsafe-inline'.
     *
     * @param  Closure(Request): (Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $nonce = base64_encode(random_bytes(16));
        View::share('cspNonce', $nonce);

        $response = $next($request);

        $response->headers->set('X-Frame-Options', 'SAMEORIGIN');
        $response->headers->set('X-Content-Type-Options', 'nosniff');
        $response->headers->set('Referrer-Policy', 'strict-origin-when-cross-origin');
        $response->headers->set('Permissions-Policy', 'camera=(), microphone=(), geolocation=()');
        $response->headers->set('Content-Security-Policy', $this->contentSecurityPolicy($nonce));
        $response->headers->set('Strict-Transport-Security', 'max-age=31536000; includeSubDomains; preload');

        return $response;
    }

    /**
     * Alpine.js evaluates its directives via `new Function()`, so 'unsafe-eval'
     * is required for the site's interactivity to work at all — there is no
     * way to scope that down further short of switching to Alpine's separate
     * CSP-safe build, which drops directive features this app relies on.
     */
    private function contentSecurityPolicy(string $nonce): string
    {
        $imgSrc = implode(' ', array_filter([
            "'self'",
            // picsum.photos (seed/demo product images) 302-redirects every
            // request to fastly.picsum.photos for the actual bytes, and CSP
            // enforces img-src against the redirect target too — the wildcard
            // covers that CDN hop without hardcoding a specific subdomain.
            'https://picsum.photos',
            'https://*.picsum.photos',
            $this->s3ImageHost(),
            'blob:',
            'data:',
        ]));

        return implode('; ', [
            "default-src 'self'",
            "script-src 'self' 'nonce-{$nonce}' 'unsafe-eval'",
            "style-src 'self' https://fonts.bunny.net",
            "font-src 'self' https://fonts.bunny.net",
            "img-src {$imgSrc}",
            "connect-src 'self'",
            "frame-src 'none'",
            "frame-ancestors 'self'",
            "object-src 'none'",
            "base-uri 'self'",
            "form-action 'self'",
        ]);
    }

    /**
     * When FILESYSTEM_DISK=s3 (production, uploads on Supabase Storage —
     * local disk doesn't survive Render's ephemeral filesystem), uploaded
     * images are served from AWS_URL's host rather than this app's own
     * origin, so CSP's img-src must allow it explicitly or every uploaded
     * image (banners, products, categories, logo) silently fails to render.
     */
    private function s3ImageHost(): ?string
    {
        $url = config('filesystems.disks.s3.url');

        if (! is_string($url) || $url === '') {
            return null;
        }

        $host = parse_url($url, PHP_URL_HOST);

        return $host !== null ? "https://{$host}" : null;
    }
}
