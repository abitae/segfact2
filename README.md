# Update in production

## IN TABLE document_states
- php artisan migrate
- php artisan db:seed --class=DocumentStateTableSeeder
- php artisan db:seed --class=BankSeeder
- php artisan db:seed --class=RolePermissionTableSeeder

- modifity table document_states fill column order
- Add 1 to users at column is_seller

Insert in file RolePermissionTableSeeder 
- Add Permission to roles
