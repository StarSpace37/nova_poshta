# nova_poshta PHP
как добавить "Нова Пошта" на ваш сайт 
Устанавливаем XAMPP, создаём MySQL, и в phpMyAdmin создаём Базу Данных, называем её nova_poshta
теперь заходим в своём ПК в  xampp > htdocs >  nova_poshta > и закидываем сюда все файлы из git
db_fill_areas.php
db_fill_cities.php
db_fill_warehouses.php
db_get_cities.php
db_get_warehouses.php
nova_poshta.php (страничка для демонстрации, так что  по желанию) 
в браузере пишем 
http://localhost/nova_poshta/db_fill_areas.php (ждём загрузку, она будет быстрой)
http://localhost/nova_poshta/db_fill_cities.php (тут загрузка продлится секунд 100)
http://localhost/nova_poshta/db_fill_warehouses.php (здесь уже будут подгружаться отделения Н.П. по этому наберитесь терпения)
Отлично, если всё сделали правильно то теперь пишем 
http://localhost/nova_poshta/nova_poshta.php 
и всё.
