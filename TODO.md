# TODO

- [x] Inspect auth routes and controllers for login/register flow.
- [x] Inspect production proxy/HTTPS middleware configuration.
- [x] Inspect database and session configuration for Render compatibility.
- [x] Fix Linux case-sensitive asset path for welcome CSS.
- [x] Make session driver safe by default for environments without `sessions` table migration applied.
- [ ] Implement security hardening: SQLi/XSS/validation/HTTPS/cookies-CSRF/auth/Google OAuth/cloud config.
- [x] Fix Google OAuth redirect_uri missing error by enforcing callback redirect config and clearing cached config.
- [ ] Verify app boots and config is valid after changes.
- [ ] Audit and harden Google OAuth request/callback flow for invalid_request handling.
- [ ] Add robust Google OAuth callback error handling and logging.
- [ ] Validate Google OAuth route + redirect URI consistency (app URL, callback, env).
- [ ] Provide Render deploy/cache checklist to apply fixes.
- [x] Update branding text from FCCC to IS351 in layout and mail subjects.
- [x] Add registration success flash message after user signup and redirect to dashboard/home.
- [ ] Validate forgot password workflow configuration and provide SMTP steps.
- [x] Confirm seeded admin credentials from DatabaseSeeder.
