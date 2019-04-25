
<?php
//Инициализация констант, содержащих информацию,
//необходимую для соединения с базой данных
define('DB_HOST', 'localhost');
define('DB_USER', 'root');
define('DB_PASSWORD', '');
define('DB_NAME', 'document_converter');

//Инициализация константы, содержащей имя каталога для загружаемых файлов изображений
define('WA_CONTR_UPLOADPATH','templates/common/contracts/');//для договоров
define('WA_ACT_UPLOADPATH','templates/common/acts/');//для актов
define('WA_BILL_UPLOADPATH','templates/common/bills/');//для счетов
//GW_UPLOADPATH - это константа принадлежащая приложению (в ней содержится имя каталога для хранения загружаемых на сервер файлов doc)
//define - Ф-ция для создания и инициализации константы 
//templates/common/ - значение константы, которое не может быть изменено- путь куда файл будет сохраняться
define('WA_MAXFILESIZE', 450000000);

?>