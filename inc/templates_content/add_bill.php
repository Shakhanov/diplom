 <?php 

 require_once('connect.php');

 $dbc = mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);

 ?>       

 <div class="container-fluid">

  <!-- Page Heading -->
  <div class="d-sm-flex align-items-center justify-content-between mb-4">
    <h1 class="h3 mb-0 text-gray-800">Добавление нового шаблона счета</h1>
  </div>


  <div class="d-sm-flex align-items-center justify-content-between mb-4">
    <span>Создайте шаблон на основе вашего документа.</span>
  </div>

  <div class="d-sm-flex align-items-center justify-content-between mb-4">
    <span>Возьмите за основу ваш шаблон документа в Ворде.
    Или воспользуйтейсь стандартными документами DOCUMENTOR</span>
  </div>
  <p class="text-left"><a href="">Ссылка на счет договор</a></p>
  <p class="text-left"><a href="">Ссылка на счет</a></p>

  <div class="d-sm-flex align-items-center justify-content-between mb-4">
    <span>По следующией ссылке вы можете скачать файл с примерами тегов, которые будут .</span>
  </div>
  <p class="text-left"><a href="">Список тегов</a></p>

  <div class="d-sm-flex align-items-center justify-content-between mb-4">
    <span>Ваш документ должен включать теги WA DOCUMENTOR для корректной работы с шаблонами.</span>
  </div>


  <div class="d-sm-flex align-items-center justify-content-between mb-4">
    <span>Добавить необходимые файлы будующих шаблонов в окно ниже и они автоматически добавлятся к вашему списку шаблонов</span>
  </div>
</div>