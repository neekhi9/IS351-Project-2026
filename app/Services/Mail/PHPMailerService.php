<?php

namespace App\Services\Mail;

use App\Models\Registration;
use App\Models\User;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\View;
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception as PHPMailerException;

class PHPMailerService
{
    /**
     * Send admin notification about a new registration.
     */
    public function sendAdminNotification(Registration $registration): void
    {
        $to = env('ADMIN_EMAIL', config('mail.from.address'));
        if (!$to) {
            Log::warning('PHPMailerService: ADMIN_EMAIL and mail.from.address are both missing; skipping admin email.');
            return;
        }

        $subject = 'IS351 - New Registration Submitted (' . ucfirst($registration->account_type) . ') #' . $registration->id;

        $html = View::make('emails.admin.registration_notification', [
            'registration' => $registration,
        ])->render();

        $this->send($to, $subject, $html);
    }

    /**
     * Send confirmation email to the registrant.
     */
    public function sendRegistrantConfirmation(Registration $registration): void
    {
        $to = $registration->email;
        if (!$to) {
            Log::warning('PHPMailerService: Registration has no email; skipping registrant confirmation.', [
                'registration_id' => $registration->id,
            ]);
            return;
        }

        // Ensure related names are available for the email template
        $registration->loadMissing(['city', 'region']);

        // Update subject to requested format
        $subject = 'IS351 - Your Registration Has Been Received (Ref #' . $registration->id . ')';

        $html = View::make('emails.registration_confirmation', [
            'registration' => $registration,
        ])->render();

        $this->send($to, $subject, $html, $this->formatRegistrantName($registration));
    }

    /**
     * Send invalid notice with resubmission link to the registrant.
     */
    public function sendInvalidNotice(Registration $registration, string $resubmissionLink): void
    {
        $to = $registration->email;
        if (!$to) {
            Log::warning('PHPMailerService: Registration has no email; skipping invalid notice.', [
                'registration_id' => $registration->id,
            ]);
            return;
        }

        $subject = 'IS351 - Action Required: Fix Your Application (Ref #' . $registration->id . ')';

        $html = View::make('emails.invalid_notification', [
            'registration' => $registration,
            'link' => $resubmissionLink,
        ])->render();

        $this->send($to, $subject, $html, $this->formatRegistrantName($registration));
    }

    /**
     * Send approved notice to the user with login link.
     */
    public function sendApprovedNotice(User $user, string $loginLink): void
    {
        $to = $user->email;
        if (!$to) {
            Log::warning('PHPMailerService: User has no email; skipping approved notice.', [
                'user_id' => $user->id ?? null,
            ]);
            return;
        }

        $subject = 'IS351 - Your Application Has Been Approved';

        $html = View::make('emails.approved_notification', [
            'user' => $user,
            'loginLink' => $loginLink,
        ])->render();

        $this->send($to, $subject, $html, $user->name ?? null);
    }

    /**
     * Send declined notice to the registrant.
     */
    public function sendDeclinedNotice(Registration $registration): void
    {
        $to = $registration->email;
        if (!$to) {
            Log::warning('PHPMailerService: Registration has no email; skipping declined notice.', [
                'registration_id' => $registration->id,
            ]);
            return;
        }

        $subject = 'IS351 - Your Application Has Been Declined (Ref #' . $registration->id . ')';

        $html = View::make('emails.declined_notification', [
            'registration' => $registration,
        ])->render();

        $this->send($to, $subject, $html, $this->formatRegistrantName($registration));
    }

    /**
     * Send OTP login link + code to an email.
     */
    public function sendOtpLink(string $email, string $verifyLink, string $code): void
    {
        if (!$email) {
            Log::warning('PHPMailerService: Email missing for OTP link.');
            return;
        }

        $subject = 'IS351 - Your Login Link and OTP Code';

        $html = View::make('emails.otp_link', [
            'verifyLink' => $verifyLink,
            'code' => $code,
        ])->render();

        $this->send($email, $subject, $html);
    }

    /**
     * Core send via PHPMailer. In local without SMTP configured, logs the MIME message instead.
     */
    protected function send(string $to, string $subject, string $html, ?string $toName = null, array $cc = [], array $bcc = []): void
    {
        $fromAddress = config('mail.from.address', 'no-reply@example.com');
        $fromName = config('mail.from.name', config('app.name', 'App'));

        $mailer = $this->makeMailer();

        // If SMTP host is not configured, log instead of sending to avoid failures locally.
        $smtpHost = config('mail.mailers.smtp.host');
        $shouldLogOnly = empty($smtpHost)
            || in_array(config('mail.default'), ['log', 'array'], true)
            || (config('app.env') === 'testing')
            || (config('app.env') === 'local' && empty(config('mail.mailers.smtp.username')));

        try {
            if ($shouldLogOnly) {
                // Build MIME message and log
                $mailer->setFrom($fromAddress, $fromName);
                $mailer->addAddress($to, $toName ?? '');
                foreach ($cc as $c) {
                    $mailer->addCC($c);
                }
                foreach ($bcc as $b) {
                    $mailer->addBCC($b);
                }
                $mailer->isHTML(true);
                $mailer->Subject = $subject;
                $mailer->Body = $html;
                $mailer->AltBody = html_entity_decode(strip_tags($html));

                // preSend generates MIME body without actually sending
                $mailer->preSend();
                $mime = $mailer->getSentMIMEMessage();
                Log::info('PHPMailerService LOG-ONLY email (no SMTP configured)', [
                    'to' => $to,
                    'subject' => $subject,
                    'mime_excerpt' => mb_substr($mime, 0, 2000),
                ]);
                return;
            }

            // Real send via SMTP
            $mailer->setFrom($fromAddress, $fromName);
            $mailer->addAddress($to, $toName ?? '');
            foreach ($cc as $c) {
                $mailer->addCC($c);
            }
            foreach ($bcc as $b) {
                $mailer->addBCC($b);
            }
            $mailer->isHTML(true);
            $mailer->Subject = $subject;
            $mailer->Body = $html;
            $mailer->AltBody = html_entity_decode(strip_tags($html));

            $mailer->send();
        } catch (PHPMailerException $e) {
            Log::error('PHPMailerService send failed', [
                'to' => $to,
                'subject' => $subject,
                'error' => $e->getMessage(),
            ]);
            // do not throw to avoid blocking user flow
        } catch (\Throwable $e) {
            Log::error('PHPMailerService unexpected failure', [
                'to' => $to,
                'subject' => $subject,
                'error' => $e->getMessage(),
            ]);
        }
    }

    /**
     * Configure PHPMailer instance from environment.
     */
    protected function makeMailer(): PHPMailer
    {
        $mailer = new PHPMailer(true);

        // SMTP configuration (prefer config() over env() to work with config caching)
        $smtpHost = config('mail.mailers.smtp.host');
        $smtpPort = (int) config('mail.mailers.smtp.port', 587);
        $smtpUser = config('mail.mailers.smtp.username');
        $smtpPass = config('mail.mailers.smtp.password');
        $smtpScheme = config('mail.mailers.smtp.scheme');

        if (!empty($smtpHost)) {
            $mailer->isSMTP();
            $mailer->Host = $smtpHost;
            $mailer->SMTPAuth = !empty($smtpUser);
            if ($mailer->SMTPAuth) {
                $mailer->Username = $smtpUser;
                $mailer->Password = $smtpPass ?? '';
            }
            $mailer->Port = $smtpPort;

            // Determine encryption from scheme/port
            $scheme = strtolower((string) $smtpScheme);
            if (in_array($scheme, ['smtps', 'ssl'], true)) {
                $mailer->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;
            } elseif ($scheme === 'smtp' && (int) $smtpPort === 587) {
                $mailer->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
            } else {
                // no encryption
                $mailer->SMTPSecure = false;
                $mailer->SMTPAutoTLS = false;
            }
        }

        // Encoding and content charset
        $mailer->CharSet = 'UTF-8';
        $mailer->isHTML(true);

        return $mailer;
    }

    protected function formatRegistrantName(Registration $registration): string
    {
        $parts = array_filter([
            $registration->title ?: null,
            $registration->first_name ?: null,
            $registration->surname ?: null,
        ]);
        return implode(' ', $parts);
    }
}
