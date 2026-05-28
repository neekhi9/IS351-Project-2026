# TODO

- [x] Create restaurant domain migrations: menus, orders, order_items, reservations.
- [ ] Create restaurant models and relationships.
- [ ] Update RBAC seeder for roles: admin, staff, customer with restaurant permissions.
- [ ] Create controllers: MenuController, OrderController, ReservationController, KitchenOrderController.
- [ ] Refactor routes/web.php to include role-based restaurant flows and keep Google OAuth routes.
- [ ] Implement customer pages: food order form, reservation form, my orders/reservations.
- [ ] Implement staff page: kitchen order dashboard with order status updates.
- [ ] Implement admin pages: manage menu and users.
- [ ] Update home/dashboard logic for role-based landing pages.
- [ ] Run migrations/seeders and verify route registration.
- [ ] Perform critical-path testing for customer/staff/admin + Google login.
