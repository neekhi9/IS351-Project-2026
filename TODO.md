# TODO

- [x] Inspect auth routes and controllers for login/register flow.
- [x] Inspect production proxy/HTTPS middleware configuration.
- [x] Inspect database and session configuration for Render compatibility.
- [x] Fix Linux case-sensitive asset path for welcome CSS.
- [x] Make session driver safe by default for environments without `sessions` table migration applied.
- [ ] Implement security hardening: SQLi/XSS/validation/HTTPS/cookies-CSRF/auth/Google OAuth/cloud config.
- [ ] Verify app boots and config is valid after changes.
- [ ] Provide Render deploy/cache checklist to apply fixes.
- [x] Update branding text from FCCC to IS351 in layout and mail subjects.
- [x] Add registration success flash message after user signup and redirect to dashboard/home.
- [ ] Validate forgot password workflow configuration and provide SMTP steps.
- [x] Confirm seeded admin credentials from DatabaseSeeder.
